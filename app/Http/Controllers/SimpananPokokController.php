<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Simpananpokok;
use App\Simpananpokokdetil;
use DB;
use Datatables;
use Illuminate\Http\Request;
use Auth;
use Fungsi;

class SimpananPokokController extends Controller
{
  public function index($anggota)
  {
    $data = Simpananpokok::with('anggota.nasabah')->where('NoAnggota', $anggota)->first();
    return view('simpanan.pokok', $data);
  }

  public function dt($rek)
  {
    $datas = Simpananpokokdetil::where('NoRek', $rek)->get();
    return Datatables::of($datas)
    ->editColumn('TglInput', function ($data) {
      return Fungsi::bulanID($data->TglInput);
    })
    ->make(true);
  }

  public function setor(Request $request, Simpananpokok $rek)
  {
    DB::beginTransaction();
    try {
      $saldo = $request->nominal + $rek->Saldo;
      $rek->update(['Saldo' => $saldo]);
      $SPd = Simpananpokokdetil::create([
        'NoRek' => $rek->NoRek, 
        'Kredit' => $request->nominal, 
        'SaldoAkhir' => $saldo, 
        'TglInput' => session('tgl'), 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($request->nominal, 1);
      Fungsi::jurnal($rek->NoRek.'-'.$SPd->id, $jd['Keterangan'], $jd['jurnal']);
      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      return redirect()->action('SimpananPokokController@index', ['anggota' => $rek->NoAnggota])
      ->with('notif', json_encode([
        'title' => "Setor Simpanan Pokok",
        'text' => "Gagal Melakukan Setor Simpanan Pokok pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "error"
      ]));
    }
    return redirect()->action('SimpananPokokController@index', ['anggota' => $rek->NoAnggota])
    ->with('notif', json_encode([
      'title' => "Setor Simpanan Pokok",
      'text' => "Berhasil Melakukan Setor Simpanan Pokok pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
      'type' => "success"
    ]));
  }

  public function tarik(Request $request, Simpananpokok $rek)
  {
    DB::beginTransaction();
    try {
      $saldo = $rek->Saldo - $request->nominal;
      $rek->update(['Saldo' => $saldo]);
      $SPd = Simpananpokokdetil::create([
        'NoRek' => $rek->NoRek, 
        'Debet' => $request->nominal, 
        'SaldoAkhir' => $saldo, 
        'TglInput' => session('tgl'), 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($request->nominal, 2);
      Fungsi::jurnal($rek->NoRek.'-'.$SPd->id, $jd['Keterangan'], $jd['jurnal']);
      DB::commit();
    }catch(\Exception $e){
      DB::rollback();
      return redirect()->action('SimpananPokokController@index', ['anggota' => $rek->NoAnggota])
      ->with('notif', json_encode([
        'title' => "Tarik Simpanan Pokok",
        'text' => "Gagal Melakukan Penarikan Simpanan Pokok pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
        'type' => "error"
      ]));
    }
    return redirect()->action('SimpananPokokController@index', ['anggota' => $rek->NoAnggota])
    ->with('notif', json_encode([
      'title' => "Tarik Simpanan Pokok",
      'text' => "Berhasil Melakukan Penarikan Simpanan Pokok pada rekening ". $rek->NoRek ." sejumlah ". $request->nominal .".",
      'type' => "success"
    ]));
  }
}
