@extends('kwitansi.master')
@section('title', 'Bukti Kas Keluar')
@section('content-header')
    <h4>Bukti Kas Keluar</h4>
@stop
@section('content')
@php
    $jumlah = new Terbilang();
@endphp
    <table class="garis">
      <tr>
        <td width="20%">Bukti</td><td> : {{ $data->BuktiTransaksi }}</td>
      </tr>
      <tr>
        <td>Keterangan</td><td> : {{ $data->Keterangan }}</td>
      </tr>
      <tr>
        <td>Terbilang</td><td class="camel"> : <b>{{ $jumlah->terbilang($data->Nominal) }} Rupiah</b></td>
      </tr>
      <tr>
        <td>Jumlah</td><td> : <b>Rp {{ number_format($data->Nominal,0,",",".") }}</b></td>
      </tr>
    </table>
@stop
@section('yang', 'Mengeluarkan')