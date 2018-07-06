<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Settingdeposito;

class SettingDepositoController extends Controller
{
    public function index()
    {
        $data = Settingdeposito::find(1);
        return view('setting.deposito', $data);
    }

    public function update(Request $reauest)
    {
        try {
            $data = Settingdeposito::find(1);
            $data->update($reauest->all());

            return redirect()->action('SettingDepositoController@index')
            ->with('notif', json_encode([
                'title' => "Setting Deposito",
                'text'  => "Berhasil Merubah Pengaturan Deposito.",
                'type'  => "success"
            ]));
        } catch (Exception $e) {
            return redirect()->action('SettingDepositoController@index')
            ->with('notif', json_encode([
                'title' => "Setting Deposito",
                'text'  => "Gagal Merubah Pengaturan Deposito.",
                'type'  => "error"
            ]));
        }
    }
}
