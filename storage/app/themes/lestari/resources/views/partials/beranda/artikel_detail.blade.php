@php
    $art = $single_artikel;
    $lvTglArtikel = static fn ($tgl) => $tgl ? \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j F Y') : '';
    $lvFotoArtikel = static fn ($gambar) => filled($gambar) && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $gambar) ? AmbilFotoArtikel($gambar, 'sedang') : null;

    // Gambar utama + hingga 3 gambar tambahan (dibuka dengan fancybox seperti tampilan lama)
    $lvGambar = collect([$art['gambar'] ?? null, $art['gambar1'] ?? null, $art['gambar2'] ?? null, $art['gambar3'] ?? null])
        ->map($lvFotoArtikel)
        ->filter()
        ->values();

    $lvKategoriNama = trim((string) ($art['kategori'] ?? ''));
    $lvLabel = $lvKategoriNama !== '' ? $lvKategoriNama : (($art['tipe'] ?? '') === 'agenda' ? 'Agenda' : null);
    $lvLayout = $layout ?? 'right-sidebar';

    // Artikel lainnya: dari arsip terbaru, tanpa artikel yang sedang dibuka
    $lvLainnya = collect($arsip_terkini ?? [])
        ->reject(static fn ($a) => (int) ($a['id'] ?? 0) === (int) ($art['id'] ?? 0))
        ->take(3);

    $lvTautan = $art['url_slug'] ?? url()->current();
    $lvJudul = $art['judul'] ?? '';
@endphp

@include('theme::commons.asset_highcharts')

<main class="lv-wrap lv-section lv-artikel" style="padding-top: 40px">
    <nav class="lv-crumb" aria-label="Lokasi halaman">
        <a href="{{ site_url() }}">Beranda</a>
        @if ($lvKategoriNama !== '' && !empty($art['kat_slug']))
            <span aria-hidden="true">›</span>
            <a href="{{ ci_route('artikel/kategori/' . $art['kat_slug']) }}">{{ $lvKategoriNama }}</a>
        @elseif ($lvLabel)
            <span aria-hidden="true">›</span>
            <span>{{ $lvLabel }}</span>
        @endif
        <span aria-hidden="true">›</span>
        <span aria-current="page">{{ \Illuminate\Support\Str::limit($lvJudul, 60) }}</span>
    </nav>

    <div class="lv-artikel__grid lv-artikel__grid--{{ $lvLayout }}">
        <article class="lv-artikel__isi" id="printableArea">
            <header class="lv-artikel__kepala">
                @if ($lvLabel)
                    <span class="lv-chip">{{ $lvLabel }}</span>
                @endif
                <h1 class="lv-artikel__judul">{{ $lvJudul }}</h1>
                <p class="lv-artikel__meta">
                    <span>{!! $svg('clock') !!}{{ $art['tgl_upload_local'] ?? $lvTglArtikel($art['tgl_upload'] ?? null) }}</span>
                    <span>{!! $svg('users') !!}{{ $art['owner'] ?? '' }}</span>
                    <span>{!! $svg('chart') !!}{{ ribuan((int) ($art['hit'] ?? 0)) }} kali dibaca</span>
                    @if (!empty($komentar) && count($komentar))
                        <span>{!! $svg('message') !!}{{ count($komentar) }} komentar</span>
                    @endif
                </p>
            </header>

            @if ($lvGambar->isNotEmpty())
                <div class="lv-artikel__galeri lv-artikel__galeri--{{ min($lvGambar->count(), 4) }}">
                    @foreach ($lvGambar as $i => $foto)
                        <a href="{{ $foto }}" data-fancybox="artikel" @if ($i === 0) class="is-utama" @endif>
                            <img src="{{ $foto }}" alt="{{ $i === 0 ? $lvJudul : 'Foto ' . ($i + 1) . ' ' . $lvJudul }}" @if ($i > 0) loading="lazy" @endif>
                        </a>
                    @endforeach
                </div>
            @endif

            @if (($art['tipe'] ?? '') === 'agenda' && !empty($detail_agenda))
                @php $tglAgenda = \Carbon\Carbon::parse($detail_agenda['tgl_agenda'])->locale('id'); @endphp
                <dl class="lv-agenda-detail">
                    <div><dt>Tanggal</dt><dd>{{ $tglAgenda->translatedFormat('l, j F Y') }}</dd></div>
                    <div><dt>Waktu</dt><dd>{{ $tglAgenda->format('H.i') }} WIB</dd></div>
                    @if (!empty($detail_agenda['koordinator_kegiatan']))
                        <div><dt>Koordinator</dt><dd>{{ $detail_agenda['koordinator_kegiatan'] }}</dd></div>
                    @endif
                    @if (!empty($detail_agenda['lokasi_kegiatan']))
                        <div><dt>Tempat</dt><dd>{{ $detail_agenda['lokasi_kegiatan'] }}</dd></div>
                    @endif
                </dl>
            @endif

            @if (!empty($art['isi']))
                <div class="lv-prose">{!! $art['isi'] !!}</div>
            @endif

            {{-- Lampiran dokumen artikel (diunggah admin), diunduh lewat rute core --}}
            @if (filled($art['dokumen'] ?? null) && is_file(LOKASI_DOKUMEN . $art['dokumen']))
                <a class="lv-lampiran" href="{{ ci_route('first.unduh_dokumen_artikel', $art['id']) }}">
                    <span class="lv-lampiran__ic" aria-hidden="true">{!! $svg('file') !!}</span>
                    <span class="lv-lampiran__teks">
                        <span class="lv-lampiran__label">Dokumen lampiran</span>
                        <strong>{{ $art['link_dokumen'] ?: $art['dokumen'] }}</strong>
                    </span>
                    <span class="lv-lampiran__aksi">Unduh</span>
                </a>
            @endif

            <div class="lv-bagikan" aria-label="Bagikan artikel">
                <span class="lv-bagikan__label">Bagikan</span>
                <a href="https://www.facebook.com/sharer.php?u={{ urlencode($lvTautan) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Facebook">{!! $svg('facebook') !!}</a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($lvJudul . "\n" . $lvTautan) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke WhatsApp">{!! $svg('wa') !!}</a>
                <a href="https://telegram.me/share/url?url={{ urlencode($lvTautan) }}&text={{ urlencode($lvJudul) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Telegram">{!! $svg('send') !!}</a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($lvTautan) }}&text={{ urlencode($lvJudul) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke X (Twitter)">{!! $svg('twitter') !!}</a>
                <a href="mailto:?subject={{ rawurlencode($lvJudul) }}&body={{ rawurlencode($lvTautan) }}" aria-label="Kirim lewat email">{!! $svg('mail') !!}</a>
                <button type="button" data-lv-salin="{{ $lvTautan }}" aria-label="Salin tautan">{!! $svg('link') !!}</button>
                <button type="button" data-lv-cetak aria-label="Cetak artikel">{!! $svg('print') !!}</button>
                <span class="lv-bagikan__info" data-lv-salin-info role="status" aria-live="polite"></span>
            </div>

            @include('theme::partials.beranda.artikel_komentar')
        </article>

        @if ($lvLayout !== 'full-content')
            @include('theme::partials.beranda.samping_artikel')
        @endif
    </div>

    @if ($lvLainnya->isNotEmpty())
        <section class="lv-lainnya" aria-labelledby="lv-lainnya-judul">
            <h2 class="lv-h3" id="lv-lainnya-judul">Artikel lainnya</h2>
            <div class="lv-grid lv-grid-3">
                @foreach ($lvLainnya as $lain)
                    @php $fotoLain = $lvFotoArtikel($lain['gambar'] ?? null) ?: $lvFotoKosong; @endphp
                    <a class="lv-build" href="{{ site_url('artikel/' . buat_slug($lain)) }}">
                        <div class="lv-build__img"><img src="{{ $fotoLain }}" alt="" loading="lazy"></div>
                        <div class="lv-build__body">
                            <span class="lv-meta">{{ $lvTglArtikel($lain['tgl_upload'] ?? null) }} · {{ ribuan((int) ($lain['hit'] ?? 0)) }} kali dibaca</span>
                            <span class="lv-build__title">{{ $lain['judul'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</main>
