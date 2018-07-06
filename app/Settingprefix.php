<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settingprefix extends Model
{
    protected $table = 'settingprefixs';
    protected $guarded = ['id'];
    public $timestamps = false;
}
