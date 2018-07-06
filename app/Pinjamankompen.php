<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pinjamankompen extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function nasabah()
    {
      return $this->belongsTo('App\Nasabah', 'NoNasabah');
    }

    public function pinjamanbaru()
    {
      return $this->belongsTo('App\Pinjaman', 'NoPinjamanBaru');
    }

    public function pinjamankompen()
    {
      return $this->belongsTo('App\Pinjaman', 'NoPinjamanKompen');
    }
}
