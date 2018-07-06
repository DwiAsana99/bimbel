<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pinjamandetil extends Model
{
  protected $guarded = ['id', 'created_at', 'updated_at'];

  public function pinjaman()
  {
    return $this->belongsTo('App\Pinjaman', 'NoPinjaman');
  }

  public function scopeLapSemua($query, $tglawal, $tglakhir)
  {
    return $query->leftJoin('pinjamans', 'pinjamans.NoPinjaman', 'pinjamandetils.NoPinjaman')
    ->leftJoin('nasabahs', 'nasabahs.NoNasabah', 'pinjamans.NoNasabah')
    ->select(
      'pinjamandetils.NoPinjaman',
      'nasabahs.NamaNasabah',
      'pinjamandetils.TglInput',
      'pinjamandetils.Pokok',
      'pinjamandetils.NominalBunga',
      'pinjamandetils.Denda',
      'pinjamandetils.Jumlah',
      'pinjamandetils.Sisa'
    )
    ->whereBetween('pinjamandetils.TglInput', [$tglawal, $tglakhir])
    ->orderBy('pinjamandetils.NoPinjaman', 'ASC')
    ->orderBy(DB::raw('CONCAT(pinjamandetils.TglInput, SUBSTR(pinjamandetils.created_at, 11, 9))'), 'asc');
  }
  
  public function scopeLapSemuaTotal($query, $tglawal = null, $tglakhir = null)
  {
    return $query->select(
      DB::raw('
        SUM(pinjamandetils.Pokok) AS Pokok, 
        SUM(pinjamandetils.NominalBunga) AS NominalBunga, 
        SUM(pinjamandetils.Denda) AS Denda, 
        SUM(pinjamandetils.Jumlah) AS Jumlah
      ')
    )
    ->when($tglawal && $tglakhir, function ($query) use ($tglawal, $tglakhir) {
      return $query->whereBetween('pinjamandetils.TglInput', [$tglawal, $tglakhir]);
    })
    ->first();
  }

  public function scopeLapRekapTotal($query, $tgl)
  {
    return $query->select(
      DB::raw('
        (select IFNULL(SUM(pinjamans.JumlahPinjaman), 0) 
          from pinjamans 
          where pinjamans.TglInput <= "'.$tgl.'"
        ) AS JumlahPinjaman,
        IFNULL(SUM(pinjamandetils.Pokok), 0) AS Pokok,
        IFNULL(SUM(pinjamandetils.NominalBunga), 0) AS NominalBunga,
        IFNULL(SUM(pinjamandetils.Denda), 0) AS Denda,
        ((select IFNULL(SUM(pinjamans.JumlahPinjaman), 0) 
            from pinjamans 
            where pinjamans.TglInput <= "'.$tgl.'"
          ) - IFNULL(SUM(pinjamandetils.Pokok), 0)
        ) AS Sisa
      ')
    )
    ->where('pinjamandetils.TglInput', '<=', $tgl)
    ->first();
  }
}
