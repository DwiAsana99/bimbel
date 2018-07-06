<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tabungan;
use App\Settingtabungan;
use App\Tabungandetil;
use Datatables;
use DB;
use Fungsi;
use Auth;

class PostingBungaController extends Controller
{
    public function index()
    {
      $setting = Settingtabungan::first();
      $periodebunga = (int)substr(session('tgl'),5,-3);
      $tabunganposting = Tabungan::where('IsAktif', true)->where('PeriodeBunga', '<=',$periodebunga)->count();
      if($setting->TanggalPosting == (int)substr(session('tgl'),8) || $tabunganposting > 0){
        $canposting = '';
      }else{
        $canposting = 'disabled';
      }
      return view('postingbunga.index', compact('canposting'));
    }

    public function dt(Request $request)
    {
        $periodebunga = (int)substr(session('tgl'),5,-3);
        DB::statement(DB::raw('set @rownum=0'));
        $setting = Settingtabungan::first();
        $datas = Tabungan::with('nasabah')
        ->where('IsAktif', true)
        ->where('PeriodeBunga', '<=',$periodebunga)
        ->where('Saldo', '>=', 50000)
        ->where(DB::raw('DATEDIFF("'.session('tgl').'", TglInput)'), '>', $setting->LamaNabungMin)
        ->select([DB::raw('@rownum  := @rownum + 1 AS rownum'), 'tabungans.NoRek', 'tabungans.Saldo', 'tabungans.SaldoRendah', 'tabungans.NoNasabah']);
        $datatables = Datatables::of($datas)
        ->addColumn('bunga', function ($data) use($setting) {
            return ($setting->BungaPersen * $data->SaldoRendah) / 100;
        })
        ->addColumn('saldoakhir', function ($data) use($setting) {
            return $data->Saldo + (($setting->BungaPersen * $data->SaldoRendah) / 100);
        })
        ->addColumn('action', function ($data) {
            return '<button id="btnremove" class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button>';
        });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }
        return $datatables->make(true);
    }

    public function posting(Request $request)
    {
      DB::beginTransaction();
      try {
        foreach ($request->tabungan as $r) {
          $tabungan = Tabungan::find($r['NoRek']);
          $periodebunga = $tabungan->PeriodeBunga == 12 ? 1 : $tabungan->PeriodeBunga + 1;
          $tabungan->update(['Saldo' => $r['saldoakhir'], 'SaldoRendah' => $r['saldoakhir'], 'SaldoTinggi' => $r['saldoakhir'], 'PeriodeBunga' => $periodebunga]);
          if ($r['bunga'] > 0) {
            $td = Tabungandetil::create([
              'NoRek' => $tabungan->NoRek,
              'Kredit' => $r['bunga'],
              'Nominal' => $r['bunga'],
              'Keterangan' => 'Bunga Tabungan',
              'SaldoAkhir' => $tabungan->Saldo,
              'TglInput' => session('tgl'),
              'user_id' => Auth::id()
            ]);
            $jd = Fungsi::jurnalDetil($r['bunga'], 7);
            Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
          }
        }
        DB::commit();
        return response()->json([
          'notif' => [
            'title' => "Posting Bunga Tabungan",
            'text' => "Berhasil Melakukan Posting Bunga ".count($request->tabungan)." Rekening Tabungan",
            'type' => "success"
          ]
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'notif' => [
            'title' => "Posting Bunga Tabungan",
            'text' => "Gagal Melakukan Posting Bunga Tabungan",
            'type' => "error"
          ]
        ], 500);
      }
    }

    public function single($NoRek)
    {
      DB::beginTransaction();
      try {
          $setting = Settingtabungan::first();
          $tabungan = Tabungan::find($NoRek);
          $saldoakhir = $tabungan->Saldo + (($setting->BungaPersen * $tabungan->SaldoRendah) / 100);
          $bunga = ($setting->BungaPersen * $tabungan->SaldoRendah) / 100;
          $periodebunga = $tabungan->PeriodeBunga == 12 ? 1 : $tabungan->PeriodeBunga + 1;
          $tabungan->update([
            'Saldo' => $saldoakhir, 
            'SaldoRendah' => $saldoakhir, 
            'SaldoTinggi' => $saldoakhir, 
            'PeriodeBunga' => $periodebunga
          ]);
          if ($bunga > 0) {
              $td = Tabungandetil::create([
                'NoRek' => $tabungan->NoRek,
                'Kredit' => $bunga,
                'Nominal' => $bunga,
                'Keterangan' => 'Bunga Tabungan',
                'SaldoAkhir' => $tabungan->Saldo,
                'TglInput' => session('tgl'),
                'user_id' => Auth::id()
              ]);
              $jd = Fungsi::jurnalDetil($bunga, 7);
              Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
          }
        DB::commit();
        return redirect()->action('TabunganController@index')
        ->with('notif', json_encode([
          'title' => "Posting Bunga Tabungan",
          'text' => "Berhasil Melakukan Posting Bunga Rekening ".$NoRek." Tabungan",
          'type' => "success"
        ]));
      } catch (\Exception $e) {
        return redirect()->action('TabunganController@index')
        ->with('notif', json_encode([
          'title' => "Posting Bunga Tabungan",
          'text' => "Gagal Melakukan Posting Bunga Tabungan",
          'type' => "error"
        ]));
      }
    }
}
