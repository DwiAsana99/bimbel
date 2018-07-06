@extends('templatePrint')
@section('title', 'Pembayaran Pinjaman - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Pembayaran Semua Pinjaman</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No Pinjaman</th>
                <th class="text-center">Nama Nasabah</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Pokok</th>
                <th class="text-center">Bunga</th>
                <th class="text-center">Denda</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $kM => $vM)
                @php
                    $rs = count($vM->detils);
                @endphp
                <tr>
                    <td rowspan="{{ $rs }}" class="text-center text-top">{{ $kM + 1 }}</td>
                    <td rowspan="{{ $rs }}" class="text-center text-top">{{ $vM->NoPinjaman }}</td>
                    <td rowspan="{{ $rs }}" class="text-left text-top">{{ $vM->NamaNasabah }}</td>
                    <td class="text-center">{{ date_format(date_create($vM->detils[0]->TglInput),"d-m-Y") }}</td>
                    <td class="text-right">{{ number_format($vM->detils[0]->Pokok,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vM->detils[0]->NominalBunga,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vM->detils[0]->Denda,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vM->detils[0]->Jumlah,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($vM->detils[0]->Sisa,0,",",".") }}</td>
                </tr>
                @foreach ($vM->detils as $kD => $vD)
                  @continue($kD == 0)
                  <tr>
                      <td class="text-center">{{ date_format(date_create($vD->TglInput),"d-m-Y") }}</td>
                      <td class="text-right">{{ number_format($vD->Pokok,0,",",".") }}</td>
                      <td class="text-right">{{ number_format($vD->NominalBunga,0,",",".") }}</td>
                      <td class="text-right">{{ number_format($vD->Denda,0,",",".") }}</td>
                      <td class="text-right">{{ number_format($vD->Jumlah,0,",",".") }}</td>
                      <td class="text-right">{{ number_format($vD->Sisa,0,",",".") }}</td>
                  </tr>
                @endforeach
            @empty
                <tr><td colspan="9" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="4"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->Pokok,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->NominalBunga,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Denda,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Jumlah,0,",",".") }}</b></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@stop