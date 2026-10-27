@php
    $lvGaleri = collect($w_gal ?? [])
        ->filter(static fn ($g) => filled($g['gambar'] ?? null) && is_file(LOKASI_GALERI . 'sedang_' . $g['gambar']))
        ->take(5);
@endphp

@if ($lvGaleri->isNotEmpty())
    <section class="lv-wrap lv-section" style="padding-bottom: 48px" aria-labelledby="lv-galeri-judul">
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-galeri-judul">Galeri Kegiatan</h2>
            <a class="lv-more" href="{{ site_url('galeri') }}">Semua album →</a>
        </div>
        <div class="lv-gallery">
            @foreach ($lvGaleri as $galeri)
                <a href="{{ site_url('galeri/' . $galeri['id']) }}">
                    <img src="{{ AmbilGaleri($galeri['gambar'], 'sedang') }}" alt="Album {{ $galeri['nama'] }}" loading="lazy">
                    <span>{{ $galeri['nama'] }}</span>
                </a>
            @endforeach
        </div>
    </section>
@endif
