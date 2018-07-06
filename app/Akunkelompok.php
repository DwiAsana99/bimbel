<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akunkelompok extends Model
{
    protected $primaryKey = 'KodeKelompok';

    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function akuns()
    {
        return $this->hasMany('App\Akun', 'KodeKelompok');
    }

    public function group()
    {
      return $this->belongsTo('App\Akungroup', 'KodeGroup');
    }

    public function detils()
    {
        return $this->hasManyThrough('App\Akundetil', 'App\Akun', 'KodeKelompok', 'KodeAkun', 'KodeKelompok');
    }
}
