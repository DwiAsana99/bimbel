@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
@stop

@section('title', 'Tambah - Nasabah')

@section('content_header')
  <h1>{{ $isEdit == true ? 'Ubah' : 'Tambah' }} Data Nasabah</h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('nasabah.index') }}"><i class="fa fa-users"></i>Nasabah</a></li>
    <li class="active">{{ $isEdit == true ? 'Ubah' : 'Tambah' }}</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Data Nasabah</h3>
        </div>
        <form role="form" data-toggle="validator" action="{{ $isEdit == true ? route('nasabah.update', ['nasabah' => $nasabah->NoNasabah]) : route('nasabah.store') }}" method="post">
          <div class="box-body">
            {{ csrf_field() }} {{ $isEdit == true ? method_field('PUT') : method_field('POST') }}
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">No. Nasabah</label>
                  <input readonly type="text" class="form-control" name="NoNasabah" value="{{$nasabah->NoNasabah}}" required>
                </div>
                <div class="form-group">
                  <label class="control-label">No. KTP</label>
                  <input type="text" class="form-control" name="NoKtp" value="{{ $nasabah->NoKtp }}" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Nama Nasabah</label>
                  <input type="text" class="form-control" name="NamaNasabah" value="{{ $nasabah->NamaNasabah }}" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Tempat Lahir</label>
                  <input type="text" class="form-control" name="TempatLahir" value="{{ $nasabah->TempatLahir }}" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Alamat</label>
                  <textarea class="form-control" style="resize: vertical;" rows="3" name="Alamat" required placeholder="Alamat">{{ $nasabah->Alamat }}</textarea>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Tanggal Lahir</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" name="TanggalLahir" value="{{ $nasabah->TanggalLahir }}" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label">Jenis Kelamin</label>
                  <select class="form-control" name="JenisKelamin" required>
                    <option {{$nasabah->JenisKelamin == 'Laki-Laki' ? 'selected' : ''}} value="Laki-Laki">Laki-Laki</option>
                    <option {{$nasabah->JenisKelamin == 'Perempuan' ? 'selected' : ''}} value="Perempuan">Perempuan</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label">No. telepon</label>
                  <input type="text" class="form-control" name="NoTelepon" value="{{ $nasabah->NoTelepon }}" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Kolektor</label>
                  <select class="form-control" name="NoKolektor" style="width: 100%;"></select>
                </div>
                @if ($isEdit == false)
                  <div class="form-group">
                    <label>
                      <input type="checkbox" name="canggota"> Tambahkan Sebagai Anggota
                    </label>
                  </div>
                  <div id="formanggota" hidden>
                    <div class="form-group">
                      <label class="control-label">Setoran Awal Simpanan Pokok</label>
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control formuang" placeholder="Simpanan Pokok" name="pokok">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Setoran Awal Simpanan Wajib</label>
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control formuang" placeholder="Simpanan Wajib" name="wajib">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>
                      <input type="checkbox" name="ctabungan"> Tambahkan Rekening Tabungan
                    </label>
                  </div>
                  <div class="form-group" id="formtabungan" hidden>
                    <label class="control-label">Setoran Awal Tabungan</label>
                    <div class="input-group">
                      <span class="input-group-addon">Rp</span>
                      <input type="text" class="form-control formuang" placeholder="Setoran Tabungan" name="tabungan">
                    </div>
                  </div>
                @endif
              </div>
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
var isEdit = {{ $isEdit ? 1 : 0 }};
var s2NamaKolektor = "{{ $NamaKolektor }}";
  $(document).ready(function () {

    $('input[name=TanggalLahir]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: 'YYYY-MM-DD'
      },
    });

    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });

    $("select[name=NoKolektor]").select2({
        placeholder: "Pilih Kolektor...",
        allowClear: true,
        ajax: {
            url: '{{ route("select2.kolektor") }}',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    if (isEdit == 1) {
      select2isi("select[name=NoKolektor]", "{{ $nasabah->NoKolektor }}", s2NamaKolektor)
    }

    $('input[type=checkbox]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    $('input[name=canggota]').on('ifChecked', function(){
      $('#formanggota').show("slow");
      $('#formanggota input').attr("required", "true");
      $('form').validator('update')
    });
    $('input[name=canggota]').on('ifUnchecked', function(){
      $('#formanggota').hide("slow");
      $('#formanggota input').removeAttr("required");
      $('#formanggota input').val('');
      $('form').validator('update')
    });

    $('input[name=ctabungan]').on('ifChecked', function(){
      $('#formtabungan').show("slow");
      $('#formtabungan input').attr("required", "true");
      $('form').validator('update')
    });
    $('input[name=ctabungan]').on('ifUnchecked', function(){
      $('#formtabungan').hide("slow");
      $('#formtabungan input').removeAttr("required");
      $('#formtabungan input').val('');
      $('form').validator('update')
    });
  });
</script>
@stop
