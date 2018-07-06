<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Tabungan extends Model
{
  protected $primaryKey = 'NoRek';

  public $incrementing = false;
  protected $guarded = ['created_at', 'updated_at'];

  public function nasabah()
  {
    return $this->belongsTo('App\Nasabah', 'NoNasabah');
  }

  public function detils()
  {
    return $this->hasMany('App\Tabungandetil', 'NoRek');
  }

  public function depositos()
  {
    return $this->hasMany('App\Deposito', 'NoTabungan');
  }

  public function scopeNoUnik($query)
  {
    $ym = Fungsi::sessionTglYM();
    $nounik = $query->where('NoRek', 'LIKE', 'TB'.$ym.'%')->orderBy('NoRek', 'DESC')->first();
    return Fungsi::autoNumber(!empty($nounik->NoRek) ? $nounik->NoRek : 'TB'.$ym.'00000',6,5);
  }

  public function scopeSelectTabungan($query)
  {
    return $query->join('nasabahs', 'nasabahs.NoNasabah', '=', 'tabungans.NoNasabah')
    ->select(['tabungans.NoRek', 'tabungans.NoNasabah', 'nasabahs.NamaNasabah'])
    ->where('tabungans.IsAktif', '=', true);
  }
}
