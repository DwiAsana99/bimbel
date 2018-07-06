<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RBACUserController extends Controller
{
    public function validasi(Request $request)
    {
        $validatedData = $this->validate($request, [
            'username' => 'unique:users'
        ]);
            
        if ($validatedData) {
            return response()->json($validatedData);
        }
        return response()->json(['message' => 'Username Tersedia'], 200);
    }
}
