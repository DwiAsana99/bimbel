<?php

namespace App\Http\Controllers\Kwitansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Kwitansi;

class KwPinjamanController extends Controller
{
    public function pembayaran($NoKwitansi)
    {   
        $kwitansi = $this->getKwitansi($NoKwitansi);
        $data = $this->getDetil($kwitansi->IdReferensi);
        return view('kwitansi.pinjaman.pembayaran', compact('kwitansi', 'data'));
    }

    private function getKwitansi($key)
    {
        return Kwitansi::find($key);
    }

    private function getMaster($key)
    {
        return Pinjaman::find($key);
    }

    private function getDetil($key)
    {
        return Pinjamandetil::where('id', $key)
        ->with(['pinjaman.nasabah'])
        ->first();
    }
}
