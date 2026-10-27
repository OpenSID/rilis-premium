{{-- Komentar artikel. Nama field, rute kirim, captcha & validasi sama dengan tampilan lama (partials/artikel/comment). --}}
@php $notif = session('notif'); @endphp

@if (!empty($komentar) && count($komentar))
    <section class="lv-komentar" aria-labelledby="lv-komentar-judul">
        <h2 class="lv-h3" id="lv-komentar-judul">Komentar ({{ count($komentar) }})</h2>
        <ol class="lv-komentar__daftar">
            @foreach ($komentar as $data)
                @php $nama = $data['pengguna']['nama'] ?? 'Pengunjung'; @endphp
                <li class="lv-komentar__item">
                    <span class="lv-avatar" aria-hidden="true">{{ mb_substr($nama, 0, 2) }}</span>
                    <div>
                        <p class="lv-meta"><strong style="color: var(--ink)">{{ $nama }}</strong> · {{ tgl_indo2($data['tgl_upload']) }}</p>
                        <p class="lv-komentar__teks">{{ $data['komentar'] }}</p>
                        @foreach ($data['children'] ?? [] as $balasan)
                            <div class="lv-komentar__balasan">
                                <p class="lv-meta"><strong style="color: var(--ink)">{{ $balasan['pengguna']['nama'] ?? '' }}</strong>
                                    @if (!empty($balasan['pengguna']['level'])) ({{ $balasan['pengguna']['level'] }}) @endif
                                    · {{ tgl_indo2($balasan['tgl_upload']) }}</p>
                                <p class="lv-komentar__teks">{{ $balasan['komentar'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </li>
            @endforeach
        </ol>
    </section>
@endif

@if (($single_artikel['boleh_komentar'] ?? 0) == 1)
    <section class="lv-komentar" aria-labelledby="lv-kirim-judul">
        <h2 class="lv-h3" id="lv-kirim-judul">Kirim komentar</h2>
        @if ($notif)
            <p class="lv-notif {{ ($notif['status'] ?? 0) == -1 ? 'is-gagal' : 'is-berhasil' }}" role="status">{{ $notif['pesan'] ?? '' }}</p>
        @endif
        <form class="lv-form" id="validasi" name="form" action="{{ ci_route("add_comment.{$single_artikel['id']}") }}" method="POST" onsubmit="return typeof validasi === 'function' ? validasi(this) : true;">
            <div class="lv-form__baris">
                <label>Nama<input type="text" name="owner" maxlength="100" value="{{ $notif['data']['owner'] ?? '' }}" autocomplete="name" required></label>
                <label>Nomor telepon/HP<input type="tel" name="no_hp" maxlength="15" value="{{ $notif['data']['no_hp'] ?? '' }}" autocomplete="tel" required></label>
                <label>Email<input type="email" name="email" maxlength="100" value="{{ $notif['data']['email'] ?? '' }}" autocomplete="email" required></label>
            </div>
            <label>Komentar<textarea name="komentar" rows="4" required>{{ $notif['data']['komentar'] ?? '' }}</textarea></label>
            <div class="lv-form__kirim">
                <div class="lv-captcha">
                    <img id="captcha" src="{{ ci_route('captcha') }}" alt="Kode keamanan (CAPTCHA)" width="120" height="40">
                    <button type="button" class="lv-btn lv-btn--ghost" onclick="document.getElementById('captcha').src = '{{ ci_route('captcha') }}?' + Math.random();">Ganti kode</button>
                </div>
                <label class="lv-captcha__isi">Kode keamanan<input type="text" name="captcha_code" maxlength="6" autocomplete="off" required></label>
                <button class="lv-btn" type="submit">Kirim komentar</button>
            </div>
            <p class="lv-meta">Nomor telepon dan email tidak ditampilkan di website.</p>
        </form>
    </section>

    <section class="lv-komentar" aria-label="Komentar Facebook">
        <div class="fb-comments" data-href="{{ $single_artikel['url_slug'] }}" data-width="100%" data-numposts="5"></div>
    </section>
@endif
