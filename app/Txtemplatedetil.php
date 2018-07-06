<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Txtemplatedetil extends Model
{
    protected $table = 'txtemplatedetils';
    protected $primaryKey = 'NoTxn';
    public $incrementing = false;
    protected $guarded = ['created_at', 'updated_at'];

    public function template()
    {
        return $this->belongsTo('App\Txtemplatedetil', 'NoTemplate');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeNoUnik($query)
    {
        $ym = Fungsi::sessionTglYM();
        $nounik = $query->where('NoTxn', 'LIKE', 'TTX'.$ym.'%')->orderBy('NoTxn', 'DESC')->first();
        return Fungsi::autoNumber(!empty($nounik->NoTxn) ? $nounik->NoTxn : 'TTX'.$ym.'00000',7,5);
    }
}
