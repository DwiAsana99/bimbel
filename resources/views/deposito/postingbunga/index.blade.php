@extends('adminlte::page')

@section('title', 'Simpanan Anggota')

@section('content_header')
  <h1>Posting Bunga Deposito</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Posting Bunga Deposito</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Deposito</h3>
          <div class="box-tools">
            <button class="btn btn-sm btn-primary" id="btnposting"><i class="fa fa-plus-square"></i> Posting</button>
          </div>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th style="width:20px;">No</th>
                <th style="width:90px;">No. Deposito</th>
                <th style="width:90px;">No. Tabungan</th>
                <th>Nama Nasabah</th>
                <th>Nominal Bunga</th>
                <th>Pajak</th>
                <th>Bunga Akhir</th>
                <th style="width:60px;">Remove</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
@stop

@section('js')
<script type="text/javascript">
	$( document ).ready(function() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	  var table = $('#table').DataTable({
	      processing: true,
	      // serverSide: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('deposito.posting.dt') !!}',
	      columns: [
            { data: 'rownum', name: 'rownum' },
	          { data: 'NoDeposito', name: 'NoDeposito' },
            { data: 'NoTabungan', name: 'NoTabungan' },
            { data: 'NamaNasabah', name: 'NamaNasabah' },
            { data: 'NominalBunga', name: 'NominalBunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'Pajak', name: 'Pajak', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'BungaAkhir', name: 'BungaAkhir', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
	          { data: 'action', name: 'action', orderable: false, searchable: false, class:'text-center' }
	      ]
	  });
    $('#table tbody').on('click', '#btnremove', function () {
      table.rows($(this).parents('tr')).remove().draw(false);
    });
    $('#btnposting').click(function () {
      var deposito = table.data().toArray();
      var r = confirm("Anda Yakin Posting Bunga Deposito\nSebanyak : " + deposito.length + " Rekenig Deposito ?");
      if (r == true) {
        if (deposito.length == 0) {
          alert('kosong bos');
        }else {
          $.ajax({
            url: '{!! route('deposito.posting.posting') !!}',
            type: 'POST',
            data: {_token: CSRF_TOKEN, deposito:deposito},
            dataType: 'JSON',
            success: function (data) {
              table.ajax.reload();
            }
          });
        }
      }
    });
  });
</script>
@stop
