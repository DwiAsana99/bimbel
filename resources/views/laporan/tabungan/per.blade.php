@extends('templatePrint')
@section('title', 'Tabungan '. $datas['datas']->NoRek .' - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Tabungan Per Nasabah</h4>
@stop
@section('content')
    <table>
      <tr>
        <td>Periode</td><td> : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</td>
      </tr>
      <tr>
        <td>No. Tabungan</td><td> : {{ $datas['datas']->NoRek }}</td>
      </tr>
      <tr>
        <td>Nasabah</td><td> : {{ $datas['datas']->nasabah->NamaNasabah }}</td>
      </tr>
    </table>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas['datas']->detils as $k => $v)
                <tr>
                    <td class="text-center text-top">{{ $k + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($v->TglInput)) }}</td>
                    <td class="text-left">{{ $v->Keterangan }}</td>
                    <td class="text-right">{{ number_format($v->Debet,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Kredit,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->SaldoAkhir,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="3"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($datas['total']['Debet'], 0, ",", ".") }}</b></td>
                <td class="text-right"><b>{{ number_format($datas['total']['Kredit'], 0, ",", ".") }}</b></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <br><b>Rincian :</b>
    <table width="40%">
        <tr><td>Setoran</td><td> : {{ number_format($datas['rinci']['S'], 0, ",", ".") }}</td></tr>
        <tr><td>Penarikan</td><td> : {{ number_format($datas['rinci']['P'], 0, ",", ".") }}</td></tr>
        <tr><td>Bunga Tabungan</td><td> : {{ number_format($datas['rinci']['BT'], 0, ",", ".") }}</td></tr>
        <tr><td>Bunga Deposito</td><td> : {{ number_format($datas['rinci']['BD'], 0, ",", ".") }}</td></tr>
    </table>
    <br><i style="font-size:10pt;"><b>Keterangan :</b> Setoran(<b>S</b>), Penarikan(<b>P</b>), Bunga Tabungan(<b>BT</b>), Bunga Deposito(<b>BD</b>).</i>
@stop