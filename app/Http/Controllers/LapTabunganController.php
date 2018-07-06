<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tabungan;
use App\Tabungandetil;
use PDF;
use DB;

class LapTabunganController extends Controller
{
    private $caseRef = 'CASE
        WHEN Keterangan = "Setoran Awal Tabungan" OR Keterangan = "Setoran Tabungan" THEN "S"
        WHEN Keterangan = "Penarikan Tabungan" THEN "P"
        WHEN Keterangan = "Bunga Tabungan" THEN "BT"
        WHEN Keterangan = "Bunga Deposito" THEN "BD"
    END AS Ref';

    public function index()
    {
        return view('laporan.tabungan.index');
    }

    public function semua(Request $request)
    {
        $tglawal = $request->tglawal;
        $tglakhir = $request->tglakhir;

        $datas = $this->getData($tglawal, $tglakhir);

        $pdf = PDF::loadView('laporan.tabungan.semua', compact('tglawal', 'tglakhir', 'datas'));
        return $pdf->stream('Tabungan Semua - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
    }

    public function per(Request $request)
    {
        $tglawal = $request->tglawal;
        $tglakhir = $request->tglakhir;
        $tn = explode("|",$request->norek);

        $datas = $this->getData($tglawal, $tglakhir, $tn[0]);

        $pdf = PDF::loadView('laporan.tabungan.per', compact('tglawal', 'tglakhir', 'datas'));
        return $pdf->stream('Tabungan '. $tn[0] .' - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y").'.pdf');
    }

    public function rekap(Request $request)
    {
        $tgl = $request->tgl;

        $datas = Tabungandetil::join('tabungans', 'tabungans.NoRek', 'tabungandetils.NoRek')
        ->join('nasabahs', 'nasabahs.NoNasabah', 'tabungans.NoNasabah')
        ->select(
          'tabungans.NoRek', 
          'nasabahs.NamaNasabah', 
          DB::raw('
            SUM(tabungandetils.Debet) AS Debet, 
            SUM(tabungandetils.Kredit) AS Kredit, 
            (SUM(tabungandetils.Kredit) - SUM(tabungandetils.Debet)) AS Saldo
          ')
        )
        ->groupBy('tabungandetils.NoRek')
        ->where('tabungandetils.TglInput', '<=', $tgl)
        ->orderBy('tabungans.NoRek', 'ASC')
        ->get();

        $total = Tabungandetil::select(
          DB::raw('
            SUM(tabungandetils.Debet) AS Debet, 
            SUM(tabungandetils.Kredit) AS Kredit, 
            (SUM(tabungandetils.Kredit) - SUM(tabungandetils.Debet)) AS Saldo
          ')
        )
        ->where('tabungandetils.TglInput', '<=', $tgl)
        ->first();

        $pdf = PDF::loadView('laporan.tabungan.rekap', compact('datas', 'total', 'tgl'));
        return $pdf->stream('Rekap Tabungan - '.date_format(date_create($tgl),"d-m-Y").'.pdf');
    }

    private function getData($tglawal, $tglakhir, $norek = null)
    {
        $datas = [
            'datas' => $this->getTabungan($tglawal, $tglakhir, $norek), 
            'total' => $this->getTotalTabungan($tglawal, $tglakhir, $norek), 
            'rinci' => $this->getRinciTabungan($tglawal, $tglakhir, $norek)
        ];

        return $datas;
    }

    private function getTabungan($tglawal, $tglakhir, $norek = null)
    {
        $caseRef = $this->caseRef;
        $datas = Tabungan::select('NoRek', 'NoNasabah')
        ->with(['nasabah:NoNasabah,NamaNasabah', 'detils' => function($query) use($tglawal, $tglakhir, $caseRef, $norek) {
            $query->when($norek, function ($query) {
                return $query->select('NoRek','TglInput','Debet','Kredit', 'Keterangan', 'SaldoAkhir');
            }, function ($query) use ($caseRef) {
                return $query->select('NoRek','TglInput','Debet','Kredit', DB::raw($caseRef), 'SaldoAkhir');
            })
            ->whereBetween('TglInput', [$tglawal, $tglakhir])
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'asc');
        }])
        ->orderBy('NoRek', 'asc')
        ->when($norek, function ($query) use ($norek) {
            return $query->where('NoRek', $norek)
            ->first();
        }, function ($query) use($tglawal, $tglakhir) {
            return $query->whereHas('detils', function($query) use($tglawal, $tglakhir) {
                $query->whereBetween('TglInput', [$tglawal, $tglakhir]);
            })
            ->get();
        });

        return $datas;
    }

    private function getTotalTabungan($tglawal, $tglakhir, $norek = null)
    {
        $total = Tabungandetil::select(DB::raw('SUM(Debet) AS Debet, SUM(Kredit) AS Kredit'))
        ->when($norek, function ($query) use ($norek) {
            return $query->where('NoRek', $norek);
        })
        ->whereBetween('TglInput', [$tglawal, $tglakhir])->first();

        return $total->toArray();
    }

    private function getRinciTabungan($tglawal, $tglakhir, $norek = null)
    {
        $rinci = Tabungandetil::select(DB::raw($this->caseRef.', IFNULL(SUM(Debet) + SUM(Kredit), 0) AS jumlah'))
        ->groupBy(DB::raw('Ref'))
        ->when($norek, function ($query) use ($norek) {
            return $query->where('NoRek', $norek);
        })
        ->whereBetween('TglInput', [$tglawal, $tglakhir])
        ->orderBy('Ref')
        ->get();

        $jadi = ['S' => 0, 'P' => 0, 'BT' => 0, 'BD' => 0];
        foreach ($rinci as $r) {
          $jadi[$r->Ref] = $r->jumlah;
        }

        return $jadi;
    }
}
