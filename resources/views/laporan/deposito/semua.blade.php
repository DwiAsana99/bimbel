@extends('templatePrint')
@section('title', 'Deposito - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Deposito Semua Rekening</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>
    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Rekening</th>
                <th class="text-center">No Deposito</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Bunga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $k => $v)
                <tr>
                    <td class="text-center">{{ $k + 1 }}</td>
                    <td class="text-center">{{ $v->NoTabungan }}</td>
                    <td class="text-center">{{ $v->NoDeposito }}</td>
                    <td class="text-left">{{ $v->NamaNasabah }}</td>
                    <td class="text-center">{{ date_format(date_create($v->TglInput),"d-m-Y") }}</td>
                    <td class="text-right">{{ number_format($v->Bunga,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="5"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->Bunga,0,",",".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop