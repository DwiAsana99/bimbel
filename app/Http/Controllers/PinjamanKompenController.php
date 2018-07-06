<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Pinjamankompen;
use App\Nasabah;
use Datatables;
use Fungsi;
use Auth;
use DB;

class PinjamanKompenController extends Controller
{
    public function index()
    {
      return view('pinjaman.kompensasi.index');
    }

    public function dt()
    {
      $datas = Nasabah::JumlahKompen();
      return Datatables::of($datas)
      ->addColumn('detil_url', function($data) {
        return url('/kompensasi/detil/' . $data->NoNasabah);
      })
      ->make(true);
    }

    public function detil(Nasabah $nasabah)
    {
      $datas = $nasabah->pinjamankompens;
      return Datatables::of($datas)
      ->editColumn('TglInput', function ($data) {
          return Fungsi::bulanID($data->TglInput);
      })
      ->make(true);
    }

    public function tambah(Request $request, $pinjaman)
    {
      $response = Pinjaman::pelunasan($pinjaman)->toArray();
      DB::beginTransaction();
      try {
        Pinjaman::where('NoPinjaman', $pinjaman)->update([
          'SisaPinjaman' => 0,
          'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.$response['TglPembayaranSelanjutnya'].'", INTERVAL 1 MONTH)'),
          'IsLunas' => true
        ]);
        $pd = Pinjamandetil::create([
          'NoPinjaman' => $response['NoPinjaman'],
          'Pokok' => $response['SisaPokok'],
          'NominalBunga' => $response['SisaBunga'],
          'Denda' => 0,
          'Jumlah' => $response['Total'],
          'Sisa' => 0,
          'user_id' => Auth::id(),
          'TglInput' => session('tgl')
        ]);

        $jd = Fungsi::jurnalDetil($pd->Pokok, 12);
        Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
        $jd = Fungsi::jurnalDetil($pd->NominalBunga, 13);
        Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
        $jd = Fungsi::jurnalDetil($pd->Denda, 14);
        Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);

        $kode = Pinjaman::noUnik();
        $request->request->add([
          'NoPinjaman' => $kode,
          'NoNasabah' => $response['NoNasabah'],
          'SisaPinjaman' => $request->input('JumlahPinjaman'),
          'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.session('tgl').'", INTERVAL 1 MONTH)'),
          'IsLunas' => false,
          'user_id' => Auth::id(),
          'TglInput' => session('tgl')
        ]);
        $request->merge(['TglJatuhTempo' => date_format(date_create($request->TglJatuhTempo),"Y-m-d"), 'AngsuranPerbulan' => str_replace(",",".",$request->AngsuranPerbulan)]);

        $pinjaman = Pinjaman::create($request->all());

        Pinjamankompen::create([
          'NoNasabah' => $response['NoNasabah'],
          'NoPinjamanKompen' => $response['NoPinjaman'],
          'SisaBungaTerakhir' => $response['SisaBunga'],
          'SisaPokokTerakhir' => $response['SisaPokok'],
          'NoPinjamanBaru' => $kode,
          'JumlahPinjaman' => $request->input('JumlahPinjaman'),
          'user_id' => Auth::id(),
          'TglInput' => session('tgl')
        ]);

        $jd = Fungsi::jurnalDetil($pinjaman->JumlahDiterima, 8);
        Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
        $jd = Fungsi::jurnalDetil($pinjaman->PotonganPropisi, 9);
        Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
        $jd = Fungsi::jurnalDetil($pinjaman->PotonganMateraiMap, 10);
        Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);
        $jd = Fungsi::jurnalDetil($pinjaman->PotonganLain, 11);
        Fungsi::jurnal($pinjaman->NoPinjaman, $jd['Keterangan'], $jd['jurnal']);

        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return redirect()->action('PinjamanController@index')
        ->with('notif', json_encode([
          'title' => "Kompensasi Pinjaman",
          'text' => "Gagal Melakukan Kompensasi Pinjaman.",
          'type' => "error"
        ]));
      }
      return redirect()->action('PinjamanController@index')
      ->with('notif', json_encode([
        'title' => "Kompensasi Pinjaman",
        'text' => "Berhasil Melakukan Kompensasi Pinjaman ".$response['NoPinjaman']." Dengan Nominal Rp ".number_format($response['Total'],2,".",",") .".",
        'type' => "success"
      ]));
    }
}
