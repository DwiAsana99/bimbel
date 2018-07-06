@extends('adminlte::page')

@section('title', 'Deposito - Setting')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
@stop

@section('content_header')
  <h1>Setting Deposito</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-gear"></i> Setting</li>
    <li class="active"> Deposito</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-5">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Setting Deposito</h3>
        </div>
        <form role="form" data-toggle="validator" autocomplete="off" action="{{ route('setting.deposito.update') }}" method="POST">
          <div class="box-body">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <div class="form-group">
              <label class="control-label">Lama Posting Bunga</label>
              <div class="input-group">
                <input type="number" class="form-control" min="1" name="BulanPostingBunga" value="{{ $BulanPostingBunga }}" required>
                <span class="input-group-addon">Bulan</span>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="row">
              <div class="form-group col-md-8">
                <label class="control-label">Batas Kena Pajak</label>
                <div class="input-group">
                  <span class="input-group-addon">Rp</span>
                  <input type="text" class="form-control formuang" name="BatasKenaPajak" value="{{ $BatasKenaPajak }}" required>
                </div>
                <span class="help-block with-errors"></span>
              </div>

              <div class="form-group col-md-4">
                <label class="control-label">Pajak</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="Pajak" value="{{ $Pajak }}" required>
                  <span class="input-group-addon">%</span>
                </div>
                <span class="help-block with-errors"></span>
              </div>
            </div>

            {{-- <br>
            <div class="form-group">
              <label>
                <input type="radio" name="IsGabungKeTabungan" value="1" {{ $IsGabungKeTabungan == false ?: "checked"}}> Gabung Ke Tabungan
              </label>
            </div>
            <div class="form-group">
              <label>
                <input type="radio" name="IsGabungKeTabungan" value="0" {{ $IsGabungKeTabungan == true ?: "checked"}}> Tidak Gabung Ke Tabungan
              </label>
            </div> --}}
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@stop

@section('js')
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
$( document ).ready(function() {
    $('input[type=radio]').iCheck({
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });
    $('.formuang').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: true
    });
});
</script>
@stop
