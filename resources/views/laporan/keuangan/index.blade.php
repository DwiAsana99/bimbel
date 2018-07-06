@extends('adminlte::page')

@section('title', 'Laporan - Keuangan')

@section('content_header')
  <h1>Laporan Keuangan</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-book"></i> Laporan</li>
    <li class="active">Keuangan</li>
  </ol>
@stop

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Jurnal Umum</h3>
      </div>
      <form id="form-jurnalumum" role="form" data-toggle="validator" action="{{ route('lap.keu.jurnalumum')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
              <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-jurnalumum">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Laba Rugi / Sisa Hasil Usaha</h3>
      </div>
      <form id="form-labarugi" role="form" data-toggle="validator" action="{{ route('lap.keu.labarugi')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
              <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-labarugi">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Buku Besar</h3>
      </div>
      <form id="form-bukubesar" role="form" data-toggle="validator" action="{{ route('lap.keu.bukubesar')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="row">
            <div class="form-group col-md-6">
              <select class="form-control" name="akun" required style="width: 100%;" required></select>
            </div>
            <div class="form-group col-md-6">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
                <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
                <input type="text" class="form-control" id="tgl-bukubesar">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
                </span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Neraca Percobaan</h3>
      </div>
      <form id="form-neracapercobaan" role="form" data-toggle="validator" action="{{ route('lap.keu.neracapercobaan')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tgl" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-neracapercobaan">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Neraca</h3>
      </div>
      <form id="form-neraca" role="form" data-toggle="validator" action="{{ route('lap.keu.neraca')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tgl" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-neraca">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-md-4">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Neraca Lajur</h3>
      </div>
      <form id="form-neracalajur" role="form" data-toggle="validator" action="{{ route('lap.keu.neracalajur')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tgl" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-neracalajur">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-print"></i> Cetak</button>
              </span>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('js')
<script type="text/javascript">
var tglsesi = "{{ date_format(date_create(session('tgl')),'d-m-Y') }}";
$(document).ready(function () {
  $('#tgl-jurnalumum').daterangepicker({
    // singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'right',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-bukubesar').daterangepicker({
    // singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'left',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-labarugi').daterangepicker({
    // singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'left',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-neracapercobaan').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'center',
    drops: 'up',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-neraca').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'center',
    drops: 'up',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-neracalajur').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'center',
    drops: 'up',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $("select[name=akun]").select2({
    placeholder: "Pilih Akun...",
    allowClear: false,
    ajax: {
      url: '{{ route("select2.akun.detil") }}',
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
  $('#tgl-jurnalumum').on('hide.daterangepicker', function(ev, picker) {
    $('#form-jurnalumum input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-jurnalumum input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-bukubesar').on('hide.daterangepicker', function(ev, picker) {
    $('#form-bukubesar input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-bukubesar input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-labarugi').on('hide.daterangepicker', function(ev, picker) {
    $('#form-labarugi input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-labarugi input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-neracapercobaan').on('hide.daterangepicker', function(ev, picker) {
    $('#form-neracapercobaan input[name=tgl]').val(picker.startDate.format('YYYY-MM-DD'));
  });
  $('#tgl-neraca').on('hide.daterangepicker', function(ev, picker) {
    $('#form-neraca input[name=tgl]').val(picker.startDate.format('YYYY-MM-DD'));
  });
  $('#tgl-neracalajur').on('hide.daterangepicker', function(ev, picker) {
    $('#form-neracalajur input[name=tgl]').val(picker.startDate.format('YYYY-MM-DD'));
  });
});
</script>
@stop