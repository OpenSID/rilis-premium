@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>Penambahan Pemudik Covid-19</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('covid19.index') }}"> Daftar Pemudik Saat Covid-19</a></li>
    <li class="active">Penambahan Pemudik Covid-19</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => route('covid19.index'), 'label' => 'Daftar Pemudik Saat Covid-19'])
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">Tambahkan Warga Pemudik</h3>
        </div>
        <div class="box-body">
            <form id="main" name="main" method="POST" class="form-horizontal">
                @csrf
                <div class="form-group">
                    <label class="col-sm-3 control-label required" for="terdata">NIK / Nama</label>
                    <div class="col-sm-4">
                        <select class="form-control select2 required" id="covid_pemudik" name="terdata" onchange="formAction('main')" style="width: 100%;">
                            <option value="">-- Silakan Masukan NIK / Nama--</option>
                            @if ($individu)
                                <option value="{{ $individu->id }}" selected>{{ 'NIK: ' . $individu->nik . ' - ' . $individu->nama . ' - ' . $individu->alamat_wilayah }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <a href="#" class="btn btn-social btn-block btn-success btn-sm" data-toggle="modal" data-target="#add-warga">
                            <i class="fa fa-plus"></i>
                            Tambah Penduduk Non Domisili
                        </a>
                        <span id="data_h_plus_msg" class="help-block">
                            <code>Untuk penduduk pendatang/tidak tetap. Masukkan data di sini.</code>
                        </span>
                    </div>
                </div>
            </form>
            <div id="form-melengkapi-data-peserta">
                <form id="validasi" action="{{ $form_action }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-8">
                            <input type="hidden" name="id_terdata" value="{{ $individu?->id }}" class="form-control input-sm required">
                        </div>
                    </div>
                    @if ($individu)
                        @include('admin.covid19.konfirmasi_pemudik')
                    @endif

                    @include('admin.covid19.form_isian_pemudik')
                </form>
            </div>
            <div class="box-footer">
                <div class="col-xs-12">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right" onclick="$('#validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-warga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus text-green"></i> Tambah Penduduk Pendatang / Tidak Tetap</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @include('admin.covid19.form_isian_penduduk')
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-success btn-sm" onclick="$('#form_penduduk').submit();"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.covid19.js_isian_pemudik')
    @include('admin.covid19.js_isian_penduduk')
    @if (old('nik') !== null)
        <script>
            $(document).ready(function() {
                $('#add-warga').modal('show');
            });
        </script>
    @endif
@endpush
