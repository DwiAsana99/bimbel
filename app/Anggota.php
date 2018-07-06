<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Anggota extends Model
{
  protected $primaryKey = 'NoAnggota';
  public $incrementing = false;
  protected $guarded = ['created_at', 'updated_at'];

  public function nasabah()
  {
    return $this->belongsTo('App\Nasabah', 'NoNasabah');
  }

  public function simpananPokok()
  {
    return $this->hasOne('App\Simpananpokok', 'NoNasabah');
  }

  public function simpananWajib()
  {
    return $this->hasOne('App\Simpananwajib', 'NoNasabah');
  }

  public function scopeNoUnik($query)
  {
    $nounik = $query->orderBy('NoAnggota', 'DESC')->first();
    return Fungsi::autoNumber(!empty($nounik->NoAnggota) ? $nounik->NoAnggota : 'AG00000',2,5);
  }
}
