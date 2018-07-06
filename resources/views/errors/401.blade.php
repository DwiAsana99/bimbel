@extends('adminlte::page')

@section('title', 'Akses Ditolak')

{{-- @section('content_header')
  <h1>401 Error Page</h1>
@stop --}}

@section('content')
  <div class="error-page">
    <h1 class="headline text-red">401</h1>
      <div class="error-content">
        <br>
        <h3><i class="fa fa-warning text-red"></i> Oops! Anda tidak bisa mengakses halaman ini.</h3>
          <p>
            Sistem tidak memberikan ijin untuk mengakses halaman ini.
            Anda bisa <a href="{{ URL('/') }}">Kembali ke Dashboard</a>.
          </p>
      </div>
  </div>
@stop
