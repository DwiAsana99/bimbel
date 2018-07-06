<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;

class Akun extends Model
{
    protected $primaryKey = 'KodeAkun';

    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function detils()
    {
        return $this->hasMany('App\Akundetil', 'KodeAkun');
    }

    public function kelompok()
    {
      return $this->belongsTo('App\AkunKelompok', 'KodeKelompok');
    }

    public function scopeJoinGroup($query)
    {
        return $query->join('akunkelompoks', 'akuns.KodeKelompok', '=', 'akunkelompoks.KodeKelompok')
                     ->join('akungroups', 'akunkelompoks.KodeGroup', '=', 'akungroups.KodeGroup')
                     ->select(
                        'akuns.KodeAkun as KodeAkun',
                        'akuns.Keterangan as akuns.Keterangan',
                        'akunkelompoks.KodeKelompok as KodeKelompok', 
                        'akunkelompoks.Keterangan as akunkelompoks.Keterangan', 
                        'akungroups.KodeGroup as KodeGroup', 
                        'akungroups.Keterangan as akungroups.Keterangan'
                     );
    }

    public function scopeNoUnik($query, $kelompok)
    {
      $nounik = $query->where('KodeAkun', 'LIKE', $kelompok.'%')->orderBy('KodeAkun', 'DESC')->first();
      return Fungsi::autoNumber(!empty($nounik->KodeAkun) ? $nounik->KodeAkun : $kelompok.'00',2,2);
    }
}
