@extends('adminlte::page')

@section('title', 'Tambah - Kolektor - Masterdata')

@section('content_header')
<h1>Tambah Kolektor</h1>
<ol class="breadcrumb">
    <li><i class="fa fa-database"></i> Masterdata</li>
    <li><a href="{{ route('m.kolektor.index') }}"> Kolektor</a></li>
    <li class="active"> Tambah</li>
</ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <form role="form" autocomplete="off" data-toggle="validator" action="{{ route('m.kolektor.update', ['kolektor' => $NoKolektor]) }}" method="post">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <div class="box-header with-border">
                <h3 class="box-title">Form Data Kolektor</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">No. Kolektor</label>
                            <input readonly type="text" class="form-control" name="NoKolektor" value="{{ $NoKolektor }}" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nama Kolektor</label>
                            <input type="text" class="form-control" name="Nama" placeholder="Nama Kolektor" value="{{ $Nama }}" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">No. Telepon</label>
                            <input type="text" class="form-control" name="NoTelp" placeholder="Nomor Telepon" required value="{{ $NoTelp }}">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Alamat</label>
                            <textarea class="form-control" style="resize: vertical;" rows="3" name="Alamat" placeholder="Alamat" required>{{ $Alamat }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Username</label>
                            <input type="text" class="form-control" readonly name="username" value="{{ $user['username'] }}" placeholder="Username" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Role</label>
                            <select class="form-control" name="role_id" required>
                                <option {{ $user['role']['id'] == 3 ? 'selected' : '' }} value="3">Kolektor Tabungan</option>
                                <option {{ $user['role']['id'] == 4 ? 'selected' : '' }} value="4">Kolektor Pinjaman</option>
                            </select>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop