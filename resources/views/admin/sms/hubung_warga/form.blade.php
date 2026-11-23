@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Hubung Warga
        <small>Kirim Pesan Grup</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sms.index') }}">Hubung Warga</a></li>
    <li class="active">Kirim Pesan Grup</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.sms.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => route('sms.arsip'), 'label' => 'Hubung Warga'])

                </div>
                <form action="{{ $formAction }}" method="POST" id="validasi">
                    @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="nama">Grup Kontak</label>
                        <select class="form-control input-sm select2 required" id="id_grup" name="id_grup">
                            <option value="">Pilih Grup Kontak</option>
                            @foreach ($grupKontak as $grup)
                                <option value="{{ $grup->id_grup }}" @selected(old('id_grup') == $grup->id_grup)>{{ $grup->nama_grup }} ( {{ $grup->anggota_count }} Anggota )</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama">Subjek Pesan</label>
                        <input name="subjek" class="form-control input-sm required" placeholder="Subjek Pesan" maxlength="100" value="{{ old('subjek') }}" />
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Pesan</label>
                        <textarea name="isi" class="form-control input-sm required" placeholder="Isi Pesan" rows="5">{{ old('isi') }}</textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
