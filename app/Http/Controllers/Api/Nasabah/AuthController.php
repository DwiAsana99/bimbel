<?php

namespace App\Http\Controllers\Api\Nasabah;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password') + ['AksesApi' => true, 'isaktif' => true, 'role_id' => 2];
    }

    protected function sendLoginResponse(Request $request)
    {
        $user = $this->guard()->user();
        if ($user->NoTelp == $request->NoTelp1 || $user->NoTelp == $request->NoTelp2) {
            $user->api_token = str_random(40);
            $user->save();

            $this->clearLoginAttempts($request);

            $response = $this->guard()->user();
            $response->nasabah;

            return response()->json(['msg' => 'Login Berhasil', 'data' => $response], 200);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json(['msg' => 'Username atau Password Salah'], 461);
    }

    public function username()
    {
        return 'username';
    }

    public function logout()
    {
      $user = $this->guard('api')->user();
      $user->api_token = null;
      $user->save();
      return response()->json(['msg' => 'Logout Berhasil'], 200);
    }

    protected function guard($guard = null)
    {
        return Auth::guard($guard);
    }
}