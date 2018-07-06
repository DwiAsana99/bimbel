<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Koperasi;
use Auth;
use Storage;
use Image;

class SettingKoperasiController extends Controller
{
    public function index()
    {
        $koperasi = Auth::user()->koperasi;
        return view('setting.koperasi', $koperasi);
    }
    
    public function update(Request $request)
    {
        try {
            $koperasi = Auth::user()->koperasi;

            if ($request->hasFile('Logo')) {
                $file = $request->file('Logo');
                $ext = $request->Logo->extension();
                $newName = date('ymdHi')."-".Auth::id()."-".$koperasi->id.".".$ext;
                $img = Image::make($file)->resize(100, 100)->encode($ext, 75);
                Storage::disk('koperasiLogo')->put($newName, $img);
                $logo = $newName;
            }else {
                $logo = $koperasi->Logo;
            }

            $koperasi->update([
                'NoKoperasi' => $request->input('NoKoperasi'),
                'Nama' => $request->input('Nama'),
                'Alamat' => $request->input('Alamat'),
                'NoTelp' => $request->input('NoTelp'),
                'Logo' => $logo
            ]);

            return redirect()->action('SettingKoperasiController@index')
            ->with('notif', json_encode([
                'title' => "Informasi Koperasi",
                'text' => "Berhasil Ubah Informasi Koperasi.",
                'type' => "success"
            ]));

        } catch (\Exception $e) {
            return redirect()->action('SettingKoperasiController@index')
            ->with('notif', json_encode([
                'title' => "Informasi Koperasi",
                'text' => "Gagal Ubah Informasi Koperasi.",
                'type' => "error"
            ]));
        }
    }
}
