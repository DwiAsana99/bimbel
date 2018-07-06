<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Jurnalmaster extends Model
{
  protected $primaryKey = 'IdJurnal';

  public $incrementing = false;
  protected $guarded = ['created_at', 'updated_at'];

  public function detils()
  {
    return $this->hasMany('App\Jurnaldetil', 'IdJurnal');
  }

  public function scopeNoUnik($query)
  {
    $ym = Fungsi::sessionTglYM();
    $nounik = $query->where('IdJurnal', 'LIKE', $ym.'%')->orderBy('IdJurnal', 'DESC')->first();
    return Fungsi::autoNumber(!empty($nounik->IdJurnal) ? $nounik->IdJurnal : $ym.'00000',4,5);
  }
}
