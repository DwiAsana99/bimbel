<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tutupbuku extends Model
{
  protected $guarded = ['id', 'created_at', 'updated_at'];

  public function scopeAkhir($query)
  {
    return $query->orderBy('id', 'desc');
  }
}
