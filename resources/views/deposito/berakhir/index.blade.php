@extends('adminlte::page')

@section('title', 'Simpanan Anggota')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Rekomendasi Deposito Berakhir</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Berakhir</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Rekomendasi Deposito Berakhir</h3>
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th style="width:90px;">No. Deposito</th>
                <th style="width:90px;">No. Tabungan</th>
                <th>Nama Nasabah</th>
                <th>Jumlah Deposito</th>
                <th>Bunga(%)</th>
                <th>Nominal Bunga</th>
                <th>Bunga Sudah Diberikan</th>
                <th style="width:150px;"></th>
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
              <td style="background-color: #ffffff !important;" width="20%"> <strong>No. Deposito </strong>  </td>
              <td style="background-color: #ffffff !important;" width="30%">  : @{{ NoDeposito }} </td>
              <td style="background-color: #ffffff !important;" width="20%"><strong> No. Tabungan </strong> </td>
              <td style="background-color: #ffffff !important;" width="30%">  : @{{ NoTabungan }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Nasabah </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ nasabah.NamaNasabah }} </td>
              <td style="background-color: #ffffff !important;"><strong> Jumlah Deposito </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang JumlahDeposito }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Bunga </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ Bunga }} %</td>
              <td style="background-color: #ffffff !important;"><strong> Berlaku </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ TglInput }} </td>
            </tr>
            <tr>
              <td style="background-color: #ffffff !important;"><strong> Jumlah Bunga </strong> </td>
              <td style="background-color: #ffffff !important;">  : Rp @{{formatuang NominalBunga }} </td>
              <td style="background-color: #ffffff !important;"><strong> Berakhir </strong> </td>
              <td style="background-color: #ffffff !important;">  : @{{ TglAkhirBerlaku }} </td>
            </tr>
          </tbody>
        </table>
      </div>
      <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="detil-@{{NoDeposito}}">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Bunga</th>
            <th>Keterangan</th>
          </tr>
        </thead>
      </table>
    </div>
  </script>

  {{-- modal-perpanjangan --}}
  <div class="modal fade" id="modal-perpanjangan">
    <div class="modal-dialog">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Perpanjangan Deposito</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label">Nasabah</label>
              <input disabled type="text" class="form-control" id="NoNasabah" required>
              <span class="help-block with-errors"></span>
            </div>
            <div class="row">
              <div class="form-group col-md-7">
                <label class="control-label">Jumlah Deposito</label>
                <div class="input-group">
                  <span class="input-group-addon">Rp</span>
                  <input disabled type="text" class="form-control" id="JumlahDeposito" required>
                </div>
                <span class="help-block with-errors"></span>
              </div>
              <div class="form-group col-md-5">
                <label class="control-label">Tanggal Seharusnya Berakhir</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input disabled type="text" class="form-control" id="tglseharusnyaberakhir">
                </div>
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label class="control-label">Jangka Waktu</label>
                <div class="input-group">
                  <input type="number" min="1" class="form-control" id="JangkaWaktu" value="0" required>
                  <span class="input-group-addon">Bulan</span>
                </div>
                <span class="help-block with-errors"></span>
              </div>
              <div class="form-group col-md-8">
                <label class="control-label">Berakhir</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input readonly type="text" class="form-control" name="TglAkhirBerlaku" value="{{ date('d-m-Y', strtotime(session('tgl'))) }}" required>
                </div>
                <span class="help-block with-errors"></span>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label class="control-label">Bunga</label>
                <div class="input-group">
                  <input type="number" step=".01" min="0.01" value="0.01" class="form-control" name="Bunga" required>
                  <span class="input-group-addon">%</span>
                </div>
                <span class="help-block with-errors"></span>
              </div>
              <div class="form-group col-md-8">
                <label class="control-label">Nominal Bunga</label>
                <div class="input-group">
                  <span class="input-group-addon">Rp</span>
                  <input disabled type="text" class="form-control" name="NominalBunga" required>
                </div>
                <span class="help-block with-errors"></span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Proses</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- modal-tarik --}}
  <div class="modal fade" id="modal-tarik">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
        {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Penarikan Deposito</h4>
          </div>
          <div class="modal-body">
            <h5>Apakah Anda Yakin Menarik Deposito Atas Nama : <strong id="nasabahdeposito"></strong></h5>
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
    var tglbuku = '{{ session('tgl') }}';
    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('deposito.berakhir.dt') !!}',
	      columns: [
            {
              "className":'details-control',
              "orderable":false,
              "searchable":false,
              "data":null,
              "defaultContent":''
            },
	          { data: 'NoDeposito', name: 'NoDeposito' },
            { data: 'NoTabungan', name: 'NoTabungan' },
            { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' },
            { data: 'JumlahDeposito', name: 'JumlahDeposito', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'Bunga', name: 'Bunga' },
            { data: 'NominalBunga', name: 'NominalBunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
            { data: 'JumlahNominalBunga', name: 'JumlahNominalBunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
	          { data: 'action', name: 'action', orderable: false, searchable: false}
	      ],
        order: [[1, 'asc']]
	  });
    $('#table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      var tableId = 'detil-' + row.data().NoDeposito;

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
          { data: 'Bunga', name: 'Bunga', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'Keterangan', name: 'Keterangan' }
        ]
      });
    };

    $('#table tbody').on('click', '#btnperpanjang', function () {
      var tb = table.row($(this).parents('tr')).data();
      $('#modal-perpanjangan form').attr('action', "{{ URL('/depositoberakhir/perpanjang') }}/" + tb.NoDeposito);
      $('#NoNasabah').val(tb.NoTabungan + ' => ' + tb.NoNasabah + ' | ' + tb.nasabah.NamaNasabah);
      $('#tglseharusnyaberakhir').val(tb.TglAkhirBerlaku);
      $('#JumlahDeposito').val(tb.JumlahDeposito);
      $('input[name=Bunga]').val(tb.Bunga);
      $('input[name=NominalBunga]').val(tb.NominalBunga);
    });

    $('#table tbody').on('click', '#btntarik', function () {
      var tb = table.row($(this).parents('tr')).data();
      $('#modal-tarik form').attr('action', "{{ URL('/depositoberakhir/tarik') }}/" + tb.NoDeposito);
      $('#nasabahdeposito').text(tb.nasabah.NamaNasabah);
    });

    $('#JumlahDeposito').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });

    $('input[name=NominalBunga]').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: true
    });

    function nominalBunga() {
      var JumlahDeposito = parseInt($('#JumlahDeposito').inputmask('unmaskedvalue'));
      var Bunga = parseFloat($('input[name=Bunga]').val()).toFixed(2);
      if (isNaN(JumlahDeposito)) { JumlahDeposito = 0; }
      if (isNaN(Bunga)) { Bunga = 0; }
      var jumlah = JumlahDeposito * Bunga / 100;
      $('input[name=NominalBunga]').val(round(jumlah, 2));
    }

    function berakhir() {
      var JangkaWaktu = parseInt($('#JangkaWaktu').val());
      var d = new Date(tglbuku);
      d.setMonth( d.getMonth( ) + JangkaWaktu );
      var TglAkhirBerlaku = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
      $('input[name=TglAkhirBerlaku]').val(TglAkhirBerlaku);
    }

    $('input[name=Bunga]').on('input', function () {
      nominalBunga();
    });
    $('#JangkaWaktu').on('input', function () {
      berakhir();
    });

    $('#modal-perpanjangan').on('hidden.bs.modal', function () {
      $(this).find('form')[0].reset();
    });
  });
</script>
@stop
