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
          {{-- <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btntambah" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus-square"></i> Tambah Template</button>
          </div> --}}
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
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

  <script id="detil-template" type="text/x-handlebars-template">
    <div class="box box-solid">
      <div class="box-header with-border">
        <div class="row">
          <div class="col-md-6">
            <h3 class="box-title">Riwayat : @{{ NamaTransaksi }}</h3>
          </div>
          <div class="col-md-6">
            <h3 class="box-title pull-right">Rp @{{formatuang TotalTrx }}</h3>
          </div>
        </div>
      </div>
      <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{NoTemplate}}">
        <thead>
          <tr>
            <th>No</th>
            <th>Keterangan</th>
            <th>Nominal</th>
            <th>Total</th>
            <th>Tanggal</th>
            <th>User</th>
          </tr>
        </thead>
      </table>
    </div>
  </script>

  {{-- modal-tambah --}}
  {{-- <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="{{ route('txtemplate.store') }}" data-toggle="validator" method="POST">
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
  </div> --}}

  {{-- modal-trx --}}
  <div class="modal fade" id="modal-tambahtx">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Transaksi</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Nama Transaksi</label>
              <input disabled type="text" class="form-control" id="NamaTransaksi">
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Bukti Transaksi</label>
              <input type="text" class="form-control" name="BuktiTransaksi" required>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Keterangan</label>
              <input type="text" class="form-control" name="Keterangan" required>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Nominal</label>
              <input type="text" class="form-control formuang" name="Nominal" required>
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
<script src="{{ asset('plugins/handlebars/handlebars.js') }}"></script>
<script type="text/javascript">
	$( document ).ready(function() {
    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('txtemplate.dt') !!}',
	      columns: [
          {
            "className":'details-control',
            "orderable":false,
            "searchable":false,
            "data":null,
            "defaultContent":''
          },
          { data: 'NoTemplate', name: 'NoTemplate', class: 'text-center' },
          { data: 'NamaTransaksi', name: 'NamaTransaksi' },
          { data: 'debet.Keterangan', name: 'debet.Keterangan' },
          { data: 'kredit.Keterangan', name: 'kredit.Keterangan' },
          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ],
        order: [[1, 'asc']]
	  });
    $('#table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var tableId = 'detil-' + row.data().NoTemplate;

      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
      } else {
        // Open this row
        row.child(template(row.data())).show();
        initTable(tableId, row.data());
        tr.addClass('shown');
        tr.next().find('td').addClass('bg-detil');
      }
    });

    function initTable(tableId, data) {
      $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
        ajax: data.detil_url,
        columns: [
          { data: 'rownum', name: 'rownum' },
          { data: 'Keterangan', name: 'Keterangan' },
          { data: 'Nominal', name: 'Nominal', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
          { data: 'Jumlah', name: 'Jumlah', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' ) },
          { data: 'TglInput', name: 'TglInput' },
          { data: 'user.name', name: 'user.name' }
        ]
      });
    };

    // $("select[name=AkunDebet]").select2({
    //   placeholder: "Pilih Akun Debet...",
    //   allowClear: false,
    //   ajax: {
    //     url: '{{ route("select2.akun.detil") }}',
    //     dataType: 'json',
    //     data: function (params) {
    //         return {
    //             q: $.trim(params.term)
    //         };
    //     },
    //     processResults: function (data) {
    //         return {
    //             results: data
    //         };
    //     },
    //     cache: true
    //   }
    // });

    // $("select[name=AkunKredit]").select2({
    //   placeholder: "Pilih Akun Kredit...",
    //   allowClear: false,
    //   ajax: {
    //     url: '{{ route("select2.akun.detil") }}',
    //     dataType: 'json',
    //     data: function (params) {
    //         return {
    //             q: $.trim(params.term)
    //         };
    //     },
    //     processResults: function (data) {
    //         return {
    //             results: data
    //         };
    //     },
    //     cache: true
    //   }
    // });

    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });

    // $('#modal-tambah').on('hidden.bs.modal', function () {
    //   $('select[name=AkunDebet]').val(null).trigger('change');
    //   $('select[name=AkunKredit]').val(null).trigger('change');
    //   $('#modal-tambah form')[0].reset();
    // });

    $('#modal-tambahtx').on('hidden.bs.modal', function () {
      $('#modal-tambahtx form')[0].reset();
    });

    $('#table tbody').on('click', '#btntambahtx', function () {
      var tb = table.row($(this).parents('tr')).data();
      $('#modal-tambahtx form').attr('action', "{{ URL('/txtemplate') }}/" + tb.NoTemplate);
      $('#NamaTransaksi').val(tb.NamaTransaksi);
    });

  });
</script>
@stop
