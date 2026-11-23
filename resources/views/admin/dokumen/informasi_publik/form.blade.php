@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>Pengaturan
        {{ $kat_nama }} Di
        {{ ucwords(setting('sebutan_desa')) }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="#" onclick="window.history.back()"> Daftar
            {{ $kat_nama }}
        </a></li>
    <li class="active">Pengaturan
        {{ $kat_nama }}
    </li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal" id="validasi">
        @csrf
        <div class="box-header with-border">
            <x-kembali-button judul="Kembali ke Daftar {{ $kat_nama }} Di {{ ucwords(setting('sebutan_desa')) }}" url="#" onclick="window.history.back()" />

        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm nomor_sk required" type="text" maxlength="200" value="{{ ($dokumen['nama'] ?? null) }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Tipe Dokumen</label>
                <div class="col-sm-6">
                    <select name="tipe" id="tipe" class="form-control input-sm required">
                        <option value="1" @selected(($dokumen['tipe'] ?? null) == 1)>File</option>
                        <option value="2" @selected(($dokumen['tipe'] ?? null) == 2)>URL</option>
                    </select>
                </div>
            </div>
            <div id="d-unggahn" style="display: {{ ($dokumen['tipe'] ?? null) == 2 ? 'none' : '' }};">
                @if (($dokumen['satuan'] ?? null))
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Dokumen</label>
                        <div class="col-sm-4">
                            <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas {{ ($dokumen['nomor_surat'] ?? null) }}" data-url="{{ route('dokumen.tampilkan_berkas', [$dokumen['id'] ?? 0, 0, 1]) }}"></i>

                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label col-sm-4" for="upload">Unggah Dokumen</label>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control {{ ($dokumen['tipe'] ?? null) == 2 || ($dokumen['tipe'] ?? null) ? '' : 'required' }}" id="file_path" name="satuan">
                            <input id="file" type="file" class="hidden" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i>
                                    Browse</button>
                            </span>
                        </div>
                        @if ($dokumen)
                            <p class="small">(Kosongkan jika tidak ingin mengubah dokumen)</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Retensi Dokumen</label>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <input
                                class="form-control input-sm"
                                placeholder="Jumlah (0-31)"
                                type="number"
                                name="retensi_number"
                                id="retensi_number"
                                min="0"
                                max="31"
                                value="{{ $dokumen['retensi_number'] ?? 0 }}"
                                @if ($readonly) disabled @endif
                            >
                            <label class="help-block error" style="display: none"></label>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control input-sm" name="retensi_unit" id="retensi_unit" @if ($readonly) disabled @endif>
                                <option value="hari" {{ isset($dokumen) && ($dokumen['retensi_unit'] ?? null) == 'hari' ? 'selected' : '' }}>Hari
                                </option>
                                <option value="minggu" {{ isset($dokumen) && ($dokumen['retensi_unit'] ?? null) == 'minggu' ? 'selected' : '' }}>Minggu
                                </option>
                                <option value="bulan" {{ isset($dokumen) && ($dokumen['retensi_unit'] ?? null) == 'bulan' ? 'selected' : '' }}>Bulan
                                </option>
                                <option value="tahun" {{ isset($dokumen) && ($dokumen['retensi_unit'] ?? null) == 'tahun' ? 'selected' : '' }}>Tahun
                                </option>
                            </select>
                        </div>
                    </div>
                    <label class="control-label text-danger">Nilai harus antara 0 hingga 31. Isi 0 jika tidak
                        digunakan.</label>
                </div>
            </div>
            <div id="d-url" class="form-group" style="display: {{ ($dokumen['tipe'] ?? null) == 2 ? '' : 'none' }};">
                <label class="control-label col-sm-4" for="nama">Link/URL Dokumen</label>
                <div class="col-sm-6">
                    <input id="url" name="url" class="form-control input-sm {{ ($dokumen['tipe'] ?? null) == 2 ? 'required' : '' }}" type="text" value="{{ ($dokumen['url'] ?? null) }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Kategori Informasi Publik</label>
                <div class="col-sm-6">
                    <select name="kategori_info_publik" class="form-control select2 input-sm required">
                        <option value="">Pilih Kategori Informasi Publik</option>
                        @foreach ($list_kategori_publik as $key => $value)
                            <option value="{{ $key }}" @selected(($dokumen['kategori_info_publik'] ?? null) == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Keterangan</label>
                <div class="col-sm-6">
                    <textarea
                        name="keterangan"
                        class="form-control input-sm"
                        maxlength="300"
                        placeholder="Keterangan"
                        rows="3"
                        style="resize:none;"
                        @if ($readonly) disabled @endif
                    >{{ $dokumen['keterangan'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Tahun</label>
                <div class="col-sm-6">
                    <input name="tahun" maxlength="4" class="form-control input-sm number required" type="text" placeholder="Contoh: 2019" value="{{ $dokumen['tahun'] ?? '' }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="tanggal">Tanggal Terbit</label>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon" style="border-radius: 5px 0 0 5px">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input
                            type="text"
                            name="published_at"
                            value="{{ ($dokumen['published_at'] ?? null) ? tgl_indo_out(($dokumen['published_at'] ?? null)) : date('d-m-Y') }}"
                            class="form-control input-sm datepicker required"
                            title="Tanggal Terbit"
                            placeholder="Masukan Tanggal Terbit"
                            style="border-radius: 0 5px 5px 0"
                            @disabled($readonly)
                            required
                        >
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="status">Status Terbit</label>
                <div class="btn-group col-sm-6" data-toggle="buttons">
                    <label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(!empty($dokumen) ? ($dokumen['status'] ?? null) == 1 : true)" @disabled($readonly)>
                        <input type="radio" name="status" class="form-check-input" value="1" @checked(!empty($dokumen) ? ($dokumen['status'] ?? null) == 1 : true) @if ($readonly) disabled @endif>
                        Ya
                    </label>
                    <label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(!empty($dokumen) ? ($dokumen['status'] ?? null) == 0 : false)" @disabled($readonly)>
                        <input type="radio" name="status" class="form-check-input" value="0" @checked(!empty($dokumen) ? ($dokumen['status'] ?? null) == 0 : false) @if ($readonly) disabled @endif>
                        Tidak
                    </label>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $('#tipe').on('change', function() {
            if (this.value == 1) {
                $('#d-unggahn').show();
                $('#d-url').hide();
                $("#file_path").addClass("required");
                $("#url").removeClass("required");
            } else {
                $('#d-unggahn').hide();
                $('#d-url').show();
                $("#file_path").removeClass("required");
                $("#url").addClass("required");
            }
        });
    </script>
@endpush
