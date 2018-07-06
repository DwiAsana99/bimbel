<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Depositodetil extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function deposito()
    {
      return $this->belongsTo('App\Deposito', 'NoDeposito');
    }
}
