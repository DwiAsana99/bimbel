<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Depositodetil;
use PDF;
use DB;

class LapDepositoController extends Controller
{
  public function index()
  {
    return view('laporan.deposito.index');
  }

  public function semua(Request $request)
  {
    $tglawal = $request->tglawal;
    $tglakhir = $request->tglakhir;

    $datas = Deposito::lapSemua($tglawal, $tglakhir)->get();
    $total = Depositodetil::select(DB::raw('SUM(Bunga) AS Bunga'))
    ->whereBetween('depositodetils.TglInput', [$tglawal, $tglakhir])
    ->first();

    $pdf = PDF::loadView('laporan.deposito.semua', compact('datas', 'total', 'tglawal', 'tglakhir'));
    return $pdf->stream('Deposito - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
  }

  public function per(Request $request)
  {
    $NoDeposito = $request->NoDeposito;
    $tglawal = $request->tglawal;
    $tglakhir = $request->tglakhir;

    $datas = Deposito::select('NoDeposito', 'NoTabungan', 'NoNasabah')
    ->with([
        'nasabah:NoNasabah,NamaNasabah', 
        'detils' => function($query) use($tglawal, $tglakhir) {
            return $query->select('id', DB::raw('CONCAT(NoDeposito, "-", id) AS NoBukti'), 'NoDeposito', 'Bunga', 'TglInput')
            ->whereBetween('TglInput', [$tglawal, $tglakhir])
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'asc');
        }
    ])
    ->where('NoDeposito', $NoDeposito)
    ->first();

    $total = Depositodetil::select(DB::raw('SUM(Bunga) AS Bunga'))
    ->where('NoDeposito', $NoDeposito)
    ->whereBetween('TglInput', [$tglawal, $tglakhir])->first();
    
    $pdf = PDF::loadView('laporan.deposito.per', compact('datas', 'total', 'tglawal', 'tglakhir'));
    return $pdf->stream('Deposito '. $datas->NoDeposito .' - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
  }

  public function rekap(Request $request)
  {
    $tgl = $request->tgl;

    $datas = Deposito::join('depositodetils', 'depositos.NoDeposito', 'depositodetils.NoDeposito')
    ->join('nasabahs', 'nasabahs.NoNasabah', 'depositos.NoNasabah')
    ->select(
      'depositos.NoDeposito', 
      'nasabahs.NamaNasabah', 
      'depositos.TglInput', 
      'depositos.TglAkhirBerlaku',       
      DB::raw('SUM(depositodetils.Bunga) AS JmlBunga'))
    ->groupBy('depositos.NoDeposito')
    ->where('depositodetils.TglInput', '<=', $tgl)
    ->orderBy('depositos.NoTabungan', 'ASC')
    ->orderBy('depositos.NoDeposito', 'ASC')
    ->get();

    $total = Depositodetil::select(DB::raw('SUM(Bunga) AS Bunga'))
    ->where('TglInput', '<=', $tgl)
    ->first();

    $pdf = PDF::loadView('laporan.deposito.rekap', compact('datas', 'total', 'tgl'));
    return $pdf->stream('Rekap Deposito - '.date_format(date_create($tgl),"d-m-Y").'.pdf');
  }
}
