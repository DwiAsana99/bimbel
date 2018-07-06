<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Simpananwajib;
use App\Simpananwajibdetil;
use Datatables;
use Auth;
use Fungsi;
use DB;
use Illuminate\Http\Request;

class SimpananWajibController extends Controller
{
  public function index($anggota)
  {
    $data = Simpananwajib::with('anggota.nasabah')->where('NoAnggota', $anggota)->first();
    return view('simpanan.wajib', $data);
  }

  public function dt($rek)
  {
    $datas = Simpananwajibdetil::where('NoRek', $rek)->get();
    return Datatables::of($datas)
    ->editColumn('TglInput', function ($data) {
        return Fungsi::bulanID($data->TglInput);
    })
    ->make(true);
  }

  public function setor(Request $request, Simpananwajib $rek)
  {
    DB::beginTransaction();
    try {
      $saldo = $request->nominal + $rek->Saldo;
      $rek->update(['Saldo' => $saldo]);
      $SWd = Simpananwajibdetil::create([
        'NoRek' => $rek->NoRek, 
        'Kredit' => $request->nominal, 
        'SaldoAkhir' => $saldo, 
        'TglInput' => session('tgl'), 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($request->nominal, 3);
      Fungsi::jurnal($rek->NoRek.'-'.$SWd->id, $jd['Keterangan'], $jd['jurnal']);
      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      return redirect()->action('SimpananWajibController@index', ['anggota' => $rek->NoAnggota])
      ->with('notif', json_encode([
        'title' => "Setor Simpanan Wajib",
        'text' => "Gagal Melakukan Setor Simpanan Wajib pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "error"
      ]));
    }
    return redirect()->action('SimpananWajibController@index', ['anggota' => $rek->NoAnggota])
    ->with('notif', json_encode([
      'title' => "Setor Simpanan Wajib",
      'text' => "Berhasil Melakukan Setor Simpanan Wajib pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
      'type' => "success"
    ]));
  }

  public function tarik(Request $request, Simpananwajib $rek)
  {
    $saldo = $rek->Saldo - $request->nominal;
    DB::beginTransaction();
    try {
      $rek->update(['Saldo' => $saldo]);
      $SWd = Simpananwajibdetil::create([
        'NoRek' => $rek->NoRek, 
        'Debet' => $request->nominal, 
        'SaldoAkhir' => $saldo, 
        'TglInput' => session('tgl'), 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($request->nominal, 4);
      Fungsi::jurnal($rek->NoRek.'-'.$SWd->id, $jd['Keterangan'], $jd['jurnal']);
      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      return redirect()->action('SimpananWajibController@index', ['anggota' => $rek->NoAnggota])
      ->with('notif', json_encode([
        'title' => "Tarik Simpanan Wajib",
        'text' => "Gagal Melakukan Penarikan Simpanan Wajib pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "error"
      ]));
    }
    return redirect()->action('SimpananWajibController@index', ['anggota' => $rek->NoAnggota])
    ->with('notif', json_encode([
      'title' => "Tarik Simpanan Wajib",
      'text' => "Berhasil Melakukan Penarikan Simpanan Wajib pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
      'type' => "success"
    ]));
  }
}
