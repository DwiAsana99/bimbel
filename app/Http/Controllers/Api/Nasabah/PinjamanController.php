<?php

namespace App\Http\Controllers\Api\Nasabah;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pinjaman;
use DB;
use Auth;

class PinjamanController extends Controller
{
    private $nasabah;

    public function __construct()
    {
        $this->nasabah = Auth::guard('api')->user()->nasabah;
    }

    public function kartu()
    {
        $pinjaman = Pinjaman::with(['detils' => function($query){
            $query->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'asc');
        }])->where('NoNasabah', $this->nasabah->NoNasabah)->where('IsLunas', false)->first();
        return response()->json(['data' => $pinjaman], 200);
    }
}