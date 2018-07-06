<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Tutupbuku;
use App\Settingkoperasi;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['AksesWeb' => true, 'isaktif' => true];
    }

    protected function authenticated()
    {
        $data = Tutupbuku::akhir()->pluck('aktif')->first();
        $tgl = is_null($data) ? date('Y-m-d') : $data;
        $tutupMax = Settingkoperasi::find(1);
        $tutupMax = $tutupMax->MaxTutupHari;

        $koperasi = Auth::user()->koperasi;

        return session(['tgl' => $tgl, 'aturtgl' => 0, 'koperasi' => $koperasi, 'maxTutup' => $tutupMax]);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
