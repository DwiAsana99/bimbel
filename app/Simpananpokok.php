<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Simpananpokok extends Model
{
  protected $primaryKey = 'NoRek';

  public $incrementing = false;
  protected $guarded = ['created_at', 'updated_at'];

  public function anggota()
  {
      return $this->belongsTo('App\Anggota', 'NoAnggota');
  }

  public function detils()
  {
      return $this->hasMany('App\Simpananpokokdetil', 'NoRek');
  }

  public function scopeNoUnik($query)
  {
    $ym = Fungsi::sessionTglYM();
    $nounik = $query->where('NoRek', 'LIKE', 'SP'.$ym.'%')->orderBy('NoRek', 'DESC')->first();
    return Fungsi::autoNumber(!empty($nounik->NoRek) ? $nounik->NoRek : 'SP'.$ym.'00000',6,5);
  }
}
