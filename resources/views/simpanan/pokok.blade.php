@extends('adminlte::page')

@section('title', 'Simpanan Pokok Anggota')

@section('content_header')
  <h1>Simpanan Pokok Anggota</h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('simpanan.index') }}"><i class="fa fa-money"></i> Simpanan</a></li>
    <li class="active">Pokok</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Rincian Simpanan Pokok Anggota</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-success" id="btnstore" data-toggle="modal" data-target="#modal"><i class="fa fa-plus-square"></i> Setoran</button>
            <button class="btn btn-sm btn-warning" id="btntarik" data-toggle="modal" data-target="#modal"><i class="fa fa-minus-square"></i> Penarikan</button>
          </div>
        </div>
        <div class="box-body">
          <table>
            <tr><td style="padding-right: 10px;"><b>No. Rekening</b></td><td> <b>:</b> {{ $NoRek }}</td></tr>
            <tr><td><b>Anggota</b></td><td> <b>:</b> {{ $NoAnggota }}</td></tr>
            <tr><td><b>Tgl. Gabung</b></td><td> <b>:</b> {{ Fungsi::bulanID($anggota['TglGabung']) }}</td></tr>
            <tr><td><b>Saldo</b></td><td> <b>:</b> Rp {{ number_format($Saldo,0,",",".") }}</td></tr>
          </table>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Saldo Akhir</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

  {{-- modal --}}
  <div class="modal fade" id="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('POST') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Setoran Simpanan Pokok</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">No. Rekening</label>
              <input disabled type="text" class="form-control" value="{{ $NoRek }}">
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Nama Anggota</label>
              <input disabled type="text" class="form-control" value="{{ $anggota['nasabah']['NamaNasabah'] }}">
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Saldo</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input disabled type="text" class="form-control formuang" value="{{ $Saldo }}">
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label id="txt-form" class="control-label"></label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="nominal" value="0" required>
              </div>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Saldo Akhir</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input disabled type="text" class="form-control formuang" id="saldoakhir" value="{{ $Saldo }}">
              </div>
              <span class="help-block with-errors"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@stop

@section('js')
<script type="text/javascript">
	$( document ).ready(function() {
    var saldoakhir = parseInt({{ $Saldo }});
    var aksi;

    $('[data-toggle="tooltip"]').tooltip();
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: "{{ route('simpanan.pokok.dt', ['rek' => $NoRek]) }}",
	      columns: [
          { data: 'TglInput', name: 'TglInput' },
          { data: 'Debet', name: 'Debet', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
          { data: 'Kredit', name: 'Kredit', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
          { data: 'SaldoAkhir', name: 'SaldoAkhir', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) }
	      ]
	  });

    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });

    $('#modal').on('shown.bs.modal', function () {
      $('input[name=nominal]').focus();
    });

    $('#modal').on('hidden.bs.modal', function () {
      $('input[name=nominal]').val(0);
      $('#saldoakhir').val(saldoakhir);
      aksi = 0;
    });

    $('#btnstore').click(function () {
      $('.modal-title').text('Setoran Simpanan Pokok');
      $('#txt-form').text('Setoran');
      $('#modal form').attr('action', "{{ route('simpanan.pokok.setor', ['rek' => $NoRek]) }}");
      aksi = 1;
    });

    $('#btntarik').click(function () {
      $('.modal-title').text('Penarikan Simpanan Pokok');
      $('#txt-form').text('Penarikan');
      $('#modal form').attr('action', "{{ route('simpanan.pokok.tarik', ['rek' => $NoRek]) }}");
      aksi = 2;
    });

    $('input[name=nominal]').on('input', function () {
      var nominal = parseInt($('input[name=nominal]').inputmask('unmaskedvalue'));
      var hasil;
      if (isNaN(nominal)) {
        nominal = 0;
      }
      if (aksi == 1) {
        hasil = nominal + saldoakhir;
      }else if (aksi == 2){
        hasil = saldoakhir - nominal;
      }
      if (hasil < 0) {
        alert('Saldo Tidak Mencukupi');
        $('input[name=nominal]').val(saldoakhir);
        $('#saldoakhir').val(0);
      }else{
        $('#saldoakhir').val(hasil);
      }
    });
  });
</script>
@stop
