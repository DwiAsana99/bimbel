@extends('adminlte::page')

@section('title', 'Posting Bunga - Tabungan')

@section('content_header')
  <h1>Posting Bunga</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Posting Bunga</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Tabungan</h3>
          <div class="box-tools">
            <button {{ $canposting }} class="btn btn-sm btn-primary" id="btnposting"><i class="fa fa-plus-square"></i> Posting</button>
          </div>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th style="width:20px;">No</th>
                <th style="width:90px;">No. Rekening</th>
                <th>Nama Anggota</th>
                <th>Saldo</th>
                <th>Saldo Rendah</th>
                <th>Bunga</th>
                <th>Saldo Akhir</th>
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
	      ajax: '{!! route('postingbunga.dt') !!}',
	      columns: [
            { data: 'rownum', name: 'rownum' },
	          { data: 'NoRek', name: 'NoRek' },
            { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' },
            { data: 'Saldo', name: 'Saldo', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'SaldoRendah', name: 'SaldoRendah', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'bunga', name: 'bunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'saldoakhir', name: 'saldoakhir', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
	          { data: 'action', name: 'action', orderable: false, searchable: false, class:'text-center' }
	      ]
	  });
    $('#table tbody').on('click', '#btnremove', function () {
      table.rows($(this).parents('tr')).remove().draw(false);
    });
    
    $('#btnposting').click(function () {
      var tabungan = table.data().toArray();
      var r = confirm("Anda Yakin Posting Bunga Tabungan\nSebanyak : " + tabungan.length + " Rekenig Tabungan ?");
      if (r == true) {
        if (tabungan.length == 0) {
          alert('kosong bos');
        }else {
          $.ajax({
            url: '{!! route('postingbunga.posting') !!}',
            type: 'POST',
            data: {_token: CSRF_TOKEN, tabungan:tabungan},
            dataType: 'JSON',
            success: function (data) {
              // table.ajax.reload();
              notifikasi(data.notif);
              window.location = "{{ route('tabungan.index') }}";
              // location.reload();
            }
          });
        }
      }
    })
  });
</script>
@stop
