<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tabungandetil extends Model
{
  protected $guarded = ['id', 'created_at', 'updated_at'];

  public function tabungan()
  {
    return $this->belongsTo('App\Tabungan', 'NoRek');
  }
}
