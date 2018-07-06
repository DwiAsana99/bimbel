<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jurnalmaster;
use App\Jurnaldetil;
use App\Akundetil;
use App\Akunkelompok;
use DB;
use PDF;

class LapKeuController extends Controller
{
  public function index()
  {
    return view('laporan.keuangan.index');
  }

  public function jurnalUmum(Request $request)
  {
    $tglawal = $request->tglawal;
    $tglakhir = $request->tglakhir;
    $datas = Jurnalmaster::with('detils')->where('IsAktif', true)->whereBetween('TglInput', [$tglawal, $tglakhir])->get();
    $total = Jurnaldetil::select(DB::raw('SUM(Debet) as Debet, SUM(Kredit) as Kredit'))->whereIn('IdJurnal', $datas->pluck('IdJurnal'))->first();

    $pdf = PDF::loadView('laporan.keuangan.jurnal_umum', compact('datas', 'total', 'tglawal', 'tglakhir'));
    return $pdf->stream('Jurnal Umum - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
  }

  public function bukuBesar(Request $request)
  {
    $akun = $request->akun;
    $ketakun = Akundetil::where('KodeDetil', $akun)->pluck('Keterangan')->first();
    $tglawal = $request->tglawal;
    $tglakhir = $request->tglakhir;
    $awal = Jurnaldetil::whereBB($akun)->totalBB()->where('jurnaldetils.TglInput', '<', $tglawal)
    ->first();
    $total = Jurnaldetil::whereBB($akun)->totalBB()->whereBetween('jurnaldetils.TglInput', [$tglawal, $tglakhir])
    ->first();
    if ($awal->SaldoNormal == 1) {
      $hasil = $awal->TDebet - $awal->TKredit;
    }else {
      $hasil = $awal->TKredit - $awal->TDebet;
    }
    $datas = Jurnaldetil::whereBB($akun)->bukuBesar($tglawal, $tglakhir)->get();
    $saldo[] =['Debet' => 0, 'Kredit' => 0];
    $lastsaldoawal =['Debet' => 0, 'Kredit' => 0];
    $mutasi =['Debet' => 0, 'Kredit' => 0];
    $saldojalan = $hasil;
    foreach ($datas as $k => $v) {
      if ($awal->SaldoNormal == 1) {
        $saldojalan += $v->Debet;
        $saldojalan -= $v->Kredit;
        if ($saldojalan < 0) {
          $saldo[$k] =[
            'Debet' => 0,
            'Kredit' => $saldojalan * -1
          ];
        }else {
          $saldo[$k] =[
            'Debet' => $saldojalan,
            'Kredit' => 0
          ];
        }

        $hmutasi = $total->TDebet - $total->TKredit;
        $mutasi = [
          'Debet' => $hmutasi > 0 ? $hmutasi : 0,
          'Kredit' => $hmutasi < 0 ? ($hmutasi * -1) : 0
        ];
        $lastsaldoawal = [
          'Debet' => $hasil > 0 ? $hasil : 0,
          'Kredit' => $hasil < 0 ? ($hasil * -1) : 0
        ];
      }else {
        $saldojalan -= $v->Debet;
        $saldojalan += $v->Kredit;
        if ($saldojalan < 0) {
          $saldo[$k] =[
            'Debet' => $saldojalan * -1,
            'Kredit' => 0
          ];
        }else {
          $saldo[$k] =[
            'Debet' => 0,
            'Kredit' => $saldojalan
          ];
        }

        $hmutasi = $total->TKredit - $total->TDebet;
        $mutasi = [
          'Debet' => $hmutasi < 0 ? ($hmutasi * -1) : 0,
          'Kredit' => $hmutasi > 0 ? $hmutasi : 0
        ];
        $lastsaldoawal = [
          'Debet' => $hasil < 0 ? ($hasil * -1) : 0,
          'Kredit' => $hasil > 0 ? $hasil : 0
        ];
      }
    }
    $lastsaldo = array_last($saldo);
    $pdf = PDF::loadView('laporan.keuangan.buku_besar', compact('datas', 'hasil', 'saldo', 'akun', 'ketakun', 'total', 'mutasi', 'lastsaldo', 'lastsaldoawal', 'tglawal', 'tglakhir'), [], ['format' => 'A4-L']);
    return $pdf->stream('Buku Besar - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
  }

  public function labaRugi(Request $request)
  {
    $tglawal = $request->tglawal;
    $tglakhir = $request->tglakhir;
    $pendapatans = Akunkelompok::where('KodeGroup', 4)->get();
    $bebans = Akunkelompok::where('KodeGroup', 5)->get();
    $detilpendapatans = Jurnaldetil::totalAkun('4', $tglawal, $tglakhir)->get();
    $detilbebans = Jurnaldetil::totalAkun('5', $tglawal, $tglakhir)->get();

    $pdf = PDF::loadView('laporan.keuangan.laba_rugi', compact('tglawal', 'tglakhir', 'pendapatans', 'bebans', 'detilpendapatans', 'detilbebans'));
    return $pdf->stream('Laba Rugi - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
  }

  public function neracaPercobaan(Request $request)
  {
    $tgl = $request->tgl;
    $data = $this->getNeracaPercobaan($tgl);
    $akuns = $data['akuns'];
    $saldoawal = $data['saldoawal'];
    $mutasi = $data['mutasi'];

    $pdf = PDF::loadView('laporan.keuangan.neraca_percobaan', compact('tgl', 'akuns', 'saldoawal', 'mutasi'), [], ['format' => 'A4-L']);
    return $pdf->stream('Neraca - '.date_format(date_create($tgl),"d-m-Y").'.pdf');
  }

  public function hari($tgl)
  {
    $data = $this->getNeracaPercobaan($tgl);
    $akuns = $data['akuns'];
    $saldoawal = $data['saldoawal'];
    $mutasi = $data['mutasi'];
    
    return view('laporan.hari.index', compact('tgl', 'akuns', 'saldoawal', 'mutasi'));
  }

  private function getNeracaPercobaan($tgl)
  {
    $akuns = Akundetil::select('akungroups.SaldoNormal', 'akundetils.KodeDetil', 'akundetils.Keterangan')
    ->join('akungroups', 'akungroups.KodeGroup', DB::raw('SUBSTRING(akundetils.KodeDetil, 1, 1)'))->get();

    $saldoawal = Jurnaldetil::select(
      'jurnaldetils.KodeAkun', 
      DB::raw('IF(akungroups.SaldoNormal = 1, (SUM(jurnaldetils.Debet) - SUM(jurnaldetils.Kredit)),  (SUM(jurnaldetils.Kredit) - SUM(jurnaldetils.Debet))) AS Saldo')
    )
    ->join('akungroups', 'akungroups.KodeGroup', DB::raw('SUBSTRING(jurnaldetils.KodeAkun, 1, 1)'))
    ->where('jurnaldetils.TglInput', '<', $tgl)
    ->groupBy('jurnaldetils.KodeAkun')
    ->get();

    $mutasi = Jurnaldetil::select('jurnaldetils.KodeAkun', DB::raw('IFNULL(SUM(jurnaldetils.Debet), 0) AS Debet'), DB::raw('IFNULL(SUM(jurnaldetils.Kredit), 0) AS Kredit'))
    ->whereDate('jurnaldetils.TglInput', $tgl)
    ->groupBy('jurnaldetils.KodeAkun')
    ->get();

    return ['akuns' => $akuns, 'saldoawal' => $saldoawal, 'mutasi' => $mutasi];
  }

  public function neraca(Request $request)
  {
    $tgl = $request->tgl;
    $aktivas = Akunkelompok::where('KodeGroup', 1)->get();
    $kewajibans = Akunkelompok::where('KodeGroup', 2)->get();
    $modals = Akunkelompok::where('KodeGroup', 3)->get();
    $detilaktivas = Jurnaldetil::totalAkun('1', $tgl)->get();
    $detilkewajibans = Jurnaldetil::totalAkun('2', $tgl)->get();
    $detilmodals = Jurnaldetil::totalAkun('3', $tgl)->get();
    // return view('laporan.keuangan.laba_rugi', compact('tglawal', 'tglakhir', 'pendapatans', 'bebans'));

    $pdf = PDF::loadView('laporan.keuangan.neraca', compact('tgl', 'aktivas', 'kewajibans', 'modals', 'detilaktivas', 'detilkewajibans', 'detilmodals'), [], ['format' => 'A4-L']);
    return $pdf->stream('Neraca - '.date_format(date_create($tgl),"d-m-Y").'.pdf');
  }

  public function neracaLajur(Request $request)
  {
    $tgl = $request->tgl;
  }
}
