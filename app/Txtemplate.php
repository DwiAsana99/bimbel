<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Txtemplate extends Model
{
    protected $table = 'txtemplates';
    protected $primaryKey = 'NoTemplate';
    protected $guarded = ['NoTemplate'];
    public $timestamps = false;

    public function detils()
    {
        return $this->hasMany('App\Txtemplatedetil', 'NoTemplate');
    }

    public function debet()
    {
        return $this->belongsTo('App\Akundetil', 'AkunDebet');
    }

    public function kredit()
    {
        return $this->belongsTo('App\Akundetil', 'AkunKredit');
    }
}
