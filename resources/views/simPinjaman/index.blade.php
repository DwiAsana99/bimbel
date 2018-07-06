@extends('adminlte::page')

@section('title', 'Simulasi Pinjaman')

@section('content_header')
<h1>Simulasi Pinjaman</h1>
@stop

@section('content')
<div class="box">
<form id="formutama" role="form" action="{{ route('simpinjaman.hitung') }}" data-toggle="validator" method="POST" autocomplete="off">
{{ csrf_field() }} {{ method_field('POST') }}
<div class="box-header with-border">
    <h3 class="box-title">Form Simulasi Pinjaman</h3>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <label class="control-label">Nama Peminjam</label>
            <input type="text" class="form-control" name="Nama" required>
            <span class="help-block with-errors"></span>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label class="control-label">Jumlah Pinjaman</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="JumlahPinjaman" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Propisi</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="PotonganPropisi" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Setoran Tabungan</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="PotonganTabungan" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group  col-md-4">
            <label class="control-label">Biaya Materai/Map</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="PotonganMateraiMap" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Biaya Asuransi</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="PotonganAsuransi" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Biaya Lain-Lain</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="PotonganLain" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-5">
            <label class="control-label">Jumlah Diterima</label>
            <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input readonly type="text" class="form-control formuang" name="JumlahDiterima" required>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-2">
            <label class="control-label">Jangka Waktu</label>
            <div class="input-group">
                <input type="number" min="1" class="form-control" name="JangkaWaktu" value="1" required>
                <span class="input-group-addon">Bulan</span>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-2">
            <label class="control-label">Bunga / Bulan</label>
            <div class="input-group">
                <input type="text" class="form-control" name="Bunga" value="1" required>
                <span class="input-group-addon">%</span>
            </div>
            <span class="help-block with-errors"></span>
        </div>
        <div class="form-group col-md-3">
            <label class="control-label">Jenis Bunga</label>
            <select class="form-control" name="JenisBunga" required>
                <option value="menetap">Menetap</option>
                <option value="menurun">Menurun</option>
                <option value="anuitas">Anuitas</option>
            </select>
            <span class="help-block with-errors"></span>
        </div>
    </div>                      
</div>
<div class="box-footer">
    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Hitung</button>
</div>
</form>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function () {
    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });

    function jumlahditerima() {
        var JumlahPinjaman = parseInt($('input[name=JumlahPinjaman]').inputmask('unmaskedvalue')) || 0;
        var PotonganTabungan = parseInt($('input[name=PotonganTabungan]').inputmask('unmaskedvalue')) || 0;
        var PotonganAsuransi = parseInt($('input[name=PotonganAsuransi]').inputmask('unmaskedvalue')) || 0;
        var PotonganPropisi = parseInt($('input[name=PotonganPropisi]').inputmask('unmaskedvalue')) || 0;
        var PotonganMateraiMap = parseInt($('input[name=PotonganMateraiMap]').inputmask('unmaskedvalue')) || 0;
        var PotonganLain = parseInt($('input[name=PotonganLain]').inputmask('unmaskedvalue')) || 0;
        var jumlah = JumlahPinjaman - (PotonganPropisi + PotonganMateraiMap + PotonganLain + PotonganTabungan + PotonganAsuransi);
        $('input[name=JumlahDiterima]').val(jumlah);
    }

    $('input[name=JumlahPinjaman]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganTabungan]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganAsuransi]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganPropisi]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganMateraiMap]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganLain]').on('input', function () {
        jumlahditerima();
    });
});
</script>
@stop
