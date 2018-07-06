<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;
use DB;

class Akundetil extends Model
{
    protected $primaryKey = 'KodeDetil';

    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function akun()
    {
      return $this->belongsTo('App\Akun', 'KodeAkun');
    }

    public function scopeNoUnik($query, $akun)
    {
      $nounik = $query->where('KodeAkun', 'LIKE', $akun.'%')->orderBy('KodeAkun', 'DESC')->first();
      return Fungsi::autoNumber(!empty($nounik->KodeDetil) ? $nounik->KodeDetil : $akun.'00',4,2);
    }

    public function scopeTotal($query, $id, $op, $tgl)
    {
      if ($id == '1' || $id == '5') {
        $kon = 'SUM(jurnaldetils.Debet) - SUM(jurnaldetils.Kredit)';
      }else{
        $kon = 'SUM(jurnaldetils.Kredit) - SUM(jurnaldetils.Debet)';
      }
      return $query->select('akundetils.KodeDetil', 'akundetils.Keterangan', DB::raw('IFNULL(jurnal.total, 0) AS Total'))
      ->leftJoin(DB::raw('(SELECT '.$kon.' AS total, KodeAkun FROM jurnaldetils WHERE SUBSTRING(KodeAkun, 1, 1) = "'.$id.'" AND TglInput '.$op.' "'.$tgl.'" GROUP BY KodeAkun) AS jurnal'), 'akundetils.KodeDetil', 'jurnal.KodeAkun')
      ->where(DB::raw('SUBSTRING(akundetils.KodeDetil, 1, 1)'), $id)->orderBy('akundetils.KodeDetil', 'ASC')->get();
    }
}
