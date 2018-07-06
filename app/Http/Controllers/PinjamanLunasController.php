<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pinjaman;
use DB;
use Datatables;
use Fungsi;

class PinjamanLunasController extends Controller
{
    public function index()
    {
        return view('pinjaman.lunas.index');
    }

    public function dt()
    {
        $queryTglPelunasan = DB::table('pinjamandetils')->select('TglInput')->whereRaw('pinjamans.NoPinjaman = pinjamandetils.NoPinjaman')->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'))->limit(1)->toSql();
        $datas = Pinjaman::with('nasabah')->select('pinjamans.*', DB::raw('('.$queryTglPelunasan.') AS TglPelunasan'))->where('IsLunas', true)->get();
        return Datatables::of($datas)
        ->editColumn('TglInput', function ($data) {
            return Fungsi::bulanID($data->TglInput);
        })
        ->editColumn('JatuhTempo', function ($data) {
            return Fungsi::bulanID($data->TglJatuhTempo);
        })
        ->editColumn('TglPelunasan', function ($data) {
            return Fungsi::bulanID($data->TglPelunasan);
        })
        ->addColumn('detil_url', function($data) {
            return url('/pinjamanlunas/detil/' . $data->NoPinjaman);
        })
        ->make(true);
    }

    public function detil(Pinjaman $pinjaman = null)
    {
        $datas = $pinjaman->detils;
        return Datatables::of($datas)
        ->editColumn('Debet', function ($data) {
            return is_null($data->Debet) ? 0 : $data->Debet;
        })
        ->editColumn('TglInput', function ($data) {
            return Fungsi::bulanID($data->TglInput);
        })
        ->make(true);
    }
}
