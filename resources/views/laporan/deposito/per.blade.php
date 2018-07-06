@extends('templatePrint')
@section('title', 'Deposito '. $datas->NoDeposito .' - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Deposito Per Rekening</h4>
@stop
@section('content')
    <table>
      <tr>
        <td>Periode</td><td> : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</td>
      </tr>
      <tr>
        <td>No. Tabungan</td><td> : {{ $datas->NoTabungan }}</td>
      </tr>
      <tr>
        <td>No. Deposito</td><td> : {{ $datas->NoDeposito }}</td>
      </tr>
      <tr>
        <td>Nasabah</td><td> : {{ $datas->nasabah->NamaNasabah }}</td>
      </tr>
    </table>

    <table class="garis" width="100%">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">No. Bukti</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Bunga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas->detils as $k => $v)
                <tr>
                    <td class="text-center text-top">{{ $k + 1 }}</td>
                    <td class="text-left">{{ $v->NoBukti }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($v->TglInput)) }}</td>
                    <td class="text-right">{{ number_format($v->Bunga,0,",",".") }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Tidak Ada Data</td></tr>
            @endforelse
            <tr>
                <td class="text-center" colspan="3"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($total->Bunga,0,",",".") }}</b></td>
            </tr>
        </tbody>
    </table>
@stop