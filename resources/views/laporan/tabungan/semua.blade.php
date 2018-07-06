@extends('templatePrint')
@section('title', 'Tabungan - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Tabungan Semua Nasabah</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>
    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Rekening</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Ref</th>
                <th class="text-center">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas['datas'] as $kT => $vT)
                @php
                    $rs = count($vT->detils);
                @endphp
                <tr>
                    <td class="text-center text-top" rowspan="{{ $rs }}">{{ $kT + 1 }}</td>
                    <td class="text-center text-top" rowspan="{{ $rs }}">{{ $vT->NoRek }}</td>
                    <td class="text-top" rowspan="{{ $rs }}">{{ $vT->nasabah->NamaNasabah }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($vT->detils[0]->TglInput)) }}</td>
                    <td class="text-right">{{ number_format($vT->detils[0]->Debet,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vT->detils[0]->Kredit,0,",",".") }}</td>
                    <td class="text-center">{{ $vT->detils[0]->Ref }}</td>
                    <td class="text-right">{{ number_format($vT->detils[0]->SaldoAkhir,0,",",".") }}</td>
                </tr>
                @foreach ($vT->detils as $kD => $vD)
                @continue($kD == 0)
                <tr>
                    <td class="text-center">{{ date('d/m/Y', strtotime($vD->TglInput)) }}</td>
                    <td class="text-right">{{ number_format($vD->Debet,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vD->Kredit,0,",",".") }}</td>
                    <td class="text-center">{{ $vD->Ref }}</td>
                    <td class="text-right">{{ number_format($vD->SaldoAkhir,0,",",".") }}</td>
                </tr>
                @endforeach
            @empty
                <tr><td colspan="8" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="4"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($datas['total']['Debet'], 0, ",", ".") }}</b></td>
                <td class="text-right"><b>{{ number_format($datas['total']['Kredit'], 0, ",", ".") }}</b></td>
                <td colspan="2"></td>
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