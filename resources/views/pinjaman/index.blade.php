@extends('adminlte::page')

@section('title', 'Pinjaman')

@section('css')
  <style media="screen">
    .bg-detil{
      background-color: #ecf0f5;
    }
  </style>
@stop

@section('content_header')
  <h1>Data Pinjaman</h1>
  <ol class="breadcrumb">
    <li class="active"><i class="fa fa-money"></i> Pinjaman</li>
  </ol>
@stop

@section('content')
	<div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">Tabel Data Pinjaman</h3>
          <div class="box-tools">
            <a href="{{ route('pinjaman.create') }}" class="btn btn-primary btn-sm" ><i class="glyphicon glyphicon-plus"></i> Tambah Pinjaman</a>
            {{-- <button class="btn btn-sm btn-primary" id="btntambah" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus-square"></i> Tambah Pinjaman</button> --}}
          </div>
        </div>
          <table class="table table-bordered" cellspacing="0" width="100%" id="table">
            <thead>
              <tr>
                <th></th>
                <th style="width:90px;">No. Pinjaman</th>
                <th>Nama Nasabah</th>
                <th>Jumlah Pinjaman</th>
                <th>Jumlah Diterima</th>
                <th>Sisa Pinjaman</th>
                <th style="width:270px;"></th>
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

  {{-- modal-tambah --}}
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form role="form" action="{{ route('pinjaman.tambah') }}" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('POST') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Tambah Pinjaman</h4>
          </div>
          <div class="modal-body">
            @include('pinjaman.formtambah')
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- modal-bayar --}}
  <div class="modal fade" id="modal-bayar">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Pembayaran Pinjaman</h4>
          </div>
          <div class="modal-body" style="padding-top: 0px;">
            @include('pinjaman.formbayar')
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- modal-lunas --}}
  <div class="modal fade" id="modal-lunas">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form role="form" action="" method="POST">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Pelunasan Pinjaman</h4>
          </div>
          <div class="modal-body">
            @include('pinjaman.formpelunasan')
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary pull-left">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- modal-Kompensasi --}}
  <div class="modal fade" id="modal-kompensasi">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form role="form" action="" data-toggle="validator" method="POST">
          {{ csrf_field() }} {{ method_field('PUT') }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title text-center">Kompensasi Pinjaman</h4>
          </div>
          <div class="modal-body" style="padding-top: 0px;">
            @include('pinjaman.kompensasi.formkompen')
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
    var aksi;
    var modalaktif;
    var tglbuku = '{{$tglbuku}}';
    var denda = {{$denda}};
    Handlebars.registerHelper('formatuang', function(value) {
      return parseFloat(value).toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    });
    var template = Handlebars.compile($("#detil-template").html());
    var templatebayar = Handlebars.compile($("#bayar-template").html());
    var templatelunas = Handlebars.compile($("#lunas-template").html());
    var templatekompensasi = Handlebars.compile($("#lunas-kompen-template").html());
	  var table = $('#table').DataTable({
	      processing: true,
	      serverSide: true,
        "dom": '<"box-body"<"row"<"col-sm-6"l><"col-sm-6"f>>><"box-body table-responsive no-padding"tr><"box-body"<"row"<"col-sm-5"i><"col-sm-7"p>>>',
	      ajax: '{!! route('pinjaman.dt') !!}',
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
          { data: 'SisaPinjaman', name: 'SisaPinjaman', class:'text-right', render: $.fn.dataTable.render.number( '.', ',', 2, 'Rp ' ) },
          { data: 'action', name: 'action', orderable: false, searchable: false}
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

    $("select[name=NoNasabah]").select2({
      placeholder: "Pilih Nasabah...",
      allowClear: false,
      ajax: {
        url: '{{ route("select2.nasabah.pinjaman") }}',
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
    $('.formuang').inputmask("numeric", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: false,
        removeMaskOnSubmit: true,
        allowMinus: true
    });

    //tambah
    $('#modal-tambah').on('hidden.bs.modal', function () {
      $('select[name=NoNasabah]').val(null).trigger('change');
      $('#modal-tambah form')[0].reset();
      modalaktif = '';
    });

    $('#modal-tambah').on('shown.bs.modal', function () {
      modalaktif = '#modal-tambah';
      tempo();
    });

    function jumlahditerima() {
      var JumlahPinjaman = parseInt($(modalaktif +' input[name=JumlahPinjaman]').inputmask('unmaskedvalue'));
      var PotonganPropisi = parseInt($(modalaktif +' input[name=PotonganPropisi]').inputmask('unmaskedvalue'));
      var PotonganMateraiMap = parseInt($(modalaktif +' input[name=PotonganMateraiMap]').inputmask('unmaskedvalue'));
      var PotonganLain = parseInt($(modalaktif +' input[name=PotonganLain]').inputmask('unmaskedvalue'));
      var totalpelunasan = $('#totalpelunasan').val();
      if (isNaN(JumlahPinjaman)) { JumlahPinjaman = 0; }
      if (isNaN(PotonganPropisi)) { PotonganPropisi = 0; }
      if (isNaN(PotonganMateraiMap)) { PotonganMateraiMap = 0; }
      if (isNaN(PotonganLain)) { PotonganLain = 0; }
      var jumlah = JumlahPinjaman - (PotonganPropisi + PotonganMateraiMap + PotonganLain);
      if (modalaktif == '#modal-kompensasi') {
        var titik = totalpelunasan.replace(/\./g, "");
        jumlah = jumlah - parseFloat(titik.replace(",", ".")).toFixed(2);
      }
      $(modalaktif +' input[name=JumlahDiterima]').val(jumlah);
    }

    function angsuran() {
      var JumlahPinjaman = parseInt($(modalaktif +' input[name=JumlahPinjaman]').inputmask('unmaskedvalue'));
      var JangkaWaktu = $(modalaktif +' input[name=JangkaWaktu]').val();
      var angsuran = JumlahPinjaman / JangkaWaktu;
      $(modalaktif +' input[name=AngsuranPerbulan]').val(parseFloat(angsuran).toFixed(2));
    }

    function tempo() {
      var JangkaWaktu = parseInt($(modalaktif +' input[name=JangkaWaktu]').val());
      var d = new Date(tglbuku);
      d.setMonth( d.getMonth( ) + JangkaWaktu );
      var jatuhtempo = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
      $(modalaktif +' input[name=TglJatuhTempo]').val(jatuhtempo);
    }

    $('input[name=JumlahPinjaman]').on('input', function () {
      jumlahditerima();
      angsuran();
    });
    $('input[name=PotonganPropisi]').on('input', function () {
      jumlahditerima();
    });
    $('input[name=PotonganMateraiMap]').on('input', function () {
      jumlahditerima();
    });
    $('input[name=PotonganLain]').on('input', function () {
      jumlahditerima();
    });
    $('input[name=JangkaWaktu]').on('input', function () {
      angsuran();
      tempo()
    });

    //bayar
    $('#table tbody').on('click', '#btnbayar', function () {
      var tb = table.row($(this).parents('tr')).data();
      modalaktif = '#modal-bayar';
      $('#modal-bayar form').attr('action', "{{ URL('/pinjaman/bayar') }}/" + tb.NoPinjaman);
      $.get("{{ URL('/pinjaman/bayar/detil') }}/" + tb.NoPinjaman, function(data){
        $('#modal-bayar div.modal-body').prepend(templatebayar(data));
        $('input[name=Pokok]').focus();
        $('input[name=Pokok]').val(parseInt(data.AngsuranPerBulan + data.tunggakanpokok));
        $('input[name=NominalBunga]').val(data.bunga);
        $('input[name=Denda]').val(data.denda);
        $('input[name=Jumlah]').val(data.total);
      });
    });

    $('#modal-bayar').on('hidden.bs.modal', function () {
      $('#rincianbayar').remove();
      $('#modal-bayar form')[0].reset();
      modalaktif = '';
    });

    function jumlahbayar() {
      var Pokok = parseInt($('input[name=Pokok]').inputmask('unmaskedvalue'));
      var NominalBunga = parseInt($('input[name=NominalBunga]').inputmask('unmaskedvalue'));
      var Denda = parseInt($('input[name=Denda]').inputmask('unmaskedvalue'));
      if (isNaN(Pokok)) { Pokok = 0; }
      if (isNaN(NominalBunga)) { NominalBunga = 0; }
      if (isNaN(Denda)) { Denda = 0; }
      var jumlah = NominalBunga + Denda + Pokok;
      $('input[name=Jumlah]').val(jumlah);
    }

    $('input[name=Pokok]').on('input', function () {
      jumlahbayar();
    });
    $('input[name=NominalBunga]').on('input', function () {
      jumlahbayar();
    });
    $('input[name=Denda]').on('input', function () {
      jumlahbayar();
    });

    function getdatalunas(NoPinjaman, aksi) {
      $.get("{{ URL('/pinjaman/lunas/detil') }}/" + NoPinjaman, function(data){
        if (aksi == 'lunas') {
          $('#modal-lunas div.modal-body').prepend(templatelunas(data));
        }else{
          $('#modal-kompensasi div.box-body').prepend(templatekompensasi(data));
          $(modalaktif +' input[name=JumlahPinjaman]').val(data.Total);
          jumlahditerima();
          angsuran()
        }
      });
    }

    //pelunasan
    $('#table tbody').on('click', '#btnlunas', function () {
      var tb = table.row($(this).parents('tr')).data();
      modalaktif = '#btnlunas';
      $('#modal-lunas form').attr('action', "{{ URL('/pinjaman/lunas') }}/" + tb.NoPinjaman);
      getdatalunas(tb.NoPinjaman, 'lunas');
    });

    $('#modal-lunas').on('hidden.bs.modal', function () {
      $('#datapelunasan').remove();
      $('#modal-lunas form')[0].reset();
      modalaktif = '';
    });

    //kompensasi
    $('#table tbody').on('click', '#btnkompensasi', function () {
      var tb = table.row($(this).parents('tr')).data();
      modalaktif = '#modal-kompensasi';
      $('#modal-kompensasi form').attr('action', "{{ URL('/kompensasi') }}/" + tb.NoPinjaman);
      getdatalunas(tb.NoPinjaman, 'kompensasi');
      tempo()
    });

    $('#modal-kompensasi').on('hidden.bs.modal', function () {
      $('#datakompenpelunasan').remove();
      $('#modal-kompensasi form')[0].reset();
      modalaktif = '';
    });

  });
</script>
@stop
