@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pnotify/pnotify.custom.min.css') }}">
    <style>
    @media only screen and (max-width: 767px) {
      .logo {
          background: linear-gradient(to right, #f39c12, #b7507b) !important;
      }
      .fixed .content-wrapper, .fixed .right-side, .main-sidebar {
        padding-top: 50px !important;
      }
    }
    @media (min-width: 768px) {
      .sidebar-mini.sidebar-collapse .user-panel {
          height: unset !important;
      }
    }
    .jos {
        -webkit-animation:spin 4s linear infinite;
        -moz-animation:spin 4s linear infinite;
        animation:spin 4s linear infinite;
    }
    @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
    @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
    @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

    .box,
    .dropdown-menu,
    .small-box {
        box-shadow: 
            0 4px 10px 0 rgba(0,0,0,0.2), 
            0 4px 20px 0 rgba(0,0,0,0.19);
    }
    .navbar {
        box-shadow: 
            4px 4px 10px 0 rgba(0,0,0,0.2), 
            4px 4px 20px 0 rgba(0,0,0,0.19);
    }
    .main-sidebar {
        box-shadow: 
            0 4px 10px 0 rgba(0,0,0,0.2), 
            0 4px 20px 0 rgba(0,0,0,0.19);
    }
    </style>
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            {{-- <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a> --}}

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation" style="background: linear-gradient(to right, #f39c12, {{ session('aturtgl') == 1 ? '#f39c12' : '#b7507b' }}) !important;">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">

                    <ul class="nav navbar-nav">
                        {{-- <li>
                            @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                            @else
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                        {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </li> --}}
                        <li class="user-menu">
                            <a class="hov">
                                <img src="{{ asset('storage/koperasi/logo/'.session('koperasi')->Logo) }}" class="user-image" alt="User Image">
                                <span class="hidden-xs">{{ session('koperasi')->Nama }}</span>
                            </a>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <img src="{{ asset('storage/koperasi/logo/'.session('koperasi')->Logo) }}" class="user-image" style="width:50px; height:50px">
                                        </div>
                                        <div class="col-xs-9">
                                            <div class="row">
                                                <div class="col-xs-12 text-left">
                                                    <a>{{ Auth::user()->name }}</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 text-left">
                                                    <a><i class="fa fa-id-card text-blue"></i> {{ Auth::user()->role->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('gantipwd.index') }}" class="btn btn-default btn-flat">Ganti Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Sign Out</a>
                                        <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                            @if(config('adminlte.logout_method'))
                                                {{ method_field(config('adminlte.logout_method')) }}
                                            @endif
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar" style="padding-top: 0px;">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <div class="user-panel">
                  <div class="pull-left image">
                    <img src="{{ asset('images/icon.png')}}" class="img-circle jos" alt="PanDana">
                  </div>
                  <div class="pull-left info">
                    <p style="font-size:24pt; color: #a28d6c;">PanDana</p>
                  </div>
                </div>

                {{-- <div class="user-panel" style="background-color: #e8e8e8;">
                    <div class="pull-left image">
                        <img src="{{ asset('storage/koperasi/logo/'.session('koperasi')->Logo) }}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{ session('koperasi')->Nama }}</p>
                        <a>{{ session('koperasi')->Alamat }}</a>
                    </div>
                </div> --}}

                <div class="sidebar-form">
                    <div class="input-group">
                        <input readonly type="text" class="form-control" value="{{Fungsi::bulanID(session('tgl'))}}">
                        <span class="input-group-btn">
                            <button type="button" data-toggle="modal" data-target="#modal-tanggal" title="Tutup Hari" class="btn btn-flat"><i class="fa fa-calendar"></i> <i class="fa fa-step-forward"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer no-print">
          <div class="pull-right hidden-xs">
            <b>Version</b> 2.0.0
          </div>
          <strong>Copyright © 2019 
          	<a href="https://pandana.id">Pandana</a> | 
          	<a href="http://halosbm.id">SBM</a>
          </strong>
        </footer>

    </div>
    <!-- ./wrapper -->
    @include('tanggal')
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/pnotify/pnotify.custom.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ajaxStart(function() { 
        Pace.restart(); 
    });

    PNotify.desktop.permission();
    if ({{ session()->has('notif') ? 1 : 0 }} === 1) {
        notifikasi({!! session('notif') !!});
    }

    if ({{ session()->has('kwitansi') ? 1 : 0 }} === 1) {
        var urlKw = "{{ session('kwitansi') }}";
        var kwWindow = window.open(urlKw, "", "width=1000,height=500");
    }

    $(document).ready(function () {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var tglpilih = "{{ date('Y-m-d', strtotime(session('tgl').' +1 day')) }}";
        var tglaktifid = "{{ date('d-m-Y', strtotime(session('tgl').' +1 day')) }}";
        var modeaturtgl = parseInt("{{session('aturtgl') ? session('aturtgl') : 0 }}");
        var tglMax = "{{ date('d-m-Y', strtotime(session('tgl').' +'.session('maxTutup').' day')) }}";
        var tglmin = tglaktifid;
        if (modeaturtgl == 1) {
            tglmin = null;
            tglMax = null;
        }
        $('#tanggal-buku').daterangepicker({
          singleDatePicker: true,
          showDropdowns: true,
          autoUpdateInput: true,
          startDate: tglaktifid,
          endDate: tglaktifid,
          maxDate: tglMax,
          minDate: tglmin,
          locale: {
            format: 'DD-MM-YYYY'
          },
        });

        $('#tanggal-buku').on('hide.daterangepicker', function(ev, picker) {
          tglpilih = picker.startDate.format('YYYY-MM-DD');
        });
        
        $('#btntanggal').click( function () {
            
            if (modeaturtgl == 1) {
                storetutuphari()
            }else{
                $.get("{{ route('tutupbuku.getpostingdata') }}", function(p){
                    if (p.tabungan > 0 || p.deposito > 0) {
                        alert('Tidak dapat melakukan Tutp Hari\nTabungan : ' + p.tabungan + ' Rekening,\nDeposito : ' + p.deposito + ' Rekening\nBelum di Posting');
                    }else{
                        storetutuphari()
                    }
                });
            }
        });

        function storetutuphari() {
            $.ajax({
                url: "{{ route('tutupbuku.update') }}",
                type: 'POST',
                data: {_token: CSRF_TOKEN, tglpilih:tglpilih},
                dataType: 'JSON',
                success: function (data, status) {
                    location.reload();
                }
            });
        }
    });
    </script>
    @stack('js')
    @yield('js')
@stop
