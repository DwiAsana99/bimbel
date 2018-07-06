<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class HasTabungan
{
    public function handle($request, Closure $next)
    {
        if (is_null(Auth::guard('api')->user()->nasabah->tabungan)) {
            return response()->json(['msg' => 'Data Tidak Tersedia'], 462);
        }
        return $next($request);
    }
}
