@extends('adminlte::page')

@section('title', 'Tabungan')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Data Tabungan</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Tabungan</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Tabungan</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btntambah" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus-square"></i> Tambah Data</button>
          </div>
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th style="width:90px;">No. Rekening</th>
                <th>Nama Nasabah</th>
                <th>Saldo Akhir</th>
                <th>Tanggal Buka</th>
                <th style="width:{{ session('aturtgl') == 1 ? '190' : '120' }}px;"></th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

  <script id="detil-template" type="text/x-handlebars-template">
    <div class="box box-solid">
      <div class="box-body">
        <table width="70%">
          <tbody>
            <tr>
              <td style="background-color: #ffffff !important;" width="20%"> <strong>No Rekening </strong>  </td>
              <td style="background-color: #ffffff !important;" width="30%">  : @{{ NoRek }} </td>
              <td style="background-color: #ffffff !important;" width="20%"><strong> Saldo Rendah </strong> </td>
              <td style="background-color: #ffffff !important;" width="30%">  : Rp @{{formatuang SaldoRendah }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Nasabah </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ nasabah.NamaNasabah }} </td>
              <td style="background-color: #ffffff !important;"><strong> Saldo Tinggi </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang SaldoTinggi }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Tanggal Buka </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ TglInput }} </td>
              <td style="background-color: #ffffff !important;"><strong> Saldo Akhir </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang Saldo }} </td>
            </tr>
          </tbody>
        </table>
      </div>
      <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{NoRek}}">
        <thead>
          <tr>
            <th></th>
            <th style="width:13%;">Tanggal Input</th>
            <th style="width:20%;">Debet</th>
            <th style="width:20%;">Kredit</th>
            <th>Keterangan</th>
            <th style="width:20%;">Saldo Akhir</th>
          </tr>
        </thead>
      </table>
    </div>
  </script>

  {{-- modal-tambah --}}
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="{{ route('tabungan.tambah') }}" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('POST') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Tambah Tabungan</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Nasabah</label>
              <select class="form-control" name="NoNasabah" required style="width: 100%;" required></select>
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Setoran awal</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" name="saldoawal" required>
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

  {{-- modal --}}
  <div class="modal fade" id="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Setoran Simpanan Pokok</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">No. Rekening</label>
              <input disabled type="text" class="form-control" id="form-norek">
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Nama Nasabah</label>
              <input disabled type="text" class="form-control" id="form-nama">
              <span class="help-block with-errors"></span>
            </div>
            <div class="form-group">
              <label class="control-label">Saldo</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input disabled type="text" class="form-control uang" id="form-saldo">
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
                <input disabled type="text" class="form-control uang" id="form-saldoakhir" >
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
<script src="{{ asset('plugins/handlebars/handlebars.js') }}"></script>
<script type="text/javascript">
	$( document ).ready(function() {
    var saldoakhir = 0;
    var aksi;
    var saldomin = {{$saldomin}};
    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('tabungan.dt') !!}',
	      columns: [
            {
              "className":'details-control',
              "orderable":false,
              "searchable":false,
              "data":null,
              "defaultContent":''
            },
	          { data: 'NoRek', name: 'NoRek' },
            { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' },
            { data: 'Saldo', name: 'Saldo', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'TglInput', name: 'TglInput' },
	          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ],
        order: [[1, 'asc']]
	  });
    $('#table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var tableId = 'detil-' + row.data().NoRek;

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
          { data: 'urutan', name: 'urutan', visible: false },
          { data: 'TglInput', name: 'TglInput', orderData: 0 },
          { data: 'Debet', name: 'Debet', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Kredit', name: 'Kredit', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Keterangan', name: 'Keterangan' },
          { data: 'SaldoAkhir', name: 'SaldoAkhir', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) }
        ],
        order: [[1, 'asc']]
      });
    };
    $("select[name=NoNasabah]").select2({
      placeholder: "Pilih Nasabah...",
      allowClear: false,
      ajax: {
          url: '{{ route("select2.nasabah") }}',
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
    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
    $('.uang').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: true
    });
    $('#modal-tambah').on('hidden.bs.modal', function () {
      $('#modal-tambah form')[0].reset();
      $('select[name=NoNasabah]').val(null).trigger('change');
    });
    $('#modal').on('hidden.bs.modal', function () {
      $('#modal form')[0].reset();
      $('#saldoakhir').val(0);
      aksi = 0;
    });
    $('#table tbody').on('click', '#btnsetor', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#modal form').attr('action', "{{ URL('/tabungan/setor') }}/" + data.NoRek);
      $('#form-norek').val(data.NoRek);
      $('#form-nama').val(data.nasabah.NamaNasabah);
      $('#form-saldo').val(data.Saldo);
      $('#form-saldoakhir').val(data.Saldo);
      saldoakhir = round(data.Saldo, 2);
      aksi = 1;
      $('#modal').find('.modal-title').text('Setoran Tabungan');
      $('#txt-form').text('Setoran');
    });
    $('#table tbody').on('click', '#btntarik', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#modal form').attr('action', "{{ URL('/tabungan/tarik') }}/" + data.NoRek);
      $('#form-norek').val(data.NoRek);
      $('#form-nama').val(data.nasabah.NamaNasabah);
      $('#form-saldo').val(data.Saldo);
      $('#form-saldoakhir').val(data.Saldo);
      saldoakhir = round(data.Saldo, 2);
      aksi = 2;
      $('#modal').find('.modal-title').text('Penarikan Tabungan');
      $('#txt-form').text('Penarikan');
    });
    $('#modal').on('shown.bs.modal', function () {
      $('input[name=nominal]').focus();
    });
    $('input[name=nominal]').on('input', function () {
      var nominal = parseInt($('input[name=nominal]').inputmask('unmaskedvalue'));
      var hasil;
      if (isNaN(nominal)) {
        nominal = 0;
      }
      if (aksi == 1) {
        hasil = nominal + saldoakhir;
        $('#form-saldoakhir').val(hasil);
      }else if (aksi == 2){
        hasil = saldoakhir - nominal;
        if (hasil < saldomin) {
          alert('Saldo Tidak Mencukupi');
          $('input[name=nominal]').val(round(saldoakhir - saldomin, 0));
          $('#form-saldoakhir').val(saldomin);
        }else{
          $('#form-saldoakhir').val(hasil);
        }
      }
    });
  });
</script>
@stop
