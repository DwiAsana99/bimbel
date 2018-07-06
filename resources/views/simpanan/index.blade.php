@extends('adminlte::page')

@section('title', 'Simpanan Anggota')

@section('content_header')
  <h1>Simpanan Anggota</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Simpanan Anggota</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Anggota</h3>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th style="width:90px;">No. Anggota</th>
                <th>Nama Anggota</th>
                <th>Tanggal Bergabung</th>
                <th style="width:120px;"></th>
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
    $('[data-toggle="tooltip"]').tooltip();
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('simpanan.dt') !!}',
	      columns: [
	          { data: 'NoAnggota', name: 'NoAnggota' },
            { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' },
            { data: 'TglGabung', name: 'TglGabung' },
	          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ]
	  });
  });
</script>
@stop