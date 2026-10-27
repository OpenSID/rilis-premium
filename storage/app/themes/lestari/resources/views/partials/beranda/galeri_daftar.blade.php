@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman galeri (tampilan Modern): daftar album (galeri) dan isi album (galeri/{id}).
    Kartu memakai gaya "Galeri Kegiatan" di beranda dalam kisi rata; data dimuat skrip beranda (pemuat "galeri").
--}}
@php
    $lvAlbum = filled($parent ?? null);
    $lvJudulAlbum = $lvAlbum ? trim((string) ($title ?? '')) : '';
@endphp

<main class="lv-wrap lv-section" style="padding-top: 40px" data-lv-galeri>
    <nav class="lv-crumb" aria-label="Lokasi halaman">
        <a href="{{ site_url() }}">Beranda</a>
        <span aria-hidden="true">›</span>
        @if ($lvAlbum)
            <a href="{{ site_url('galeri') }}">Galeri</a>
            <span aria-hidden="true">›</span>
            <span aria-current="page">{{ \Illuminate\Support\Str::limit($lvJudulAlbum, 60) }}</span>
        @else
            <span aria-current="page">Galeri</span>
        @endif
    </nav>

    <div class="lv-head">
        <div>
            <h1 class="lv-h2">{{ $lvAlbum ? $lvJudulAlbum : 'Galeri Foto' }}</h1>
            <p class="lv-daftar__ket">
                {{ $lvAlbum ? 'Klik foto untuk melihat ukuran penuh.' : 'Dokumentasi kegiatan ' . $namaDesa . ', dikelompokkan per album.' }}
            </p>
        </div>
        @if ($lvAlbum)
            <a class="lv-more" href="{{ site_url('galeri') }}">← Semua album</a>
        @endif
    </div>

    <div
        class="lv-gallery lv-gallery--daftar"
        data-lv-lazy="galeri"
        data-lv-url="{{ ci_route('internal_api.galeri') . ($lvAlbum ? '/' . $parent : '') }}"
        data-lv-ukuran="{{ $lvAlbum ? 16 : 12 }}"
        @if ($lvAlbum) data-lv-album @endif
        aria-live="polite"
        aria-busy="true"
    >
        @for ($i = 0; $i < 8; $i++)
            <div class="lv-skeleton" aria-hidden="true"></div>
        @endfor
    </div>
    <p class="lv-empty" data-lv-galeri-kosong hidden>{{ $lvAlbum ? 'Belum ada foto di album ini.' : 'Belum ada album galeri yang ditampilkan.' }}</p>
    <div class="lv-pager" data-lv-galeri-pager hidden></div>
</main>
