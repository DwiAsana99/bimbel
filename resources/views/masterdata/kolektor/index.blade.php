@extends('adminlte::page')

@section('title', 'Kolektor - Masterdata')

@section('content_header')
  <h1>Master Data Kolektor</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-database"></i> Masterdata</li>
    <li class="active"> Kolektor</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Kolektor</h3>
          <div class="box-tools">
            <a href="{{ route('m.kolektor.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Tambah Data</a>
          </div>
        </div>
          <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th style="width:90px;">No. Kolektor</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>No. Telp</th>
                <th>Alamat</th>
                <th style="width:20px;"></th>
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
	      ajax: "{!! route('m.kolektor.dt') !!}",
	      columns: [
	          { data: 'NoKolektor', name: 'NoKolektor' },
            { data: 'Nama', name: 'Nama' },
            { data: 'user.username', name: 'user.username' },
            { data: 'user.role.name', name: 'user.role.name' },
            { data: 'NoTelp', name: 'NoTelp' },
            { data: 'Alamat', name: 'Alamat' },
	          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ]
	  });
  });
</script>
@stop