<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Depositodetil;
use App\Settingdeposito;
use App\Deposito;
use Datatables;
use Auth;
use Fungsi;

class DepositoController extends Controller
{
    public function index()
    {
        $setting = Settingdeposito::first();
        return view('deposito.index', $setting);
    }

    public function dt()
    {
        $datas = Deposito::with('nasabah')->where('IsBerakhir', false);
        return Datatables::of($datas)
        ->editColumn('TglInput', function ($data) {
            return Fungsi::bulanID($data->TglInput);
        })
        ->editColumn('TglAkhirBerlaku', function ($data) {
            return Fungsi::bulanID($data->TglAkhirBerlaku);
        })
        ->addColumn('detil_url', function($data) {
            return url('/deposito/detil/' . $data->NoDeposito);
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

    public function tambah(Request $request)
    {
      try {
        $tn = explode("|",$request->input('tn'));
        $nounik = Deposito::noUnik();
        $request->request->add([
          'NoDeposito' => $nounik,
          'NoTabungan' => $tn[0],
          'NoNasabah' => $tn[1],
          'JumlahNominalBunga' => 0,
          'TglBungaAkhir' => session('tgl'),
          'user_id' => Auth::id(),
          'TglInput' => session('tgl')
        ]);
        $request->merge(['TglAkhirBerlaku' => date("Y-m-d", strtotime($request->input('TglAkhirBerlaku')))]);
        $deposito = Deposito::create($request->except(['tn']));
        
        $jd = Fungsi::jurnalDetil($deposito->JumlahDeposito, 15);
        Fungsi::jurnal($deposito->NoDeposito, $jd['Keterangan'], $jd['jurnal']);
      } catch (\Exception $e) {
        return redirect()->action('DepositoController@index')
        ->with('notif', json_encode([
          'title' => "Tambah Deposito",
          'text' => "Data Deposito Baru Gagal Dibuat.",
          'type' => "error"
        ]));
      }
      return redirect()->action('DepositoController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Deposito",
        'text' => "Data Deposito ". $deposito->NoDeposito ." Dengan Jumlah Rp ". number_format($deposito->JumlahDeposito,0,".",",") ." Berhasil Dibuat.",
        'type' => "success"
      ]));
    }

    public function select2(Request $r, $status)
    {
        $query = Deposito::join('nasabahs', 'nasabahs.NoNasabah', 'depositos.NoNasabah')
        ->select('depositos.NoDeposito', 'nasabahs.NamaNasabah')
        ->where('IsBerakhir', $status);

        $term = trim($r->q);
        if (empty($term)) {
            $tags = $query->get();
        }else {
            $tags = $query->whereRaw('(NoDeposito LIKE "%'. $term .'%" OR NamaNasabah LIKE "%'. $term .'%")')->get();
        }
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->NoDeposito, 'text' => $tag->NoDeposito.' | '.$tag->NamaNasabah];
        }
        return response()->json($formatted_tags);
    }
}
