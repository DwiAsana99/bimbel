<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settingdeposito;
use App\Depositodetil;
use App\Deposito;
use App\Tabungan;
use App\Tabungandetil;
use Datatables;
use DB;
use Auth;
use Fungsi;

class DepositoPostingBungaController extends Controller
{
    public function index()
    {   
        return view('deposito.postingbunga.index');
    }

    public function dt(Request $request)
    {
        $setting = Settingdeposito::first();
        DB::statement(DB::raw('set @rownum=0'));
        $datas = Deposito::postingBunga($setting->BulanPostingBunga)
        ->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'), 
            'depositos.NoDeposito', 
            'depositos.NoTabungan', 
            'nasabahs.NamaNasabah', 
            'depositos.NominalBunga', 
            DB::raw('IF(depositos.JumlahDeposito >= '.$setting->BatasKenaPajak.', 
                depositos.NominalBunga * '.$setting->Pajak.' /100, 
                0
            ) AS Pajak'),
            DB::raw('IF(depositos.JumlahDeposito >= '.$setting->BatasKenaPajak.', 
                (depositos.NominalBunga - (depositos.NominalBunga * '.$setting->Pajak.' /100)) + depositos.JumlahNominalBunga, 
                depositos.NominalBunga + depositos.JumlahNominalBunga
            ) AS BungaAkhir')
        ]);
        $datatables = Datatables::of($datas)
        ->addColumn('action', function ($data) {
            return '<button id="btnremove" class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button>';
        });
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }
        return $datatables->make(true);
    }

    public function posting(Request $request)
    {
      DB::beginTransaction();
      try {
        $iduser = Auth::id();
        $tgl = session('tgl');
        $setting = Settingdeposito::first()->pluck('IsGabungKeTabungan');

        foreach ($request->deposito as $r) {
            $deposito = Deposito::find($r['NoDeposito']);

            $deposito->update([
                'JumlahNominalBunga' => $r['BungaAkhir'],
                'TglBungaAkhir' => DB::raw('DATE_ADD(TglBungaAkhir, INTERVAL 1 MONTH)')
            ]);
            $dd = Depositodetil::create([
                'NoDeposito' => $deposito->NoDeposito,
                'Bunga' => $r['NominalBunga'] - $r['Pajak'],
                'Pajak' => $r['Pajak'],
                'Keterangan' => 'Bunga Deposito',
                'TglInput' => $tgl,
                'user_id' => $iduser
            ]);

            $detils = [
                'Keterangan' => 'Biaya Bunga Deposito',
                'jurnal' => [
                    [
                        'KodeAkun' => 410104,
                        'Debet' => 0,
                        'Kredit' => $r['Pajak']
                    ],
                    [
                        'KodeAkun' => 210201,
                        'Debet' => 0,
                        'Kredit' => $r['NominalBunga'] - $r['Pajak']
                    ],
                    [
                        'KodeAkun' => 510102,
                        'Debet' => $r['NominalBunga'],
                        'Kredit' => 0
                    ]
                ]
            ];

            // $jd = Fungsi::jurnalDetil($r['NominalBunga'], 16);
            Fungsi::jurnal($deposito->NoDeposito.'-'.$dd->id, $detils['Keterangan'], $detils['jurnal']);

            // if ($setting == true) {
            //   $tabungan = Tabungan::find($r['NoTabungan']);
            //   $tabungan->update([
            //     'Saldo' => $tabungan->Saldo + $r['NominalBunga'],
            //     'SaldoTinggi' => $tabungan->SaldoTinggi + $r['NominalBunga']
            //   ]);
            //   Tabungandetil::create([
            //     'NoRek' => $tabungan->NoRek,
            //     'Kredit' => $r['NominalBunga'],
            //     'Nominal' => $r['NominalBunga'],
            //     'Keterangan' => 'Bunga Deposito',
            //     'SaldoAkhir' => $tabungan->Saldo,
            //     'TglInput' => $tgl,
            //     'user_id' => $iduser
            //   ]);
            // }
        }
        DB::commit();
        return response()->json([
            'notif' => [
                'title' => "Posting Bunga Tabungan",
                'text' => "Berhasil Melakukan Posting Bunga ".count($request->deposito)." Rekening Deposito",
                'type' => "success"
            ]
        ], 200);
      } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'notif' => [
                'title' => "Posting Bunga Tabungan",
                'text' => "Gagal Melakukan Posting Bunga Deposito",
                'type' => "error"
            ]
        ], 500);
      }
    }
}
