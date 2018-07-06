<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nasabah;
use App\Anggota;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Tabungan;
use App\Tabungandetil;
use App\Deposito;
use App\Depositodetil;
use App\Akundetil;
use App\Jurnaldetil;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $tgl = session('tgl');
        $nasabah = $this->getNasabah();
        $pinjaman = $this->getPinjaman($tgl);
        $tabungan = $this->getTabungan($tgl);
        $deposito = $this->getDeposito($tgl);
        $kasMasukKeluar = json_encode($this->getKasMasukKeluar($tgl));
        $tabunganTinggi = json_encode($this->getTabunganTinggi());
        $pinjamanTinggi = json_encode($this->getPinjamanTinggi());
        $pendapatanBeban = json_encode($this->getPendapatanBeban($tgl));

        return view('home', compact(
            'nasabah', 
            'pinjaman', 
            'tabungan', 
            'deposito', 
            'kasMasukKeluar',
            'pendapatanBeban',
            'tabunganTinggi',
            'pinjamanTinggi'
        ));
    }

    private function getNasabah()
    {
        $nasabah = Nasabah::whereNotIn('NoNasabah', function($query) {
            $query->select('NoNasabah')
            ->from('anggotas')
            ->where('IsAktif', true);
        })
        ->where('IsAktif', true)
        ->count();

        $anggota = Anggota::where('IsAktif', true)
        ->count();

        return [
            'nasabah' => $nasabah, 
            'anggota' => $anggota, 
            'total' => $nasabah + $anggota
        ];
    }
    private function getPinjaman($tgl)
    {
        $total = Pinjaman::where('IsLunas', false)
        ->count();

        $baru = Pinjaman::where('TglInput', $tgl)
        ->count();

        $pembayaran = Pinjamandetil::where('TglInput', $tgl)
        ->count();

        return [
            'total' => $total, 
            'baru' => $baru, 
            'pembayaran' => $pembayaran
        ];
    }

    private function getTabungan($tgl)
    {
        $total = Tabungan::where('IsAktif', true)
        ->whereNull('TglTutup')
        ->count();

        $baru = Tabungan::where('TglInput', $tgl)
        ->count();

        $setor = Tabungandetil::where('TglInput', $tgl)
        ->where('Kredit', '>', 0)
        ->count();

        $tarik = Tabungandetil::where('TglInput', $tgl)
        ->where('Debet', '>', 0)
        ->count();

        return [
            'total' => $total, 
            'baru' => $baru, 
            'setor' => $setor, 
            'tarik' => $tarik
        ];
    }

    private function getDeposito($tgl)
    {
        $total = Deposito::where('IsBerakhir', false)
        ->count();
        
        $baru = Deposito::where('TglInput', $tgl)
        ->count();

        $bunga = Depositodetil::where('TglInput', $tgl)
        ->count();

        $tempo = Deposito::where('TglBungaAkhir', 'DATE_ADD(DATE_ADD('.$tgl.',INTERVAL -1 MONTH)')
        ->where('TglAkhirBerlaku', '>', $tgl)
        ->count();

        return [
            'total' => $total, 
            'baru' => $baru, 
            'bunga' => $bunga, 
            'tempo' => $tempo
        ];
    }

    private function getPendapatanBeban($tgl)
    {
        $pendapatan = Akundetil::total('4', '=', $tgl)
        ->pluck('Total')
        ->toArray();

        $pendapatantext = Akundetil::select('Keterangan')
        ->where(DB::raw('SUBSTRING(akundetils.KodeDetil, 1, 1)'), '4')
        ->orderBy('KodeDetil', 'ASC')
        ->get()
        ->pluck('Keterangan')
        ->toArray();

        $beban = Akundetil::total('5', '=', $tgl)
        ->pluck('Total')
        ->toArray();

        $bebantext = Akundetil::select('Keterangan')
        ->where(DB::raw('SUBSTRING(akundetils.KodeDetil, 1, 1)'), '5')
        ->orderBy('KodeDetil', 'ASC')
        ->get()
        ->pluck('Keterangan')
        ->toArray();

        $totalPendapatan = Jurnaldetil::total('4', '=', $tgl);
        $totalBeban = Jurnaldetil::total('5', '=', $tgl);

        return [
            'pendapatan' => [
                'label' => $pendapatantext,
                'data' => $pendapatan
            ], 
            'beban' => [
                'label' => $bebantext,
                'data' => $beban
            ], 
            'total' => [$totalPendapatan->total, $totalBeban->total]
        ];
    }

    private function getKasMasukKeluar($tgl)
    {
        $tglawal = Carbon::createFromFormat('Y-m-d', $tgl)->addDays(-6)->format('Y-m-d');
        $kas = Jurnaldetil::join('jurnalmasters', 'jurnalmasters.IdJurnal', 'jurnaldetils.IdJurnal')
        ->where(DB::raw('SUBSTR(jurnaldetils.KodeAkun, 1, 4)'), '1101')
        ->where('jurnalmasters.IsAktif', true)
        ->whereBetween('jurnaldetils.TglInput', [$tglawal, $tgl])
        ->select(DB::raw('DATE_FORMAT(jurnaldetils.TglInput, "%d/%m/%Y") AS tgl, IFNULL(SUM(jurnaldetils.Debet), 0) AS masuk, IFNULL(SUM(jurnaldetils.Kredit), 0) AS keluar'))
        ->groupBy('jurnaldetils.TglInput')
        ->get();
        $data = ['tgl' => $kas->pluck('tgl')->toArray(), 'masuk'=> $kas->pluck('masuk')->toArray(), 'keluar'=> $kas->pluck('keluar')->toArray()];
        return $data;
    }

    private function getTabunganTinggi()
    {
        $tabungan = Tabungan::select('nasabahs.NamaNasabah', 'tabungans.Saldo')
        ->join('nasabahs', 'nasabahs.NoNasabah', 'tabungans.NoNasabah')
        ->where('tabungans.IsAktif', true)
        ->whereNull('tabungans.TglTutup')
        ->orderBy('tabungans.Saldo', 'desc')
        ->limit(10)
        ->get();
        $data = ['nasabah' => $tabungan->pluck('NamaNasabah')->toArray() , 'saldo'=> $tabungan->pluck('Saldo')->toArray()];
        return $data;
    }

    private function getPinjamanTinggi()
    {
        $pinjaman = Pinjaman::select('nasabahs.NamaNasabah', 'pinjamans.SisaPinjaman')
        ->join('nasabahs', 'nasabahs.NoNasabah', 'pinjamans.NoNasabah')
        ->where('pinjamans.IsLunas', false)
        ->orderBy('pinjamans.SisaPinjaman', 'desc')
        ->limit(10)
        ->get();
        $data = ['nasabah' => $pinjaman->pluck('NamaNasabah')->toArray() , 'sisa'=> $pinjaman->pluck('SisaPinjaman')->toArray()];
        return $data;
    }
}