<?php

namespace App\Http\Controllers;
use App\Depositodetil;
use App\Deposito;
use App\Depositoperpanjang;
use Datatables;
use Auth;
use Fungsi;
use DB;

use Illuminate\Http\Request;

class DepositoBerakhirController extends Controller
{
    public function index()
    {
      return view('deposito.berakhir.index');
    }

    public function dt()
    {
      $datas = Deposito::with('nasabah')
      ->where('TglAkhirBerlaku', '<', session('tgl'))
      ->where('IsBerakhir', false);
      return Datatables::of($datas)
      ->editColumn('TglInput', function ($data) {
          return Fungsi::bulanID($data->TglInput);
      })
      ->editColumn('TglAkhirBerlaku', function ($data) {
          return Fungsi::bulanID($data->TglAkhirBerlaku);
      })
      ->addColumn('detil_url', function($data) {
        return url('/depositoberakhir/detil/' . $data->NoDeposito);
      })
      ->addColumn('action', function ($data) {
          return '<a class="btn btn-xs btn-success" id="btntarik" data-toggle="modal" data-target="#modal-tarik"><i class="fa fa-plus-square"></i> Tarik</a>
          <button class="btn btn-xs btn-warning" id="btnperpanjang" data-toggle="modal" data-target="#modal-perpanjangan"><i class="fa fa-minus-square"></i> Perpanjang</button>';
      })
      ->make(true);
    }

    public function detil($deposito = null)
    {
      $datas = Depositodetil::where('NoDeposito', $deposito);
      return Datatables::of($datas)
      ->orderColumn('TglInput', 'id $1')
      ->editColumn('TglInput', function ($data) {
          return Fungsi::bulanID($data->TglInput);
      })
      ->make(true);
    }

    public function tarik(Deposito $deposito)
    {
      DB::beginTransaction();
      try {
        $deposito->update(['IsBerakhir' => true]);
        $jd = Fungsi::jurnalDetil($deposito->JumlahDeposito, 17);
        Fungsi::jurnal($deposito->NoDeposito, $jd['Keterangan'], $jd['jurnal']);
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return redirect()->action('DepositoBerakhirController@index')
        ->with('notif', json_encode([
          'title' => "Penarikan Deposito",
          'text' => "Gagal Melakukan Penarikan Deposito.",
          'type' => "error"
        ]));
      }
      return redirect()->action('DepositoBerakhirController@index')
      ->with('notif', json_encode([
        'title' => "Penarikan Deposito",
        'text' => "Berhasil Melakukan Penarikan Deposito dengan No. Deposito ". $deposito->NoDeposito ." sejumlah ". $deposito->JumlahDeposito .".",
        'type' => "success"
      ]));
    }

    public function perpanjang(Request $request, Deposito $deposito)
    {
      $TglAkhirSeharusnya = $deposito->TglAkhirBerlaku;
      DB::beginTransaction();
      try {
        $deposito->update([
          'TglAkhirBerlaku' => date("Y-m-d", strtotime($request->input('TglAkhirBerlaku'))), 
          'Bunga' => $request->input('Bunga'),
          'NominalBunga' => $request->input('NominalBunga')
        ]);
        Depositoperpanjang::create([
          'NoDeposito' => $deposito->NoDeposito, 
          'TglAkhirSeharusnya' => $TglAkhirSeharusnya, 
          'TglAkhir' => $deposito->TglAkhirBerlaku,
          'TglInput' => session('tgl'),
          'user_id' => Auth::id()
        ]);
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return redirect()->action('DepositoBerakhirController@index')
        ->with('notif', json_encode([
          'title' => "Perpanjangan Deposito",
          'text' => "Gagal Melakukan Perpanjangan Deposito.",
          'type' => "error"
        ]));
      }
      return redirect()->action('DepositoBerakhirController@index')
      ->with('notif', json_encode([
        'title' => "Perpanjangan Deposito",
        'text' => "Berhasil Melakukan Perpanjangan Deposito dengan No. Deposito ". $deposito->NoDeposito .".",
        'type' => "success"
      ]));
    }
}
