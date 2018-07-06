<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Akungroup;
use App\Akunkelompok;
use App\Akun;
use App\Akundetil;

class AkunController extends Controller
{
    public function index()
    {
        return view('akun.index');
    }

    public function dt()
    {
        $datas = Akun::joinGroup();
        return Datatables::of($datas)
        ->addColumn('detil_url', function($data) {
            return url('/m/akun/detil/' . $data->KodeAkun);
        })
        ->addColumn('action', function ($data) {
            return '<button class="btn btn-xs btn-success" id="btntambahdetil" data-toggle="modal" data-target="#modal-detil"><i class="fa fa-plus-square"></i> Detil</button>
            <button class="btn btn-xs btn-warning" id="btneditakun" data-toggle="modal" data-target="#modal-akun"><i class="fa fa-minus-square"></i> Edit</button>';
        })
        ->make(true);
    }

    public function detil($akun)
    {
        $datas = Akundetil::where('KodeAkun', $akun);
        return Datatables::of($datas)
        ->addColumn('action', function ($data) {
            return '<button class="btn btn-xs btn-success" onclick="editdetil('.$data->KodeDetil.')"><i class="fa fa-edit"></i> Edit</button>';
        })
        ->make(true);
    }

    public function storeAkun(Request $request)
    {
      try {
        $akun = Akun::create($request->all());
      } catch (\Exception $e) {
        return redirect()->action('AkunController@index')
        ->with('notif', json_encode([
          'title' => "Tambah Akun",
          'text' => "Gagal Menambah Data Akun.",
          'type' => "error"
        ]));
      }
      return redirect()->action('AkunController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Akun",
        'text' => "Berhasil Tambah Data Akun ".$request->KodeAkun.".",
        'type' => "success"
      ]));
    }

    public function storeDetil(Request $request)
    {
      try {
        $detil = Akundetil::create($request->all());
      } catch (\Exception $e) {
        return redirect()->action('AkunController@index')
        ->with('notif', json_encode([
          'title' => "Tambah Detil Akun",
          'text' => "Gagal Menambah Data Detil Akun.",
          'type' => "error"
        ]));
      }
      return redirect()->action('AkunController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Detil Akun",
        'text' => "Berhasil Tambah Data Detil Akun ".$request->KodeDetil.".",
        'type' => "success"
      ]));
    }

    public function updateAkun(Request $request, Akun $akun)
    {
      try {
        $akun->update($request->all());
      } catch (\Exception $e) {
        return redirect()->action('AkunController@index')
        ->with('notif', json_encode([
          'title' => "Ubah Akun",
          'text' => "Gagal Mengubah Data Akun.",
          'type' => "error"
        ]));
      }
      return redirect()->action('AkunController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Detil Akun",
        'text' => "Berhasil Ubah Data Akun ".$request->KodeAkun.".",
        'type' => "success"
      ]));
    }

    public function updateDetil(Request $request, Akundetil $detil)
    {
      try {
        $detil->update($request->all());
      } catch (\Exception $e) {
        return redirect()->action('AkunController@index')
        ->with('notif', json_encode([
          'title' => "Ubah Detil Akun",
          'text' => "Gagal Mengubah Data Detil Akun.",
          'type' => "error"
        ]));
      }
      return redirect()->action('AkunController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Detil Detil Akun",
        'text' => "Berhasil Ubah Data Detil Akun ".$request->KodeDetil.".",
        'type' => "success"
      ]));
    }

    //select2
    public function select2group(Request $r)
    {
        $term = trim($r->q);
        if (empty($term)) {
            $tags = Akungroup::all();
        }else {
          $tags = Akungroup::where('Keterangan', 'LIKE', '%'. $term .'%')->get();
        }
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->KodeGroup, 'text' => $tag->Keterangan];
        }
        return response()->json($formatted_tags);
    }

    public function select2kelompok(Request $r)
    {
      $term = trim($r->q);
      if (empty($term)) {
        $query = Akunkelompok::join('akungroups', 'akunkelompoks.KodeGroup', '=', 'akungroups.KodeGroup');
        $groups = $query->select('akungroups.KodeGroup as KodeGroup', 'akungroups.Keterangan as Keterangan')->distinct()->get();
        $tags = $query->select('akunkelompoks.*')->get();
      }else {
          $query = Akunkelompok::join('akungroups', 'akunkelompoks.KodeGroup', '=', 'akungroups.KodeGroup')
          ->where('akunkelompoks.Keterangan', 'LIKE', '%'. $term .'%')
          ->orWhere('akungroups.Keterangan', 'LIKE', '%'. $term .'%')
          ->orWhere('akunkelompoks.KodeKelompok', 'LIKE', '%'. $term .'%');

          $groups = $query->select('akungroups.KodeGroup as KodeGroup', 'akungroups.Keterangan as Keterangan')->distinct()->get();

          $tags = $query->select('akunkelompoks.*')->get();
      }
      $formatted_tags = [];

      foreach ($groups as $gk => $gv) {
        $formatted_tags[] = ['text' => $gv->Keterangan];
        foreach ($tags as $tk => $tv) {
          if ($gv->KodeGroup == $tv->KodeGroup){
            $formatted_tags[$gk]['children'][] = ['id' => $tv->KodeKelompok, 'text' => $tv->KodeKelompok.' | '.$tv->Keterangan];
          }
        }
      }
      return response()->json($formatted_tags);
    }

    public function select2akun(Request $r)
    {
      $term = trim($r->q);
      if (empty($term)) {
        $query = Akun::join('akunkelompoks', 'akunkelompoks.KodeKelompok', '=', 'akuns.KodeKelompok');
        $groups = $query->select('akunkelompoks.KodeKelompok as KodeKelompok', 'akunkelompoks.Keterangan as Keterangan')->distinct()->get();
        $tags = $query->select('akuns.*')->get();
      }else {
          $query = Akun::join('akunkelompoks', 'akunkelompoks.KodeKelompok', '=', 'akuns.KodeKelompok')
          ->where('akunkelompoks.Keterangan', 'LIKE', '%'. $term .'%')
          ->orWhere('akuns.Keterangan', 'LIKE', '%'. $term .'%')
          ->orWhere('akuns.KodeAkun', 'LIKE', '%'. $term .'%');

          $groups = $query->select('akunkelompoks.KodeKelompok as KodeKelompok', 'akunkelompoks.Keterangan as Keterangan')->distinct()->get();

          $tags = $query->select('akuns.*')->get();
      }
      $formatted_tags = [];

      foreach ($groups as $gk => $gv) {
          $formatted_tags[] = ['text' => $gv->Keterangan];
          foreach ($tags as $tk => $tv) {
              if ($gv->KodeKelompok == $tv->KodeKelompok){
                  $formatted_tags[$gk]['children'][] = ['id' => $tv->KodeAkun, 'text' => $tv->KodeAkun.' | '.$tv->Keterangan];
              }
          }
      }
      return response()->json($formatted_tags);
    }

    public function select2detil(Request $r)
    {
      $term = trim($r->q);
      if (empty($term)) {
        $query = Akundetil::join('akuns', 'akuns.KodeAkun', '=', 'akundetils.KodeAkun');
        $groups = $query->select('akuns.KodeAkun as KodeAkun', 'akuns.Keterangan as Keterangan')->distinct()->get();
        $tags = $query->select('akundetils.*')->get();
      }else {
        $query = Akundetil::join('akuns', 'akuns.KodeAkun', '=', 'akundetils.KodeAkun')
        ->where('akuns.Keterangan', 'LIKE', '%'. $term .'%')
        ->orWhere('akundetils.Keterangan', 'LIKE', '%'. $term .'%')
        ->orWhere('akundetils.KodeDetil', 'LIKE', '%'. $term .'%');

        $groups = $query->select('akuns.KodeAkun as KodeAkun', 'akuns.Keterangan as Keterangan')->distinct()->get();

        $tags = $query->select('akundetils.*')->get();
      }
      $formatted_tags = [];

      foreach ($groups as $gk => $gv) {
          $formatted_tags[] = ['text' => $gv->Keterangan];
          foreach ($tags as $tk => $tv) {
              if ($gv->KodeAkun == $tv->KodeAkun){
                  $formatted_tags[$gk]['children'][] = ['id' => $tv->KodeDetil, 'text' => $tv->KodeDetil.' | '.$tv->Keterangan];
              }
          }
      }
      return response()->json($formatted_tags);
    }
}