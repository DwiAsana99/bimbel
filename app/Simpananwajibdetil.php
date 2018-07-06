<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simpananwajibdetil extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function simpananWajib()
    {
        return $this->belongsTo('App\Simpananwajib', 'NoRek');
    }
}
