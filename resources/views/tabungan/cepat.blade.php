@extends('adminlte::page')

@section('title', $data['status']. ' - Tabungan')

@section('css')
  <style>
    #table-nasabah tbody tr {
      cursor: pointer;
    }
  </style>
@stop

@section('content_header')
  <h1>{{ $data['status']}} Tabungan</h1>
  <ol class="breadcrumb">
    <li><i class="fa fa-money"></i> Tabungan</li>
    <li class="active"> {{ $data['status']}} Tabungan</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-body">
          <div class="form-group col-md-5">
            <label class="control-label">No. Rekening Nasabah</label>
            <div class="input-group">
              <span class="input-group-addon"><strong style="font-size:14pt;">TB</strong></span>
              <input autocomplete="off" type="text" name="NoRek" class="form-control input-lg">
              <span class="input-group-btn">
                  <button type="button" id="btn-nasabah" data-toggle="modal" data-target="#modal-nasabah" class="btn btn-default btn-lg btn-flat">...</button>
              </span>
            </div>
          </div>
          <div class="form-group col-md-5">
            <label class="control-label">Nominal {{ $data['status2'] }}</label>
            <div class="input-group">
              <span class="input-group-addon"><strong style="font-size:14pt;">Rp</strong></span>
              <input autocomplete="off" disabled type="text" name="nominal" class="form-control input-lg">
            </div>
          </div>
          <div class="form-group col-md-2" style="padding-top: 25px;">
            <button disabled id="btnproses" class="btn btn-primary btn-lg" style="width:100%;">Proses</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-nasabah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Pilih Nasabah</h4>
        </div>
        <table class="table table-striped table-condensed table-hover" cellspacing="0" width="100%" id="table-nasabah">
          <thead>
            <tr>
              <th style="width:20px;">No. Rek</th>
              <th>Nama Nasabah</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <script id="detil-template" type="text/x-handlebars-template">
    <div id="detil-html" class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="table-responsive">
            <table width="100%">
              <tbody>
                <tr>
                  <td width="10%"> <strong>No Rekening </strong>  </td>
                  <td width="30%">  : @{{ NoRek }} </td>
                  <!-- <td width="10%"><strong> Saldo Rendah </strong> </td>
                  <td width="20%">  : Rp @{{formatuang SaldoRendah }} </td> -->
                  <td width="30%" rowspan="3">
                    <div class="form-group">
                      <div class="input-group">
                        <span class="input-group-addon"><strong>Saldo Akhir</strong></span>
                        <input disabled type="text" id="form-saldoakhir" value="@{{formatuang Saldo}}" class="form-control">
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong> Nasabah </strong> </td>
                  <td>  : @{{ nasabah.NamaNasabah }} </td>
                  <!-- <td><strong> Saldo Tinggi </strong> </td>
                  <td>  : Rp @{{formatuang SaldoTinggi }} </td> -->
                </tr>
                <tr>
                  <td><strong> Tanggal Buka </strong> </td>
                  <td>  : @{{ TglInput }} </td>
                  <!-- <td><strong> Saldo Akhir </strong> </td>
                  <td>  : Rp @{{formatuang Saldo }} </td> -->
                </tr>
              </tbody>
            </table>
          </div>
          </div>
          <table class="table table-striped table-bordered details-table" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th>Tanggal Input</th>
                <th>Debet</th>
                <th>Kredit</th>
                <th>Keterangan</th>
                <th>Saldo Akhir</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </script>
@stop

@section('js')
<script src="{{ asset('plugins/handlebars/handlebars.js') }}"></script>
<script type="text/javascript">
	$( document ).ready(function() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var saldoakhir = 0;
    var aksi = '{{$data['status']}}';
    var saldomin = {{ $setting->SaldoMin }};

    var tableNasabah = $('#table-nasabah').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-12"p>>>',
	      ajax: '{!! route('tabungan.dt') !!}',
	      columns: [
	          { data: 'NoRek', name: 'NoRek' },
            { data: 'nasabah.NamaNasabah', name: 'nasabah.NamaNasabah' }
	      ]
	  });

    $('#table-nasabah tbody').on('click', 'tr', function () {
        var data = tableNasabah.row( this ).data();
        $('input[name=NoRek]').val(data.NoRek);
        getDetilTabungan(data.NoRek);
        $('#modal-nasabah').modal("hide");
    });

    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
    function initTable(norek) {
      var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
        ajax: '{{url('/tabungan/detil')}}/'+ norek,
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
    $('input[name=NoRek]').focus();
    $('input[name=nominal]').inputmask("numeric", {
        groupSeparator: ".",
        digits: 0,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: false
    });
    $('input[name=NoRek]').inputmask("numeric", {
        rightAlign: false,
        allowMinus: false
    });

    function getDetilTabungan(norek) {
      $('#detil-html').remove();
        $.get("{{ url('/tabungan/detildata') }}/"+norek, function(data){
          if (!data.NoRek) {
            $('input[name=nominal]').prop('disabled', true);
            alert('Rekening Tabungan Tidak Tersedia');         
          }else{
            $('.content').append(template(data));
            $('input[name=nominal]').prop('disabled', false);
            initTable(norek);
            saldoakhir = data.Saldo;
            $('input[name=nominal]').focus();
          }
      });
    }

    $('input[name=NoRek]').keypress(function(e) {
      if(e.which == 13) {
        var norek = 'TB' + $(this).val();
        getDetilTabungan(norek);
      }
    });
    $('input[name=nominal]').keypress(function(e) {
      if(e.which == 13) {
        var nominal = parseInt($(this).inputmask('unmaskedvalue'));
        var hasil;
        if (isNaN(nominal)) {
          nominal = 0;
        }
        if (aksi == 'Setor') {
          hasil = nominal + saldoakhir;
        }else if (aksi == 'Tarik'){
          hasil = saldoakhir - nominal;
        }
        if (hasil < saldomin) {
          alert('Saldo Tidak Mencukupi');
          $('input[name=nominal]').val(saldoakhir - saldomin);
          $('#form-saldoakhir').val(parseFloat(saldomin).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
        }else{
          $('#btnproses').focus();
          $('#form-saldoakhir').val(parseFloat(hasil).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
        }
      }
    });
    $('input[name=nominal]').on('input', function(e) {
      var nominal = parseInt($(this).inputmask('unmaskedvalue'));
      var hasil;
      if (isNaN(nominal)) {
        nominal = 0;
      }else{
        $('#btnproses').prop('disabled', false);
      }
      if (aksi == 'Setor') {
        hasil = nominal + saldoakhir;
      }else if (aksi == 'Tarik'){
        hasil = saldoakhir - nominal;
      }
      if (hasil < saldomin) {
        alert('Saldo Tidak Mencukupi');
        $('input[name=nominal]').val(saldoakhir - saldomin);
        $('#form-saldoakhir').val(parseFloat(saldomin).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
      }else{
        $('#form-saldoakhir').val(parseFloat(hasil).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
      }
    });
    $('#btnproses').on('keydown', function(e) {
      if(e.which == 13 || e.which == 1) {
        konfirmasi();
      }
    });
    $('#btnproses').on('click', function(e) {
      konfirmasi();
    });

    function konfirmasi() {
      if (parseInt($('input[name=nominal]').inputmask('unmaskedvalue')) > 0) {
        $('input[name=nominal]').focus();
        var r = confirm("Anda Yakin " + aksi + " Tabungan\nSebanyak : Rp" + $('input[name=nominal]').val());
        if (r == true) {
          var rs = confirm("Anda yakin ?");
          if (rs == true) {
            store();
          }
        }
      }
    }

    function store() {
      var nominal = parseInt($('input[name=nominal]').inputmask('unmaskedvalue'));
      var norek = 'TB' + $('input[name=NoRek]').val();
      $.ajax({
        url: '{{ url('/') }}/'+ aksi.toLowerCase() + 'cepat/' + norek,
        type: 'POST',
        data: {_token: CSRF_TOKEN, nominal:nominal},
        dataType: 'JSON',
        success: function (data) {
          $('input[name=NoRek]').val(null);
          $('input[name=nominal]').val(null);
          $('input[name=NoRek]').focus();
          $('#detil-html').remove();
          $('#btnproses').prop('disabled', true);
          notifikasi(data.notif);
        }
      });
    }
	});
</script>
@stop
