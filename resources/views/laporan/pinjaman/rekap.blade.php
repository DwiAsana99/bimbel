@extends('templatePrint')
@section('title', 'Rekap Pinjaman - '.date_format(date_create($tgl),"d-m-Y"))
@section('content-header')
    <h4>Laporan Rekap Pinjaman</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tgl),"d-m-Y") }}</strong>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Pinjaman</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Realisasi</th>
                <th class="text-center">Jangka Waktu</th>
                <th class="text-center">Jatuh Tempo</th>
                <th class="text-center">Pinjaman</th>
                {{-- <th class="text-center">Total Pembayaran</th> --}}
                <th class="text-center">Pokok</th>
                <th class="text-center">Bunga</th>
                <th class="text-center">Denda</th>
                
                <th class="text-center">Sisa</th>
            </tr>
            {{-- <tr>
              <th class="text-center">Pokok</th>
              <th class="text-center">Bunga</th>
              <th class="text-center">Denda</th>
            </tr> --}}
        </thead>
        <tbody>
            @forelse ($datas as $k => $v)
                <tr>
                    <td class="text-center">{{ $k + 1 }}</td>
                    <td class="text-center">{{ $v->NoPinjaman }}</td>
                    <td class="text-left">{{ $v->NamaNasabah }}</td>
                    <td class="text-center">{{ date_format(date_create($v->TglInput),"d-m-Y") }}</td>
                    <td class="text-center">{{ $v->JangkaWaktu }}</td>
                    <td class="text-center">{{ date_format(date_create($v->TglJatuhTempo),"d-m-Y") }}</td>          
                    <td class="text-right">{{ number_format($v->JumlahPinjaman,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Pokok,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->NominalBunga,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Denda,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Sisa,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="6"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->JumlahPinjaman,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Pokok,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->NominalBunga,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Denda,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Sisa,0,",",".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop