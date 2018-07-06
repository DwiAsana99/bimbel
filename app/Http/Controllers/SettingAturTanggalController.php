<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Tutupbuku;
use App\Settingkoperasi;

class SettingAturTanggalController extends Controller
{
    public function index()
    {
        $tutupMax = Settingkoperasi::find(1);
        return view('setting.aturtanggal', compact('tutupMax'));
    }

    public function update(Request $request)
    {
        $mode = $request->mode == 'on' ? 1 : 0;
        $modenotif = $mode == 1 ? 'Mengaktifkan' : 'Mematikan';
        try {
            Settingkoperasi::where('id', 1)->update(['MaxTutupHari' => $request->tutupMax]);
            session()->forget('maxTutup');
            session(['maxTutup' => $request->tutupMax]);
            if ($mode == 1) {
                session()->forget('aturtgl');
                session(['aturtgl' => $mode]);
                return redirect()->action('SettingAturTanggalController@index')
                ->with('notif', json_encode([
                    'title' => "Pengaturan Tanggal",
                    'text' => "Berhasil ".$modenotif." Mode Pengaturan Tanggal.",
                    'type' => "success"
                ]));
            } else {
                $tglbuku = Tutupbuku::akhir()->pluck('aktif')->first();
                session()->forget('tgl');
                session(['tgl' => $tglbuku]);
                session()->forget('aturtgl');
                session(['aturtgl' => $mode]);
                return redirect()->action('SettingAturTanggalController@index')
                ->with('notif', json_encode([
                    'title' => "Pengaturan Tanggal",
                    'text' => "Berhasil ".$modenotif." Mode Pengaturan Tanggal, dan Mengembalikan Tanggal Aktif Seperti Normal.",
                    'type' => "success"
                ]));
            }
        } catch (\Exception $e) {
            return redirect()->action('SettingAturTanggalController@index')
            ->with('notif', json_encode([
                'title' => "Pengaturan Tanggal",
                'text' => "Gagal ".$modenotif." Mode Pengaturan Tanggal.",
                'type' => "error"
            ]));
        }
    }
}
