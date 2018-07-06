<?php

namespace App\Http\Controllers\Kwitansi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Txtemplatedetil;
use App\Kwitansi;

class KwKasController extends Controller
{
    public function masuk($NoKwitansi)
    {   
        $kwitansi = $this->getKwitansi($NoKwitansi);
        $data = $this->getTemplate($kwitansi->IdReferensi);
        return view('kwitansi.kas.masuk', compact('kwitansi', 'data'));
    }

    public function keluar($NoKwitansi)
    {   
        $kwitansi = $this->getKwitansi($NoKwitansi);
        $data = $this->getTemplate($kwitansi->IdReferensi);
        return view('kwitansi.kas.keluar', compact('kwitansi', 'data'));
    }

    private function getKwitansi($key)
    {
        return Kwitansi::find($key);
    }

    private function getTemplate($key)
    {
        return Txtemplatedetil::find($key);
    }
}
