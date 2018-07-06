<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Fungsi;

class TxLainController extends Controller
{
    public function index()
    {
      return view('txlain.index');
    }

    public function store(Request $request)
    {
      DB::beginTransaction();
      try {
        Fungsi::jurnal($request->BuktiTransaksi, $request->Keterangan, $request->jurnal);
        DB::commit();
        return response()->json([
          'notif' => [
            'title' => "Transaksi Lain",
            'text' => "Berhasil Menambah Transaksi Lain.",
            'type' => "success"
          ]
        ], 200);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
          'notif' => [
            'title' => "Transaksi Lain",
            'text' => "Gagal Menambah Transaksi Lain.",
            'type' => "error"
          ]
        ], 500);
      }
    }
}