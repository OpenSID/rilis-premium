@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Kontak {{ $navigasi }}
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ $navigasi === 'Penduduk' ? route('daftar_kontak.penduduk') : route('daftar_kontak.index') }}">Kontak {{ $navigasi }}</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.daftar_kontak.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => $navigasi === 'Penduduk' ? route('daftar_kontak.penduduk') : route('daftar_kontak.index'), 'label' => $navigasi])
                </div>
                <form action="{{ $formAction }}" method="POST" class="form-horizontal" id="validasi">
                    @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm nama required" name="nama" placeholder="OpenDesa" value="{{ old('nama', $daftarKontak?->nama) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cara Hubung Warga</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm required" id="hubung_warga" name="hubung_warga" onchange="jenis(this.value);">
                                <option value="">Pilih Cara Hubungi</option>
                                @foreach (['SMS', 'Email', 'Telegram'] as $value)
                                    @continue((bool) setting('aktifkan_sms') === false && $value === 'SMS')
                                    <option value="{{ $value }}" @selected(old('hubung_warga', $daftarKontak?->hubung_warga) === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telepon</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm number" id="telepon" name="telepon" placeholder="081234567890" value="{{ old('telepon', $daftarKontak?->telepon) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm email" id="email" name="email" placeholder="opendesa@mail.com" value="{{ old('email', $daftarKontak?->email) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telegram</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm number" id="telegram" name="telegram" placeholder="2076476442" value="{{ old('telegram', $daftarKontak?->telegram) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control input-sm" rows="5" placeholder="Keterangan lainnya...">{{ old('keterangan', $daftarKontak?->keterangan) }}</textarea>
                        </div>
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
            $('#hubung_warga').change();
        });

        function jenis(tipe) {
            $('#telepon').removeClass('required');
            $('#email').removeClass('required');
            $('#telegram').removeClass('required');

            if (tipe == 'SMS') {
                $('#telepon').addClass('required');
            } else if (tipe == 'Email') {
                $('#email').addClass('required');
            } else if (tipe == 'Telegram') {
                $('#telegram').addClass('required');
            }
        }
    </script>
@endpush
