<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fungsi;
use DB;

class Pinjaman extends Model
{
  protected $table = 'pinjamans';
  protected $primaryKey = 'NoPinjaman';

  public $incrementing = false;
  protected $guarded = [];

  public function nasabah()
  {
    return $this->belongsTo('App\Nasabah', 'NoNasabah');
  }

  public function detils()
  {
    return $this->hasMany('App\Pinjamandetil', 'NoPinjaman');
  }

  public function detilPokoks()
  {
    return $this->hasMany('App\Pinjamandetilpokok', 'NoPinjaman');
  }

  public function detilBungas()
  {
    return $this->hasMany('App\Pinjamandetilbunga', 'NoPinjaman');
  }
  
  public function detilDendas()
  {
    return $this->hasMany('App\Pinjamandetildenda', 'NoPinjaman');
  }

  public function pinjamanbaru()
  {
    return $this->hasOne('App\Pinjamankompen', 'NoPinjamanBaru');
  }

  public function pinjamankompen()
  {
    return $this->hasOne('App\Pinjamankompen', 'NoPinjamanKompen');
  }

  public function scopeNoUnik($query)
  {
    $ym = Fungsi::sessionTglYM();
    $nounik = $query->where('NoPinjaman', 'LIKE', 'KR'.$ym.'%')->orderBy('NoPinjaman', 'DESC')->first();
    return Fungsi::autoNumber(!empty($nounik->NoPinjaman) ? $nounik->NoPinjaman : 'KR'.$ym.'00000',6,5);
  }

  public function scopeBayar($query, $NoPinjaman)
  {
    $pinjaman = $query->select(
      'NoPinjaman', 
      'NoNasabah', 
      'JumlahPinjaman', 
      'SisaPinjaman',
      DB::raw('
        (SELECT NamaNasabah FROM nasabahs WHERE pinjamans.NoNasabah = nasabahs.NoNasabah) AS NamaNasabah,
        (JumlahPinjaman - SisaPinjaman) AS SudahDibayar,
        CASE
            WHEN JenisBunga = "Menurun" THEN SisaPinjaman * Bunga / 100
            WHEN JenisBunga = "Menetap" THEN AngsuranPerbulan * Bunga / 100
        END AS BungaPeriodeIni,
        (SELECT IFNULL(SUM(Debet) - SUM(Kredit), 0) FROM pinjamandetilpokoks
            WHERE NoPinjaman = "'.$NoPinjaman.'" AND Periode < pinjamans.PeriodeSelanjutnya
        )AS TunggakanPokok,
        (SELECT IFNULL(SUM(Debet) - SUM(Kredit), 0) FROM pinjamandetilbungas
            WHERE NoPinjaman = "'.$NoPinjaman.'" AND Periode < pinjamans.PeriodeSelanjutnya
        )AS TunggakanBunga,
        (SELECT IFNULL(SUM(Debet) - SUM(Kredit), 0) FROM pinjamandetildendas
            WHERE NoPinjaman = "'.$NoPinjaman.'" AND Periode < pinjamans.PeriodeSelanjutnya
        )AS Denda,
        IF(SisaPinjaman < AngsuranPerbulan, SisaPinjaman, AngsuranPerbulan) AS PokokPeriodeIni
      '),
      'TempBayar AS BayarTerakhir'
    )
    ->where('NoPinjaman', $NoPinjaman)->first();

    $pinjaman->TunggakanPokok = $pinjaman->TunggakanPokok > 0 ?: 0;
    $pinjaman->BayarTerakhir = is_null($pinjaman->BayarTerakhir) ? 'Belum Pernah Membayar' : Fungsi::bulanID($pinjaman->BayarTerakhir);
    $pinjaman->Total = ($pinjaman->PokokPeriodeIni + $pinjaman->TunggakanPokok) + ($pinjaman->BungaPeriodeIni + $pinjaman->TunggakanBunga) + $pinjaman->Denda;

    return $pinjaman;
  }

  public function scopePelunasan($query, $nopinjaman)
  {
    $pinjaman = $query->join('nasabahs', 'pinjamans.NoNasabah', '=', 'nasabahs.NoNasabah')
      ->leftJoin('pinjamandetils', 'pinjamans.NoPinjaman', '=', 'pinjamandetils.NoPinjaman')
      ->where('pinjamans.NoPinjaman', $nopinjaman)
      ->groupBy('pinjamandetils.NoPinjaman')
      ->select([
        'pinjamans.*',
        'nasabahs.NamaNasabah',
        DB::raw('IFNULL(SUM(pinjamandetils.Pokok), 0) as PokokDibayar'),
        DB::raw('IFNULL(SUM(pinjamandetils.NominalBunga), 0) as BungaDibayar')
      ])->first();
      $pinjaman->SisaPokok = $pinjaman->JumlahPinjaman - $pinjaman->PokokDibayar;
      if ($pinjaman->JenisBunga == 'menetap') {
        $pinjaman->BungaNominal = ($pinjaman->JumlahPinjaman * $pinjaman->Bunga / 100) * $pinjaman->JangkaWaktu;
        $pinjaman->SisaBunga = $pinjaman->BungaNominal - $pinjaman->BungaDibayar;
      }else {
        $pinjaman->BungaNominal = $pinjaman->SisaPinjaman * $pinjaman->Bunga / 100;
        $pinjaman->SisaBunga = $pinjaman->BungaNominal;
      }
      $pinjaman->Total = $pinjaman->SisaPokok + $pinjaman->SisaBunga;

      return $pinjaman;
  }

  public function scopeLapRekap($query, $tgl)
  {
    return $query->join('nasabahs', 'nasabahs.NoNasabah', 'pinjamans.NoNasabah')
    ->select(
      'pinjamans.NoPinjaman',
      'nasabahs.NamaNasabah',
      'pinjamans.TglInput',
      'pinjamans.JangkaWaktu',
      'pinjamans.TglJatuhTempo',
      'pinjamans.JumlahPinjaman',
      DB::raw('
        (select IFNULL(SUM(pinjamandetils.Pokok), 0) 
          from pinjamandetils 
          where pinjamandetils.NoPinjaman = pinjamans.NoPinjaman 
          AND pinjamandetils.TglInput <= "'.$tgl.'"
        ) AS Pokok,
        (select IFNULL(SUM(pinjamandetils.NominalBunga), 0) 
          from pinjamandetils 
          where pinjamandetils.NoPinjaman = pinjamans.NoPinjaman 
          AND pinjamandetils.TglInput <= "'.$tgl.'"
        ) AS NominalBunga,
        (select IFNULL(SUM(pinjamandetils.Denda), 0) 
          from pinjamandetils 
          where pinjamandetils.NoPinjaman = pinjamans.NoPinjaman 
          AND pinjamandetils.TglInput <= "'.$tgl.'"
        ) AS Denda
      '),
      DB::raw('(
        pinjamans.JumlahPinjaman - (
          select IFNULL(SUM(pinjamandetils.Pokok), 0) 
          from pinjamandetils 
          where pinjamandetils.NoPinjaman = pinjamans.NoPinjaman 
          AND pinjamandetils.TglInput <= "'.$tgl.'"
        )
      ) AS Sisa')
    )
    ->where('pinjamans.TglInput', '<=', $tgl)
    ->orderBy('pinjamans.NoPinjaman', 'ASC');
  }

  public function scopeLapRealisasi($query, $tglawal, $tglakhir)
  {
    return $query->join('nasabahs', 'nasabahs.NoNasabah', 'pinjamans.NoNasabah')
    ->select('pinjamans.*', 'nasabahs.NamaNasabah')
    ->whereBetween('TglInput', [$tglawal, $tglakhir]);
  }

  public function scopeLapRealisasiTotal($query, $tglawal, $tglakhir)
  {
    return $query->select(
      DB::raw('
        SUM(JumlahPinjaman) AS JumlahPinjaman,
        SUM(PotonganPropisi) AS PotonganPropisi,
        SUM(PotonganMateraiMap) AS PotonganMateraiMap,
        SUM(PotonganLain) AS PotonganLain,
        SUM(JumlahDiterima) AS JumlahDiterima
      ')
    )
    ->whereBetween('TglInput', [$tglawal, $tglakhir])
    ->first();
  }
}
