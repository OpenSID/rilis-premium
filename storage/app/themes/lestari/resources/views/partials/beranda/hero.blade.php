@php
    // Slide pertama selalu latar website (identitas desa), diikuti gambar dari sumber slider di pengaturan inti.
    $lvSlides = [['src' => $latar_website, 'judul' => null, 'url' => null]];
    $lvLokasi = $slider_gambar['lokasi'] ?? '';
    foreach ($slider_gambar['gambar'] ?? [] as $gambar) {
        $berkas = $lvLokasi . 'sedang_' . ($gambar['gambar'] ?? '');
        if (empty($gambar['gambar']) || ! is_file($berkas)) {
            continue;
        }
        $url = null;
        if (isset($gambar['slug'], $gambar['thn'])) {
            $url = site_url('artikel/' . buat_slug($gambar));
        } elseif (isset($gambar['id']) && $lvLokasi === LOKASI_FOTO_ARTIKEL) {
            $url = site_url('artikel/' . $gambar['id']);
        }
        $lvSlides[] = ['src' => base_url($berkas), 'judul' => $gambar['judul'] ?? null, 'url' => $url];
    }
    $lvHeroStatis = $cfg('gaya_hero', 'slider') === 'statis' || count($lvSlides) === 1;
    $lvProvinsi = $desa['nama_propinsi'] ?? '';
@endphp

@if ($lvHeroStatis)
    <section class="lv-hero lv-hero--static" aria-label="Identitas {{ strtolower($sebutanDesa) }}">
        <div class="lv-hero__slide is-active"><img src="{{ $latar_website }}" alt=""></div>
        <div class="lv-hero__shade" style="background: rgba(0, 28, 77, .72)"></div>
        <div class="lv-wrap lv-hero__body">
            <span class="lv-hero__kicker">Website Resmi Pemerintah {{ $sebutanDesa }}</span>
            <h1 class="lv-hero__title">{{ $namaDesa }}</h1>
            <p class="lv-hero__sub">{{ $wilayahDesa }}{{ $lvProvinsi ? ', Provinsi ' . ucwords(strtolower($lvProvinsi)) : '' }}</p>
            <div class="lv-hero-cari">
                <form class="lv-hero-search" method="get" action="{{ site_url() }}" role="search" data-lv-cari>
                    <label class="lv-sr" for="lv-hero-cari">Cari halaman, berita, produk, atau dokumen</label>
                    <input id="lv-hero-cari" type="search" name="cari" maxlength="50" placeholder="Cari layanan, berita, produk, atau dokumen…" autocomplete="off">
                    <button class="lv-btn" type="submit">Cari</button>
                </form>
                <div class="lv-cari-hasil lv-cari-hasil--melayang" data-lv-cari-hasil hidden></div>
            </div>
        </div>
    </section>
@else
    <section class="lv-hero" aria-roledescription="carousel" aria-label="Galeri {{ strtolower($sebutanDesa) }}" data-lv-slider>
        @foreach ($lvSlides as $i => $slide)
            <div class="lv-hero__slide {{ $i === 0 ? 'is-active' : '' }}" data-judul="{{ $slide['judul'] }}" data-url="{{ $slide['url'] }}" aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
                <img src="{{ $slide['src'] }}" alt="{{ $slide['judul'] ?: 'Pemandangan ' . $namaDesa }}" @if ($i > 0) loading="lazy" @endif>
            </div>
        @endforeach
        <div class="lv-hero__shade"></div>
        <div class="lv-wrap lv-hero__body">
            <span class="lv-hero__kicker">Website Resmi Pemerintah {{ $sebutanDesa }}</span>
            <h1 class="lv-hero__title">{{ $namaDesa }}</h1>
            <p class="lv-hero__sub">{{ $wilayahDesa }}{{ $lvProvinsi ? ', Provinsi ' . ucwords(strtolower($lvProvinsi)) : '' }}</p>
        </div>
        <div class="lv-hero__bar">
            <div class="lv-wrap">
                <div class="lv-hero__count">
                    <span><span data-lv-current>01</span> / {{ str_pad(count($lvSlides), 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="lv-dots">
                        @foreach ($lvSlides as $i => $slide)
                            <button type="button" class="{{ $i === 0 ? 'is-active' : '' }}" data-lv-goto="{{ $i }}" aria-label="Tampilkan slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
                <div class="lv-hero__nav">
                    <a class="lv-hero__caption" href="#" data-lv-caption hidden></a>
                    <button type="button" class="lv-round" data-lv-prev aria-label="Slide sebelumnya">{!! $svg('left') !!}</button>
                    <button type="button" class="lv-round lv-round--solid" data-lv-next aria-label="Slide berikutnya">{!! $svg('right') !!}</button>
                </div>
            </div>
        </div>
    </section>
@endif
