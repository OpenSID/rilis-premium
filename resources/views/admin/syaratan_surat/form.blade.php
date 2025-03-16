@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Dokumen Persyaratan Surat
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_mohon') }}">Dokumen Persyaratan Surat</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('surat_mohon'), 'label' => 'Dokumen Persyaratan Surat'])
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group @error('ref_syarat_nama') has-error @enderror">
                    <label class="col-sm-3 control-label">Nama Dokumen</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm nomor_sk" id="ref_syarat_nama" name="ref_syarat_nama" placeholder="Nama Dokumen" value="{{ old('ref_syarat_nama', $ref_syarat_surat->ref_syarat_nama) }}" />
                        @error('ref_syarat_nama')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
