<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Simpananpokokdetil extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function simpananPokok()
    {
        return $this->belongsTo('App\Simpanpokok', 'NoRek');
    }
}
