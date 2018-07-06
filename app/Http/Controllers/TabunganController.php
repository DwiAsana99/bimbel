<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Tabungan;
use App\Tabungandetil;
use App\Settingtabungan;
use DB;
use Auth;
use Fungsi;

class TabunganController extends Controller
{
    public function index()
    {
      $setting = Settingtabungan::first();
      $saldomin = $setting->SaldoMin;
      return view('tabungan.index', compact('saldomin'));
    }

    public function dt()
    {
      $periodebunga = (int)substr(session('tgl'),5,-3);
      $setting = Settingtabungan::first();
      $datas = Tabungan::select('tabungans.*', DB::raw('IF(PeriodeBunga <= '.$periodebunga.' AND DATEDIFF("'.session('tgl').'", TglInput) > '.$setting->LamaNabungMin.' AND Saldo >= 50000, 1, 0) AS BisaPosting'))->with('nasabah')->where('IsAktif', true)->get();
      return Datatables::of($datas)
      ->editColumn('TglInput', function ($data) {
          return Fungsi::bulanID($data->TglInput);
      })
      ->addColumn('detil_url', function($data) {
        return url('/tabungan/detil/' . $data->NoRek);
      })
      ->addColumn('action', function ($data) {
          if(session('aturtgl') == 1) {
              return '<button class="btn btn-xs btn-success" id="btnsetor" data-toggle="modal" data-target="#modal"><i class="fa fa-plus-square"></i> Setor</button>
              <button class="btn btn-xs btn-warning" id="btntarik" data-toggle="modal" data-target="#modal"><i class="fa fa-minus-square"></i> Tarik</button>
              <a '.($data->BisaPosting == 1 ? 'href="'.route('postingbunga.single', ['NoRek' => $data->NoRek]).'"' : 'disabled').' class="btn btn-xs btn-primary"><i class="fa fa-line-chart"></i> Posting</a>';
          }else {
              return '<button class="btn btn-xs btn-success" id="btnsetor" data-toggle="modal" data-target="#modal"><i class="fa fa-plus-square"></i> Setor</button>
              <button class="btn btn-xs btn-warning" id="btntarik" data-toggle="modal" data-target="#modal"><i class="fa fa-minus-square"></i> Tarik</button>';
          }
      })
      ->make(true);
    }

    public function detil($tabungan = null)
    {
      $datas = Tabungandetil::where('NoRek', $tabungan)->select('tabungandetils.*', DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9)) AS urutan'))->get();
      return Datatables::of($datas)
      ->editColumn('Debet', function ($data) {
          return is_null($data->Debet) ? 0 : $data->Debet;
      })
      ->editColumn('TglInput', function ($data) {
          return Fungsi::bulanID($data->TglInput);
      })
      ->make(true);
    }

    public function detildata($tabungan = null)
    {
      $datas = Tabungan::with('nasabah')->where('IsAktif', true)->where('NoRek', $tabungan)->first();
      return response()->json($datas);
    }

    public function tambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $tabungan = $this->tabunganBaru($request->input('NoNasabah'), $request->input('saldoawal'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->action('TabunganController@index')
            ->with('notif', json_encode([
                'title' => "Buka Tabungan",
                'text' => "Gagal Membuka Tabungan Baru.",
                'type' => "error"
            ]));
        }
        return redirect()->action('TabunganController@index')
        ->with('notif', json_encode([
            'title' => "Buka Tabungan",
            'text' => "Data Tabungan ".$tabungan->NoRek." Dengan Setoran Awal Rp ".number_format($tabungan->Saldo,0,".",",")." Berhasil Dibuat.",
            'type' => "success"
        ]));
    }

    public function tambahDariPinjaman(Request $request)
    {
        DB::beginTransaction();
        try {
            $tabungan = $this->tabunganBaru($request->input('NoNasabah'), $request->input('saldoawal'));
            DB::commit();
            return response()->json([
              'notif' => [
                'title' => "Tambah Tabungan",
                'text' => "Berhasil Melakukan Tambah Tabungan ".$tabungan->NoRek,
                'type' => "success"
              ],
              'data' => $tabungan
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
              'notif' => [
                'title' => "Tambah Tabungan",
                'text' => "Gagal Melakukan Tambah Tabungan",
                'type' => "error"
              ]
            ], 500);
        }
    }

    private function tabunganBaru($nonasabah, $nominal)
    {
        $kode = Tabungan::noUnik();
        $stgl = session('tgl');

        $tabungan = Tabungan::create([
            'NoRek' => $kode,
            'NoNasabah' => $nonasabah,
            'Saldo' => $nominal,
            'SaldoRendah' => 0,
            'SaldoTinggi' => $nominal,
            'PeriodeBunga' => (int)substr($stgl, 5, 2) == 12 ? 1 : (int)substr($stgl, 5, 2) + 1,
            'IsAktif' => true,
            'user_id' => Auth::id(),
            'TglInput' => $stgl
        ]);

        if ($nominal > 0) {
          $td = Tabungandetil::create([
              'NoRek' => $tabungan->NoRek,
              'Kredit' => $tabungan->Saldo,
              'Nominal' => $tabungan->Saldo,
              'Keterangan' => 'Setoran Awal Tabungan',
              'SaldoAkhir' => $tabungan->Saldo,
              'TglInput' => $tabungan->TglInput,
              'user_id' => Auth::id()
          ]);

          $jd = Fungsi::jurnalDetil($nominal, 5);
          Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        }

        return $tabungan;
    }

    public function setor(Request $request, Tabungan $tabungan)
    {
      $saldo = $request->nominal + $tabungan->Saldo;
      DB::beginTransaction();
      try {
        $tabungan->update(['Saldo' => $saldo, 'SaldoTinggi' => $saldo]);
        $tabungancheck = Tabungandetil::where('NoRek', $tabungan->NoRek)->first();
        if ($tabungancheck == null) {
          $statusTabungan = 'Setoran Awal Tabungan';
        }else {
          $statusTabungan = 'Setoran Tabungan';
        }
        $td = Tabungandetil::create([
          'NoRek' => $tabungan->NoRek,
          'Kredit' => $request->nominal,
          'Nominal' => $request->nominal,
          'Keterangan' => $statusTabungan,
          'SaldoAkhir' => $tabungan->Saldo,
          'TglInput' => session('tgl'),
          'user_id' => Auth::id()
        ]);
        $jd = Fungsi::jurnalDetil($request->nominal, 5);
        Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        DB::commit();
      }catch(Exception $e){
        DB::rollback();
        return redirect()->action('TabunganController@index')
        ->with('notif', json_encode([
          'title' => "Setoran Tabungan",
          'text' => "Gagal Melakukan Setoran Tabungan",
          'type' => "error"
        ]));
      }
      return redirect()->action('TabunganController@index')
      ->with('notif', json_encode([
        'title' => "Setoran Tabungan",
        'text' => "Berhasil Melakukan Setoran Tabungan pada rekening ". $tabungan->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "success"
      ]));
    }

    public function tarik(Request $request, Tabungan $tabungan)
    {
      $saldo = $tabungan->Saldo - $request->nominal;
      $saldorendah = $saldo < $tabungan->SaldoRendah ? $saldo : $tabungan->SaldoRendah;
      DB::beginTransaction();
      try {
        $tabungan->update(['Saldo' => $saldo, 'SaldoRendah' => $saldorendah]);
        $td = Tabungandetil::create([
          'NoRek' => $tabungan->NoRek,
          'Debet' => $request->nominal,
          'Nominal' => $request->nominal,
          'Keterangan' => 'Penarikan Tabungan',
          'SaldoAkhir' => $tabungan->Saldo,
          'TglInput' => session('tgl'),
          'user_id' => Auth::id()
        ]);
        $jd = Fungsi::jurnalDetil($request->nominal, 6);
        Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        DB::commit();
      }catch(Exception $e){
        DB::rollback();
        return redirect()->action('TabunganController@index')
        ->with('notif', json_encode([
          'title' => "Penarikan Tabungan",
          'text' => "Gagal Melakukan Penarikan Tabungan",
          'type' => "error"
        ]));
      }
      return redirect()->action('TabunganController@index')
      ->with('notif', json_encode([
        'title' => "Penarikan Tabungan",
        'text' => "Berhasil Melakukan Penarikan Tabungan pada rekening ". $tabungan->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "success"
      ]));
    }

    public function select2(Request $r)
    {
      $term = trim($r->q);
      if (empty($term)) {
        $tags = Tabungan::selectTabungan()->get();
      }else {
        $tags = Tabungan::selectTabungan()
        ->where('NoNasabah', 'LIKE', '%'. $term .'%')
        ->orWhere('NamaNasabah', 'LIKE', '%'. $term .'%')
        ->orWhere('NoRek', 'LIKE', '%'. $term .'%')
        ->get();
      }
      $formatted_tags = [];
      foreach ($tags as $tag) {
        $formatted_tags[] = ['id' => $tag->NoRek.'|'.$tag->NoNasabah, 'text' => $tag->NoRek.' => '.$tag->NoNasabah.' | '.$tag->NamaNasabah];
      }
      return response()->json($formatted_tags);
    }
}
