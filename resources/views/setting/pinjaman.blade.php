@extends('adminlte::page')

@section('title', 'Pinjaman - Setting')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
@stop

@section('content_header')
  <h1>Setting Pinjaman</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-gear"></i> Setting</li>
    <li class="active"> Pinjaman</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-5">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Setting Pinjaman</h3>
        </div>
        <form role="form" data-toggle="validator" action="{{ route('setting.pinjaman.update') }}" method="POST">
          <div class="box-body">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <div class="form-group">
              <label class="control-label">Denda Pinjaman</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control" name="DendaPinjaman" value="{{ $DendaPinjaman }}" required>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <br>
            <div class="form-group">
              <label>
                <input type="checkbox" name="InputDenda" {{ $InputDenda == true ? "checked" : "" }}> Dapat Input Bunga Saat Pembayaran
              </label>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="InputBunga" {{ $InputBunga == true ? "checked" : "" }}> Dapat Input Bunga Saat Pembayaran
              </label>
            </div>
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
$(document).ready(function () {
    $('input[name=DendaPinjaman]').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
    $('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
});
</script>
@stop
