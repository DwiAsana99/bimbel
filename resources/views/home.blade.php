@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
{{-- @can('jos') --}}
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $nasabah['total'] }}</h3>
                <p>Anggota & Nasabah</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a class="small-box-footer">Detail <i class="fa fa-arrow-circle-down"></i></a>
            <div class="row">
                <div class="col-sm-6 description-block" style="border-right: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $nasabah['nasabah'] }}</h5>
                    <span class="description-text">Nasabah</span>
                </div>
                <div class="col-sm-6 description-block">
                    <h5 class="description-header">{{ $nasabah['anggota'] }}</h5>
                    <span class="description-text">Anggota</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $pinjaman['total'] }}</h3>
                <p>Rekening Pinjaman</p>
            </div>
            <div class="icon">
                <i class="fa fa-credit-card"></i>
            </div>
            <a class="small-box-footer">Transaksi Hari Ini <i class="fa fa-arrow-circle-down"></i></a>
            <div class="row">
                <div class="col-sm-6 description-block" style="border-right: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $pinjaman['baru'] }}</h5>
                    <span class="description-text">Baru</span>
                </div>
                <div class="col-sm-6 description-block">
                    <h5 class="description-header">{{ $pinjaman['pembayaran'] }}</h5>
                    <span class="description-text">Pembayaran</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $tabungan['total'] }}</h3>
                <p>Rekening Tabungan</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a class="small-box-footer">Transaksi Hari Ini <i class="fa fa-arrow-circle-down"></i></a>
            <div class="row">
                <div class="col-sm-4 description-block" style="border-right: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $tabungan['baru'] }}</h5>
                    <span class="description-text">Baru</span>
                </div>
                <div class="col-sm-4 description-block">
                    <h5 class="description-header">{{ $tabungan['setor'] }}</h5>
                    <span class="description-text">Setor</span>
                </div>
                <div class="col-sm-4 description-block" style="border-left: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $tabungan['tarik'] }}</h5>
                    <span class="description-text">Tarik</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $deposito['total'] }}</h3>
                <p>Rekening Deposito</p>
            </div>
            <div class="icon">
                <i class="fa fa-line-chart"></i>
            </div>
            <a class="small-box-footer">Transaksi Hari Ini <i class="fa fa-arrow-circle-down"></i></a>
            <div class="row">
                <div class="col-sm-4 description-block" style="border-right: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $deposito['baru'] }}</h5>
                    <span class="description-text">Baru</span>
                </div>
                <div class="col-sm-4 description-block">
                    <h5 class="description-header">{{ $deposito['bunga'] }}</h5>
                    <span class="description-text">Bunga</span>
                </div>
                <div class="col-sm-4 description-block" style="border-left: 1px solid #f4f4f454;">
                    <h5 class="description-header">{{ $deposito['tempo'] }}</h5>
                    <span class="description-text">J.Tempo</span>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endcan --}}
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header text-center with-border">
                <h3 class="box-title">Pendapatan & Biaya</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_pendapatan_biaya" width="100" height="15"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header text-center with-border">
                <h3 class="box-title">Pendapatan</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_pendapatan" width="50" height="20"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-danger">
            <div class="box-header text-center with-border">
                <h3 class="box-title">Biaya</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_biaya" width="50" height="20"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header text-center with-border">
                <h3 class="box-title">Kas Masuk & Keluar</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_kas_masuk_keluar" width="100" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header text-center with-border">
                <h3 class="box-title">10 Jumlah Tabungan Tertinggi</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_tabungan_tertinggi" width="100" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header text-center with-border">
                <h3 class="box-title">10 Sisa Pinjaman Tertinggi</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="chart_sisa_pinjaman_tertinggi" width="100" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('plugins/Chartjs/Chart.min.js') }}"></script>
<script>
$(document).ready(function () {
    var kasMasukKeluar = {!! $kasMasukKeluar !!};
    var pendapatanBeban = {!! $pendapatanBeban !!};
    var tabunganTinggi = {!! $tabunganTinggi !!};
    var pinjamanTinggi = {!! $pinjamanTinggi !!};

    var canvasPendapatanBiaya = $('#chart_pendapatan_biaya').get(0).getContext('2d');
    var canvasPendapatan = $('#chart_pendapatan').get(0).getContext('2d');
    var canvasBiaya = $('#chart_biaya').get(0).getContext('2d');
    var canvasKasMasukKeluar = $('#chart_kas_masuk_keluar').get(0).getContext('2d');
    var canvasTabunganTertinggi = $('#chart_tabungan_tertinggi').get(0).getContext('2d');
    var canvasSisaPinjamanTertinggi = $('#chart_sisa_pinjaman_tertinggi').get(0).getContext('2d');



    var dataPendapatanBiaya = {
        labels  : ['Pendapatan', 'Biaya'],
        datasets: [{
            backgroundColor: [
                "#47FF4E",
                "#FF3F3B"
            ],
            data : pendapatanBeban.total
        }]
    };
    var optPendapatanBiaya = {
        scales : {
            xAxes : [{
                ticks : {
                    max : 50000,
                    min : 0
                }
            }]
        },
        legend: {
            display: false
        },
        maintainAspectRatio : false,
        responsive : true
    };


    var dataPendapatan = {
        labels  : pendapatanBeban.pendapatan.label,
        datasets: [{
            backgroundColor: [
                "#FF3F3B",
                "#8863E8",
                "#FFF748",
                "#188AFF",
                "#47FF4E"
            ],
          data : pendapatanBeban.pendapatan.data
        }]
    };
    var optPendapatan = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
            position: 'right'
        },
    };


    var dataBiaya = {
        labels  : pendapatanBeban.beban.label,
        datasets: [{
            backgroundColor: [
                "#FF3F3B",
                "#188AFF",
                "#8863E8",
            ],
          data : pendapatanBeban.beban.data
        }]
    };
    var optBiaya = optPendapatan;


    var dataKasMasukKeluar = {
      labels  : kasMasukKeluar.tgl,
      datasets: [
        {
          label : 'Kas Masuk',
          lineTension : 0,
          borderColor : 'rgba(131, 131, 255, 1)',
          fill : false,
          data : kasMasukKeluar.masuk
        },
        {
          label : 'Kas Keluar',
          lineTension : 0,
          borderColor : 'rgba(255, 83, 83, 1)',
          fill : false,
          data : kasMasukKeluar.keluar
        }
      ]
    };
    var optKasMasukKeluar = {
        legend: {
            display: false
        },
        tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
                label: function (t, d) {
                return d.datasets[t.datasetIndex].label + ' ' + t.yLabel.toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                }
            }
        },
        scales : {
          yAxes : [{
            ticks : {
              callback: function(value, index, values) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }]
        },
        maintainAspectRatio : false,
        responsive : true
    };


    var dataTabunganTertinggi = {
      labels : tabunganTinggi.nasabah,
      datasets : [
        {
            backgroundColor: [
                "#FF3F3B",
                "#8863E8",
                "#FFF748",
                "#188AFF",
                "#FF3F3B",
                "#8863E8",
                "#FFF748",
                "#188AFF",
                "#188AFF",
                "#47FF4E"
            ],
            data : tabunganTinggi.saldo
        }
      ]
    };
    var optTabunganTertinggi = {
        maintainAspectRatio : false,
        responsive : true,
        // animation : {
        //     duration : 1,
        //     onComplete : function() {
        //         var chartInstance = this.chart,
        //         ctx = chartInstance.ctx;

        //         ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        //         ctx.textAlign = 'center';
        //         ctx.textBaseline = 'bottom';

        //         this.data.datasets.forEach(function(dataset, i) {
        //             var meta = chartInstance.controller.getDatasetMeta(i);
        //             meta.data.forEach(function(bar, index) {
        //                 var data = dataset.data[index];
        //                 ctx.fillText(data, bar._model.x, bar._model.y - 5);
        //             });
        //         });
        //     }
        // },
        legend: {
            display: false
        },
        tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
                label: function (t, d) {
                return t.yLabel.toFixed(2).replace(".", ",").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                }
            }
        },
        scales: {
            xAxes: [{
                ticks: {
                    display: false
                }
            }],
            yAxes : [{
            ticks : {
              callback: function(value, index, values) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }]
        }
    };

    var dataSisaPinjamanTertinggi = {
      labels : pinjamanTinggi.nasabah,
      datasets : [
        {
            backgroundColor : [
                "#FF3F3B",
                "#8863E8",
                "#FFF748",
                "#188AFF",
                "#FF3F3B",
                "#8863E8",
                "#8863E8",
                "#FFF748",
                "#188AFF",
                "#47FF4E"
            ],
            data : pinjamanTinggi.sisa
        }
      ]
    };
    var optSisaPinjamanTertinggi = optTabunganTertinggi;

    var chartPendapatanBiaya = new Chart(canvasPendapatanBiaya, {
        type: 'horizontalBar',
        data: dataPendapatanBiaya,
        options: optPendapatanBiaya
    });
    var chartPendapatan = new Chart(canvasPendapatan, {
        type: 'pie',
        data: dataPendapatan,
        options: optPendapatan
    });
    var chartBiaya = new Chart(canvasBiaya, {
        type: 'pie',
        data: dataBiaya,
        options: optBiaya
    });
    var chartKasMasukKeluar = new Chart(canvasKasMasukKeluar, {
        type: 'line',
        data: dataKasMasukKeluar,
        options: optKasMasukKeluar
    });
    var chartTabunganTertinggi = new Chart(canvasTabunganTertinggi, {
        type: 'bar',
        data: dataTabunganTertinggi,
        options: optTabunganTertinggi
    });
    var chartSisaPinjamanTertinggi = new Chart(canvasSisaPinjamanTertinggi, {
        type: 'bar',
        data: dataSisaPinjamanTertinggi,
        options: optSisaPinjamanTertinggi
    });
});
</script>
@stop