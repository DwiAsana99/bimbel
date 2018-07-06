@extends('adminlte::page')

@section('title', 'Atur Tanggal - Setting')

@section('css')
  <link rel="stylesheet" href="{{ asset('plugins/bootstraptoggle/bootstrap-toggle.min.css') }}">
  <style>
    .toggle.android { border-radius: 0px;}
    .toggle.android .toggle-handle { border-radius: 0px; }
  </style>
@stop

@section('content_header')
  <h1>Atur Tanggal</h1>
@stop

@section('content')
	<div class="row">
    <div class="col-md-5">
      <div class="box">
        <form role="form" class="form-inline" data-toggle="validator" action="{{ route('setting.aturtanggal.update') }}" method="POST">
          <div class="box-body">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="form-group">
              <h3>Mode Pengaturan Tanggal</h3>
            </div>
            <div class="form-group">
              <input type="checkbox" name="mode" {{session('aturtgl') == 1 ? 'checked' : '' }} data-toggle="toggle" data-style="android" data-size="large" data-onstyle="info">
            </div>
            <div class="form-group" style="font-size: 12pt">
              <label>Maksimal Tutup Hari </label>
              <input type="number" class="form-control" name="tutupMax" value="{{ $tutupMax->MaxTutupHari }}" max="28" min="1" required>
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
  <script src="{{ asset('plugins/bootstraptoggle/bootstrap-toggle.min.js') }}"></script>
@stop