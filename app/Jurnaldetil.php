<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Jurnaldetil extends Model
{
  protected $guarded = ['created_at', 'updated_at'];

  public function master()
  {
    return $this->belongsTo('App\Jurnaldetil', 'IdJurnal');
  }

  public function scopeTotalBB($query)
  {
    return $total = $query->select('akungroups.SaldoNormal', DB::raw('SUM(jurnaldetils.Debet) AS TDebet, SUM(jurnaldetils.Kredit) AS TKredit'));
  }

  public function scopeBukuBesar($query, $tglawal, $tglakhir)
  {
    return $query->select('jurnaldetils.TglInput', 'jurnalmasters.Keterangan', 'jurnalmasters.BuktiTransaksi', 'jurnaldetils.Debet', 'jurnaldetils.Kredit')
    ->whereBetween('jurnaldetils.TglInput', [$tglawal, $tglakhir])
    ->orderBy('jurnaldetils.TglInput', 'asc');
  }

  public function scopeWhereBB($query, $akun)
  {
    return $query->join('jurnalmasters', 'jurnalmasters.IdJurnal', 'jurnaldetils.IdJurnal')
    ->join('akungroups', 'akungroups.KodeGroup', DB::raw('SUBSTRING(jurnaldetils.KodeAkun, 1, 1)'))
    ->where('jurnaldetils.KodeAkun', $akun);
  }

  public function scopeTotalAkun($query, $id, $tglawal, $tglakhir = null)
  {
    if ($id == '1' || $id == '5') {
      $kon = 'SUM(jurnaldetils.Debet) - SUM(jurnaldetils.Kredit)';
    }else{
      $kon = 'SUM(jurnaldetils.Kredit) - SUM(jurnaldetils.Debet)';
    }
    return $query->select('akundetils.*', DB::raw('IFNULL('.$kon.', 0) AS total'))
    ->rightJoin('akundetils', 'akundetils.KodeDetil', '=', 'jurnaldetils.KodeAkun')
    ->when($tglakhir, function ($query) use ($tglawal, $tglakhir) {
      return $query->whereBetween('jurnaldetils.TglInput', [$tglawal, $tglakhir]);
    }, function ($query) use ($tglawal) {
      return $query->where('jurnaldetils.TglInput', '<=', $tglawal);
    })
    ->where(DB::raw('SUBSTRING(akundetils.KodeDetil, 1, 1)'), $id)
    ->groupBy('akundetils.KodeDetil');
  }

  public function scopeTotal($query, $id, $op, $tgl)
  {
    if ($id == '1' || $id == '5') {
      $kon = 'SUM(Debet) - SUM(Kredit)';
    }else{
      $kon = 'SUM(Kredit) - SUM(Debet)';
    }
    return $query->select(DB::raw('IFNULL('.$kon.', 0) AS total'))
    ->where(DB::raw('SUBSTRING(KodeAkun, 1, 1)'), $id)
    ->where('TglInput', $op, $tgl)
    ->first();
  }
}
