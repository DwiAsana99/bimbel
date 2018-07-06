<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tutupbuku;
use App\Tabungan;
use App\Deposito;
use App\Pinjaman;
use App\Settingpinjaman;
use App\Settingdeposito;
use DB;
use Session;

class TutupBukuController extends Controller
{
  public function update(Request $request)
  {
    if (session('aturtgl') == 1) {
      try {
        session()->forget('tgl');
        session(['tgl' => $request['tglpilih']]);
        return response()->json([
          'msg' => 'Berhasil Mengganti Tanggal Aktif Menjadi '.$request['tglpilih'].' (DALAM MODE PENGATURAN TANGGAL AKTIF).'
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'msg' => 'Gagal Mengganti Tanggal Aktif Menjadi '.$request['tglpilih'].' (DALAM MODE PENGATURAN TANGGAL AKTIF).'
        ], 400);
      }
    }else{
      DB::beginTransaction();
      try {
        // $setting = Settingpinjaman::first();
        // $this->updatePinjaman($setting);
        // $this->kartuPokok($setting);
        // $this->kartuBunga($setting);
        // $this->kartuDenda($setting);
        Tutupbuku::create(['sebelumnya' => session('tgl'), 'aktif' => $request['tglpilih']]);
        
        session()->forget('tgl');
        session(['tgl' => $request['tglpilih']]);
        
        DB::commit();
        return response()->json([
          'msg' => 'Berhasil Mengganti Tanggal Aktif Menjadi '.$request['tglpilih'].'.'
        ], 200);
      } catch (Exception $e) {
        DB::rollback();
        dd($e);
        return response()->json([
          'msg' => 'Gagal Mengganti Tanggal Aktif Menjadi '.$request['tglpilih'].'.'
        ], 400);
      }
    }
  }

  public function getPostingData()
  {
    $periodebunga = (int)substr(session('tgl'),5,-3);
    $setting = Settingdeposito::first()->pluck('BulanPostingBunga');
    $tabungan = Tabungan::where('IsAktif', true)->where('PeriodeBunga', '<=',$periodebunga)->count();
    $deposito = Deposito::where('IsBerakhir', false)
    ->where(DB::raw('TIMESTAMPDIFF(MONTH, TglBungaAkhir, "'.session('tgl').'")'), '>=', $setting)->count();

    return response()->json(['tabungan' => $tabungan, 'deposito' => $deposito], 200);
  }

  private function updatePinjaman($setting)
  {
    Pinjaman::where('IsLunas', false)
    ->whereRaw('DATEDIFF("'. session('tgl') .'", TglPembayaranSelanjutnya) > '.$setting->Lewat)
    ->update(['PeriodeSelanjutnya' => DB::raw('(PeriodeSelanjutnya + 1)')]);
    return;
  }

  private function kartuPokok($setting)
  {
    $pinjaman = Pinjaman::select(
      'Nopinjaman', 
      'PeriodeSelanjutnya AS Periode', 
      DB::raw('
        IF(SisaPinjaman < AngsuranPerbulan, SisaPinjaman, AngsuranPerbulan) AS Debet,
        "'. session('tgl') .'" AS TglInput
      ')
    )
    ->where('IsLunas', false)
    ->whereRaw('DATEDIFF("'. session('tgl') .'", TglPembayaranSelanjutnya) > '.$setting->Lewat)
    ->get()
    ->toArray();

    DB::table('pinjamandetilpokoks')->insert($pinjaman);
    return;
  }
  private function kartuBunga($setting)
  {
    $pinjaman = Pinjaman::select(
      'Nopinjaman', 
      'PeriodeSelanjutnya AS Periode', 
      DB::raw('
        CASE
          WHEN JenisBunga = "Menurun" THEN SisaPinjaman * Bunga / 100
          WHEN JenisBunga = "Menetap" THEN AngsuranPerbulan * Bunga / 100
        END AS Debet,
        "'. session('tgl') .'" AS TglInput
      ')
    )
    ->where('IsLunas', false)
    ->whereRaw('DATEDIFF("'. session('tgl') .'", TglPembayaranSelanjutnya) > '.$setting->Lewat)
    ->get()
    ->toArray();

    DB::table('pinjamandetilbungas')->insert($pinjaman);
    return;
  }
  private function kartuDenda($setting)
  {
    $pinjaman = Pinjaman::select(
      'Nopinjaman', 
      'PeriodeSelanjutnya AS Periode', 
      DB::raw('
        (SELECT 
          IF(
            IFNULL(SUM(Debet) - SUM(Kredit), 0) > 0, 
            CASE
              WHEN '.$setting->Mode.' = 1 THEN (SUM(Debet) - SUM(Kredit)) * '.$setting->DendaPinjaman.' / 100
              WHEN '.$setting->Mode.' = 0 THEN (SUM(Debet) - SUM(Kredit)) + '.$setting->DendaPinjaman.' 
            END, 
            0
          ) 
          FROM pinjamandetilpokoks
          WHERE NoPinjaman = pinjamans.NoPinjaman AND Periode < pinjamans.PeriodeSelanjutnya
        )AS Debet,
        "'. session('tgl') .'" AS TglInput
      ')
    )
    ->where('IsLunas', false)
    ->whereRaw('DATEDIFF("'. session('tgl') .'", TglPembayaranSelanjutnya) > '.$setting->Lewat)
    ->get()
    ->toArray();

    DB::table('pinjamandetildendas')->insert($pinjaman);
    return;
  }
}
