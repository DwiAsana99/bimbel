@extends('adminlte::page')

@section('title', 'Hasil - Simulasi Pinjaman')

@section('content_header')
<h1>Hasil Simulasi Pinjaman</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('simpinjaman.index') }}"><i class="fa fa-users"></i>Simulasi Pinjaman</a></li>
    <li class="active">Hasil</li>
</ol>
@stop

@section('content')
<div class="box box-solid">
<div class="box-body">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header" style="margin-bottom: 5px; text-transform: uppercase;">
            Simulasi pinjaman dengan bunga {{ $data['JenisBunga'] }}
            <img style="width: 80px;" class="pull-right" src="{{ asset('images/PandanaLogo.png') }}"/>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <h4>Peminjam : {{ $data['Nama'] }}</h4>
        </div>
        <div class="col-xs-6">
            <h4 class="pull-right">Pinjaman : {{ number_format($data['JumlahPinjaman'],0,",",".") }}</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-condensed table-bordered">
                <tr>
                    <th>Jangka Waktu</th>
                    <td>{{ $data['JangkaWaktu'] }}</td>
                    <th>Jumlah Pinjaman</th>
                    <td class="text-right" >{{ number_format($data['JumlahDiterima'],0,",",".") }}</td>
                    <th>Biaya Materai/Map</th>
                    <td class="text-right" >{{ number_format($data['PotonganMateraiMap'],0,",",".") }}</td>
                </tr>
                <tr>
                    <th>Bunga/Bulan</th>
                    <td>{{ $data['Bunga'] }} %</td>
                    <th>Propisi</th>
                    <td class="text-right">{{ number_format($data['PotonganPropisi'],0,",",".") }}</td>
                    <th>Biaya Asuransi</th>
                    <td class="text-right">{{ number_format($data['PotonganAsuransi'],0,",",".") }}</td>
                </tr>
                <tr>
                    <th>Jenis Bunga</th>
                    <td>{{ $data['JenisBunga'] }}</td>
                    <th>Setoran Tabungan</th>
                    <td class="text-right">{{ number_format($data['PotonganTabungan'],0,",",".") }}</td>
                    <th>Biaya Lain-lain</th>
                    <td class="text-right">{{ number_format($data['PotonganLain'],0,",",".") }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="box-body no-padding">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="text-center" style="width:4%;">Periode</th>
                    <th class="text-center" style="width:24%;">Pokok</th>
                    <th class="text-center" style="width:24%;">Bunga</th>
                    <th class="text-center" style="width:24%;">Angsuran</th>
                    <th class="text-center" style="width:24%;">Sisa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hasil as $h)
                    <tr>
                        <td class="text-center">{{ $h['periode'] }}</td>
                        <td class="text-right">{{ number_format($h['pokok'],0,",",".") }}</td>
                        <td class="text-right">{{ number_format($h['bunga'],0,",",".") }}</td>
                        <td class="text-right">{{ number_format($h['angsuran'],0,",",".") }}</td>
                        <td class="text-right">{{ number_format($h['sisa'],0,",",".") }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="box-footer no-print">
    <button type="button" class="btn btn-primary pull-right" onclick="window.print()"><i class="fa fa-print"></i> Cetak</button>
</div>
</div>
@stop

{{-- @section('js')
<script type="text/javascript">
$(document).ready(function () {
    
});
</script>
@stop --}}