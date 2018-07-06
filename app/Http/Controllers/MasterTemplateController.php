<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use App\Txtemplate;

class MasterTemplateController extends Controller
{
    public function index()
    {
        return view('masterdata.template.index');
    }

    public function dt()
    {
        $datas = Txtemplate::with(['debet:KodeDetil,Keterangan', 'kredit:KodeDetil,Keterangan'])->where('IsAktif', true);
        return Datatables::of($datas)
        ->addColumn('action', function ($data) {
            return '<button class="btn btn-xs btn-success" id="btnedit" data-toggle="modal" data-target="#modal-edit"><i class="fa fa-edit"></i></button>';
        })
        ->make(true);
    }

    public function store(Request $request)
    {
        try {
            $template = Txtemplate::create($request->all());
            return redirect()->action('MasterTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Template Tx",
                'text' => "Berhasil Menambah Template Transaksi.",
                'type' => "success"
            ]));
        } catch (\Exception $e) {
            return redirect()->action('MasterTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Template Tx",
                'text' => "Gagal Menambah Template Transaksi.",
                'type' => "error"
            ]));
        }
    }

    public function update(Request $request, Txtemplate $template)
    {
        try {
            $template->update($request->all());
            return redirect()->action('MasterTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Ubah Template Tx",
                'text' => "Berhasil Merubah Template Transaksi.",
                'type' => "success"
            ]));
        } catch (\Exception $e) {
            return redirect()->action('MasterTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Ubah Template Tx",
                'text' => "Gagal Merubah Template Transaksi.",
                'type' => "error"
            ]));
        }
    }
}
