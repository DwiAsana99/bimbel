<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tabungan;
use App\Tabungandetil;
use App\Tutupbuku;
use Fungsi;
use DB;
use Auth;

class TabunganController extends Controller
{
    public function show($norek)
    {
      $tabungan = Tabungan::find($norek);
      if ($tabungan) {
          $tabungan->nasabah;
          $response = $tabungan;
          $response->detils = Tabungandetil::where('NoRek', $tabungan->NoRek)->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'desc')->limit(7)->get();
          return response()->json(['msg' => 'Berhasil Mengambil Data', 'data' => $response], 200);
      }else{
          return response()->json(['msg' => 'Data Tidak Tersedia'], 462);
      }
    }

    public function setor(Request $request, $norek)
    {
      DB::beginTransaction();
      try {
        $tabungan = Tabungan::find($norek);
        $tabungan->nasabah;
        $tgl = Tutupbuku::akhir()->pluck('aktif')->first();
        $saldo = $request->nominal + $tabungan->Saldo;
        $tabungan->update(['Saldo' => $saldo, 'SaldoTinggi' => $saldo]);
        $td = Tabungandetil::create([
          'NoRek' => $tabungan->NoRek,
          'Kredit' => $request->nominal,
          'Nominal' => $request->nominal,
          'Keterangan' => 'Setoran Tabungan',
          'SaldoAkhir' => $tabungan->Saldo,
          'TglInput' => $tgl,
          'user_id' => Auth::id()
        ]);
        $jd = Fungsi::jurnalDetil($request->nominal, 5);
        Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        DB::commit();
        $response = $tabungan;
        $response->detils = Tabungandetil::where('NoRek', $tabungan->NoRek)->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'desc')->limit(5)->get();
        return response()->json(['msg' => 'Berhasil Melakukan Setoran Tabungan', 'data' => $response], 200);
      }catch(\Exception $e){
        DB::rollback();
        return response()->json(['msg' => 'Gagal Melakukan Setoran Tabungan'], 463);
      }
    }

    public function tarik(Request $request, $norek)
    {
      DB::beginTransaction();
      try {
        $tabungan = Tabungan::find($norek);
        $tabungan->nasabah;
        $tgl = Tutupbuku::akhir()->pluck('aktif')->first();
        $saldo = $tabungan->Saldo - $request->nominal;
        $saldorendah = $saldo < $tabungan->SaldoRendah ? $saldo : $tabungan->SaldoRendah;
        $tabungan->update(['Saldo' => $saldo, 'SaldoRendah' => $saldorendah]);
        $td = Tabungandetil::create([
          'NoRek' => $tabungan->NoRek,
          'Debet' => $request->nominal,
          'Nominal' => $request->nominal,
          'Keterangan' => 'Penarikan Tabungan',
          'SaldoAkhir' => $tabungan->Saldo,
          'TglInput' => $tgl,
          'user_id' => Auth::id()
        ]);
        $jd = Fungsi::jurnalDetil($request->nominal, 6);
        Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        DB::commit();
        $response = $tabungan;
        $response->detils = Tabungandetil::where('NoRek', $tabungan->NoRek)->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'desc')->limit(5)->get();
        return response()->json(['msg' => 'Berhasil Melakukan Penarikan Tabungan', 'data' => $response], 200);
      }catch(\Exception $e){
        DB::rollback();
        return response()->json(['msg' => 'Gagal Melakukan Penarikan Tabungan'], 463);
      }
    }
    public function mutasi($norek, $tglawal, $tglakhir)
    {
      $tabungan = Tabungan::find($norek);
      $tabungan->nasabah;
      $tabungan->detils = Tabungandetil::select('tabungandetils.*', DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9)) AS TglInputWaktu'))
      ->where('NoRek', $norek)
      ->whereBetween('tabungandetils.TglInput', [$tglawal, $tglakhir])
      ->orderBy('TglInputWaktu', 'asc')
      ->get();
      if ($tabungan) {
        return response()->json(['msg' => 'Berhasil Mengambil Data', 'data' => $tabungan], 200);
      }else{
        return response()->json(['msg' => 'Data Tidak Tersedia'], 462);
      }
    }
}
