<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akungroup extends Model
{
    protected $primaryKey = 'KodeGroup';

    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function kelompoks()
    {
        return $this->hasMany('App\Akunkelompok', 'KodeGroup');
    }

    public function akuns()
    {
        return $this->hasManyThrough('App\Akun', 'App\AkunKelompok', 'KodeGroup', 'KodeKelompok', 'KodeGroupd');
    }
}
