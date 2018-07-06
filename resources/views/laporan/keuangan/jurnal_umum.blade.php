@extends('templatePrint')
@section('title', 'Jurnal Umum - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Jurnal Umum</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>
    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Bukti Trx</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Akun</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $km => $vm)
                @php
                    $rs = count($vm->detils);
                @endphp
                <tr>
                    <td class="text-center" rowspan="{{ $rs }}">{{ $km + 1 }}</td>
                    <td class="text-center" rowspan="{{ $rs }}">{{ date_format(date_create($vm->TglInput),"d-m-Y") }}</td>
                    <td class="text-left" rowspan="{{ $rs }}">{{ $vm->BuktiTransaksi }}</td>
                    <td class="text-left" rowspan="{{ $rs }}">{{ $vm->Keterangan }}</td>
                    <td class="text-center">{{$vm->detils[0]->KodeAkun}}</td>
                    <td class="text-right">{{ number_format($vm->detils[0]->Debet,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vm->detils[0]->Kredit,0,",",".") }}</td>
                </tr>
                @foreach ($vm->detils as $kd => $vd)
                @continue($kd == 0)
                    <tr>
                        <td>{{$vd->KodeAkun}}</td>
                        <td class="text-right">{{ number_format($vd->Debet,0,",",".") }}</td>
                        <td class="text-right">{{ number_format($vd->Kredit,0,",",".") }}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td colspan="7" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <th colspan="5">Total</th>
                <td class="text-right"><b>{{ number_format($total->Debet,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Kredit,0,",",".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop