<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Settingpinjaman;
use App\Settingtabungan;
use App\Nasabah;
// use App\Pinjamandetilpokok;
// use App\Pinjamandetilbunga;
// use App\Pinjamandetildenda;
use App\Kwitansi;
use App\Tabungandetil;
use App\Tabungan;
use DB;
use Auth;
use Fungsi;

class PinjamanController extends Controller
{
  public function index()
  {
    $setting = Settingpinjaman::first();
    $denda = $setting->DendaPinjaman;
    $tglbuku = session('tgl');
    $minTabungan = Settingtabungan::pluck('SaldoMin')->first();
    return view('pinjaman.indextest', compact('denda', 'tglbuku', 'setting', 'minTabungan'));
  }

  public function dt()
  {
    $datas = Pinjaman::with('nasabah')->where('IsLunas', false);
    return Datatables::of($datas)
    ->editColumn('TglInput', function ($data) {
        return Fungsi::bulanID($data->TglInput);
    })
    ->editColumn('JatuhTempo', function ($data) {
        return Fungsi::bulanID($data->TglJatuhTempo);
    })
    ->addColumn('detil_url', function($data) {
      return url('/pinjaman/detil/' . $data->NoPinjaman);
    })
    ->addColumn('action', function ($data) {
        return '<button class="btn btn-xs btn-success" id="btnbayar" data-toggle="modal" data-target="#modal-bayar"><i class="fa fa-money"></i> Bayar</button>
        <button class="btn btn-xs btn-warning" id="btnkompensasi" data-toggle="modal" data-target="#modal-kompensasi"><i class="fa fa-repeat"></i> Kompen</button>
        <button class="btn btn-xs btn-info" id="btnlunas" data-toggle="modal" data-target="#modal-lunas"><i class="fa fa-check-square"></i> Lunas</button>';
    })
    ->make(true);
  }

  public function detil($pinjaman = null)
  {
    $datas = Pinjamandetil::where('NoPinjaman', $pinjaman)->select('pinjamandetils.*', DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9)) AS urutan'))->get();
    return Datatables::of($datas)
    ->editColumn('Debet', function ($data) {
        return is_null($data->Debet) ? 0 : $data->Debet;
    })
    ->editColumn('TglInput', function ($data) {
        return Fungsi::bulanID($data->TglInput);
    })
    ->make(true);
  }

  public function create()
  {
    return view('pinjaman.tambah');
  }

  public function select2nasabah(Request $r)
  {
      $term = trim($r->q);
      if (empty($term)) {
        $tags = Nasabah::with('tabungan:NoRek,NoNasabah')->whereNotIn('NoNasabah', function($query) {
          $query->select('NoNasabah')->from('pinjamans')->where('IsLunas', false);
        })->get();
      }else {
        $tags = Nasabah::with('tabungan:NoRek,NoNasabah')->whereNotIn('NoNasabah', function($query) {
          $query->select('NoNasabah')->from('pinjamans')->where('IsLunas', false);
        })->whereRaw('(NoNasabah LIKE "%'. $term .'%" OR NamaNasabah LIKE "%'. $term .'%")')
        ->get();
      }
      $formatted_tags = [];
      foreach ($tags as $tag) {
          $formatted_tags[] = [
            'id' => $tag->NoNasabah, 
            'text' => $tag->NoNasabah.' | '.$tag->NamaNasabah, 
            'NoTabungan' => ($tag->tabungan ? $tag->tabungan->NoRek : null)
        ];
      }
      return response()->json($formatted_tags);
  }

  public function select2(Request $r)
  {
    $term = trim($r->q);
    if (empty($term)) {
      $tags = Pinjaman::join('nasabahs', 'pinjamans.NoNasabah', 'nasabahs.NoNasabah')
      ->select('pinjamans.NoPinjaman', 'nasabahs.NamaNasabah')
      ->where('pinjamans.IsLunas', false)
      ->get();
    }else {
      $tags = Pinjaman::join('nasabahs', 'pinjamans.NoNasabah', 'nasabahs.NoNasabah')
      ->select('pinjamans.NoPinjaman', 'nasabahs.NamaNasabah')
      ->where('pinjamans.IsLunas', false)
      ->where('pinjamans.NoPinjaman', 'LIKE', '%'. $term .'%')
      ->orWhere('nasabahs.NamaNasabah', 'LIKE', '%'. $term .'%')
      ->get();
    }
    $formatted_tags = [];
    foreach ($tags as $tag) {
        $formatted_tags[] = ['id' => $tag->NoPinjaman, 'text' => $tag->NoPinjaman.' | '.$tag->NamaNasabah];
    }
    return response()->json($formatted_tags);
  }

  public function select2lunas(Request $r)
  {
    $term = trim($r->q);
    $query = Pinjaman::join('nasabahs', 'nasabahs.NoNasabah', '=', 'pinjamans.NoNasabah')
    ->where('pinjamans.IsLunas', true);

    if (!empty($term)) {
      $query = $query->where('pinjamans.NoPinjaman', 'LIKE', '%'. $term .'%')
      ->orWhere('nasabahs.NamaNasabah', 'LIKE', '%'. $term .'%');
    }

    $nasabahs = $query->select('nasabahs.NoNasabah', 'nasabahs.NamaNasabah')->distinct()->get();
    $tags = $query->select('pinjamans.NoNasabah', 'pinjamans.NoPinjaman')->get();
    $formatted_tags = [];

    foreach ($nasabahs as $gk => $gv) {
      $formatted_tags[] = ['text' => $gv->NamaNasabah];
      foreach ($tags as $tk => $tv) {
        if ($gv->NoNasabah == $tv->NoNasabah){
          $formatted_tags[$gk]['children'][] = ['id' => $tv->NoPinjaman, 'text' => $tv->NoPinjaman];
        }
      }
    }
    return response()->json($formatted_tags);
  }

  public function tambah(Request $request)
  {
    DB::beginTransaction();
    try {
      $tabungancheck = Tabungandetil::where('NoRek', $request->input('NoTabungan'))->first();
      if ($tabungancheck == null) {
        $statusTabungan = 'Setoran Awal Tabungan';
      }else {
        $statusTabungan = 'Setoran Tabungan';
      }

      if ($request->input('PotonganTabungan') > 0) {
        $tabungan = Tabungan::find($request->input('NoTabungan'));
        $saldo = $request->input('PotonganTabungan') + $tabungan->Saldo;
        $tabungan->update(['Saldo' => $saldo, 'SaldoTinggi' => $saldo]);
        $td = Tabungandetil::create([
            'NoRek' => $request->input('NoTabungan'),
            'Kredit' => $request->input('PotonganTabungan'),
            'Nominal' => $request->input('PotonganTabungan'),
            'Keterangan' => $statusTabungan,
            'SaldoAkhir' => $saldo,
            'TglInput' => session('tgl'),
            'user_id' => Auth::id()
        ]);

        $tjd = Fungsi::jurnalDetil($request->input('PotonganTabungan'), 5);
        Fungsi::jurnal($request->input('NoTabungan').'-'.$td->id, $tjd['Keterangan'], $tjd['jurnal']);
      }

      $kode = Pinjaman::noUnik();
      $request->request->add([
        'NoPinjaman' => $kode,
        'SisaPinjaman' => $request->input('JumlahPinjaman'),
        'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.session('tgl').'", INTERVAL 1 MONTH)'),
        'IsLunas' => false,
        'user_id' => Auth::id(),
        'PeriodeSelanjutnya' => 1,
        'TglInput' => session('tgl')
      ]);
      $request->merge(['TglJatuhTempo' => date_format(date_create($request->TglJatuhTempo),"Y-m-d"), 'AngsuranPerbulan' => str_replace(",",".",$request->AngsuranPerbulan)]);

      $pinjaman = Pinjaman::create($request->all());

      $jd = Fungsi::jurnalDetil($pinjaman->JumlahDiterima, 8);
      Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pinjaman->PotonganPropisi, 9);
      Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pinjaman->PotonganMateraiMap, 10);
      Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pinjaman->PotonganLain, 11);
      Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pinjaman->PotonganAsuransi, 18);
      Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      return redirect()->action('PinjamanController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Pinjaman",
        'text' => "Data Pinjaman Baru Gagal Dibuat.",
        'type' => "error"
      ]));
    }
    return redirect()->action('PinjamanController@index')
    ->with('notif', json_encode([
      'title' => "Tambah Pinjaman",
      'text' => "Data Pinjaman ".$pinjaman->NoPinjaman." Dengan Nominal Rp ". number_format($pinjaman->JumlahPinjaman,0,".",","). " Berhasil Dibuat.",
      'type' => "success"
    ]));
  }

  public function bayardetil($pinjaman)
  {
    $result = DB::select( DB::raw("SELECT IFNULL(SUM(d.pokok), 0) AS dibayar,
      IFNULL((AngsuranPerbulan * PERIOD_DIFF(DATE_FORMAT(?, '%Y%m'), DATE_FORMAT(p.TglInput, '%Y%m')))-SUM(d.pokok), 0) AS tunggakanpokok,
      IF(p.JenisBunga = 'menetap', p.Bunga * p.JumlahPinjaman / 100, p.Bunga * p.SisaPinjaman / 100) AS bunga,
      (SELECT DendaPinjaman FROM settingpinjamans LIMIT 1) * DATEDIFF(?, p.TglPembayaranSelanjutnya) AS denda,
      p.NoPinjaman, p.JumlahPinjaman, p.SisaPinjaman, p.AngsuranPerBulan, n.NamaNasabah, n.NoNasabah, t.NoRek, t.Saldo, 
      (SELECT TglInput FROM pinjamandetils WHERE NoPinjaman = p.NoPinjaman ORDER BY TglInput DESC LIMIT 1) AS bayarakhir
      FROM pinjamans AS p JOIN nasabahs AS n ON p.NoNasabah = n.NoNasabah
      LEFT JOIN pinjamandetils AS d ON p.NoPinjaman = d.NoPinjaman
      LEFT JOIN tabungans AS t ON n.NoNasabah = t.NoNasabah 
      WHERE p.NoPinjaman = ?"), [session('tgl'), session('tgl'), $pinjaman]);

    $response = $result[0];

    $response->tunggakanpokok = $response->tunggakanpokok < 0 ? 0 : $response->tunggakanpokok;
    $response->denda = $response->denda < 0 ? 0 : $response->denda;
    $response->bayarakhir = is_null($response->bayarakhir) ? 'Belum Pernah Membayar' : Fungsi::bulanID($response->bayarakhir);
    $response->total = $response->tunggakanpokok + $response->AngsuranPerBulan + $response->bunga + $response->denda;
    return response()->json($response);
    // $pinjaman = Pinjaman::bayar($pinjaman);
    // return response()->json($pinjaman);
  }
  
  public function bayar(Request $request, Pinjaman $pinjaman)
  {
    DB::beginTransaction();
    try {
      if ($request->input('denganTabungan') == "on") {
          $tabungan = Tabungan::find($request->input('NoTabungan'));
          $saldo = $tabungan->Saldo - $request->input('Jumlah');
          $saldorendah = $saldo < $tabungan->SaldoRendah ? $saldo : $tabungan->SaldoRendah;
          $tabungan->update(['Saldo' => $saldo, 'SaldoRendah' => $saldorendah]);
          $td = Tabungandetil::create([
              'NoRek' => $request->input('NoTabungan'),
              'Debet' => $request->input('Jumlah'),
              'Nominal' => $request->input('Jumlah'),
              'Keterangan' => 'Penarikan Tabungan',
              'SaldoAkhir' => $saldo,
              'TglInput' => session('tgl'),
              'user_id' => Auth::id()
          ]);
          $tjd = Fungsi::jurnalDetil($request->input('Jumlah'), 6);
          Fungsi::jurnal($request->input('NoTabungan').'-'.$td->id, $tjd['Keterangan'], $tjd['jurnal']);
      }

      $sisa = $pinjaman->SisaPinjaman - $request->input('Pokok');
      $lunas = $sisa <= 0 ? true : false;
      $pinjaman->update([
        'SisaPinjaman' => $sisa,
        'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.$pinjaman->TglPembayaranSelanjutnya.'", INTERVAL 1 MONTH)'),
        'IsLunas' => $lunas,
        'TempBayar' => session('tgl')
      ]);
      $request->request->add([
        'NoPinjaman' => $pinjaman->NoPinjaman,
        'Sisa' => $sisa,
        'user_id' => Auth::id(),
        'TglInput' => session('tgl')
      ]);
      $pd = Pinjamandetil::create($request->except(['denganTabungan', 'SaldoTabungan', 'NoTabungan']));

      $jd = Fungsi::jurnalDetil($pd->Pokok, 12);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pd->NominalBunga, 13);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pd->Denda, 14);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);

      $kwitansi = Fungsi::kwitansi($pd->id, 'Pembayaran Kredit', '0101');

      DB::commit();

      return redirect()->action('PinjamanController@index')
      ->with('notif', json_encode([
        'title' => "Pembayaran Pinjaman",
        'text' => "Berhasil Membayar Pinjaman ".$pinjaman->NoPinjaman." Dengan Nominal Rp ".number_format(str_replace(",",".",$request->Jumlah), 2,",",".") .".",
        'type' => "success"
      ]))
      ->with('kwitansi', route('kw.pinjaman.pembayaran', ['NoKwitansi' => $kwitansi->NoKwitansi]));
    } catch (\Exception $e) {
      DB::rollback();
      return redirect()->action('PinjamanController@index')
      ->with('notif', json_encode([
        'title' => "Pembayaran Pinjaman",
        'text' => "Gagal Membayar Pinjaman.",
        'type' => "error"
      ]));
    }
  }

  public function lunasdetil($pinjaman)
  {
    $response = Pinjaman::pelunasan($pinjaman);
    return response()->json($response);
  }

  public function lunas(Request $request, $pinjaman)
  {
    $response = Pinjaman::pelunasan($pinjaman)->toArray();
    DB::beginTransaction();
    try {
      Pinjaman::where('NoPinjaman', $pinjaman)->update([
        'SisaPinjaman' => 0,
        'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.$response['TglPembayaranSelanjutnya'].'", INTERVAL 1 MONTH)'),
        'IsLunas' => true
      ]);
      $request->request->add([
        'NoPinjaman' => $response['NoPinjaman'],
        'Pokok' => $response['SisaPokok'],
        'NominalBunga' => $request->bunga_lunas,
        'Denda' => 0,
        'Jumlah' => $request->total_lunas,
        'Sisa' => 0,
        'user_id' => Auth::id(),
        'TglInput' => session('tgl')
      ]);
      $pd = Pinjamandetil::create($request->except(['bunga_lunas', 'total_lunas']));

      $jd = Fungsi::jurnalDetil($pd->Pokok, 12);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pd->NominalBunga, 13);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
      $jd = Fungsi::jurnalDetil($pd->Denda, 14);
      Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      return redirect()->action('PinjamanController@index')
      ->with('notif', json_encode([
        'title' => "Pelunasan Pinjaman",
        'text' => "Gagal Melakukan Pelunasan Pinjaman.",
        'type' => "error"
      ]));
    }
    return redirect()->action('PinjamanController@index')
    ->with('notif', json_encode([
      'title' => "Pelunasan Pinjaman",
      'text' => "Berhasil Melakukan Pelunasan Pinjaman ". $response['NoPinjaman'] ." Dengan Nominal Rp ". number_format($response['Total'],2,".",",") .".",
      'type' => "success"
    ]));
  }

}
