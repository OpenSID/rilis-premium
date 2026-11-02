@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-kembali-button judul="Kembali Ke Daftar Pengaduan" url="pengaduan_admin" />
        </div>
        {!! form_open($form_action, 'class="form-horizontal" id="validasi" enctype="multipart/form-data"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="status">Status</label>
                <div class="col-sm-9">
                    <select class="form-control input-sm required" required name="status" {{ $pengaduan_warga->status == '3' ? 'disabled' : '' }}>
                        <option value="">Pilih Status Pengaduan</option>
                        <option value="2" {{ selected(2, $pengaduan_warga->status) }}>Sedang Diproses</option>
                        <option value="3" {{ selected(3, $pengaduan_warga->status) }}>Selesai Diproses</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="isi">Tanggapan</label>
                <div class="col-sm-9">
                    <textarea
                        name="isi"
                        class="form-control input-sm required"
                        required
                        maxlength="300"
                        placeholder="Masukkan Tanggapan"
                        rows="3"
                        style="resize:none;"
                    ></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="gambar">Foto Dokumentasi</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control input-sm" id="gambar" name="gambar[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <span class="help-block">
                        <code>Opsional, maksimal 5 foto ({{ \App\Http\Requests\Pengaduan\PengaduanDokumentasiRequest::batasUkuranMb() }} MB per foto). Format: jpg, jpeg, png, webp.</code><br>
                        Foto disimpan sebagai dokumentasi <b>Proses Penanganan</b>, atau <b>Sesudah Penanganan</b> jika status Selesai Diproses, dengan keterangan dari isi tanggapan.
                        Kelola, ubah, atau tambah foto <i>Sebelum Penanganan</i> di
                        <a href="{{ ci_route('pengaduan_dokumentasi.dokumentasi', $pengaduan_warga->id) }}">halaman Dokumentasi Penyelesaian</a>.
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="tampil_publik" value="1">
                            Tampilkan foto di halaman publik
                        </label>
                    </div>
                    <span class="help-block"><code>Jangan centang jika foto memuat data pribadi, wajah, atau lokasi rumah warga.</code></span>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
