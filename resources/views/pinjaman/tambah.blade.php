@extends('adminlte::page')

@section('title', 'Tambah - Pinjaman')

@section('content_header')
<h1>Tambah Pinjaman</h1>
<ol class="breadcrumb">
    <li><a href="{{ route('pinjaman.index') }}"><i class="fa fa-users"></i>Pinjaman</a></li>
    <li class="active">Tambah</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <form id="formutama" role="form" action="{{ route('pinjaman.tambah') }}" data-toggle="validator" method="POST" autocomplete="off">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="box-header with-border">
                    <h3 class="box-title">Form Pinjaman</h3>
                </div>
                <div class="box-body">
                    @include('pinjaman.formtambah')
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-nasabah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" action="{{ route('nasabah.store') }}" data-toggle="validator" method="POST" autocomplete="off">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title text-center">Tambah Nasabah Baru</h4>
                </div>
                <div class="modal-body">
                    @include('nasabah.formnasabah')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <div class="modal fade" id="modal-tabungan">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form" autocomplete="off" action="{{ route('tabungan.tambahpinjaman') }}" data-toggle="validator" method="POST">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Tambah Tabungan</h4>
                </div>
                <div class="modal-body">
                    <input type="text" name="NoNasabah">
                    <div class="form-group">
                        <label class="control-label">Setoran awal</label>
                        <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="text" class="form-control formuang" name="saldoawal" required>
                        </div>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function () {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var tglbuku = "{{ session('tgl') }}";
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
    $("select[name=NoKolektor]").select2({
        placeholder: "Pilih Kolektor...",
        allowClear: true,
        ajax: {
            url: '{{ route("select2.kolektor") }}',
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
        allowMinus: false
    });
    $('input[name=TanggalLahir]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD-MM-YYYY'
        },
    });

    $("select[name=NoNasabah]").on('select2:select', function (e) { 
        var noTabungan = e.params.data.NoTabungan;
        setTabungan(noTabungan);
    });

    function setTabungan(noTabungan) {
        if (noTabungan == null) {
            $('input[name=NoTabungan]').val(null);
            $('input[name=NoTabungan]').attr('placeholder', 'Tidak Memiliki Tabungan');
            $('#btntabungan').prop('disabled', false);
        }else{
            $('input[name=NoTabungan]').val(noTabungan);
            $('#btntabungan').prop('disabled', true);
        }
    }

    $("#btntabungan").click(function() {
        var nasabah = $("select[name=NoNasabah]").val();
        $.ajax({
            url: "{{ route('tabungan.tambahpinjaman') }}",
            type: 'POST',
            data: {_token: CSRF_TOKEN, NoNasabah: nasabah, saldoawal:0},
            dataType: 'JSON'
        }).done(function(data) {
            setTabungan(data.data.NoRek);
            $('input[name=Jaminan]').focus();
            notifikasi(data.notif);
        }).fail(function(data) {
            notifikasi(data.notif);
        });
    })

    $('#modal-nasabah form').validator().on('submit', function (e) { 
        if (e.isDefaultPrevented() == false) {
            e.preventDefault();
            var form = request($(this));
            form.TanggalLahir = tglnormal(form.TanggalLahir);
            $.ajax({
                url: "{{ route('nasabah.api.store') }}",
                type: 'POST',
                data: form,
                dataType: 'JSON'
            }).done(function(data) {
                select2isi("select[name=NoNasabah]", data.data.NoNasabah, data.data.NoNasabah + ' | ' + data.data.NamaNasabah);
                setTabungan(null);
                $('#modal-nasabah').modal('hide');
                $('input[name=Jaminan]').focus();
                notifikasi(data.notif);
            }).fail(function(data) {
                alert("error");
                notifikasi(data.notif);
            });
        }
    });

    var confirmed = false;
    $('#formutama').validator().on('submit', function (e) { 
        if (confirmed == false) {
            if (e.isDefaultPrevented() == false) {
                e.preventDefault();
                var data = $('select[name=NoNasabah]').select2('data');
                var r = confirm("Anda Yakin Menambah Pinjaman Baru\nAtas Nasabah : " + data[0].text + "\nSejumlah " + $('input[name=JumlahPinjaman]').val().replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") + " ?");
                if (r == true) {
                    confirmed = true;
                    $('#formutama').submit();
                }
            }
        }
    });

    function tempo() {
        var JangkaWaktu = parseInt($('input[name=JangkaWaktu]').val());
        var jatuhtempo = tgl(tglbuku, JangkaWaktu);
        $('input[name=TglJatuhTempo]').val(jatuhtempo);
    }
    function jumlahditerima() {
        var JumlahPinjaman = parseInt($('input[name=JumlahPinjaman]').inputmask('unmaskedvalue')) || 0;
        var PotonganTabungan = parseInt($('input[name=PotonganTabungan]').inputmask('unmaskedvalue')) || 0;
        var PotonganAsuransi = parseInt($('input[name=PotonganAsuransi]').inputmask('unmaskedvalue')) || 0;
        var PotonganPropisi = parseInt($('input[name=PotonganPropisi]').inputmask('unmaskedvalue')) || 0;
        var PotonganMateraiMap = parseInt($('input[name=PotonganMateraiMap]').inputmask('unmaskedvalue')) || 0;
        var PotonganLain = parseInt($('input[name=PotonganLain]').inputmask('unmaskedvalue')) || 0;
        var totalpelunasan = $('#totalpelunasan').val();
        var jumlah = JumlahPinjaman - (PotonganPropisi + PotonganMateraiMap + PotonganLain + PotonganTabungan + PotonganAsuransi);
        $('input[name=JumlahDiterima]').val(jumlah);
    }

    function angsuran() {
        var JumlahPinjaman = parseInt($('input[name=JumlahPinjaman]').inputmask('unmaskedvalue'));
        var JangkaWaktu = $('input[name=JangkaWaktu]').val();
        var angsuran = JumlahPinjaman / JangkaWaktu;
        $('input[name=AngsuranPerbulan]').val(parseFloat(angsuran).toFixed(2));
    }

    $('input[name=JumlahPinjaman]').on('input', function () {
        jumlahditerima();
        angsuran();
    });
    $('input[name=PotonganTabungan]').on('input', function () {
        jumlahditerima();
    });
    $('input[name=PotonganAsuransi]').on('input', function () {
        jumlahditerima();
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
    $('#modal-nasabah').on('hidden.bs.modal', function () {
      $('#modal-nasabah form')[0].reset();
      $('input[name=TanggalLahir]').val(tgl(tglbuku, 0));
    });
    $('#modal-nasabah').on('shown.bs.modal', function () {
        $.get("{{ route('nasabah.nounik') }}", function(data){
            $('#modal-nasabah input[name=NoNasabah]').val(data);
        });
        $('input[name=TanggalLahir]').focus();
    });

});
</script>
@stop
