@extends('templatePrint')
@section('title', 'Realisasi Pinjaman - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Realisasi Pinjaman</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Pinjaman</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Realisasi</th>
                <th class="text-center">Pinjaman</th>
                <th class="text-center">Bunga</th>
                <th class="text-center">Propisi</th>
                <th class="text-center">Materai/Map</th>
                <th class="text-center">Lain</th>
                <th class="text-center">Diterima</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $k => $v)
                <tr>
                    <td class="text-center">{{ $k + 1 }}</td>
                    <td class="text-center">{{ $v->NoPinjaman }}</td>
                    <td class="text-left">{{ $v->NamaNasabah }}</td>
                    <td class="text-center">{{ date_format(date_create($v->TglInput),"d-m-Y") }}</td>
                    <td class="text-right">{{ number_format($v->JumlahPinjaman,0,",",".") }}</td>
                    <td class="text-left">{{ $v->Bunga.'% - '.$v->JenisBunga }}</td>
                    <td class="text-right">{{ number_format($v->PotonganPropisi,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->PotonganMateraiMap,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->PotonganLain,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->JumlahDiterima,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="4"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->JumlahPinjaman,0,",",".") }}</b></td>
                <td></td>
                <td class="text-right"><b>{{ number_format($total->PotonganPropisi,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->PotonganMateraiMap,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->PotonganLain,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->JumlahDiterima,0,",",".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop