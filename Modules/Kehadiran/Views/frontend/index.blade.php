@extends('kehadiran::frontend.layouts.index')

@section('content')

    <div class="row vertical-align" style="background-color: #ffffff">
        <div class="col-sm-8 hidden-xs" style="padding: 0px;">
            @include('kehadiran::frontend.left')
        </div>
        <div class="col-sm-4 col-xm-4">
            <div class="row">
                <div class="col-xm-12 text-center" style="padding-top:100px; padding-left: 25px; padding-right: 25px;">
                    <img class="user-image" src="{{ AmbilFoto($masuk['foto'], '', $masuk['sex']) }}" alt="Foto Penduduk" height="120px">
                    @if ($success != 0)
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <strong>{{ 'Rekam Kehadiran ' . ($kehadiran ? 'Masuk' : 'Keluar') . ' Berhasil' }}</strong>
                        </div>
                        <div class="alert alert-warning alert-dismissible fade in" role="alert">
                            Halaman akan keluar otomatis dalam 5 detik
                        </div>
                    @endif
                </div>
                <div class="col-xm-12 text-center">
                    <h2>{{ $masuk['pamong_nama'] }}</h2>
                    <h4>{{ $masuk['jabatan'] }}</h4>
                </div>
                <div class="col-xm-12" id="pesan-kehadiran" style="padding-left: 25px; padding-right: 25px;"></div>
                <div class="col-xm-12 text-center" style="padding-left: 25px; padding-right: 25px;">
                    {!! form_open_multipart(ci_route('kehadiran.check-in-out'), 'name="check" id="validasi"') !!}
                    @if (!$success && $kamera)
                        <div class="form-group" id="kamera-kehadiran">
                            <label>Bukti Foto Kehadiran {{ $kehadiran ? 'Keluar' : 'Masuk' }}</label>
                            <div class="thumbnail" style="margin-bottom: 5px;">
                                <video id="kamera-video" autoplay playsinline muted style="width: 100%; max-height: 240px; transform: scaleX(-1);"></video>
                                <img id="kamera-hasil" alt="Foto Kehadiran" style="width: 100%; display: none;">
                            </div>
                            <canvas id="kamera-kanvas" style="display: none;"></canvas>
                            <input type="hidden" name="foto" id="foto">
                            <button type="button" id="ambil-foto" class="btn btn-info btn-block btn-sm"><i class="fa fa-camera"></i> Ambil Foto</button>
                            <button type="button" id="ulang-foto" class="btn btn-default btn-block btn-sm" style="display: none;"><i class="fa fa-refresh"></i> Ulangi Foto</button>
                            <p id="kamera-pesan" class="text-danger" style="display: none; margin-top: 5px;"></p>
                        </div>
                    @endif
                    <div class="checkbox">
                        @if (!$kehadiran && !$success)
                            <input type="hidden" name="status_kehadiran" value="hadir">
                            <button id="hadir" class="btn btn-success btn-small">Rekam Masuk</button>
                        @endif

                        @if ($kehadiran && !$success)
                            <div class="form-group">
                                <select name="status_kehadiran" id="status_kehadiran" class="form-control">
                                    @foreach ($pilihan as $nilai => $item)
                                        <option value="{{ $nilai }}" data-label="{{ $item['label_keterangan'] }}" data-wajib="{{ $item['wajib_keterangan'] ? 1 : 0 }}">{{ $item['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-left">
                                <label for="keterangan"><span id="label-keterangan">Keterangan</span> <small id="sifat-keterangan" class="text-muted">(Opsional)</small></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="2" maxlength="{{ $maks_keterangan }}" placeholder="Masukkan keterangan"></textarea>
                                <small class="text-muted">Maksimal {{ $maks_keterangan }} karakter</small>
                            </div>
                            <button id="keluar" class="btn btn-danger btn-small">Keluar</button>
                        @endif
                    </div>
                    </form>
                </div>
                <div class="col-xm-12 text-center">
                    <a class="btn bg-olive margin" href="{{ ci_route('kehadiran.logout') }}">Selesai</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            var success = "{{ $success }}";
            var url = "{{ ci_route('kehadiran.logout') }}";

            if (success) {
                setTimeout(function() {
                    location.href = url;
                }, 5000);
            }

            // Keterangan keluar: label & sifat wajib mengikuti pilihan (dinas luar -> tujuan, izin -> alasan)
            function aturKeterangan() {
                var opsi = $('#status_kehadiran option:selected');
                var wajib = opsi.data('wajib') == 1;

                $('#label-keterangan').text(opsi.data('label') || 'Keterangan');
                $('#sifat-keterangan').text(wajib ? '*Wajib diisi' : '(Opsional)').toggleClass('text-danger', wajib).toggleClass('text-muted', !wajib);
                $('#keterangan').prop('required', wajib);
            }

            $('#status_kehadiran').on('change', aturKeterangan);
            aturKeterangan();

            // Kamera: foto diambil langsung, tidak bisa unggah dari galeri
            var kamera = $('#kamera-kehadiran').length > 0;
            var stream = null;

            function pesanKamera(pesan) {
                $('#kamera-pesan').text(pesan).show();
            }

            function mulaiKamera() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    pesanKamera('Kamera tidak tersedia. Pastikan halaman dibuka melalui HTTPS dan perangkat memiliki kamera.');
                    $('#ambil-foto').prop('disabled', true);

                    return;
                }

                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user'
                    },
                    audio: false
                }).then(function(media) {
                    stream = media;
                    $('#kamera-video')[0].srcObject = media;
                    $('#kamera-pesan').hide();
                }).catch(function() {
                    pesanKamera('Akses kamera ditolak atau kamera tidak ditemukan. Izinkan akses kamera pada peramban.');
                    $('#ambil-foto').prop('disabled', true);
                });
            }

            function hentikanKamera() {
                if (stream) {
                    stream.getTracks().forEach(function(track) {
                        track.stop();
                    });
                    stream = null;
                }
            }

            if (kamera) {
                mulaiKamera();

                $('#ambil-foto').on('click', function() {
                    var video = $('#kamera-video')[0];

                    if (!stream || !video.videoWidth) {
                        pesanKamera('Kamera belum siap, silakan coba lagi.');

                        return;
                    }

                    var lebar = Math.min(video.videoWidth, 640);
                    var tinggi = Math.round(video.videoHeight * lebar / video.videoWidth);
                    var kanvas = $('#kamera-kanvas')[0];

                    kanvas.width = lebar;
                    kanvas.height = tinggi;
                    kanvas.getContext('2d').drawImage(video, 0, 0, lebar, tinggi);

                    var foto = kanvas.toDataURL('image/jpeg', 0.8);

                    $('#foto').val(foto);
                    $('#kamera-hasil').attr('src', foto).show();
                    $('#kamera-video').hide();
                    $('#ambil-foto').hide();
                    $('#ulang-foto').show();
                    $('#kamera-pesan').hide();
                    hentikanKamera();
                });

                $('#ulang-foto').on('click', function() {
                    $('#foto').val('');
                    $('#kamera-hasil').hide().attr('src', '');
                    $('#kamera-video').show();
                    $('#ulang-foto').hide();
                    $('#ambil-foto').show();
                    mulaiKamera();
                });
            }

            var waktu = "{{ $kehadiran->jam_masuk ?? '' }}";
            var sekarang = "{{ date('H:i') }}";

            function tampilPesan(tipe, pesan) {
                $('#pesan-kehadiran').append($('<div>', {
                    'class': 'alert alert-' + tipe,
                    role: 'alert'
                }).text(pesan));
            }

            // Rekam kehadiran via AJAX (POST + CSRF dari anti-csrf.js), respons JSON
            $('form[name="check"]').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);

                $('#pesan-kehadiran').empty();

                if (kamera && !$('#foto').val()) {
                    pesanKamera('Silakan ambil foto terlebih dahulu.');

                    return false;
                }

                if ($('#keterangan').prop('required') && !$.trim($('#keterangan').val())) {
                    tampilPesan('danger', $('#label-keterangan').text() + ' wajib diisi.');
                    $('#keterangan').focus();

                    return false;
                }

                if ($('#keluar').length && waktu >= sekarang && !confirm('Anda masuk kurang dari 1 menit, apakah Anda yakin ingin keluar?')) {
                    return false;
                }

                var data = {
                    status_kehadiran: form.find('[name="status_kehadiran"]').val()
                };

                if ($('#keterangan').length) {
                    data.keterangan = $('#keterangan').val();
                }

                if ($('#foto').val()) {
                    data.foto = $('#foto').val();
                }

                // Cegah klik ganda selama permintaan berjalan
                var tombol = form.find('#hadir, #keluar');

                tombol.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                }).done(function(res) {
                    hentikanKamera();
                    form.hide();
                    tampilPesan('success', res.message);
                    tampilPesan('warning', 'Halaman akan keluar otomatis dalam 5 detik');

                    setTimeout(function() {
                        location.href = res.redirect || url;
                    }, 5000);
                }).fail(function(xhr) {
                    tombol.prop('disabled', false);

                    var res = xhr.responseJSON || {};
                    var pesan = res.errors ? $.map(res.errors, function(item) {
                        return item[0];
                    }).join(' ') : (res.message || 'Terjadi kesalahan, silakan coba lagi.');

                    tampilPesan('danger', pesan);

                    // Sesi berakhir / gawai tidak terdaftar / di luar jam kerja
                    if (xhr.status === 403) {
                        setTimeout(function() {
                            location.href = url;
                        }, 3000);
                    }
                });

                return false;
            });
        });
    </script>
@endpush
