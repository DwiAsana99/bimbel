<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pinjaman;
use App\Pinjamandetil;
use App\Tutupbuku;
use Fungsi;
use DB;
use Auth;

class PinjamanController extends Controller
{
    public function show($nonasabah)
    {
        $pinjaman = Pinjaman::where('NoNasabah', $nonasabah)->where('IsLunas', false)->first();
        if ($pinjaman) {
            $pinjaman->nasabah;
            $response = $pinjaman;
            $response->detils = Pinjamandetil::where('NoPinjaman', $pinjaman->NoPinjaman)
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'desc')
            ->limit(7)->get();
            return response()->json(['msg' => 'Berhasil Mengambil Data', 'data' => $response], 200);
        }else{
            return response()->json(['msg' => 'Data Tidak Tersedia'], 462);
        }
    }

    public function tagihan($nonasabah)
    {
        $tgl = Tutupbuku::akhir()->pluck('aktif')->first();
        $result = DB::select( DB::raw("SELECT IFNULL(SUM(d.pokok), 0) AS dibayar,
        IFNULL((AngsuranPerbulan * PERIOD_DIFF(DATE_FORMAT(?, '%Y%m'), DATE_FORMAT(p.TglInput, '%Y%m')))-SUM(d.pokok), 0) AS tunggakanpokok,
        IF(p.JenisBunga = 'menetap', p.Bunga * p.JumlahPinjaman / 100, p.Bunga * p.SisaPinjaman / 100) AS bunga,
        (SELECT DendaPinjaman FROM settingpinjamans LIMIT 1) * DATEDIFF(?, p.TglPembayaranSelanjutnya) AS denda,
        p.NoPinjaman, p.JumlahPinjaman, p.SisaPinjaman, p.AngsuranPerBulan, n.NamaNasabah, n.NoNasabah,
        (SELECT TglInput FROM pinjamandetils WHERE NoPinjaman = p.NoPinjaman ORDER BY TglInput DESC LIMIT 1) AS bayarakhir
        FROM pinjamans AS p JOIN nasabahs AS n ON p.NoNasabah = n.NoNasabah
        JOIN pinjamandetils AS d ON p.NoPinjaman = d.NoPinjaman
        WHERE p.NoNasabah = ? AND p.IsLunas = false"), [$tgl, $tgl, $nonasabah]);

        $response = $result[0];
        if(!empty($response->NoPinjaman)) {
            $response->tunggakanpokok = $response->tunggakanpokok < 0 ? 0 : $response->tunggakanpokok;
            $response->denda = $response->denda < 0 ? 0 : $response->denda;
            $response->bayarakhir = is_null($response->bayarakhir) ? 'Belum Pernah Membayar' : Fungsi::bulanID($response->bayarakhir);
            $response->total = $response->tunggakanpokok + $response->AngsuranPerBulan + $response->bunga + $response->denda;

            return response()->json(['msg' => 'Berhasil Mengambil Data', 'data' => $response], 200);
        }else {
            return response()->json(['msg' => 'Data Tidak Tersedia'], 462);
        }
    }

    public function bayar(Request $request, $nopinjaman)
    {
        DB::beginTransaction();
        try {
            $tgl = Tutupbuku::akhir()->pluck('aktif')->first();
            $pinjaman = Pinjaman::where('NoNasabah', $nopinjaman)->where('IsLunas', false)->first();
            $sisa = $pinjaman->SisaPinjaman - $request->Pokok;
            $lunas = $sisa <= 0 ? true : false;
            $pinjaman->update([
              'SisaPinjaman' => $sisa,
              'TglPembayaranSelanjutnya' => DB::raw('DATE_ADD("'.$pinjaman->TglPembayaranSelanjutnya.'", INTERVAL 1 MONTH)'),
              'IsLunas' => $lunas
            ]);
            $pd = Pinjamandetil::create([
                'NoPinjaman' => $pinjaman->NoPinjaman,
                'Pokok' => $request->Pokok,
                'NominalBunga' => $request->NominalBunga,
                'Denda' => $request->Denda,
                'Jumlah' => $request->Jumlah,
                'Sisa' => $sisa,
                'user_id' => Auth::id(),
                'TglInput' => $tgl
            ]);

            $jd = Fungsi::jurnalDetil($pd->Pokok, 12);
            Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
            $jd = Fungsi::jurnalDetil($pd->NominalBunga, 13);
            Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);
            $jd = Fungsi::jurnalDetil($pd->Denda, 14);
            Fungsi::jurnal($pd->NoPinjaman.'-'.$pd->id, $jd['Keterangan'], $jd['jurnal']);

            DB::commit();
            $pinjaman->nasabah;            
            $response = $pinjaman;
            $response->detils = Pinjamandetil::where('NoPinjaman', $pinjaman->NoPinjaman)
            ->orderBy(DB::raw('CONCAT(TglInput, SUBSTR(created_at, 11, 9))'), 'desc')
            ->limit(7)->get();
            return response()->json(['msg' => 'Berhasil Melakukan Pembayaran Pinjaman', 'data' => $response], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['msg' => 'Gagal Melakukan Pembayaran Pinjaman'], 463);
        }
    }
}
