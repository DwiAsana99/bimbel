<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settingtabungan;
use App\Tabungan;
use App\Tabungandetil;
use DB;
use Fungsi;
use Auth;

class TabunganCepatController extends Controller
{
    public function setor()
    {
      $setting = Settingtabungan::first();
      $data = ['status' => 'Setor', 'status2' => 'Setoran'];
      return view('tabungan.cepat', compact('data', 'setting'));
    }

    public function tarik()
    {
      $setting = Settingtabungan::first();
      $data = ['status' => 'Tarik', 'status2' => 'Penarikan'];
      return view('tabungan.cepat', compact('data', 'setting'));
    }

    public function setorProses(Request $request, Tabungan $tabungan)
    {
        $saldo = $request['nominal'] + $tabungan->Saldo;
        DB::beginTransaction();
        try {
            $tabungan->update(['Saldo' => $saldo, 'SaldoTinggi' => $saldo]);
            $tabungancheck = Tabungandetil::where('NoRek', $tabungan->NoRek)->first();
            if ($tabungancheck == null) {
                $statusTabungan = 'Setoran Awal Tabungan';
            }else {
                $statusTabungan = 'Setoran Tabungan';
            }
            $td = Tabungandetil::create([
                'NoRek' => $tabungan->NoRek,
                'Kredit' => $request['nominal'],
                'Nominal' => $request['nominal'],
                'Keterangan' => $statusTabungan,
                'SaldoAkhir' => $tabungan->Saldo,
                'TglInput' => session('tgl'),
                'user_id' => Auth::id()
            ]);
            $jd = Fungsi::jurnalDetil($request->nominal, 5);
            Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
            DB::commit();
            return response()->json([
                'notif' => [
                  'title' => "Setoran Tabungan",
                  'text' => "Berhasil Melakukan Setoran Tabungan pada rekening ". $tabungan->NoRek ." sejumlah ". $request['nominal'] .".",
                  'type' => "success"
                ]
            ], 200);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'notif' => [
                  'title' => "Setoran Tabungan",
                  'text' => "Gagal Melakukan Setoran Tabungan",
                  'type' => "error"
                ]
            ], 500);
        }
    }

    public function tarikProses(Request $request, Tabungan $tabungan)
    {
        $saldo = $tabungan->Saldo - $request['nominal'];
        $saldorendah = $saldo < $tabungan->SaldoRendah ? $saldo : $tabungan->SaldoRendah;
        DB::beginTransaction();
        try {
            $tabungan->update(['Saldo' => $saldo, 'SaldoRendah' => $saldorendah]);
            $td = Tabungandetil::create([
                'NoRek' => $tabungan->NoRek,
                'Debet' => $request['nominal'],
                'Nominal' => $request['nominal'],
                'Keterangan' => 'Penarikan Tabungan',
                'SaldoAkhir' => $tabungan->Saldo,
                'TglInput' => session('tgl'),
                'user_id' => Auth::id()
            ]);
            $jd = Fungsi::jurnalDetil($request->nominal, 6);
            Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
            DB::commit();
            return response()->json([
                'notif' => [
                    'title' => "Penarikan Tabungan",
                    'text' => "Berhasil Melakukan Penarikan Tabungan pada rekening ". $tabungan->NoRek ." sejumlah ". $request['nominal'] .".",
                    'type' => "success"
                ]
            ], 200);
        } catch(Exception $e) {
            DB::rollback();
            return response()->json([
                'notif' => [
                    'title' => "Penarikan Tabungan",
                    'text' => "Gagal Melakukan Penarikan Tabungan",
                    'type' => "error"
                ]
            ], 500);
        }
    }
}
