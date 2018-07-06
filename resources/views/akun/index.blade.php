@extends('adminlte::page')

@section('title', 'Akun - Master Data')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Data Akun</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Akun</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Akun</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btntambahakun" data-toggle="modal" data-target="#modal-akun"><i class="fa fa-plus-square"></i> Tambah Akun</button>
          </div>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th style="width:90px;">Kode</th>
                <th>Keterangan</th>
                <th>Group</th>
                <th>Kelompok</th>
                <th style="width:120px;"></th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

    <script id="detil-template" type="text/x-handlebars-template">
      <div class="box box-solid">
        <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{KodeAkun}}">
          <thead>
            <tr>
              <th style="width:90px;">Kode</th>
              <th>Keterangan</th>
              <th style="width:120px;"></th>
            </tr>
          </thead>
        </table>
      </div>
    </script>

    {{-- modal-akun --}}
    <div class="modal fade" id="modal-akun">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" action="{{ route('akun.store') }}" data-toggle="validator" method="POST">
                {{ csrf_field() }} {{ method_field('POST') }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Tambah Akun</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="select-kelompok">
                            <label class="control-label">Kelompok Akun</label>
                            <select class="form-control" name="KodeKelompok" required style="width: 100%;" required></select>
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-3">
                            <label class="control-label">Kode</label>
                            <input readonly type="text" class="form-control" name="KodeAkun" required>
                            <span class="help-block with-errors"></span>
                          </div>
                          <div class="form-group col-md-9">
                            <label class="control-label">Keterangan</label>
                            <input type="text" class="form-control" name="Keterangan" required>
                            <span class="help-block with-errors"></span>
                          </div>
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

    {{-- modal-detil --}}
    <div class="modal fade" id="modal-detil">
      <div class="modal-dialog">
        <div class="modal-content">
          <form role="form" action="{{ route('akun.detil.store') }}" data-toggle="validator" method="POST">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title">Tambah Detil Akun</h4>
            </div>
            <div class="modal-body">
              <div class="form-group" id="select-akun">
                <label class="control-label">Akun</label>
                <select class="form-control" name="KodeAkun" required style="width: 100%;" required></select>
                <span class="help-block with-errors"></span>
              </div>
              <div class="row">
                <div class="form-group col-md-3">
                  <label class="control-label">Kode</label>
                  <input readonly type="text" class="form-control" name="KodeDetil" required>
                  <span class="help-block with-errors"></span>
                </div>
                <div class="form-group col-md-9">
                  <label class="control-label">Keterangan</label>
                  <input type="text" class="form-control" name="Keterangan" required>
                  <span class="help-block with-errors"></span>
                </div>
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
  function editdetil(kode) {
    $('#modal-detil form').attr('action', "{{ URL('m/akun/detil') }}/" + kode);
    $('input[name=_method]').val('PUT');
    $('#modal-detil .modal-title').text("Ubah Detil Akun");
    $('select[name=KodeAkun]').prop('disabled', true);
    $('select[name=KodeAkun]').prop('required', false);
    $('#select-akun').hide();
    $.get("{{ url('m/akun/detil/edit') }}/"+ kode, function(data){
      $('input[name=KodeDetil]').val(data.KodeDetil);
      $('input[name=Keterangan]').val(data.Keterangan);
    });
    $('#modal-detil').modal("show");
  };
	$( document ).ready(function() {
    var template = Handlebars.compile($("#detil-template").html());
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
        ajax: '{!! route('akun.dt') !!}',
        columns: [
            {
            "className":'details-control',
            "orderable":false,
            "searchable":false,
            "data":null,
            "defaultContent":''
            },
            { data: 'KodeAkun', name: 'KodeAkun' },
            { data: 'akuns.Keterangan', name: 'akuns.Keterangan' },
            { data: 'akungroups.Keterangan', name: 'akungroups.Keterangan' },
            { data: 'akunkelompoks.Keterangan', name: 'akunkelompoks.Keterangan' },
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']]
    });

    $('#table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var tableId = 'detil-' + row.data().KodeAkun;

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
              { data: 'KodeDetil', name: 'KodeDetil' },
              { data: 'Keterangan', name: 'Keterangan' },
              { data: 'action', name: 'action', orderable: false, searchable: false}
          ]
      });
    };

    $("select[name=KodeKelompok]").select2({
        placeholder: "Pilih Kelompok Akun...",
        allowClear: false,
        ajax: {
            url: '{{ route("select2.akun.kelompok") }}',
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

    $("select[name=KodeAkun]").select2({
        placeholder: "Pilih Kelompok Akun...",
        allowClear: false,
        ajax: {
            url: '{{ route("select2.akun.akun") }}',
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

    $('#btntambahakun').click( function () {
      $('#modal-akun form').attr('action', "{{ route('akun.store') }}");
      $('input[name=_method]').val('POST');
      $('#modal-detil .modal-title').text('Tambah Akun');
      $('select[name=KodeKelompok]').prop('disabled', false);
      $('select[name=KodeKelompok]').prop('required', true);
      $('#select-kelompok').show();
    });

    $('#table tbody').on('click', '#btneditakun', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#modal-akun form').attr('action', "{{ URL('/m/akun') }}/" + data.KodeAkun);
      $('input[name=_method]').val('PUT');
      $('#modal-akun .modal-title').text('Ubah Akun');
      $('select[name=KodeKelompok]').prop('disabled', true);
      $('select[name=KodeKelompok]').prop('required', false);
      $('#select-kelompok').hide();
      $('input[name=KodeAkun]').val(data.KodeAkun);
      $('input[name=Keterangan]').val(data.akuns.Keterangan);
    });

    $('#modal-akun').on('hidden.bs.modal', function () {
      $('#modal-akun form')[0].reset();
      $('#modal-detil form')[0].reset();
      $('select[name=KodeKelompok]').val(null).trigger('change');
    });

    $('select[name=KodeKelompok]').on('select2:select', function (e) {
      var data = e.params.data;
      $.get("{{ url('m/akun/last') }}/"+ data.id, function(data){
        $('input[name=KodeAkun]').val(data);
      });
    });


    $('#table tbody').on('click', '#btntambahdetil', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#modal-detil form').attr('action', "{{ route('akun.detil.store') }}");
      $('input[name=_method]').val('POST');
      $('#modal-detil .modal-title').text('Tambah Detil Akun');
      $('select[name=KodeAkun]').prop('disabled', false);
      $('select[name=KodeAkun]').prop('required', true);
      $('#select-akun').show();
      var newOption = new Option(data.KodeAkun + ' | ' + data.akuns.Keterangan, data.KodeAkun, false, false);
      $('select[name=KodeAkun]').append(newOption).trigger('change');
      getLastDetil(data.KodeAkun);
    });

    $('#modal-detil').on('hidden.bs.modal', function () {
      $('#modal-detil form')[0].reset();
      $('#modal-akun form')[0].reset();
      $('select[name=KodeAkun]').val(null).trigger('change');
    });

    $('select[name=KodeAkun]').on('select2:select', function (e) {
      var data = e.params.data;
      getLastDetil(data.id)
    });

    function getLastDetil(id) {
      $.get("{{ url('m/akun/detil/last') }}/"+ id, function(data){
        $('input[name=KodeDetil]').val(data);
      });
    }
  });
</script>
@stop
