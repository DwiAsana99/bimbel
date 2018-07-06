@extends('adminlte::page')

@section('title', 'Laporan - Tabungan')

@section('content_header')
  <h1>Laporan Tabungan Semua Nasabah</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-book"></i> Laporan</li>
    <li><a href="{{ route('lap.tabungan.index') }}">Tabungan</a></li>
    <li class="active">Semua Nasabah</li>
  </ol>
@stop

@section('content')
<div class="box box-solid">
    <div class="box-header with-border no-print">
        <h3 class="box-title">
            Periode : 
            {{ date('d/m/Y', strtotime($tglawal)) . ' - ' . date('d/m/Y', strtotime($tglakhir)) }}
        </h3>
        <div class="box-tools">
            <form action="{{ route('print.tabungan.semua') }}" method="post">
                {{ csrf_field() }} {{ method_field('POST') }}
                <input type="hidden" name="tglawal" value="{{ $tglawal }}">
                <input type="hidden" name="tglakhir" value="{{ $tglakhir }}">
                <button type="submit" class="btn btn-sm btn-default">
                    <i class="glyphicon glyphicon-print"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-bordered table-condensed" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No Rekening</th>
                        <th class="text-center">Nama Nasabah</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Debet</th>
                        <th class="text-center">Kredit</th>
                        <th class="text-center">Ref</th>
                        <th class="text-center">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas['datas'] as $kT => $vT)
                        @php
                            $rs = count($vT->detils) + 1;
                        @endphp
                        <tr>
                            <td class="text-center" rowspan="{{ $rs }}">{{ $kT + 1 }}</td>
                            <td class="text-center" rowspan="{{ $rs }}">{{ $vT->NoRek }}</td>
                            <td rowspan="{{ $rs }}">{{ $vT->nasabah->NamaNasabah }}</td>
                        </tr>
                        @foreach ($vT->detils as $kD => $vD)
                        <tr>
                            <td class="text-center">{{ date('d/m/Y', strtotime($vD->TglInput)) }}</td>
                            <td class="text-right">{{ number_format($vD->Debet,0,",",".") }}</td>
                            <td class="text-right">{{ number_format($vD->Kredit,0,",",".") }}</td>
                            <td class="text-center">{{ $vD->Ref }}</td>
                            <td class="text-right">{{ number_format($vD->SaldoAkhir,0,",",".") }}</td>
                        </tr>
                        @endforeach
                        @empty
                        <tr><td colspan="8" class="text-center">Tidak Ada Data</td></tr>
                    @endforelse
                    <tr>
                        <td class="text-center" colspan="4"><b>TOTAL</b></td>
                        <td class="text-right"><b>{{ number_format($datas['total']['Debet'],0,",",".") }}</b></td>
                        <td class="text-right"><b>{{ number_format($datas['total']['Kredit'],0,",",".") }}</b></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box-body">
        <small>
            <i><b>Keterangan :</b> Setoran(<b>S</b>), Penarikan(<b>P</b>), Bunga Tabungan(<b>BT</b>), Bunga Deposito(<b>BD</b>).</i>
        </small>
    </div>
    <div class="box-footer">
        <div class="row">
            <div class="col-md-3">
                <div class="description-block border-right">
                    <span class="description-text">Setoran</span>
                    <h5 class="description-header">Rp {{ number_format($datas['rinci']['S'] ?: 0,0,",",".") }}</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="description-block border-right">
                    <span class="description-text">Penarikan</span>
                    <h5 class="description-header">Rp {{ number_format($datas['rinci']['P'] ?: 0,0,",",".") }}</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="description-block border-right">
                    <span class="description-text">Bunga Tabungan</span>
                    <h5 class="description-header">Rp {{ number_format($datas['rinci']['BT'] ?: 0,0,",",".") }}</h5>
                </div>
            </div>

            <div class="col-md-3">
                <div class="description-block">
                    <span class="description-text">Bunga Deposito</span>
                    <h5 class="description-header">Rp {{ number_format($datas['rinci']['BD'] ?: 0,0,",",".") }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@stop