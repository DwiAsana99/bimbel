@extends('adminlte::page')

@section('title', 'Laporan - Pinjaman')

@section('content_header')
  <h1>Laporan Pinjaman</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-book"></i> Laporan</li>
    <li class="active">Pinjaman</li>
  </ol>
@stop

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Laporan Pembayaran Pinjaman Semua Nasabah</h3>
      </div>
      <form id="form-semua" role="form" data-toggle="validator" action="{{ route('lap.pinjaman.semua')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
              <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-semua">
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
        <h3 class="box-title">Laporan Riwayat Per Pinjaman</h3>
      </div>
      <form id="form-riwayat" role="form" data-toggle="validator" action="{{ route('lap.pinjaman.riwayat')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <select class="form-control" name="pinjaman" required style="width: 100%;" required></select>
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
        <h3 class="box-title">Laporan Pinjaman Per Nasabah</h3>
      </div>
      <form id="form-per" role="form" data-toggle="validator" action="{{ route('lap.pinjaman.per')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="row">
            <div class="form-group col-md-6">
              <select class="form-control" name="nasabah" required style="width: 100%;" required></select>
            </div>
            <div class="form-group col-md-6">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
                <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
                <input type="text" class="form-control" id="tgl-per">
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
  <div class="col-md-6">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Laporan Realisasi Pinjaman</h3>
      </div>
      <form id="form-realisasi" role="form" data-toggle="validator" action="{{ route('lap.pinjaman.realisasi')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tglawal" value="{{ session('tgl') }}" required>
              <input type="hidden" name="tglakhir" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-realisasi">
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
        <h3 class="box-title">Laporan Rekap Pinjaman</h3>
      </div>
      <form id="form-rekap" role="form" data-toggle="validator" action="{{ route('lap.pinjaman.rekap')  }}" method="post" target="_blank">
        <div class="box-body">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="hidden" name="tgl" value="{{ session('tgl') }}" required>
              <input type="text" class="form-control" id="tgl-rekap">
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
  $('#tgl-semua').daterangepicker({
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
  $('#tgl-per').daterangepicker({
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
  $('#tgl-realisasi').daterangepicker({
    // singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: true,
    opens: 'right',
    drops: 'up',
    startDate: tglsesi,
    endDate: tglsesi,
    locale: {
      format: 'DD-MM-YYYY'
    },
  });
  $('#tgl-rekap').daterangepicker({
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
  $("select[name=nasabah]").select2({
    placeholder: "Pilih Nasabah...",
    allowClear: false,
    ajax: {
      url: '{{ route("select2.pinjaman") }}',
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

  $("select[name=pinjaman]").select2({
    placeholder: "Pilih Pinjaman...",
    allowClear: false,
    ajax: {
      url: '{{ route("select2.pinjaman.lunas") }}',
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

  $('#tgl-semua').on('hide.daterangepicker', function(ev, picker) {
    $('#form-semua input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-semua input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-per').on('hide.daterangepicker', function(ev, picker) {
    $('#form-per input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-per input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-realisasi').on('hide.daterangepicker', function(ev, picker) {
    $('#form-realisasi input[name=tglawal]').val(picker.startDate.format('YYYY-MM-DD'));
    $('#form-realisasi input[name=tglakhir]').val(picker.endDate.format('YYYY-MM-DD'));
  });
  $('#tgl-rekap').on('hide.daterangepicker', function(ev, picker) {
    $('#form-rekap input[name=tgl]').val(picker.startDate.format('YYYY-MM-DD'));
  });
});
</script>
@stop