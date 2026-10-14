@extends('admin.layouts.index')

@section('title')
    <h1>Rotasi AppKey</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('pengaturan') }}">Pengaturan</a></li>
    <li class="active">Rotasi AppKey</li>
@endsection

@section('content')
    {{-- Kotak Edukasi & Penjelasan Fitur --}}
    <div class="box box-solid box-default" style="border-top: 3px solid #3c8dbc; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
        <div class="box-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-12">
                    <h4 style="margin-top: 0; color: #3c8dbc; font-weight: 600;">
                        <i class="fa fa-info-circle"></i> Mengenal Fitur Rotasi Kunci Aplikasi (AppKey)
                    </h4>
                    <p class="text-muted" style="font-size: 13px; margin-bottom: 16px;">
                        <strong>AppKey</strong> adalah kunci rahasia utama yang digunakan OpenSID untuk mengamankan data penting desa melalui enkripsi. Fitur ini memungkinkan Super Admin mengganti kunci enkripsi lama dengan kunci baru secara otomatis, aman, dan menyeluruh.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6" style="margin-bottom: 10px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; min-height: 110px;">
                        <div style="font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 5px;">
                            <i class="fa fa-shield text-primary" style="margin-right: 5px;"></i> Apa yang Dilindungi?
                        </div>
                        <div style="font-size: 12px; color: #64748b; line-height: 1.5;">
                            Kata sandi basis data, berkas dokumen yang diunggah, token sesi masuk pengguna, serta data sensitif desa lainnya.
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6" style="margin-bottom: 10px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; min-height: 110px;">
                        <div style="font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 5px;">
                            <i class="fa fa-calendar-check-o text-warning" style="margin-right: 5px;"></i> Kapan Digunakan?
                        </div>
                        <div style="font-size: 12px; color: #64748b; line-height: 1.5;">
                            Saat dicurigai terjadi kebocoran kunci, pergantian administrator pengelola server, atau audit pemeliharaan berkala.
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12" style="margin-bottom: 10px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; min-height: 110px;">
                        <div style="font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 5px;">
                            <i class="fa fa-cogs text-success" style="margin-right: 5px;"></i> Alur Kerja Otomatis
                        </div>
                        <div style="font-size: 12px; color: #64748b; line-height: 1.5;">
                            Sistem otomatis membuat cadangan (<em>backup</em>), menerbitkan kunci baru, dan mengenkripsi ulang seluruh data lama.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Status Kunci & Perhatian --}}
        <div class="col-md-5">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-key text-primary"></i> Status Kunci Saat Ini</h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-striped table-hover" style="margin-bottom: 0;">
                        <tbody>
                            <tr>
                                <th style="width: 45%; vertical-align: middle;">Sidik Jari Key Aktif</th>
                                <td style="vertical-align: middle;">
                                    @if ($fingerprint)
                                        <span class="label label-info" style="font-family: monospace; font-size: 12px; letter-spacing: 1px; padding: 4px 8px;">
                                            {{ $fingerprint }}
                                        </span>
                                    @else
                                        <span class="label label-danger">Tidak Terbaca</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="vertical-align: middle;">Rotasi Terakhir</th>
                                <td style="vertical-align: middle;">
                                    @if ($lastRotation)
                                        <i class="fa fa-clock-o text-muted"></i> {{ $lastRotation->created_at?->translatedFormat('d F Y H:i') }}
                                    @else
                                        <span class="text-muted"><i class="fa fa-info-circle"></i> Belum pernah dirotasi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="vertical-align: middle;">Dilakukan Oleh</th>
                                <td style="vertical-align: middle;">
                                    @if ($pelakuLast)
                                        <span class="label label-default"><i class="fa fa-user"></i> {{ $pelakuLast }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($multiTenant)
                <div class="alert alert-warning" style="border-left: 4px solid #e08e0b;">
                    <h4><i class="fa fa-sitemap"></i> Deteksi Multi-Desa</h4>
                    <p style="font-size: 13px; margin-bottom: 0;">
                        Basis data ini dipakai bersama oleh <strong>lebih dari satu desa</strong>.
                        Rotasi hanya mengganti AppKey <strong>desa ini</strong>; desa lain tetap memakai kuncinya masing-masing.
                        Selama rotasi berjalan, proses backup/restore/acak data di desa lain juga ditahan sementara.
                    </p>
                </div>
            @endif

            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-size: 13px;"><i class="fa fa-exclamation-triangle"></i> Perhatian & Efek Samping</h3>
                </div>
                <div class="box-body" style="font-size: 13px;">
                    <ul style="padding-left: 18px; margin-bottom: 0;">
                        <li style="margin-bottom: 6px;">
                            <strong>Sesi pengguna akan berakhir:</strong> Seluruh pengguna yang sedang aktif akan otomatis ter-<em>logout</em> dan perlu melakukan login ulang.
                        </li>
                        <li>
                            <strong>Tautan lama kedaluwarsa:</strong> Tautan dokumen publik lama yang menggunakan parameter terenkripsi tidak dapat lagi digunakan (menjadi 404).
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pra-Pemeriksaan & Tindakan --}}
        <div class="col-md-7">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-check-square-o text-primary"></i> Pra-Pemeriksaan Kesiapan Sistem</h3>
                    @if ($blocked)
                        <span class="label label-danger pull-right" style="font-size: 12px; padding: 4px 8px;">
                            <i class="fa fa-times"></i> Belum Siap
                        </span>
                    @else
                        <span class="label label-success pull-right" style="font-size: 12px; padding: 4px 8px;">
                            <i class="fa fa-check"></i> Siap Rotasi
                        </span>
                    @endif
                </div>
                <div class="box-body no-padding">
                    <table class="table table-hover" style="margin-bottom: 0;">
                        <tbody>
                            @foreach ($checks as $check)
                                <tr>
                                    <td style="width: 36px; text-align: center; vertical-align: middle;">
                                        @if ($check['passed'])
                                            <i class="fa fa-check-circle text-green" style="font-size: 16px;"></i>
                                        @elseif ($check['blocking'])
                                            <i class="fa fa-times-circle text-red" style="font-size: 16px;"></i>
                                        @else
                                            <i class="fa fa-exclamation-triangle text-yellow" style="font-size: 16px;"></i>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <span style="font-weight: 500; font-size: 13px;">{{ $check['label'] }}</span>
                                        @if (! $check['passed'] && $check['pesan'])
                                            <div class="text-danger" style="font-size: 12px; margin-top: 2px;">
                                                <i class="fa fa-angle-right"></i> {{ $check['pesan'] }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer" style="padding: 15px;">
                    @if ($blocked)
                        <div class="alert alert-danger" style="margin-bottom: 12px; padding: 10px 14px; font-size: 13px;">
                            <i class="fa fa-ban"></i> Satu atau lebih syarat pra-pemeriksaan di atas belum terpenuhi. Selesaikan kendala tersebut agar rotasi dapat dimulai.
                        </div>
                    @else
                        <div class="alert alert-success" style="margin-bottom: 12px; padding: 10px 14px; font-size: 13px;">
                            <i class="fa fa-check-circle"></i> Seluruh syarat kesiapan telah terpenuhi. Anda dapat melanjutkan proses rotasi kunci.
                        </div>
                    @endif

                    <button type="button" id="btn-buka-modal-rotasi" class="btn btn-warning btn-block" style="font-size: 14px; font-weight: bold; padding: 10px;" @if ($blocked) disabled @endif>
                        <i class="fa fa-refresh"></i> Rotasi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-rotasi" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" id="rotasi-modal-x">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-key text-yellow"></i> Konfirmasi Rotasi AppKey</h4>
                </div>
                <div class="modal-body" id="modal-rotasi-body">
                    <div class="alert alert-warning" style="font-size: 13px;">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>Tindakan ini tidak dapat dibatalkan setelah dimulai.</strong>
                        Seluruh sesi pengguna akan keluar otomatis dan tautan dokumen publik lama akan kedaluwarsa (404).
                    </div>

                    @if ($tanpaOtp)
                        <div class="alert alert-info" style="font-size: 13px;">
                            <i class="fa fa-info-circle"></i> Mode demo: kode verifikasi (OTP) tidak diperlukan.
                        </div>
                    @else
                        <div class="form-group">
                            <label><i class="fa fa-paper-plane-o"></i> Kirim kode verifikasi (OTP) ke:</label>
                            <div>
                                <label class="radio-inline"><input type="radio" name="otp-method" value="email" checked> Email</label>
                                <label class="radio-inline"><input type="radio" name="otp-method" value="telegram"> Telegram</label>
                                <button type="button" id="btn-kirim-otp" class="btn btn-link btn-xs" style="font-weight: 600;">Kirim kode</button>
                            </div>
                            <span id="otp-status" class="help-block"></span>
                        </div>

                        <div class="form-group">
                            <label for="rotasi-otp"><i class="fa fa-lock"></i> Kode verifikasi</label>
                            <input type="text" id="rotasi-otp" class="form-control" maxlength="6" placeholder="Masukkan 6 digit kode OTP">
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="rotasi-konfirmasi"><i class="fa fa-pencil"></i> Ketik <code>ROTASI</code> untuk melanjutkan</label>
                        <input type="text" id="rotasi-konfirmasi" class="form-control" autocomplete="off" placeholder="Ketik kata ROTASI">
                    </div>

                    <div id="rotasi-error" class="alert alert-danger" style="display:none;"></div>
                </div>
                <div class="modal-footer" id="modal-rotasi-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-rotasi-lanjut" class="btn btn-warning" disabled>
                        <i class="fa fa-key"></i> Mulai Rotasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            csrfToken = csrfToken ? csrfToken.content : '{{ csrf_token() }}';

            function postJson(url, body) {
                return fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body
                }).then(function (r) { return r.json().then(function (d) { return { ok: r.ok, body: d }; }); });
            }

            function esc(s) {
                var d = document.createElement('div');
                d.textContent = s == null ? '' : String(s);
                return d.innerHTML;
            }

            // --- Modal rotasi: konfirmasi → hasil di modal yang sama lewat fetch.
            // jalankan() menjalankan command app:key-rotate (sama dengan jalur CLI) lalu
            // langsung mengembalikan hasilnya. ---
            var modalBody   = document.getElementById('modal-rotasi-body');
            var modalFooter = document.getElementById('modal-rotasi-footer');
            var modalX      = document.getElementById('rotasi-modal-x');
            var confirmHtml = modalBody.innerHTML;
            var footerHtml  = modalFooter.innerHTML;

            var openBtn = document.getElementById('btn-buka-modal-rotasi');

            function resetModal() {
                modalBody.innerHTML   = confirmHtml;
                modalFooter.innerHTML = footerHtml;
                modalFooter.style.display = '';
                if (modalX) { modalX.style.display = ''; }
                pasangHandlerKonfirmasi();
            }

            function pasangHandlerKonfirmasi() {
                var otpInput  = document.getElementById('rotasi-otp');
                var konfInput = document.getElementById('rotasi-konfirmasi');
                var lanjut    = document.getElementById('btn-rotasi-lanjut');
                var errorBox  = document.getElementById('rotasi-error');
                var otpStatus = document.getElementById('otp-status');
                var btnKirim  = document.getElementById('btn-kirim-otp');

                // Mode demo: field OTP tidak dirender, cukup kata konfirmasi.
                function refreshLanjut() {
                    var word = (konfInput.value || '').trim().toUpperCase();
                    lanjut.disabled = word !== 'ROTASI' || (otpInput && !(otpInput.value || '').trim());
                }
                if (otpInput) { otpInput.addEventListener('input', refreshLanjut); }
                konfInput.addEventListener('input', refreshLanjut);
                refreshLanjut();

                if (btnKirim) btnKirim.addEventListener('click', function () {
                    var method = document.querySelector('input[name="otp-method"]:checked').value;
                    btnKirim.disabled = true;
                    // CSRF cukup dari header X-CSRF-TOKEN Laravel yang sudah dipasang postJson().
                    postJson('{{ route('rotasi-app-key.kirim-otp') }}', 'method=' + encodeURIComponent(method)).then(function (res) {
                        otpStatus.textContent = (res.body && res.body.message) || (res.ok ? 'Kode terkirim.' : 'Gagal mengirim kode.');
                        otpStatus.className = 'help-block ' + (res.ok && res.body.status ? 'text-green' : 'text-red');
                    }).catch(function () {
                        otpStatus.textContent = 'Gagal mengirim kode (sesi/CSRF kedaluwarsa?). Muat ulang halaman lalu coba lagi.';
                        otpStatus.className = 'help-block text-red';
                    }).finally(function () {
                        btnKirim.disabled = false;
                    });
                });

                lanjut.addEventListener('click', function () {
                    lanjut.disabled = true;
                    lanjut.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sedang merotasi…';
                    if (modalX) { modalX.style.display = 'none'; }
                    errorBox.style.display = 'none';

                    var body = 'konfirmasi=' + encodeURIComponent(konfInput.value.trim())
                        + '&otp=' + encodeURIComponent(otpInput ? otpInput.value.trim() : '');

                    postJson('{{ route('rotasi-app-key.jalankan') }}', body).then(function (res) {
                        if (!res.ok || !res.body || res.body.status !== true) {
                            errorBox.textContent = (res.body && res.body.message) || 'Gagal memulai rotasi.';
                            errorBox.style.display = '';
                            lanjut.disabled = false;
                            lanjut.innerHTML = '<i class="fa fa-key"></i> Mulai Rotasi';
                            if (modalX) { modalX.style.display = ''; }
                            refreshLanjut();
                            return;
                        }
                        tampilkanHasil(res.body.sukses, res.body.hasil);
                    }).catch(function () {
                        errorBox.textContent = 'Gagal menghubungi server.';
                        errorBox.style.display = '';
                        lanjut.disabled = false;
                        lanjut.innerHTML = '<i class="fa fa-key"></i> Mulai Rotasi';
                        if (modalX) { modalX.style.display = ''; }
                    });
                });
            }

            function tampilkanHasil(sukses, hasil) {
                hasil = hasil || {};

                var html = sukses
                    ? '<div class="alert alert-success"><i class="fa fa-check-circle"></i> <strong>Rotasi AppKey berhasil.</strong></div>'
                    : '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Rotasi AppKey gagal.</strong> ' + esc(hasil.error || '') + '</div>';

                html += '<table class="table table-condensed"><tbody>';
                html += '<tr><td style="width:45%;">Sidik jari key lama</td><td><code>' + (hasil.fingerprint_lama ? '…' + esc(hasil.fingerprint_lama) : '—') + '</code></td></tr>';
                if (sukses) {
                    html += '<tr><td>Sidik jari key baru</td><td><code>' + (hasil.fingerprint_baru ? '…' + esc(hasil.fingerprint_baru) : '—') + '</code></td></tr>';
                }
                html += '<tr><td>Path backup</td><td><code>' + esc(hasil.backup_path || '—') + '</code></td></tr>';
                if (sukses && hasil.detail && hasil.detail.upload_stats) {
                    html += '<tr><td>Rekord upload di-update</td><td>' + esc(hasil.detail.upload_stats.updated_records_count || 0) + '</td></tr>';
                    html += '<tr><td>File upload di-rename</td><td>' + esc(hasil.detail.upload_stats.renamed_files_count || 0) + '</td></tr>';
                }
                html += '</tbody></table>';

                modalBody.innerHTML = html;
                modalFooter.style.display = '';
                modalFooter.innerHTML = sukses
                    ? '<a href="{{ url('siteman') }}" class="btn btn-warning"><i class="fa fa-sign-in"></i> Login Ulang</a>'
                    : '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>';

                if (! sukses) {
                    // Rotasi gagal (sudah di-rollback oleh RotateAppKeyAction) — sesi admin
                    // masih berlaku, jadi modal cukup ditutup dan halaman dimuat ulang
                    // sendiri oleh admin untuk melihat status pra-pemeriksaan terbaru.
                    modalX.style.display = '';
                }
            }

            if (openBtn) {
                openBtn.addEventListener('click', function () {
                    resetModal();
                    window.jQuery('#modal-rotasi').modal('show');
                });
            }
        });
    </script>
@endsection
