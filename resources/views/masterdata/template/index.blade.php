@extends('adminlte::page')

@section('title', 'Template Trx')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Template Transaksi</h1>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Template Transaksi</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btntambah" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus-square"></i> Tambah Template</button>
          </div>
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th style="width:10px">No</th>
                <th>Nama Transaksi</th>
                <th>Akun Debet</th>
                <th>Akun Kredit</th>
                <th style="width:50px;"></th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

  {{-- modal-tambah --}}
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="{{ route('m.txtemplate.store') }}" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Tambah Template Transaksi</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Nama Transaksi</label>
              <input type="text" class="form-control" name="NamaTransaksi" required>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Akun Debet</label>
              <select class="form-control" name="AkunDebet" required style="width: 100%;" required></select>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Akun Kredit</label>
              <select class="form-control" name="AkunKredit" required style="width: 100%;" required></select>
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

  {{-- modal-edit --}}
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Ubah Template Transaksi</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Nama Transaksi</label>
              <input type="text" class="form-control" name="NamaTransaksi" required>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Akun Debet</label>
              <select class="form-control" name="AkunDebet" required style="width: 100%;" required></select>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Akun Kredit</label>
              <select class="form-control" name="AkunKredit" required style="width: 100%;" required></select>
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
	var table = $('#table').DataTable({
	    processing: true,
	    serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	    ajax: '{!! route('m.txtemplate.dt') !!}',
	    columns: [
            { data: 'NoTemplate', name: 'NoTemplate', class: 'text-center' },
            { data: 'NamaTransaksi', name: 'NamaTransaksi' },
            { data: 'debet.Keterangan', name: 'debet.Keterangan' },
            { data: 'kredit.Keterangan', name: 'kredit.Keterangan' },
            { data: 'action', name: 'action', orderable: false, searchable: false}
	    ],
        order: [[0, 'asc']]
	});

    $("select[name=AkunDebet]").select2({
        placeholder: "Pilih Akun Debet...",
        allowClear: false,
        ajax: {
            url: '{{ route("select2.akun.detil") }}',
            dataType: 'json',
            data: function (params) {
                return { q: $.trim(params.term) };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });

    $("select[name=AkunKredit]").select2({
        placeholder: "Pilih Akun Kredit...",
        allowClear: false,
        ajax: {
            url: '{{ route("select2.akun.detil") }}',
            dataType: 'json',
            data: function (params) {
                return { q: $.trim(params.term) };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        }
    });

    $('#modal-tambah').on('hidden.bs.modal', function () {
        $('#modal-tambah select[name=AkunDebet]').val(null).trigger('change');
        $('#modal-tambah select[name=AkunKredit]').val(null).trigger('change');
        $('#modal-tambah form')[0].reset();
    });

    $('#modal-edit').on('hidden.bs.modal', function () {
        $('#modal-edit select[name=AkunDebet]').val(null).trigger('change');
        $('#modal-edit select[name=AkunKredit]').val(null).trigger('change');
        $('#modal-edit form')[0].reset();
    });

    $('#table tbody').on('click', '#btnedit', function () {
        var tb = table.row($(this).parents('tr')).data();
        $('#modal-edit form').attr('action', "{{ URL('/m/txtemplate') }}/" + tb.NoTemplate);
        $('#modal-edit input[name=NamaTransaksi]').val(tb.NamaTransaksi);
        select2isi("#modal-edit select[name=AkunDebet]", tb.debet.KodeDetil, tb.debet.Keterangan);
        select2isi("#modal-edit select[name=AkunKredit]", tb.kredit.KodeDetil, tb.kredit.Keterangan);
    });

});
</script>
@stop