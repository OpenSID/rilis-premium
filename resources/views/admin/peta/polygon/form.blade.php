@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Tipe Area
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('polygon.index') }}"> Tipe Area</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validasi">
            @csrf
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => route('polygon.index-default'), 'label' => 'Tipe Area'])
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama</label>
                        <div class="col-sm-9">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="{{ PEMETAAN_NAMA_MAX_LENGTH }}" type="text" value="{{ old('nama', $polygon['nama'] ?? null) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Warna</label>
                        <div class="col-sm-5">
                            <div class="input-group my-colorpicker2">
                                <input type="text" id="color" name="color" class="form-control input-sm color required" placeholder="#FFFFFF" value="{{ old('color', $polygon['color'] ?? null) }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="enabled">Status</label>
                        <div class="col-sm-6">
                            <select name="enabled" id="enabled" class="form-control input-sm required">
                                @foreach (\App\Enums\AktifEnum::all() as $value => $label)
                                <option value="{{ $value }}" @selected(old('enabled', $polygon['enabled'] ?? null) == $value)>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.my-colorpicker2').colorpicker();
        })
    </script>
@endpush
