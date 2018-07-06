@extends('templatePrint')
@section('title', 'Neraca Percobaan - '.date_format(date_create($tgl),"d-m-Y")))
@section('content-header')
    <h4>Laporan Neraca Percobaan</h4>
@stop
@section('content')
    <strong>Periode : {{ date_format(date_create($tgl),"d-m-Y") }}</strong>
    <table width="100%" class="garis">
        <thead>
          <tr>
            <th rowspan="2">No. Akun</th>
            <th rowspan="2">Nama Akun</th>
            <th colspan="2">Saldo Awal</th>
            <th colspan="2">Mutasi Hari Ini</th>
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
          {{$tsak = 0}}
          {{$tsad = 0}}
          {{$tmk = 0}}
          {{$tmd = 0}}
          {{$tskd = 0}}
          {{$tskk = 0}}
          @foreach ($akuns as $ka => $va)
            <tr>
              {{$sak = 0}}
              {{$sad = 0}}
              {{$mk = 0}}
              {{$md = 0}}
              {{$skd = 0}}
              {{$skk = 0}}
              <td>{{$va->KodeDetil}}</td>
              <td>{{$va->Keterangan}}</td>
              @foreach ($saldoawal as $ksa => $vsa)
                @if ($vsa->KodeAkun == $va->KodeDetil)
                  @if ($va->SaldoNormal == 1)
                    @if ($vsa->Saldo > 0)
                      {{$sad = $vsa->Saldo}}
                    @else
                      {{$sak = $vsa->Saldo * -1}}
                    @endif
                  @else
                    @if ($vsa->Saldo > 0)
                      {{$sak = $vsa->Saldo}}
                    @else
                      {{$sad = $vsa->Saldo * -1}}
                    @endif
                  @endif
                @endif
              @endforeach
              <td align="right">{{ number_format($sad,0,",",".") }}</td>
              <td align="right">{{ number_format($sak,0,",",".") }}</td>
              @foreach ($mutasi as $km => $vm)
                @if ($vm->KodeAkun == $va->KodeDetil)
                  {{$md = $vm->Debet}}
                  {{$mk = $vm->Kredit}}
                @endif
              @endforeach
              <td align="right">{{ number_format($md,0,",",".") }}</td>
              <td align="right">{{ number_format($mk,0,",",".") }}</td>
              @if ($va->SaldoNormal == 1)
                {{$temp = $sad - $sak + $md - $mk}}
                @if ($temp > 0)
                  {{$skd = $temp}}
                @else
                  {{$skk = $temp * -1}}
                @endif
              @else
                {{$temp = $sak - $sad + $mk - $md}}
                @if ($temp > 0)
                  {{$skk = $temp}}
                @else
                  {{$skd = $temp * -1}}
                @endif
              @endif
              <td align="right">{{ number_format($skd,0,",",".") }}</td>
              <td align="right">{{ number_format($skk,0,",",".") }}</td>
              {{$tsak += $sak}}
              {{$tsad += $sad}}
              {{$tmk += $mk}}
              {{$tmd += $md}}
              {{$tskd += $skd}}
              {{$tskk += $skk}}
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td align="center" colspan="2"><b>TOTAL</b></td>
            <td align="right"><b>{{ number_format($tsad,0,",",".") }}</b></td>
            <td align="right"><b>{{ number_format($tsak,0,",",".") }}</b></td>
            <td align="right"><b>{{ number_format($tmd,0,",",".") }}</b></td>
            <td align="right"><b>{{ number_format($tmk,0,",",".") }}</b></td>
            <td align="right"><b>{{ number_format($tskd,0,",",".") }}</b></td>
            <td align="right"><b>{{ number_format($tskk,0,",",".") }}</b></td>
          </tr>
        </tfoot>
    </table>
@stop