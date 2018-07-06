<?php

namespace App\Http\Controllers\Api\Nasabah;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tabungan;
use DB;
use Auth;

class TabunganController extends Controller
{
    private $tabungan;

    public function __construct()
    {
        $this->middleware('HasTabungan');
        $this->tabungan = Auth::guard('api')->user()->nasabah->tabungan;
    }

    public function cekSaldo()
    {
        return response()->json(['data' => $this->tabungan->Saldo], 200);
    }

    public function mutasi($tglawal, $tglakhir)
    {
        $tabungan = Tabungan::with(['detils' => function($query) use($tglawal, $tglakhir) {
            $query->whereBetween('tabungandetils.TglInput', [$tglawal, $tglakhir])
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'asc');
        }])->where('NoRek', $this->tabungan->NoRek)->first();
        return response()->json(['data' => $tabungan], 200);
    }
}
