<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;
use DB;

class Kwitansi extends Model
{
    protected $table = 'kwitansi';
    protected $primaryKey = 'NoKwitansi';
    public $incrementing = false;
    protected $fillable = ['NoKwitansi', 'JenisKwitansi', 'Keterangan', 'IdReferensi', 'user_id', 'TglInput'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeNoUnik($query, $prefix)
    {
        $nounik = $query->where('NoKwitansi', 'LIKE', $prefix.'%')->orderBy('NoKwitansi', 'DESC')->first();
        return Fungsi::autoNumber(!empty($nounik->NoKwitansi) ? $nounik->NoKwitansi : $prefix.'00000',10,5);
    }
}
