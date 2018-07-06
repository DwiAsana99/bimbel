<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use NoUnik;

class Kolektor extends Model
{
    protected $primaryKey = 'NoKolektor';

    public $incrementing = false;
    protected $fillable = ['NoKolektor', 'Nama', 'Alamat', 'NoTelp', 'IsAktif', 'TglInput', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function nasabahs()
    {
        return $this->hasMany('App\Nasabah', 'NoKolektor');
    }

    public function scopeNoUnik($query)
    {
        $nounik = new NoUnik(2);
        return $nounik->noUnik($query, $this->getKeyName());
    }
}
