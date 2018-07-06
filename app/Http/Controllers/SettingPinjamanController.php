<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settingpinjaman;

class SettingPinjamanController extends Controller
{
    public function index()
    {
        $data = Settingpinjaman::first();
        return view('setting.pinjaman', $data);
    }

    public function update(Request $request)
    {   
        try {
            if ($request->input('InputDenda') == "on") {
                $request->merge(['InputDenda' => true]);
            }else {
                $request->merge(['InputDenda' => false]);
            }
            if ($request->input('InputBunga') == "on") {
                $request->merge(['InputBunga' => true]);
            }else {
                $request->merge(['InputBunga' => false]);
            }
            $data = Settingpinjaman::find(1);
            $data->update($request->all());

            return redirect()->action('SettingPinjamanController@index')
            ->with('notif', json_encode([
                'title' => "Setting Pinjaman",
                'text'  => "Berhasil Merubah Pengaturan Pinjaman.",
                'type'  => "success"
            ]));
        } catch (\Exception $e) {
            return redirect()->action('SettingPinjamanController@index')
            ->with('notif', json_encode([
                'title' => "Setting Pinjaman",
                'text'  => "Gagal Merubah Pengaturan Pinjaman.",
                'type'  => "error"
            ]));
        }          
    }
}
