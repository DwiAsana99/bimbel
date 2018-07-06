<?php

namespace App\Http\Controllers;

use App\Nasabah;
use App\Anggota;
use App\Simpananpokok;
use App\Simpananwajib;
use App\Simpananpokokdetil;
use App\Simpananwajibdetil;
use App\Tabungan;
use App\Tabungandetil;
use Illuminate\Http\Request;
use Datatables;
use Fungsi;
use DB;
use Auth;

class NasabahController extends Controller
{
    public function index()
    {
        return view('nasabah.index');
    }

    public function dt()
    {
        $datas = Nasabah::all();
        return Datatables::of($datas)
        ->editColumn('TglGabung', function ($data) {
            return Fungsi::bulanID($data->TglGabung);
        })
        ->addColumn('aktif', function ($data) {
            $isanggota = $data->anggota ? 'fa fa-users' : 'fa fa-user';
            if ($data->IsAktif == 1) {
                $isaktif = 'text-primary';
                $tooltip = $data->anggota ? 'Nasabah Adalah Anggota' : 'Nasabah Bukan Anggota';
            }else{
                $isaktif = 'text-red';
                $tooltip = 'Nasabah Tidak Aktif';
            }
            return '<i class="'.$isanggota.' '.$isaktif.'" data-toggle="tooltip" title="'.$tooltip.'"></i>';
        })
        ->addColumn('action', function ($data) {
            $isanggota = $data->anggota ? array('btn-danger', 'Keluar Anggota', 'href="nasabah/'.$data->NoNasabah.'/anggota"') : array('btn-success', 'Jadikan Anggota', 'id="btn-anggota" data-toggle="modal" data-target="#modal-anggota"');
            // $isaktif = $data->IsAktif == 1 ?  array('btn-danger', 'NonAktifkan Nasabah') : array('btn-success', 'Aktifkan Nasabah');
            return '<a href="nasabah/'.$data->NoNasabah.'/edit" class="btn btn-xs btn-default" data-toggle="tooltip" title="Ubah Data Nasabah"><i class="fa fa-edit"></i> Ubah</a>
            <a '.$isanggota[2].' class="btn btn-xs '.$isanggota[0].'" data-toggle="tooltip" title="'.$isanggota[1].'"><i class="fa fa-users"></i></a>';
             // <a href="nasabah/'.$data->NoNasabah.'/isaktif" class="btn btn-xs '.$isaktif[0].'" data-toggle="tooltip" title="'.$isaktif[1].'"><i class="fa fa-power-off"></i></a>
             // <a href="nasabah/'.$data->NoNasabah.'/anggota" class="btn btn-xs '.$isanggota[0].'" data-toggle="tooltip" title="'.$isanggota[1].'"><i class="fa fa-users"></i></a>
        })->rawColumns(['action', 'aktif'])
        ->make(true);
    }

    public function create()
    {
      $isEdit = false;
      $nasabah = new Nasabah;
      $nasabah->NoNasabah = Nasabah::noUnik();
      $NamaKolektor = '';
      return view('nasabah.form', compact('nasabah', 'isEdit', 'NamaKolektor'));
    }

    public function store(Request $request)
    {
      $request->request->add(['TglGabung' => session('tgl'), 'IsAktif' => true]);
      DB::beginTransaction();
      try {
        //store nasabah
        $nasabah = Nasabah::create($request->except([
          'canggota', 
          'ctabungan', 
          'tabungan', 
          'pokok', 
          'wajib'
        ]));

        if ($request->canggota == "on") {
          $anggota = $this->tambahAnggota(
            $nasabah->NoNasabah, 
            $nasabah->TglGabung, 
            $request->input('pokok'), 
            $request->input('wajib')
          );
        }

        if ($request->ctabungan == "on") {
          $kode = Tabungan::noUnik();

          $tabungan = Tabungan::create([
            'NoRek' => $kode,
            'NoNasabah' => $request->input('NoNasabah'),
            'Saldo' => $request->input('tabungan'),
            'SaldoRendah' => 0,
            'SaldoTinggi' => $request->input('tabungan'),
            'PeriodeBunga' => ((int)substr(session('tgl'), 5, 2)) == 12 ? 1 : ((int)substr(session('tgl'), 5, 2)) + 1,
            'IsAktif' => true,
            'user_id' => Auth::id(),
            'TglInput' => session('tgl')
          ]);
          $td = Tabungandetil::create([
            'NoRek' => $tabungan->NoRek,
            'Kredit' => $tabungan->Saldo,
            'Nominal' => $tabungan->Saldo,
            'Keterangan' => 'Setoran Awal Tabungan',
            'SaldoAkhir' => $tabungan->Saldo,
            'TglInput' => $tabungan->TglInput,
            'user_id' => Auth::id()
          ]);
          $jd = Fungsi::jurnalDetil($tabungan->Saldo, 5);
          Fungsi::jurnal($tabungan->NoRek.'-'.$td->id, $jd['Keterangan'], $jd['jurnal']);
        }

        DB::commit();
      }catch (\Exception $e){
        DB::rollback();
        return redirect()->action('NasabahController@index')
        ->with('notif', json_encode([
          'title' => "Tambah Nasabah",
          'text' => "Gagal Menambah Data Nasabah",
          'type' => "error"
        ]));
      }
      return redirect()->action('NasabahController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Nasabah",
        'text' => "Data Nasabah ".$nasabah->NoNasabah." Berhasil Ditambah.",
        'type' => "success"
      ]));
    }

    public function apiStore(Request $request)
    {
      try {
        $request->request->add(['TglGabung' => session('tgl'), 'IsAktif' => true]);
        $nasabah = Nasabah::create($request->all());
        return response()->json([
          'notif' => [
            'title' => "Tambah Nasabah",
            'text' => "Data Nasabah ".$nasabah->NoNasabah." Berhasil Ditambah.",
            'type' => "success"
          ],
          'data' => $nasabah
        ], 200);
      } catch (\Exception $e) {
        return response()->json([
          'notif' => [
            'title' => "Tambah Nasabah",
            'text' => "Gagal Menambah Data Nasabah",
            'type' => "error"
          ]
        ], 500);
      }
    }

    private function tambahAnggota($NoNasabah, $TglGabung, $SetorPokok, $SetorWajib)
    {
      $kode = Anggota::noUnik();

      $anggota = Anggota::create([
        'NoAnggota' => $kode, 
        'NoNasabah' => $NoNasabah, 
        'TglGabung' => $TglGabung, 
        'IsAktif' => true
      ]);

      //store simpanan
      $kodesp = Simpananpokok::noUnik();

      $SP = Simpananpokok::create([
        'NoRek' => $kodesp, 
        'NoAnggota' => $anggota->NoAnggota, 
        'Saldo' => $SetorPokok, 
        'IsAktif' => true, 
        'user_id' => Auth::id()
      ]);
      $SPd = Simpananpokokdetil::create([
        'NoRek' => $SP->NoRek, 
        'Kredit' => $SP->Saldo, 
        'SaldoAkhir' => $SP->Saldo, 
        'TglInput' => $anggota->TglGabung, 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($SP->Saldo, 1);
      Fungsi::jurnal($SP->NoRek.'-'.$SPd->id, $jd['Keterangan'], $jd['jurnal']);

      $kodesw = Simpananwajib::noUnik();

      $SW = Simpananwajib::create([
        'NoRek' => $kodesw, 
        'NoAnggota' => $anggota->NoAnggota, 
        'Saldo' => $SetorWajib, 
        'IsAktif' => true, 
        'user_id' => Auth::id()
      ]);
      $SWd = Simpananwajibdetil::create([
        'NoRek' => $SW->NoRek, 
        'Kredit' => $SW->Saldo, 
        'SaldoAkhir' => $SW->Saldo, 
        'TglInput' => $anggota->TglGabung, 
        'user_id' => Auth::id()
      ]);
      $jd = Fungsi::jurnalDetil($SW->Saldo, 3);
      Fungsi::jurnal($SW->NoRek.'-'.$SWd->id, $jd['Keterangan'], $jd['jurnal']);

      return $anggota->NoAnggota;
    }

    public function edit(Nasabah $nasabah)
    {
        $isEdit = true;
        $NamaKolektor = $nasabah->kolektor ? $nasabah->kolektor->Nama : '';
        return view('nasabah.form', compact('nasabah', 'isEdit', 'NamaKolektor'));
    }

    public function update(Request $request, Nasabah $nasabah)
    {
        try {
          $nasabah->update($request->except('NoNasabah'));
          return redirect()->action('NasabahController@index')
          ->with('notif', json_encode([
            'title' => "Ubah Nasabah",
            'text' => "Data Nasabah ".$nasabah->NoNasabah." Berhasil Diubah.",
            'type' => "success"
          ]));
        } catch (\Exception $e) {
          return redirect()->action('NasabahController@index')
          ->with('notif', json_encode([
            'title' => "Ubah Nasabah",
            'text' => "Gagal Merubah Data Nasabah.",
            'type' => "success"
          ]));
        }
    }

    public function anggota(Request $request, $nasabah)
    {
      try {
        DB::beginTransaction();
        $anggota = $this->tambahAnggota(
          $nasabah, 
          session('tgl'), 
          $request->pokok, 
          $request->wajib
        );
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        return redirect()->action('NasabahController@index')
        ->with('notif', json_encode([
          'title' => "Tambah Anggota",
          'text' => "Nasabah Gagal Menjadi Anggota",
          'type' => "error"
        ]));
      }
      return redirect()->action('NasabahController@index')
      ->with('notif', json_encode([
        'title' => "Tambah Anggota",
        'text' => "Nasabah Berhasil Menjadi Anggota ".$anggota.".",
        'type' => "success"
      ]));
    }

    public function isAktif(Nasabah $nasabah)
    {
      if ($nasabah->IsAktif == true) {
        $nasabah->update(['IsAktif' => false]);
        return redirect()->action('NasabahController@index')
        ->with('notif', json_encode([
          'title' => "Status Nasabah",
          'text' => "Status Nasabah ".$nasabah->NoNasabah." Menjadi Tidak Aktif.",
          'type' => "success"
        ]));
      }else{
        $nasabah->update(['IsAktif' => true]);
        return redirect()->action('NasabahController@index')
        ->with('notif', json_encode([
          'title' => "Status Nasabah",
          'text' => "Status Nasabah ".$nasabah->NoNasabah." Menjadi Aktif.",
          'type' => "success"
        ]));
      }
    }

    public function select2(Request $r)
    {
      $term = trim($r->q);
      if (empty($term)) {
          $tags = Nasabah::whereNotIn('NoNasabah', function($query) {
            $query->select('NoNasabah')->from('tabungans')->where('IsAktif', true);
          })->get();
      }else {
        $tags = Nasabah::whereNotIn('NoNasabah', function($query) {
          $query->select('NoNasabah')->from('tabungans')->where('IsAktif', true);
        })->where('NoNasabah', 'LIKE', '%'. $term .'%')
        ->orWhere('NamaNasabah', 'LIKE', '%'. $term .'%')
        ->get();
      }
      $formatted_tags = [];
      foreach ($tags as $tag) {
          $formatted_tags[] = ['id' => $tag->NoNasabah, 'text' => $tag->NoNasabah.' | '.$tag->NamaNasabah];
      }
      return response()->json($formatted_tags);
    }
}
