<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimPinjamanController extends Controller
{
    public function index()
    {
        return view('simPinjaman.index');
    }

    public function hitung(Request $request)
    {
        $data = $request->all();
        switch ($data['JenisBunga']) {
            case 'menetap':
                $hasil = $this->menetap($request->input('JangkaWaktu'), $request->input('Bunga'), $request->input('JumlahPinjaman'));
                break;
            case 'menurun':
                $hasil = $this->menurun($request->input('JangkaWaktu'), $request->input('Bunga'), $request->input('JumlahPinjaman'));
                break;
            case 'anuitas':
                $hasil = $this->anuitas($request->input('JangkaWaktu'), $request->input('Bunga'), $request->input('JumlahPinjaman'));
                break;
            default:
                # code...
                break;
        }

        return view('simPinjaman.hasil', compact('data', 'hasil'));
    }

    private function menetap($waktu, $bunga, $jumlah)
    {
        $perJumlah = $jumlah / $waktu;
        $perBunga = ($jumlah * ($bunga / 100) * $waktu) / $waktu;

        $hasil = [];
        $sisa = $jumlah;
        for ($i=0; $i < $waktu; $i++) { 
            $sisa = $sisa - $perJumlah;
            $hasil[$i] = array(
                'periode' => $i + 1,
                'pokok' => $perJumlah,
                'bunga' => $perBunga,
                'angsuran' => $perJumlah + $perBunga,
                'sisa' => $sisa
            );
        }

        return $hasil;
    }

    private function menurun($waktu, $bunga, $jumlah)
    {
        $perJumlah = $jumlah / $waktu;

        $hasil = [];
        $sisa = $jumlah;
        for ($i=0; $i < $waktu; $i++) { 
            $perBunga = $sisa * ($bunga / 100);
            $sisa = $sisa - $perJumlah;
            $hasil[$i] = array(
                'periode' => $i + 1,
                'pokok' => $perJumlah,
                'bunga' => $perBunga,
                'angsuran' => $perJumlah + $perBunga,
                'sisa' => $sisa
            );
        }

        return $hasil;
    }

    private function anuitas($waktu, $bunga, $jumlah)
    {
        $hasil = [];
        $sisa = $jumlah;
        $perAngsuran = $sisa * ($bunga / 100) / (1-1/pow(1+($bunga / 100), $waktu));
        for ($i=0; $i < $waktu; $i++) { 
            $perBunga = $sisa * ($bunga / 100);
            $perJumlah = $perAngsuran - $perBunga;
            $sisa = $sisa - $perJumlah;
            $hasil[$i] = array(
                'periode' => $i + 1,
                'pokok' => $perJumlah,
                'bunga' => $perBunga,
                'angsuran' => $perAngsuran,
                'sisa' => $sisa
            );
        }

        return $hasil;
    }
}
