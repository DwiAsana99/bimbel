<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settingdeposito extends Model
{
    protected $table = 'settingdepositos';
    protected $primaryKey = 'id';
    protected $fillable = ['BulanPostingBunga', 'IsGabungKeTabungan', 'BatasKenaPajak', 'Pajak'];
    public $timestamps = false;
}
