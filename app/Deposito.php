<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Fungsi;

class Deposito extends Model
{
    protected $primaryKey = 'NoDeposito';

    public $incrementing = false;
    protected $guarded = ['created_at', 'updated_at'];

    public function detils()
    {
      return $this->hasMany('App\Depositodetil', 'NoDeposito');
    }

    public function nasabah()
    {
      return $this->belongsTo('App\Nasabah', 'NoNasabah');
    }

    public function tabungan()
    {
      return $this->belongsTo('App\Tabungan', 'NoTabungan');
    }

    public function scopeNoUnik($query)
    {
      $ym = Fungsi::sessionTglYM();
      $nounik = $query->where('NoDeposito', 'LIKE', 'DP'.$ym.'%')->orderBy('NoDeposito', 'DESC')->first();
      return Fungsi::autoNumber(!empty($nounik->NoDeposito) ? $nounik->NoDeposito : 'DP'.$ym.'00000',6,5);
    }

    public function scopePostingBunga($query, $setting)
    {
      return $query->join('nasabahs', 'depositos.NoNasabah', '=', 'nasabahs.NoNasabah')
                   ->where('depositos.IsBerakhir', '=', false)
                   ->where(DB::raw('TIMESTAMPDIFF(MONTH, depositos.TglBungaAkhir, "'.session('tgl').'")'), '>=', $setting);
    }

    public function scopeLapSemua($query, $tglawal, $tglakhir)
    {
      return $query->join('nasabahs', 'depositos.NoNasabah', '=', 'nasabahs.NoNasabah')
      ->join('depositodetils', 'depositos.NoDeposito', 'depositodetils.NoDeposito')
      ->select(
        'depositos.NoTabungan', 
        'depositos.NoDeposito', 
        'nasabahs.NamaNasabah', 
        'depositodetils.TglInput', 
        'depositodetils.Bunga'
      )
      ->whereBetween('depositodetils.TglInput', [$tglawal, $tglakhir])
      ->orderBy('depositos.NoTabungan', 'ASC')
      ->orderBy('depositos.NoDeposito', 'ASC')
      ->orderBy(DB::raw('CONCAT(depositodetils.TglInput, SUBSTR(depositodetils.created_at, 11, 9))'), 'asc');
    }
}
