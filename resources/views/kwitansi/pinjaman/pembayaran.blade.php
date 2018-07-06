@extends('kwitansi.master')
@section('title', 'Kwitansi Pembayaran Pinjaman')
@section('content-header')
    <h4>Kwitansi</h4>
@stop
@section('content')
@php
    $jumlah = new Terbilang();
@endphp
    <table class="garis">
      <tr>
        <td width="20%">Telah Terima Dari</td><td> : {{ $data->pinjaman->NoNasabah." - ".$data->pinjaman->nasabah->NamaNasabah }}</td>
      </tr>
      <tr>
        <td>Guna Membayar</td><td> : Pinjaman <b>{{ $data->NoPinjaman }}</b> Milik Nasabah <b>{{ $data->pinjaman->nasabah->NamaNasabah }}</b></td>
      </tr>
      <tr>
        <td>Terbilang</td><td class="camel"> : <b>{{ $jumlah->terbilang($data->Jumlah) }} Rupiah</b></td>
      </tr>
      <tr>
        <td>Jumlah</td><td> : <b>Rp {{ number_format($data->Jumlah,0,",",".") }}</b></td>
      </tr>
    </table>
@stop
@section('yang', 'Menerima')