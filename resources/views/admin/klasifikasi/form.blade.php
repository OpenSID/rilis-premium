@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1> Klasifikasi Surat
        <small>{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('klasifikasi.index') }}">Klasifikasi Surat </a></li>
    <li class="active">{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <form action="{{ $form_action }}" method="POST" id="validasi" class="form-horizontal">
        @csrf
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => route('klasifikasi.index'), 'label' => 'Daftar Klasifikasi Surat'])
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="kode">Kode</label>
                <div class="col-sm-6">
                    <input name="kode" class="form-control input-sm alfanumerik_titik required" type="text" placeholder="Kode" value="{{ old('kode', $data?->kode) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Nama</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm required" type="text" placeholder="Nama" value="{{ old('nama', $data?->nama) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="uraian">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="uraian" class="form-control input-sm required" placeholder="Keterangan">{{ old('uraian', $data?->uraian) }}</textarea>
                </div>
            </div>
        </div>
        <div class='box-footer'>
            <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
    </form>
@endsection
