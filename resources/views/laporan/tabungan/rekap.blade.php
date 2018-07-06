@extends('templatePrint')
@section('title', 'Rekap Tabungan - '.date_format(date_create($tgl),"d-m-Y"))
@section('content-header')
    <h4>Laporan Rekap Tabungan</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tgl),"d-m-Y") }}</strong>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Rekening</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $k => $v)
                <tr>
                    <td class="text-center">{{ $k + 1 }}</td>
                    <td class="text-center">{{ $v->NoRek }}</td>
                    <td class="text-left">{{ $v->NamaNasabah }}</td>
                    <td class="text-right">{{ number_format($v->Debet,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Kredit,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Saldo,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="3"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->Debet, 0, ",", ".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Kredit, 0, ",", ".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Saldo, 0, ",", ".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop