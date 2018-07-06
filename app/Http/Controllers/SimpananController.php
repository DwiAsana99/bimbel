<?php

namespace App\Http\Controllers;

use App\Anggota;
use Datatables;
use Illuminate\Http\Request;
use Fungsi;

class SimpananController extends Controller
{
    public function index()
    {
        return view('simpanan.index');
    }

    public function dt()
    {
        $datas = Anggota::with('nasabah')->where('IsAktif', true)->get();
        return Datatables::of($datas)
        ->editColumn('TglGabung', function ($data) {
            return Fungsi::bulanID($data->TglGabung);
        })
        ->addColumn('action', function ($data) {
            return '<a href="simpanan/pokok/'.$data->NoAnggota.'" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Pokok</a>
            <a href="simpanan/wajib/'.$data->NoAnggota.'" class="btn btn-xs btn-success"><i class="fa fa-edit"></i> Wajib</a>';
        })
        ->make(true);
    }
}
