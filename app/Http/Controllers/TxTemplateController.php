<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use DB;
use Fungsi;
use App\Txtemplate;
use Auth;
use App\Txtemplatedetil;

class TxTemplateController extends Controller
{
    public function index()
    {
        return view('txtemplate.index');
    }

    public function dt()
    {
        $datas = Txtemplate::with(['debet:KodeDetil,Keterangan', 'kredit:KodeDetil,Keterangan'])->where('IsAktif', true);
        return Datatables::of($datas)
        ->addColumn('detil_url', function($data) {
            return url('/txtemplate/detil/' . $data->NoTemplate);
        })
        ->addColumn('action', function ($data) {
            return '<button class="btn btn-xs btn-success" id="btntambahtx" data-toggle="modal" data-target="#modal-tambahtx"><i class="fa fa-edit"></i></button>';
        })
        ->make(true);
    }

    public function detil(Request $request, $template)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $datas = Txtemplatedetil::with('user:id,name')->select(DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'txtemplatedetils.*')->where('NoTemplate', $template);
        $datatables = Datatables::of($datas)
        ->editColumn('TglInput', function ($data) {
            return Fungsi::bulanID($data->TglInput);
        });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }
        return $datatables->make(true);
    }

    public function store(Request $request)
    {
        try {
            $template = Txtemplate::create($request->all());
            return redirect()->action('TxTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Template Tx",
                'text' => "Berhasil Menambah Template Transaksi.",
                'type' => "success"
            ]));
        } catch (\Exception $e) {
            return redirect()->action('TxTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Tambah Template Tx",
                'text' => "Gagal Menambah Template Transaksi.",
                'type' => "error"
            ]));
        }
    }

    public function tambah(Request $request, Txtemplate $template)
    {
        DB::beginTransaction();
        try {
            $jumlah = $request->Nominal + $template->TotalTrx;
            $template->update(['TotalTrx' => $jumlah]);
            $nounik = Txtemplatedetil::noUnik();
            $detil = Txtemplatedetil::create([
                'NoTxn' => $nounik, 
                'NoTemplate' => $template->NoTemplate, 
                'Nominal' => $request->Nominal, 
                'Jumlah' => $jumlah, 
                'BuktiTransaksi' => $request->BuktiTransaksi, 
                'Keterangan' => $request->Keterangan, 
                'TglInput' => session('tgl'), 
                'user_id' => Auth::id()
            ]);
            $jd = [
                [
                    'KodeAkun' => $template->AkunDebet,
                    'Debet' => $request->Nominal,
                    'Kredit' => '0'
                ],
                [
                    'KodeAkun' => $template->AkunKredit,
                    'Debet' => '0',
                    'Kredit' => $request->Nominal
                ]
            ];
            Fungsi::jurnal($detil->BuktiTransaksi, $request->Keterangan, $jd);

            if (substr($template->AkunDebet,0,4) == '1101') {
                $kwitansi = Fungsi::kwitansi($detil->NoTxn, 'Kas Masuk', '0201');
                $rKw = 'masuk';
            } elseif (substr($template->AkunKredit,0,4) == '1101') {
                $kwitansi = Fungsi::kwitansi($detil->NoTxn, 'Kas Keluar', '0202');
                $rKw = 'keluar';
            }

            DB::commit();
            return redirect()->action('TxTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Template Transaksi",
                'text' => "Berhasil Melakukan Transaksi dengan Template.",
                'type' => "success"
            ]))
            ->with('kwitansi', route('kw.kas.'.$rKw, ['NoKwitansi' => $kwitansi->NoKwitansi]));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->action('TxTemplateController@index')
            ->with('notif', json_encode([
                'title' => "Template Transaksi",
                'text' => "Gagal Melakukan Transaksi dengan Template.",
                'type' => "danger"
            ]));
        }
    }
}