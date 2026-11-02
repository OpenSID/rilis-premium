@php
    $lvKehadiran = setting('tampilkan_kehadiran') && $on('tampil_kehadiran');
    $lvTeksHadir = $cfg('teks_hadir', 'Ada di kantor');
    $lvTeksTidak = $cfg('teks_tidak_hadir', 'Tidak di kantor');
    $lvSosialPamong = ['facebook', 'instagram', 'youtube', 'twitter', 'tiktok', 'whatsapp'];
@endphp

@if ($lvPerangkat->isNotEmpty())
    <section class="lv-wrap lv-section" aria-labelledby="lv-aparatur-judul">
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-aparatur-judul">Aparatur {{ $sebutanDesa }}</h2>
            <div style="display: flex; gap: 10px">
                <button type="button" class="lv-icon-btn" style="border-radius: 50%" data-lv-scroll="lv-staff" data-arah="-1" aria-label="Geser ke kiri">{!! $svg('left') !!}</button>
                <button type="button" class="lv-icon-btn" style="border-radius: 50%; background: var(--c1); color: #fff; border: 0" data-lv-scroll="lv-staff" data-arah="1" aria-label="Geser ke kanan">{!! $svg('right') !!}</button>
            </div>
        </div>
        <div class="lv-staff" id="lv-staff">
            @foreach ($lvPerangkat as $pamong)
                @php
                    $sosial = collect(json_decode($pamong['media_sosial'] ?? '[]', true) ?: [])
                        ->only($lvSosialPamong)
                        // Hanya tautan http(s) (atau nomor untuk WhatsApp) agar isian seperti "javascript:" tidak bisa dijalankan
                        ->filter(static fn ($v, $k) => is_string($v) && ($k === 'whatsapp' ? preg_match('/\d{8,}/', $v) : preg_match('~^https?://~i', trim($v))));
                    $hadir = ($pamong['status_kehadiran'] ?? null) === 'hadir';
                @endphp
                <div class="lv-person">
                    <div class="lv-person__photo">
                        <img src="{{ $pamong['foto'] }}" alt="Foto {{ $pamong['nama'] }}" loading="lazy">
                        @if ($lvKehadiran && ($pamong['kehadiran'] ?? null) == 1)
                            <span class="lv-badge {{ $hadir ? 'lv-badge--ok' : '' }}">{{ $hadir ? $lvTeksHadir : $lvTeksTidak }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="lv-person__name">{{ $lvNamaGelar($pamong) }}</div>
                        <div class="lv-meta">{{ $pamong['jabatan'] }}</div>
                    </div>
                    @if ($sosial->isNotEmpty())
                        <div class="lv-person__soc">
                            @foreach ($sosial as $jenis => $tautan)
                                <a href="{{ $jenis === 'whatsapp' ? 'https://wa.me/' . preg_replace('/\D/', '', $tautan) : $tautan }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($jenis) }} {{ $pamong['nama'] }}">{!! $svg($jenis === 'whatsapp' ? 'wa' : $jenis) !!}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif
