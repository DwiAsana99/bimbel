@extends('templatePrint')
@section('title', 'Buku Besar - '.date_format(date_create($tglawal),"d-m-Y").' / '.date_format(date_create($tglakhir),"d-m-Y"))
@section('content-header')
    <h4>Laporan Buku Besar</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tglawal),"d-m-Y").' s/d '.date_format(date_create($tglakhir),"d-m-Y") }}</strong>
    <br>
    <br>
    <table width="100%">
      <tr>
        <td class="text-left"><strong>Akun : {{ $akun.' ( '.$ketakun.' )' }}</strong></td>
        <td class="text-right"><strong>Saldo Awal : {{ number_format($hasil,0,",",".") }}</strong></td>
      </tr>
    </table>
    <table class="garis" width="100%">
      <thead>
        <tr>
          <th rowspan="2">No</th>
          <th rowspan="2">Tanggal</th>
          <th rowspan="2">Keterangan</th>
          <th rowspan="2">Bukti Trx</th>
          <th rowspan="2">Debet</th>
          <th rowspan="2">Kredit</th>
          <th colspan="2">Saldo</th>
        </tr>
        <tr>
          <th>Debet</th>
          <th>Kredit</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($datas as $k => $v)
          <tr>
            <td align="center">{{ $k+1 }}</td>
            <td>{{ date_format(date_create($v->TglInput),"d-m-Y") }}</td>
            <td>{{ $v->Keterangan }}</td>
            <td>{{ $v->BuktiTransaksi }}</td>
            <td align="right">{{ number_format($v->Debet,0,",",".") }}</td>
            <td align="right">{{ number_format($v->Kredit,0,",",".") }}</td>
            <td align="right">{{ number_format($saldo[$k]['Debet'],0,",",".") }}</td>
            <td align="right">{{ number_format($saldo[$k]['Kredit'],0,",",".") }}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4">Total</th>
          <td align="right">{{ number_format($total->TDebet,0,",",".") }}</td>
          <td align="right">{{ number_format($total->TKredit,0,",",".") }}</td>
          <th colspan="2"></th>
        </tr>
      </tfoot>
    </table>
  <br>
  <br>
  <br>
    <table center width="50%" class="garis">
      <thead>
        <tr>
          <th colspan="2">Saldo Awal</th>
          <th colspan="2">Mutasi</th>
          <th colspan="2">Saldo Akhir</th>
        </tr>
        <tr>
          <th>Debet</th>
          <th>Kredit</th>
          <th>Debet</th>
          <th>Kredit</th>
          <th>Debet</th>
          <th>Kredit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td align="right">{{ number_format($lastsaldoawal['Debet'],0,",",".") }}</td>
          <td align="right">{{ number_format($lastsaldoawal['Kredit'],0,",",".") }}</td>
          <td align="right">{{ number_format($mutasi['Debet'],0,",",".") }}</td>
          <td align="right">{{ number_format($mutasi['Kredit'],0,",",".") }}</td>
          <td align="right">{{ number_format($lastsaldo['Debet'],0,",",".") }}</td>
          <td align="right">{{ number_format($lastsaldo['Kredit'],0,",",".") }}</td>
        </tr>
      </tbody>
    </table>
@stop