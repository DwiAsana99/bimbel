<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Fungsi;

class Nasabah extends Model
{
	protected $primaryKey = 'NoNasabah';

    public $incrementing = false;
    protected $guarded = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function kolektor()
    {
        return $this->belongsTo('App\Kolektor', 'NoKolektor');
    }

    public function anggota()
    {
        return $this->hasOne('App\Anggota', 'NoNasabah');
    }

    public function tabungan()
    {
        return $this->hasOne('App\Tabungan', 'NoNasabah');
    }

        public function pinjamans()
    {
        return $this->hasMany('App\Pinjaman', 'NoNasabah');
    }

        public function depositos()
    {
        return $this->hasMany('App\Deposito', 'NoNasabah');
    }

	public function scopeJumlahKompen($query)
	{
		return $query->join('pinjamankompens', 'pinjamankompens.NoNasabah', '=', 'nasabahs.NoNasabah')
        ->groupBy('nasabahs.NoNasabah')
        ->select(['nasabahs.NoNasabah', 'nasabahs.NamaNasabah', DB::raw('count(pinjamankompens.id) as Jumlah')]);
	}

	public function pinjamankompens()
    {
        return $this->hasMany('App\Pinjamankompen', 'NoNasabah');
    }

    public function scopeNoUnik($query)
    {
        $nounik = $query->orderBy('NoNasabah', 'DESC')->first();
        return Fungsi::autoNumber(!empty($nounik->NoNasabah) ? $nounik->NoNasabah : 'NS00000',2,5);
    }
}
