@extends('adminlte::page')

@section('title', 'Laporan Neraca Saldo Harian')

@section('content_header')
<h1>Laporan Neraca Saldo</h1>
@stop

@section('content')
<div class="box box-success">
<div class="box-header with-border">
    <h3 class="box-title">Laporan Neraca Saldo Hari Ini : {{ date_format(date_create($tgl),"d/m/Y") }}</h3>
</div>
<div class="box-body no-padding">
    <div class="table-responsive">
    <table class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th class="text-center" rowspan="2">No. Akun</th>
                <th class="text-center" rowspan="2">Nama Akun</th>
                <th class="text-center" colspan="2">Saldo Awal</th>
                <th class="text-center" colspan="2">Mutasi Hari Ini</th>
                <th class="text-center" colspan="2">Saldo Akhir</th>
            </tr>
            <tr>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
                <th class="text-center">Debet</th>
                <th class="text-center">Kredit</th>
            </tr>
        </thead>
        <tbody>
@php
    $tsak = 0;
    $tsad = 0;
    $tmk = 0;
    $tmd = 0;
    $tskd = 0;
    $tskk = 0;
@endphp
            @foreach ($akuns as $ka => $va)
                <tr>
@php
    $sak = 0;
    $sad = 0;
    $mk = 0;
    $md = 0;
    $skd = 0;
    $skk = 0;

    foreach ($saldoawal as $ksa => $vsa) {
        if ($vsa->KodeAkun == $va->KodeDetil) {
            if ($va->SaldoNormal == 1) {
                if ($vsa->Saldo > 0) {
                    $sad = $vsa->Saldo;
                } else {
                    $sak = $vsa->Saldo * -1;
                }
            } else {
                if ($vsa->Saldo > 0) {
                    $sak = $vsa->Saldo;
                } else {
                    $sad = $vsa->Saldo * -1;
                }
            }
        }
    }
@endphp
                    <td>{{$va->KodeDetil}}</td>
                    <td>{{$va->Keterangan}}</td>
                    <td class="text-right">{{ number_format($sad,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($sak,0,",",".") }}</td>
@php
    foreach ($mutasi as $km => $vm) {
        if ($vm->KodeAkun == $va->KodeDetil) {
            $md = $vm->Debet;
            $mk = $vm->Kredit;
        }
    }
@endphp
                    <td class="text-right">{{ number_format($md,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($mk,0,",",".") }}</td>
@php
    if ($va->SaldoNormal == 1) {
        $temp = $sad - $sak + $md - $mk;
        if ($temp > 0) {
            $skd = $temp;
        } else {
            $skk = $temp * -1;
        }
    } else {
        $temp = $sak - $sad + $mk - $md;
        if ($temp > 0) {
            $skk = $temp;
        } else {
            $skd = $temp * -1;
        }
    }
@endphp
                    <td class="text-right">{{ number_format($skd,0,",",".") }}</td>
                    <td class="text-right">{{ number_format($skk,0,",",".") }}</td>
@php
    $tsak += $sak;
    $tsad += $sad;
    $tmk += $mk;
    $tmd += $md;
    $tskd += $skd;
    $tskk += $skk;
@endphp
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="2"><b>TOTAL</b></td>
                <td class="text-right"><b>{{ number_format($tsad,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($tsak,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($tmd,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($tmk,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($tskd,0,",",".") }}</b></td>
                <td class="text-right"><b>{{ number_format($tskk,0,",",".") }}</b></td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>
<div class="box-footer">
    <form role="form" action="{{ route('lap.keu.neracapercobaan')  }}" method="post" target="_blank">
        {{ csrf_field() }} {{ method_field('POST') }}
        <input type="hidden" name="tgl" value="{{ $tgl }}">
        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Cetak</button>
    </form>
</div>
</div>
@stop

{{-- @section('js')
<script type="text/javascript">
$(document).ready(function () {
    
});
</script>
@stop --}}