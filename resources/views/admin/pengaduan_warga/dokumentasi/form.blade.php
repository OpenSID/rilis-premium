@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Dokumentasi Pengaduan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_dokumentasi.dokumentasi', $pengaduan->id) }}">Dokumentasi Pengaduan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($form_action, 'class="form-horizontal" enctype="multipart/form-data" id="validasi"') !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-kembali-button judul="Kembali Ke Daftar Dokumentasi" url="pengaduan_dokumentasi/dokumentasi/{{ $pengaduan->id }}" />
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="jenis">Jenis Dokumentasi</label>
                                <div class="col-sm-7">
                                    <select class="form-control input-sm select2 required" id="jenis" name="jenis">
                                        <option value=''>-- Pilih Jenis Dokumentasi --</option>
                                        @foreach ($jenis as $key => $value)
                                            <option value="{{ $key }}" @selected($main && $main->jenis == $key)>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ($main && $main->gambar)
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="nama"></label>
                                    <div class="col-sm-7">
                                        <img class="attachment-img img-responsive img-circle" src="{{ to_base64(LOKASI_PENGADUAN . $main->gambar) }}" alt="Gambar Dokumentasi" width="200">
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="upload">Unggah Foto</label>
                                <div class="col-sm-7">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="file_path">
                                        <input id="file" type="file" class="hidden" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                        </span>
                                    </div>
                                    <span class="help-block"><code>(Kosongkan jika tidak ingin mengubah gambar. Format: jpg, jpeg, png, webp. Maksimal {{ \App\Http\Requests\Pengaduan\PengaduanDokumentasiRequest::batasUkuranMb() }} MB)</code></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan Tindakan</label>
                                <div class="col-sm-7">
                                    <textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" placeholder="Keterangan tindakan yang dilakukan">{{ $main->keterangan ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tampil_publik">Tampilkan di Halaman Publik</label>
                                <div class="col-sm-7">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="tampil_publik" id="tampil_publik" value="1" @checked($main?->tampil_publik)>
                                            Ya, foto dan keterangan ini dapat dilihat pelapor/masyarakat
                                        </label>
                                    </div>
                                    <span class="help-block"><code>Jangan centang jika foto memuat data pribadi, wajah, atau lokasi rumah warga.</code></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
