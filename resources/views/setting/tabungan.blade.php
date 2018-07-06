@extends('adminlte::page')

@section('title', 'Tabungan - Setting')

@section('content_header')
  <h1>Setting Tabungan</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-gear"></i> Setting</li>
    <li class="active"> Tabungan</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-5">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Setting Tabungan</h3>
        </div>
        <form role="form" data-toggle="validator" action="{{ route('setting.tabungan.update') }}" method="POST">
          <div class="box-body">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <div class="form-group">
              <label class="control-label">Bunga Tabungan</label>
              <div class="input-group">
                <input type="number" step="0.01" class="form-control" name="BungaPersen" value="{{ $BungaPersen }}" required>
                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Minimal Durasi Tabungan</label>
              <div class="input-group">
                <input type="number" class="form-control" name="LamaNabungMin" value="{{ $LamaNabungMin }}" required>
                <span class="input-group-addon">Hari</span>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Minimal Saldo</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control" name="SaldoMin" value="{{ $SaldoMin }}" required>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Tanggal Pemberian Bunga</label>
              <input type="number" class="form-control" max="31" name="TanggalPosting" value="{{ $TanggalPosting }}" required>
              <span class="help-block with-errors"></span>
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
<script type="text/javascript">
  $(document).ready(function () {
    $('input[name=SaldoMin]').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
  });
</script>
@stop
