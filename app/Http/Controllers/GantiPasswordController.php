<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class GantiPasswordController extends Controller
{
    public function index()
    {
        return view('gantipassword');
    }

    public function ganti(Request $request)
	{
        try {
            $user = Auth::user();
            if (Hash::check($request->input('passlama'), $user->password)) {
                $user->update(['password' => bcrypt($request->input('password'))]);
                return redirect()->action('GantiPasswordController@index')
                ->with('notif', json_encode([
                    'title' => "Ganti Password",
                    'text'  => "Berhasil Merubah Password",
                    'type'  => "success"
                ]));
            }else{
                return redirect()->action('GantiPasswordController@index')
                ->with('notif', json_encode([
                    'title' => "Ganti Password",
                    'text'  => "Password Lama Salah",
                    'type'  => "error"
                ]));
            }
        } catch (\Exception $e) {
            return redirect()->action('GantiPasswordController@index')
            ->with('notif', json_encode([
                'title' => "Ganti Password",
                'text'  => "Gagal Merubah Password",
                'type'  => "error"
            ]));
        }
    }
}
