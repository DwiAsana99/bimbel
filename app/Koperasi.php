<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Koperasi extends Model
{
    protected $table = 'koperasi';
    protected $fillable = ['NoKoperasi', 'Nama', 'Alamat', 'NoTelp', 'Logo'];

    public function users()
    {
        return $this->hasMany('App\User', 'Koperasi');
    }
}
