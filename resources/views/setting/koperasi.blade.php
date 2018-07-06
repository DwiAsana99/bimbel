@extends('adminlte::page')

@section('title', 'Koperasi - Setting')

@section('content_header')
  <h1>Setting Koperasi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Form Informasi Koperasi</h3>
            </div>
            <form role="form" data-toggle="validator" action="{{ route('setting.koperasi.update') }}" enctype="multipart/form-data" method="POST">
                <div class="box-body">
                    {{ csrf_field() }} {{ method_field('PUT') }}
                    <img id="imglogo" src="{{ asset('storage/koperasi/logo/'.$Logo) ?: asset('images/icon.png') }}" style="width: 70px; height: 70px;" class="margin">
                    <div class="fom-group">
                        <label class="control-label">Logo Koperasi</label>
                        <input type="file" name="Logo" value="{{ $Logo }}">
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">No. Koperasi</label>
                        <input type="text" class="form-control" name="NoKoperasi" value="{{ $NoKoperasi }}" required>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Nama Koperasi</label>
                        <input type="text" class="form-control" name="Nama" value="{{ $Nama }}" required>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Alamat</label>
                        <input type="text" class="form-control" name="Alamat" value="{{ $Alamat }}" required>
                        <span class="help-block with-errors"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">No Telepon</label>
                        <input type="text" class="form-control" name="NoTelp" value="{{ $NoTelp }}" required>
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
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imglogo').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $("input[name=Logo]").change(function() {
        readURL(this);
    });
});
</script>
@stop