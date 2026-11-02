@php
    $lvAcara = null;
    if ($on('tampil_banner_acara') && filled($cfg('acara_judul'))) {
        $baca = static function ($nilai, $format) {
            try {
                return filled($nilai) ? \Carbon\Carbon::createFromFormat($format, trim($nilai)) : null;
            } catch (\Throwable $e) {
                return null;
            }
        };
        $mulai = $baca($cfg('acara_mulai'), 'd-m-Y');
        $selesai = $baca($cfg('acara_selesai'), 'd-m-Y');
        $target = $baca($cfg('acara_tanggal'), 'd-m-Y H:i') ?? $baca($cfg('acara_tanggal'), 'd-m-Y');
        $sekarang = \Carbon\Carbon::now();
        $tampil = (! $mulai || $sekarang->gte($mulai->copy()->startOfDay()))
            && (! $selesai || $sekarang->lte($selesai->copy()->endOfDay()));
        if ($tampil) {
            $lvAcara = [
                'judul' => $cfg('acara_judul'),
                'keterangan' => $cfg('acara_keterangan'),
                'gambar' => $cfg('acara_gambar'),
                'target' => $target && $target->isFuture() ? $target : null,
            ];
        }
    }
@endphp

@if ($lvAcara)
    <section class="lv-wrap" style="padding-top: 48px" aria-label="Pengumuman acara">
        <div class="lv-event">
            @if ($lvAcara['gambar'])
                <img class="lv-event__img" src="{{ base_url($lvAcara['gambar']) }}" alt="">
            @endif
            <div class="lv-event__text">
                @if ($lvAcara['target'])
                    <span class="lv-event__kicker">{{ $lvAcara['target']->locale('id')->translatedFormat('l, j F Y · H.i') }} WIB</span>
                @endif
                <h2 class="lv-event__title">{{ $lvAcara['judul'] }}</h2>
                @if ($lvAcara['keterangan'])
                    <p style="color: var(--body)">{{ $lvAcara['keterangan'] }}</p>
                @endif
            </div>
            @if ($lvAcara['target'])
                <div class="lv-countdown" data-lv-countdown="{{ $lvAcara['target']->toIso8601String() }}" aria-label="Hitung mundur acara">
                    <div><b data-u="d">--</b><span>Hari</span></div>
                    <div><b data-u="h">--</b><span>Jam</span></div>
                    <div><b data-u="m">--</b><span>Menit</span></div>
                    <div><b data-u="s">--</b><span>Detik</span></div>
                </div>
            @endif
        </div>
    </section>
@endif
