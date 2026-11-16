@extends('admin.layouts.index')

@section('title')
    <h1>{{ $nama }}</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('pengaturan') }}">Pengaturan</a></li>
    <li class="active">{{ $nama }}</li>
@endsection

@section('content')
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title"><strong>Modul {{ $nama }} belum diaktifkan</strong></h3>
        </div>
        <div class="box-body">
            <div class="alert alert-warning mb-0">
                <p><i class="fa fa-exclamation-triangle"></i> Fitur {{ $nama }} belum dapat digunakan karena modulnya belum diaktifkan.</p>
                <p class="mb-0">
                    Aktifkan terlebih dahulu modul <strong>{{ $nama }}</strong> melalui menu
                    <a href="{{ url('modul') }}"><strong>Pengaturan &gt; Modul</strong></a>.
                    Pengaktifan modul hanya dapat dilakukan oleh pengguna dengan peran <strong>Super Admin</strong>.
                </p>
            </div>
        </div>
    </div>
@endsection
