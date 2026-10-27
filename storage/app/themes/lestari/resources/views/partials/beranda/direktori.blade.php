@php
    $lvUrlStatistikAwal = $lvUrlStatistik();
    $lvUrlBantuan = $lvUrlStatistik('bantuan_penduduk');
    $lvDirektori = [
        'Data ' . $sebutanDesa => array_values(array_filter([
            ['Data Wilayah Administratif', 'map', site_url('data-wilayah')],
            $lvUrlStatistikAwal ? ['Statistik Penduduk', 'chart', $lvUrlStatistikAwal] : null,
            ['Status IDM', 'target', site_url('status-idm')],
            ['SDGs ' . $sebutanDesa, 'globe', site_url('status-sdgs')],
            ['Kesehatan & Stunting', 'heart', site_url('data-kesehatan')],
            // Data Vaksinasi disembunyikan sementara: tema belum punya tampilan halaman data-vaksinasi (error 500).
            // Tampilkan lagi bersama perbaikan halaman vaksinasi.
        ])),
        'Pemerintahan' => [
            ['Pemerintah ' . $sebutanDesa, 'users', site_url('pemerintah')],
            ['Struktur Organisasi', 'users', site_url('struktur-organisasi-dan-tata-kerja')],
            ['Produk Hukum', 'book', site_url('peraturan-desa')],
            ['Informasi Publik', 'info', site_url('informasi-publik')],
            ['Pembangunan', 'build', site_url('pembangunan')],
            ['Inventaris ' . $sebutanDesa, 'box', site_url('inventaris')],
        ],
        'Layanan Warga' => array_values(array_filter([
            setting('layanan_mandiri') ? ['Layanan Mandiri', 'file', site_url('layanan-mandiri/masuk')] : null,
            ['Pengaduan', 'message', site_url('pengaduan')],
            ['Cek DPT Online', 'vote', site_url('data-dpt')],
            $lvUrlBantuan ? ['Data Bantuan', 'gift', $lvUrlBantuan] : null,
            ['Lapak ' . $sebutanDesa, 'store', site_url('lapak')],
            ['Arsip Artikel', 'layers', site_url('arsip')],
        ])),
    ];
@endphp

<section class="lv-wrap lv-section" style="padding-bottom: 40px" aria-labelledby="lv-dir-judul">
    <div class="lv-head">
        <h2 class="lv-h2" id="lv-dir-judul">Layanan &amp; Informasi</h2>
        <p style="color: var(--muted); max-width: 420px">Semua data dan layanan publik {{ $namaDesa }} dalam satu tempat.</p>
    </div>
    <div class="lv-dir">
        @foreach ($lvDirektori as $grup => $tautan)
            <div>
                <h3 class="lv-h3">{{ $grup }}</h3>
                @foreach ($tautan as [$judul, $ikon, $url])
                    <a href="{{ $url }}">{!! $svg($ikon) !!}<span>{{ $judul }}</span>{!! $svg('right', 'lv-chev') !!}</a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
