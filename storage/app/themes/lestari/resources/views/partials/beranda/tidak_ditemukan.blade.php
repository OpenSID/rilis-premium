@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman tidak ditemukan (tampilan Modern): menu belum terdaftar/aktif atau artikel tidak ada.
    Parameter $lvTidakDitemukan: judul, pesan, menu (true bila penyebabnya menu belum aktif).
    Petunjuk mengaktifkan menu hanya untuk admin yang sedang masuk; pengunjung mendapat pencarian & tautan alternatif.
--}}
@php
    $lvTd = $lvTidakDitemukan ?? [];
    $lvAdmin = (bool) rescue(static fn () => function_exists('ci_auth') && ci_auth(), false, false);

    // Tautan alternatif: hanya halaman yang menunya aktif (halaman lain akan berakhir di 404 yang sama)
    $lvCalon = [
        'pemerintah' => ['Pemerintah ' . $sebutanDesa, 'users'],
        'peta' => ['Peta ' . $sebutanDesa, 'map'],
        'pengaduan' => ['Pengaduan', 'message'],
        'peraturan-desa' => ['Produk Hukum', 'book'],
    ];
    $lvTautan = collect($lvCalon)
        ->only(array_keys($lvLinkMenuAktif()))
        ->map(static fn ($t, $link) => [$t[0], $t[1], site_url($link)])
        ->values()
        ->prepend(['Arsip Artikel', 'layers', site_url('arsip')]);
    if ($lvUrlStatistikAwal = $lvUrlStatistik()) {
        $lvTautan->splice(1, 0, [['Statistik ' . $sebutanDesa, 'chart', $lvUrlStatistikAwal]]);
    }
@endphp

<main class="lv-wrap lv-section lv-404" style="padding-top: 40px">
    <div class="lv-404__isi">
        <p class="lv-404__kode" aria-hidden="true">404</p>
        <h1 class="lv-h2">{{ $lvTd['judul'] ?? 'Halaman tidak ditemukan' }}</h1>
        <p class="lv-404__pesan">{{ $lvTd['pesan'] ?? 'Halaman yang Anda buka tidak tersedia atau sudah dipindahkan.' }}</p>
        <div class="lv-404__aksi">
            <a class="lv-btn" href="{{ site_url() }}">{!! $svg('left') !!} Kembali ke Beranda</a>
            <button type="button" class="lv-btn lv-btn--ghost" data-lv-kembali hidden>Halaman sebelumnya</button>
        </div>

        @if ($lvAdmin && ! empty($lvTd['menu']))
            <div class="lv-404__admin" role="note">
                {!! $svg('info') !!}
                <p>
                    <b>Info untuk admin:</b> halaman ini baru bisa dibuka pengunjung setelah ditambahkan dan diaktifkan di Admin Web &rsaquo; Menu.
                    <a href="{{ site_url('menu') }}">Buka pengaturan Menu</a> &middot;
                    <a href="https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu" target="_blank" rel="noopener noreferrer">Panduan</a>
                </p>
            </div>
        @endif
    </div>

    <div class="lv-404__bantu">
        <div class="lv-search">
            <form method="get" action="{{ site_url() }}" role="search">
                <label class="lv-sr" for="lv-404-cari">Cari artikel</label>
                <input id="lv-404-cari" type="search" name="cari" maxlength="50" placeholder="Cari berita, pengumuman, atau informasi {{ strtolower($sebutanDesa) }}…">
                <button class="lv-btn" type="submit">Cari</button>
            </form>
        </div>
        <div class="lv-dir">
            <div>
                <h2 class="lv-h3">Mungkin Anda mencari</h2>
                @foreach ($lvTautan as [$judul, $ikon, $url])
                    <a href="{{ $url }}">{!! $svg($ikon) !!}<span>{{ $judul }}</span>{!! $svg('right', 'lv-chev') !!}</a>
                @endforeach
            </div>
        </div>
    </div>
</main>
