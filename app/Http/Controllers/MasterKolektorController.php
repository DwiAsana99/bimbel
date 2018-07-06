<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kolektor;
use App\User;
use DB;
use Datatables;

class MasterKolektorController extends Controller
{
    public function index()
    {   
        return view('masterdata.kolektor.index');
    }

    public function dt()
    {
        $datas = $this->getKolektor();
        return Datatables::of($datas)
        ->addColumn('action', function ($data) {
            return '<a href="'. route('m.kolektor.edit', ['kolektor' => $data->NoKolektor]) .'" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></a>';
        })
        ->make(true);
    }

    public function create()
    {
        $NoKolektor = Kolektor::noUnik();
        return view('masterdata.kolektor.create', compact('NoKolektor'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $user = $this->insertUser($data);
            $kolektor = $this->insertKolektor($data, $user->id);

            DB::commit();
            return redirect()->action('MasterKolektorController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Kolektor",
                'text' => "Berhasil Menambah Data Kolektor ".$kolektor->NoKolektor,
                'type' => "success"
            ]));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->action('MasterKolektorController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Kolektor",
                'text' => "Gagal Menambah Data Kolektor",
                'type' => "error"
            ]));
        }
    }

    public function edit($kolektor)
    {
        $data = $this->getKolektor($kolektor)->first();
        return view('masterdata.kolektor.edit', $data);
    }

    public function update(Request $request, Kolektor $kolektor)
    {
        DB::beginTransaction();
        try {
            $kolektor->update([
                'NoKolektor' => $request->input('NoKolektor'),
                'Nama' => $request->input('Nama'),
                'Alamat' => $request->input('Alamat'),
                'NoTelp' => $request->input('NoTelp'),
                'TglInput' => session('tgl')
            ]);
            $kolektor->user->update([
                'name' => $request->input('Nama'),
                'NoTelp' => $request->input('NoTelp'),
                'role_id' => $request->input('role_id')
            ]);
            DB::commit();
            return redirect()->action('MasterKolektorController@index')
            ->with('notif', json_encode([
                'title' => "Ubah Kolektor",
                'text' => "Berhasil Merubah Data Kolektor ".$kolektor->NoKolektor,
                'type' => "success"
            ]));
        } catch (Exception $e) {
            dd($e);
            DB::rollback();
            return redirect()->action('MasterKolektorController@index')
            ->with('notif', json_encode([
                'title' => "Ubah Kolektor",
                'text' => "Gagal Merubah Data Kolektor",
                'type' => "error"
            ]));
        }
    }

    public function select2(Request $r)
    {
        $term = trim($r->q);
        $query = Kolektor::where('IsAktif', true);
        if (empty($term)) {
            $tags = $query->get();
        }else {
            $tags = $query->where('NoKolektor', 'LIKE', '%'. $term .'%')
            ->orWhere('Nama', 'LIKE', '%'. $term .'%')
            ->get();
        }
        $formatted_tags = [];
        foreach ($tags as $tag) {
            $formatted_tags[] = ['id' => $tag->NoKolektor, 'text' => $tag->NoKolektor.' | '.$tag->Nama];
        }
        return response()->json($formatted_tags);
    }

    private function getKolektor($NoKolektor = null)
    {
        return $datas = Kolektor::with(['user' => function ($query) {
                $query->select('id', 'username', 'role_id')
                ->with('role:id,name');
            }
        ])->when($NoKolektor, function ($query) use ($NoKolektor) {
            return $query->where('NoKolektor', $NoKolektor);
        })
        ->where('IsAktif', true);
    }

    private function insertKolektor($data, $user)
    {
        return Kolektor::create([
            'NoKolektor' => $data['NoKolektor'],
            'user_id'    => $user,
            'Nama' => $data['Nama'],
            'Alamat' => $data['Alamat'],
            'NoTelp' => $data['NoTelp'],
            'IsAktif' => true,
            'TglInput' => session('tgl')
        ]);
    }

    private function insertUser($data)
    {
        return User::create([
            'name' => $data['Nama'],
            'username' => $data['username'],
            'role_id' => $data['role_id'],
            'NoTelp' => $data['NoTelp'],
            'isaktif' => 1,
            'AksesApi' => true,
            'AksesWeb' => false,
            'Koperasi' => session('koperasi'),
            'Password' => bcrypt($data['username'])
        ]);
    }
}
