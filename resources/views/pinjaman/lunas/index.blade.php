@extends('adminlte::page')

@section('title', 'Pinjaman Lunas')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Pinjaman Lunas</h1>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <table class="table table-bordered" cellspacing="0" width="100%" id="table">
          <thead>
            <tr>
              <th></th>
              <th style="width:90px;">No. Pinjaman</th>
              <th>Nama Nasabah</th>
              <th>Jumlah Pinjaman</th>
              <th>Jumlah Diterima</th>
              <th>Tgl Pelunasan</th>
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
              <td style="background-color: #ffffff !important;" width="20%"> <strong>No. Pinjaman </strong>  </td>
              <td style="background-color: #ffffff !important;" width="30%">  : @{{ NoPinjaman }} </td>
              <td style="background-color: #ffffff !important;" width="20%"><strong> Nasabah </strong> </td>
              <td style="background-color: #ffffff !important;" width="30%">  : @{{ nasabah.NamaNasabah }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Realisasi </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ TglInput }} </td>
              <td style="background-color: #ffffff !important;"><strong> Jatuh Tempo </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ JatuhTempo }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Pinjaman </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang JumlahPinjaman }} </td>
              <td style="background-color: #ffffff !important;"><strong> Sisa </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang SisaPinjaman }} </td>
            </tr>
          </tbody>
        </table>
      </div>
      <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{NoPinjaman}}">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Pokok</th>
            <th>Bunga</th>
            <th>Denda</th>
            <th>Jumlah</th>
            <th>Sisa</th>
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
    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('pinjamanlunas.dt') !!}',
	      columns: [
          {
            "className":'details-control',
            "orderable":false,
            "searchable":false,
            "data":null,
            "defaultContent":''
          },
          { data: 'NoPinjaman', name: 'NoPinjaman' },
          { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' },
          { data: 'JumlahPinjaman', name: 'JumlahPinjaman', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'JumlahDiterima', name: 'JumlahDiterima', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'TglPelunasan', name: 'TglPelunasan' }          
	      ],
        order: [[1, 'asc']]
	  });
    $('#table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var tableId = 'detil-' + row.data().NoPinjaman;

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
          { data: 'TglInput', name: 'TglInput' },
          { data: 'Pokok', name: 'Pokok', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'NominalBunga', name: 'NominalBunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Denda', name: 'Denda', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Jumlah', name: 'Jumlah', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Sisa', name: 'Sisa', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) }
        ]
      });
    };
  });
</script>
@stop
