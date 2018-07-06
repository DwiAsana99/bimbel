@extends('adminlte::page')

@section('title', 'Ganti Password')

@section('content_header')
  <h1>Ganti Password</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Ganti Password</h3>
            </div>
            <form role="form" data-toggle="validator" action="{{ route('gantipwd.ganti') }}" enctype="multipart/form-data" method="POST">
                <div class="box-body">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <div class="form-group">
                        <label class="control-label">Password Lama</label>
                        <input type="password" class="form-control" name="passlama" placeholder="Password Lama" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password Baru</label>
                        <input type="password" id="pasbaru" data-minlength="5" class="form-control" name="password" placeholder="Password Baru" required>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Verifikasi Password</label>
                        <input type="password" data-match="#pasbaru" data-match-error="Password Tidak Sama" class="form-control" placeholder="Verifikasi Password Baru" required>
                        <div class="help-block with-errors"></div>
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