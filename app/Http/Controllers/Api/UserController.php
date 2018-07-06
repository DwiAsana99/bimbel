<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
    public function auth()
    {
        $data = Auth::user();
        return response()->json(['msg' => 'Data User', 'data' => $data], 200);
    }
}
