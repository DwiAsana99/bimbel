@extends('adminlte::page')

@section('title', 'Transaksi Lain')

@section('content_header')
  <h1> Transaksi Lain</h1>
  <ol class="breadcrumb">
  <li class="active"> Transaksi Lain</li>
  </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Penjurnalan</h3>
        </div>
        <div class="box-body">
          <form role="form" action="" method="post">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="row">
              <div class="form-group col-md-12">
                <label class="control-label">Bukti Transaksi</label>
                <input type="text" class="form-control" name="BuktiTransaksi">
              </div>
              {{-- <div class="form-group col-md-6">
                <label class="control-label">Nama Transaksi</label>
                <input type="text" class="form-control" name="NamaTransaksi">
              </div> --}}
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label class="control-label">Keterangan</label>
                <input type="text" class="form-control" name="Keterangan">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-3">
                <label class="control-label">Akun</label>
                <select class="form-control" name="KodeAkun" style="width: 100%;" required></select>
              </div>
              <div class="form-group col-md-4">
                <label class="control-label">Debet</label>
                <input type="text" class="form-control formuang" value="0" name="Debet">
              </div>
              <div class="form-group col-md-4">
                <label class="control-label">Kredit</label>
                <input type="text" class="form-control formuang" value="0" name="Kredit">
              </div>
              <div class="form-group col-md-1">
                <button type="button" id="btn-proses" class="btn btn-primary form-control" style="margin-top: 25px;"><i class="glyphicon glyphicon-arrow-down"></i></button>
              </div>
            </div>
          </form>
        </div>
        <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="table">
          <thead>
            <tr>
              <th style="width:100px;">Akun</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th style="width:20px;"></th>
            </tr>
          </thead>
          <tfoot>
            <tr class="success">
              <th class="text-center" style="width:100px;">Total</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th style="width:20px;"></th>
            </tr>
          </tfoot>
        </table>
        <div class="box-footer">
          <button disabled type="button" id="btn-simpan" class="btn btn-success pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
      </div>
    </div>
  </div>
@stop

@section('js')
<script>
  $.fn.dataTable.Api.register('sum()', function () {
    return this.flatten().reduce( function (a, b) {
      if (typeof a === 'string') {
        a = a.replace(/[^\d.-]/g, '') * 1;
      }
      console.log(b);
      
      if (typeof b === 'string') {
        b = b.replace(/[^\d.-]/g, '') * 1;
      }
      return a + b;
    }, 0);
  });
  $( document ).ready(function() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $('.formuang').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
	  var table = $('#table').DataTable({
      processing: true,
      searching: false,
      ordering: false,
      dom: '<"box-body table-responsive no-padding"tr>',
      columns: [
        { data: 'KodeAkun', name: 'KodeAkun' },
        { data: 'Debet', name: 'Debet', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
        { data: 'Kredit', name: 'Kredit', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
        { data: null, defaultContent: '<button id="btn-remove" class="btn btn-xs btn-danger"><i class="fa fa-close"></i></button>', class:'text-center', orderable: false, searchable: false},
      ],
      drawCallback: function () {
        var api = this.api();
        $(api.column(1).footer()).html(
          $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ).display(api.column(1).data().sum())
        );
        $(api.column(2).footer()).html(
          $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ).display(api.column(2).data().sum())
        );
      }
    });

    $("select[name=KodeAkun]").select2({
        placeholder: "Pilih Akun...",
        allowClear: false,
        ajax: {
            url: '{{ route("select2.akun.detil") }}',
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

    $('#table tbody').on('click', '#btn-remove', function () {
      table.rows($(this).parents('tr')).remove().draw(false);
      if (!table.data().count()) {
        $('#btn-simpan').prop('disabled', true);
      }
    });
    $('input[name=Debet]').on('input', function () {
      if ($(this).inputmask('unmaskedvalue') > 0) {
        $('input[name=Kredit]').val(0);
      }
    });
    $('input[name=Kredit]').on('input', function () {
      if ($(this).inputmask('unmaskedvalue') > 0) {
        $('input[name=Debet]').val(0);
      }
    });
    $('#btn-proses').click(function () {
      if ($('select[name=KodeAkun]').val() != null) {
        var debet = $('input[name=Debet]').inputmask('unmaskedvalue') || 0;
        var kredit = $('input[name=Kredit]').inputmask('unmaskedvalue') || 0;
        if (debet == 0 && kredit == 0) {
          $('input[name=Debet]').focus();
        }else{
          $('#btn-simpan').prop('disabled', false);
          table.row.add({
            'KodeAkun' : $('select[name=KodeAkun]').val(),
            'Debet' : $('input[name=Debet]').inputmask('unmaskedvalue') || 0,
            'Kredit' : $('input[name=Kredit]').inputmask('unmaskedvalue') || 0
          }).draw(false);
        }
      }else{
        $('select[name=KodeAkun]').select2('open');
      }
    });

    function bersih() {
      $('input[name=BuktiTransaksi]').val('');
      $('input[name=Keterangan]').val('');
      $('select[name=KodeAkun]').val(null).trigger('change');
      $('input[name=Debet]').val(0);
      $('input[name=Kredit]').val(0);
      table.clear().draw();
    }

    $('#btn-simpan').click(function () {
      if (table.column(1).data().sum() == table.column(2).data().sum()) {
        var jurnal = table.data().toArray();
        var BuktiTransaksi = $('input[name=BuktiTransaksi]').val();
        // var NamaTransaksi = $('input[name=NamaTransaksi]').val();
        var Keterangan = $('input[name=Keterangan]').val();
        $.ajax({
          url: "{{ route('txlain.store') }}",
          type: 'POST',
          data: {_token: CSRF_TOKEN, jurnal:jurnal, BuktiTransaksi:BuktiTransaksi, Keterangan:Keterangan},
          dataType: 'JSON',
          success: function (data) {
            bersih();
            $('input[name=BuktiTransaksi]').focus();
            notifikasi(data.notif);
          }
        });
      }else{
        alert('Tidak balance');
      }
    });
  });
</script>
@stop
