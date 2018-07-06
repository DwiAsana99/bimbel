@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
@stop

@section('body')
  <div class="wrapper">
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow">404</h2>
        <div class="error-content">
          <br>
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Halaman Tidak Ditemukan.</h3>
          <p>
            Sistem tidak bisa menemukan halaman yang anda cari.
            Anda bisa <a href="{{ URL('/') }}">Kembali ke Dashboard</a>.
          </p>
        </div>
      </div>
    </section>
  </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
@stop
