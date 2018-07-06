@extends('adminlte::page')

@section('title', 'Kompensasi - Pinjaman')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Data Kompensasi</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-money"></i> Pinjaman</li>
    <li class="active"><i class="fa fa-money"></i> Kompensasi</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Kompensasi</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btntambah" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus-square"></i> Tambah Kompensasi</button>
          </div>
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th style="width:90px;">No. Nasabah</th>
                <th>Nama Nasabah</th>
                <th>Jumlah Kompensasi</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>

  <script id="detil-template" type="text/x-handlebars-template">
    <div class="box box-solid">
      <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{NoNasabah}}">
        <thead>
          <tr>
            <th>No. Pinjaman Kompensasi</th>
            <th>Sisa Pokok Terakhir</th>
            <th>Sisa Bunga Terakhir</th>
            <th>Jumlah Pinjaman Baru</th>
            <th>Tanggal Transaksi</th>
          </tr>
        </thead>
      </table>
    </div>
  </script>

@stop

@section('js')
<script src="{{ asset('plugins/handlebars/handlebars.js') }}"></script>
<script type="text/javascript">
	$( document ).ready(function() {
    var template = Handlebars.compile($("#detil-template").html());
    var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('pinjaman.kompensasi.dt') !!}',
	      columns: [
            {
              "className":'details-control',
              "orderable":false,
              "searchable":false,
              "data":null,
              "defaultContent":''
            },
	          { data: 'NoNasabah', name: 'NoNasabah' },
            { data: 'NamaNasabah', name: 'NamaNasabah' },
            { data: 'Jumlah', name: 'Jumlah' }
	      ],
        order: [[1, 'asc']]
	  });

    $('#table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var tableId = 'detil-' + row.data().NoNasabah;

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
          { data: 'NoPinjamanKompen', name: 'NoPinjamanKompen' },
          { data: 'SisaPokokTerakhir', name: 'SisaPokokTerakhir', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'SisaBungaTerakhir', name: 'SisaBungaTerakhir', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'JumlahPinjaman', name: 'JumlahPinjaman', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'TglInput', name: 'TglInput' },
        ]
      });
    };
  });
</script>
@stop
