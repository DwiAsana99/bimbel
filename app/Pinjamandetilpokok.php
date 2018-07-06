<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pinjamandetilpokok extends Model
{
    protected $table = 'pinjamandetilpokoks';
    protected $fillable = ['NoPinjaman', 'Periode', 'Debet', 'Kredit', 'TglInput'];

    public function pinjaman()
    {
        return $this->belongsTo('App\Pinjaman', 'NoPinjaman');
    }

    public function scopeTunggakan($query)
    {
        $periodeAkhir = $query->max('Periode');
        return $query->select(DB::raw('IFNULL(SUM(Debet) - SUM(Kredit), 0) AS Tunggakan'))
        ->where('Periode', '<', $periodeAkhir);
    }
}
