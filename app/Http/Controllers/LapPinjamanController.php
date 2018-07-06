<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Nasabah;

class LapPinjamanController extends Controller
{
    public function index()
    {
        return view('laporan.pinjaman.index');
    }

    public function semua(Request $request)
    {
        $tglawal = $request->tglawal;
        $tglakhir = $request->tglakhir;

        $datas = $this->getPinjaman($tglawal, $tglakhir);
        $total = Pinjamandetil::lapSemuaTotal($tglawal, $tglakhir);

        $pdf = PDF::loadView('laporan.pinjaman.semua', compact('datas', 'total', 'tglawal', 'tglakhir'), [], ['format' => 'A4-L']);
        return $pdf->stream('Pembayaran Pinjaman - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
    }

    public function per(Request $request)
    {
        $tglawal = $request->tglawal;
        $tglakhir = $request->tglakhir;
        $aksi = 0;

        $pinjaman = Pinjaman::find($request->nasabah);
        $detils = Pinjamandetil::where('NoPinjaman', $request->nasabah)
        ->whereBetween('pinjamandetils.TglInput', [$tglawal, $tglakhir])
        ->orderBy('pinjamandetils.created_at', 'ASC')
        ->get();

        $total = Pinjamandetil::where('NoPinjaman', $request->nasabah)->lapSemuaTotal($tglawal, $tglakhir);

        $pdf = PDF::loadView('laporan.pinjaman.per', compact('pinjaman', 'detils', 'total', 'tglawal', 'tglakhir', 'aksi'));
        return $pdf->stream('Pembayaran Pinjaman '.$pinjaman->NoPinjaman.' - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
    }

    public function riwayat(Request $request)
    {
        $aksi = 1;
        
        $pinjaman = Pinjaman::find($request->pinjaman);
        $detils = Pinjamandetil::where('NoPinjaman', $request->pinjaman)
        ->orderBy('pinjamandetils.created_at', 'ASC')
        ->get();

        $total = Pinjamandetil::where('NoPinjaman', $request->pinjaman)->lapSemuaTotal();

        $pdf = PDF::loadView('laporan.pinjaman.per', compact('pinjaman', 'detils', 'total', 'aksi'));
        return $pdf->stream('Riwayat Pinjaman - '.$request->pinjaman.'.pdf');
    }

    public function rekap(Request $request)
    {
        $tgl = $request->tgl;

        $datas = Pinjaman::lapRekap($tgl)->get();
        $total = Pinjamandetil::lapRekapTotal($tgl);

        $pdf = PDF::loadView('laporan.pinjaman.rekap', compact('datas', 'total', 'tgl'), [], ['format' => 'A4-L']);
        return $pdf->stream('Rekap Pinjaman - '.date_format(date_create($tgl),"d-m-Y").'.pdf');
    }

    public function realisasi(Request $request)
    {
        $tglawal = $request->tglawal;
        $tglakhir = $request->tglakhir;

        $datas = Pinjaman::lapRealisasi($tglawal, $tglakhir)->get();
        $total = Pinjaman::lapRealisasiTotal($tglawal, $tglakhir);

        $pdf = PDF::loadView('laporan.pinjaman.realisasi', compact('datas', 'total', 'tglawal', 'tglakhir'), [], ['format' => 'A4-L']);
        return $pdf->stream('Pembayaran Pinjaman - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
    }

    private function getPinjaman($tglawal, $tglakhir, $nopinjaman = null)
    {
        $datas = Pinjaman::Join('nasabahs', 'nasabahs.NoNasabah', 'pinjamans.NoNasabah')
        ->select('NoPinjaman', 'nasabahs.NoNasabah', 'nasabahs.NamaNasabah')
        ->with(['detils' => function($query) use($tglawal, $tglakhir, $nopinjaman) {
            $query->select('NoPinjaman', 'TglInput', 'Pokok', 'NominalBunga', 'Denda', 'Jumlah', 'Sisa')
            ->whereBetween('TglInput', [$tglawal, $tglakhir])
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'asc');
        }])
        ->orderBy('NoPinjaman', 'asc')
        ->whereHas('detils', function($query) use($tglawal, $tglakhir) {
            $query->whereBetween('TglInput', [$tglawal, $tglakhir]);
        })
        ->get();

      return $datas;
    }
}
