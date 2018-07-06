<?php
namespace App\Helpers;

use App\Jurnalmaster;
use App\Jurnaldetil;
use App\Akungroup;
use App\Akundetil;
use App\Settingakun;
use App\Kwitansi;
use Auth;

class Fungsi
{
    public static function autoNumber($id_terakhir, $panjang_kode, $panjang_angka)
    {
	    $kode = substr($id_terakhir, 0, $panjang_kode);
	    $angka = substr($id_terakhir, $panjang_kode, $panjang_angka);
	    $angka_baru = str_repeat("0", $panjang_angka - strlen($angka+1)).($angka+1);
	    $id_baru = $kode.$angka_baru;

	    return $id_baru;
    }

    public static function kwitansi($ref, $ket, $jenis)
    {
        $tgl = Fungsi::sessionTglYMD();
        $kwitansiNoUnik = Kwitansi::noUnik($jenis.$tgl);

        return Kwitansi::create([
            'NoKwitansi' => $kwitansiNoUnik,
            'JenisKwitansi' => $jenis,
            'IdReferensi' => $ref,
            'Keterangan' => $ket,
            'user_id' => Auth::id(),
            'TglInput' => session('tgl')
        ]);
    }

    public static function sessionTglYM()
    {
	    return date("ym", strtotime(session('tgl')));
    }

    public static function sessionTglYMD()
    {
	    return date("ymd", strtotime(session('tgl')));
    }

    public static function bulanID($b)
    {
        $bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $arr = explode("-", $b);
        $jadi = $arr[2]." ".$bulan[(int)$arr[1]]." ".$arr[0];
        return $jadi;
    }

    public static function jurnalDetil($saldo, $setting)
    {
        $sa = Settingakun::find($setting);
        $detils = [
            'Keterangan' => $sa->NamaTransaksi,
            'jurnal' => [
                [
                    'KodeAkun' => $sa->AkunDebet,
                    'Debet' => $saldo,
                    'Kredit' => '0'
                ],
                [
                    'KodeAkun' => $sa->AkunKredit,
                    'Debet' => '0',
                    'Kredit' => $saldo
                ]
            ]
        ];
        return $detils;
    }

    public static function jurnal($bukti, $ket, $detils)
    {
        $master = [
            'IdJurnal' => Jurnalmaster::noUnik(), 
            'BuktiTransaksi' => $bukti, 
            'Keterangan' => $ket, 
            'TglInput' => session('tgl'), 
            'user_id' => Auth::id(),
            'IsAktif' => true 
        ];
        Jurnalmaster::create($master);

        foreach ($detils as $detil) {

            Jurnaldetil::create([
                'IdJurnal' => $master['IdJurnal'],
                'KodeAkun' => $detil['KodeAkun'],
                'Debet' => $detil['Debet'],
                'Kredit' => $detil['Kredit'],
                'TglInput' => $master['TglInput']
            ]);

            $AkunDetil = Akundetil::find($detil['KodeAkun']);
            $KodeGroup = substr($detil['KodeAkun'],0, 1);
            $AkunGroup = Akungroup::find($KodeGroup);

            if ($AkunGroup->SaldoNormal == 1) {
                if ($detil['Debet'] > 0) {
                    $AkunDetil->update(['Saldo' => $AkunDetil->Saldo + $detil['Debet']]);
                }else {
                    $AkunDetil->update(['Saldo' => $AkunDetil->Saldo - $detil['Kredit']]);
                }
            } else {
                if ($detil['Kredit'] > 0) {
                    $AkunDetil->update(['Saldo' => $AkunDetil->Saldo + $detil['Kredit']]);
                }else {
                    $AkunDetil->update(['Saldo' => $AkunDetil->Saldo - $detil['Debet']]);
                }
            }
        }
        return $master;
    }
}
