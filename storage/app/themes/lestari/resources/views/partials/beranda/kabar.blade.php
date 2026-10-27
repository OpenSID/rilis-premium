@php
    $lvFoto = static function ($gambar, $ukuran = 'sedang') {
        return filled($gambar) && is_file(LOKASI_FOTO_ARTIKEL . $ukuran . '_' . $gambar)
            ? AmbilFotoArtikel($gambar, $ukuran)
            : null;
    };
    $lvTgl = static fn ($tgl) => $tgl ? \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j F Y') : '';

    // Mode daftar (halaman hasil pencarian & kategori, lihat beranda/daftar_artikel): judul halaman sendiri, tanpa headline
    $lvDaftar ??= null;
    $lvHeadline = $lvDaftar ? null : ($headline ?? null);
@endphp

<section class="lv-wrap lv-section" style="padding-top: {{ $lvDaftar ? 48 : 72 }}px" aria-labelledby="lv-kabar-judul">
    <div class="lv-head">
        @if ($lvDaftar)
            <div>
                <h1 class="lv-h2" id="lv-kabar-judul">{{ $lvDaftar['judul'] }}</h1>
                @if (filled($lvDaftar['keterangan'] ?? null))
                    <p class="lv-daftar__ket">{{ $lvDaftar['keterangan'] }}</p>
                @endif
            </div>
        @else
            <h2 class="lv-h2" id="lv-kabar-judul">Kabar {{ $sebutanDesa }}</h2>
        @endif
        <a class="lv-more" href="{{ site_url('arsip') }}">Semua artikel →</a>
    </div>

    @if (filled($lvDaftar['cari'] ?? null))
        {{-- Hasil pencarian dari sumber lain (halaman, lapak, pembangunan, dokumen); diisi skrip beranda --}}
        <div class="lv-cari-lain" data-lv-cari-halaman="{{ $lvDaftar['cari'] }}" hidden>
            <h2 class="lv-h3">Ditemukan juga di</h2>
            <div class="lv-cari-lain__isi" data-lv-cari-lain-isi></div>
        </div>
        <h2 class="lv-h3 lv-cari-lain__artikel" data-lv-cari-artikel-judul hidden>Artikel &amp; Berita</h2>
    @endif

    <div class="lv-news">
        <div class="lv-news__main">
            @if ($lvHeadline)
                @php $headline = $lvHeadline; @endphp
                @php $fotoHeadline = $lvFoto($headline['gambar']); @endphp
                <a class="lv-headline" href="{{ $headline->url_slug }}">
                    <div class="lv-headline__img">
                        @if ($fotoHeadline)
                            <img src="{{ $fotoHeadline }}" alt="">
                        @else
                            <img src="{{ $lvFotoKosong }}" alt="">
                        @endif
                        <span class="lv-tag">Headline</span>
                    </div>
                    <div class="lv-headline__body">
                        <span class="lv-meta">{{ $lvTgl($headline['tgl_upload']) }}</span>
                        <h3 class="lv-headline__title">{{ $headline['judul'] }}</h3>
                        <p class="lv-headline__excerpt">{{ \Illuminate\Support\Str::limit(strip_tags(htmlspecialchars_decode((string) $headline['isi'])), 180) }}</p>
                        <span class="lv-meta">{{ $headline['author']['nama'] ?? '' }}{{ isset($headline['author']['nama']) ? ' · ' : '' }}{{ ribuan((int) ($headline['hit'])) }} kali dibaca</span>
                    </div>
                </a>
            @endif

            @forelse ($artikel as $post)
                @php
                    $fotoPost = $lvFoto($post['gambar'], 'kecil');
                    $namaKategori = optional($post->category)->kategori;
                @endphp
                <a class="lv-post" href="{{ $post->url_slug }}">
                    <span class="lv-thumb">
                        @if ($fotoPost)
                            <img src="{{ $fotoPost }}" alt="" loading="lazy">
                        @else
                            <img src="{{ $lvFotoKosong }}" alt="" loading="lazy">
                        @endif
                    </span>
                    <span class="lv-post__body">
                        @if (filled($namaKategori) && ! is_numeric($namaKategori))
                            <span class="lv-post__cat">{{ $namaKategori }}</span>
                        @endif
                        <span class="lv-post__title">{{ $post['judul'] }}</span>
                        <span class="lv-meta">
                            {{ $lvTgl($post['tgl_upload']) }}
                            @if ($post['owner']) · {{ $post['owner'] }} @endif
                            · {{ ribuan((int) ($post['hit'])) }} kali dibaca
                            @if ($post->jumlah_komentar) · {{ $post->jumlah_komentar }} komentar @endif
                        </span>
                    </span>
                </a>
            @empty
                @unless ($lvHeadline)
                    <p class="lv-empty">{{ $lvDaftar['kosong'] ?? 'Belum ada artikel yang diterbitkan.' }}</p>
                @endunless
            @endforelse

            <div class="lv-pager">@include('theme::commons.page')</div>
        </div>

        @include('theme::partials.beranda.samping_artikel')
    </div>
</section>
