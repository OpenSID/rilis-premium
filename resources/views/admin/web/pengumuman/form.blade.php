@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        Pengumuman
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pengumuman.index') }}">Pengumuman</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-info">
                    <div class="box-header with-border">
                        @include('admin.layouts.components.tombol_kembali', ['url' => route('pengumuman.index'), 'label' => 'Pengumuman'])
                    </div>
                    <div class="box-body">
                        <div class="form-group @error('judul') has-error @enderror">
                            <label class="control-label" for="judul">Judul</label>
                            <input type="text" class="form-control input-sm required" id="judul" name="judul" minlength="3" maxlength="100" placeholder="Judul pengumuman" value="{{ old('judul', $data?->judul) }}" />
                            @error('judul')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group @error('keterangan') has-error @enderror">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <textarea class="form-control input-sm" id="keterangan" name="keterangan" rows="5" maxlength="1000" placeholder="Isi pengumuman (wajib jika tidak ada gambar)" style="resize: vertical;">{{ old('keterangan', $data?->keterangan) }}</textarea>
                            @error('keterangan')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group @error('tautan') has-error @enderror">
                                    <label class="control-label" for="tautan">Tautan</label>
                                    <input type="text" class="form-control input-sm url" id="tautan" name="tautan" maxlength="255" placeholder="Contoh: https://desa.id/artikel/publikasi-statistik" value="{{ old('tautan', $data?->tautan) }}" />
                                    <span class="help-block small text-red">Opsional. Tujuan saat gambar atau tombol pada popup diklik, misalnya publikasi statistik atau media sosial desa.</span>
                                    @error('tautan')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group @error('judul_tautan') has-error @enderror">
                                    <label class="control-label" for="judul_tautan">Teks Tombol</label>
                                    <input type="text" class="form-control input-sm" id="judul_tautan" name="judul_tautan" maxlength="50" placeholder="Contoh: Lihat Selengkapnya" value="{{ old('judul_tautan', $data?->judul_tautan) }}" />
                                    <span class="help-block small text-red">Opsional. Jika kosong, tombol tidak ditampilkan.</span>
                                    @error('judul_tautan')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group @error('tgl_mulai') has-error @enderror">
                                    <label class="control-label" for="tgl_mulai_tayang">Tanggal Mulai Tayang</label>
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control input-sm datepicker periode_tayang" id="tgl_mulai_tayang" name="tgl_mulai" placeholder="Tanggal mulai" autocomplete="off" value="{{ old('tgl_mulai', $data?->tgl_mulai?->format('d-m-Y')) }}" />
                                    </div>
                                    @error('tgl_mulai')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group @error('tgl_selesai') has-error @enderror">
                                    <label class="control-label" for="tgl_selesai_tayang">Tanggal Selesai Tayang</label>
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        <input type="text" class="form-control input-sm datepicker periode_tayang" id="tgl_selesai_tayang" name="tgl_selesai" placeholder="Tanggal selesai" autocomplete="off" value="{{ old('tgl_selesai', $data?->tgl_selesai?->format('d-m-Y')) }}" />
                                    </div>
                                    @error('tgl_selesai')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group @error('status') has-error @enderror">
                                    <label class="control-label" for="status">Status</label>
                                    <select class="form-control input-sm select2" id="status" name="status">
                                        @foreach ($daftar_status as $key => $label)
                                            <option value="{{ $key }}" @selected((string) old('status', $data?->status ?? \App\Enums\AktifEnum::AKTIF) === (string) $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <span class="help-block small text-red">Periode tayang opsional. Kosongkan jika pengumuman tampil tanpa batas waktu.</span>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Gambar</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group preview-img @error('gambar') has-error @enderror">
                            <img class="img-responsive" src="{{ $data?->url_gambar ?? asset('images/404-image-not-found.jpg') }}" alt="Gambar Pengumuman">
                            <div class="input-group input-group-sm" style="margin-top: 10px;">
                                <input type="text" class="form-control file-path" placeholder="Pilih gambar">
                                <input type="file" class="hidden file-input" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info file-browser"><i class="fa fa-search"></i></button>
                                </span>
                                @if ($data?->url_gambar)
                                    <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                                        <input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_gambar" value="1" @checked(old('hapus_gambar'))>
                                    </span>
                                @endif
                            </div>
                            @error('gambar')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                            <span class="help-block small text-red">
                                Format jpg, jpeg, png, atau webp, maksimal {{ round(\App\Actions\Pengumuman\UnggahGambarPengumuman::batasUkuranKb() / 1024, 1) }} MB.
                                Disimpan sebagai webp dengan sisi terpanjang maksimal {{ \App\Actions\Pengumuman\UnggahGambarPengumuman::UKURAN_MAKSIMAL }} piksel.
                                @if ($data?->url_gambar)
                                    Pilih gambar baru untuk mengganti, atau centang kotak merah untuk menghapus.
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @include('admin.layouts.components.validasi_form')
    <script>
        $(function() {
            var $mulai = $('#tgl_mulai_tayang');
            var $selesai = $('#tgl_selesai_tayang');

            function keTanggal(nilai) {
                var bagian = (nilai || '').split('-');

                return bagian.length === 3 ? new Date(bagian[2], bagian[1] - 1, bagian[0]) : null;
            }

            // Tanggal mulai tidak boleh setelah tanggal selesai (berlaku juga jika diketik manual).
            $.validator.addMethod('periode_tayang', function() {
                var mulai = keTanggal($mulai.val());
                var selesai = keTanggal($selesai.val());

                return !mulai || !selesai || mulai <= selesai;
            }, 'Tanggal mulai tayang tidak boleh setelah tanggal selesai tayang.');

            // Batasi pilihan di datepicker supaya rentang tidak terbalik.
            function batasiPilihan() {
                $selesai.datepicker('setStartDate', keTanggal($mulai.val()) || false);
                $mulai.datepicker('setEndDate', keTanggal($selesai.val()) || false);
            }

            $mulai.add($selesai).on('changeDate clearDate change', function() {
                batasiPilihan();

                if ($mulai.val() && $selesai.val()) {
                    $mulai.valid();
                    $selesai.valid();
                }
            });

            batasiPilihan();
        });
    </script>
@endpush
