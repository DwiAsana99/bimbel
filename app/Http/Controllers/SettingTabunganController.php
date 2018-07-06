<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settingtabungan;

class SettingTabunganController extends Controller
{
    public function index()
    {
    	$data = Settingtabungan::find(1);
    	return view('setting.tabungan', $data);
    }

    public function update(Request $reauest)
    {
    	try {
    		$data = Settingtabungan::find(1);
			$data->update($reauest->all());
			return redirect()->action('SettingTabunganController@index')
			->with('notif', json_encode([
				'title' => "Setting Tabungan",
				'text'  => "Berhasil Merubah Pengaturan Tabungan.",
				'type'  => "success"
			]));
    	} catch (\Exception $e) {
			return redirect()->action('SettingTabunganController@index')
			->with('notif', json_encode([
                'title' => "Setting Tabungan",
                'text'  => "Gagal Merubah Pengaturan Tabungan.",
                'type'  => "error"
            ]));
    	}
    }
}
