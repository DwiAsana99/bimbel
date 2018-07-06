@extends('adminlte::page')

@section('title', 'Nasabah')

@section('content_header')
  <h1>Data Nasabah</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-users"></i> Nasabah</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Nasabah</h3>
          <div class="box-tools">
            <a href="{{ route('nasabah.create') }}" class="btn btn-primary btn-sm" ><i class="glyphicon glyphicon-plus"></i> Tambah Data</a>
          </div>
        </div>
        {{-- <div class="box-body"> --}}
            <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
              <thead>
                <tr>
                  <th style="width:90px;">No. Nasabah</th>
                  <th style="width:20px;">Status</th>
                  <th>Nama Nasabah</th>
                  <th>Alamat</th>
                  <th>No. Telepon</th>
                  <th>Tanggal Daftar</th>
                  <th style="width:100px;"></th>
                </tr>
              </thead>
            </table>
        {{-- </div> --}}
      </div>
    </div>
  </div>

  {{-- modal-anggota --}}
  <div class="modal fade" id="modal-anggota">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Setoran Awal Simpanan Pokok</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" placeholder="Simpanan Pokok" name="pokok">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label">Setoran Awal Simpanan Wajib</label>
              <div class="input-group">
                <span class="input-group-addon">Rp</span>
                <input type="text" class="form-control formuang" placeholder="Simpanan Wajib" name="wajib">
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
<script type="text/javascript">
	$( document ).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('nasabah.dt') !!}',
	      columns: [
	          { data: 'NoNasabah', name: 'NoNasabah' },
            { data: 'aktif', name: 'aktif', searchable: false, class:'text-center'},
            { data: 'NamaNasabah', name: 'NamaNasabah' },
            { data: 'Alamat', name: 'Alamat' },
            { data: 'NoTelepon', name: 'NoTelepon' },
            { data: 'TglGabung', name: 'TglGabung' },
	          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ]
	  });
    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
    $('#table tbody').on('click', '#btn-anggota', function () {
      var data = table.row($(this).parents('tr')).data();
      $('#modal-anggota form').attr('action', "{{ URL('/nasabah/anggota') }}/" + data.NoNasabah);
      $('#modal-anggota').find('.modal-title').text('Menjadikan Nasabah ' + data.NamaNasabah + ' Sebagai Anggota');
    });
    $('#modal-anggota').on('hidden.bs.modal', function () {
      $('#modal-anggota form')[0].reset();
    });
  });
</script>
@stop