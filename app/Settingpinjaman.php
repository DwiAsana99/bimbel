<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settingpinjaman extends Model
{
  protected $table = 'settingpinjamans';
  protected $guarded = ['id'];
  public $timestamps = false;
}
