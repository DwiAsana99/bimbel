@extends('templatePrint')
@if ($aksi == 0)
    @section('title', 'Pembayaran Pinjaman '.$pinjaman->NoPinjaman.' - '.date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y"))
@elseif ($aksi == 1)
    @section('title', 'Riwayat Pinjaman '.$pinjaman->NoPinjaman)
@endif
@section('content-header')
    @if ($aksi == 0)
        <h4>Laporan Pembayaran Per Pinjaman</h4>
    @elseif ($aksi == 1)
        <h4>Laporan Riwayat Pembayaran Per Pinjaman</h4>
    @endif
@stop
@section('content')
    <table>
        @if ($aksi == 0)
          <tr>
            <td>Periode</td><td> : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</td>
          </tr>
        @endif
        <tr>
            <td>No. Pinjaman</td><td> : {{ $pinjaman->NoPinjaman }}</td>
        </tr>
        <tr>
            <td>Nama Nasabah</td><td> : {{ $pinjaman->nasabah->NamaNasabah }}</td>
        </tr>
        <tr>
            <td>Jumlah Pinjaman</td><td> : {{ number_format($pinjaman->JumlahPinjaman,0,",",".") }}</td>
        </tr>
        <tr>
            <td>Bunga</td><td> : {{ $pinjaman->Bunga }}%</td>
        </tr>
        <tr>
            <td>Jenis Bunga</td><td> : {{ $pinjaman->JenisBunga }}</td>
        </tr>
        <tr>
            <td>Tanggal Realisasi</td><td> : {{ date_format(date_create($pinjaman->TglInput),"d-m-Y") }}</td>
        </tr>
    </table>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Pokok</th>
                <th class="text-center">Bunga</th>
                <th class="text-center">Denda</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($detils as $k => $v)
                <tr>
                    <td class="text-center">{{ $k +1 }}</td>
                    <td class="text-center">{{ date_format(date_create($v->TglInput),"d-m-Y") }}</td>
                    <td class="text-right">{{ number_format($v->Pokok,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->NominalBunga,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Denda,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Jumlah,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($v->Sisa,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="2"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->Pokok,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->NominalBunga,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Denda,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($total->Jumlah,0,",",".") }}</b></td>
                <td></td>
            </tr>
        </tbody>
    </table>
@stop