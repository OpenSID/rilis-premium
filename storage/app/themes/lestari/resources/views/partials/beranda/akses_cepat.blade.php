@php
    $lvUrlStatistikAwal = $lvUrlStatistik();
    $lvAkses = array_values(array_filter([
        setting('layanan_mandiri') ? ['Surat Online', 'Ajukan dari rumah', 'file', site_url('layanan-mandiri/masuk')] : null,
        ['Pengaduan', 'Sampaikan aspirasi', 'message', site_url('pengaduan')],
        $lvUrlStatistikAwal ? ['Statistik', 'Data penduduk', 'chart', $lvUrlStatistikAwal] : null,
        ['Peta ' . $sebutanDesa, 'Wilayah & lokasi', 'map', site_url('peta')],
        ['Lapak ' . $sebutanDesa, 'Produk warga', 'store', site_url('lapak')],
        ['APB ' . $sebutanDesa, 'Transparansi dana', 'wallet', ! empty($transparansi['data_widget']) ? '#transparansi' : site_url('informasi-publik')],
    ]));
@endphp

<div class="lv-wrap lv-quick-wrap">
    <nav class="lv-quick" aria-label="Akses cepat">
        @foreach ($lvAkses as [$judul, $sub, $ikon, $url])
            <a href="{{ $url }}">
                <span class="lv-quick__ic">{!! $svg($ikon) !!}</span>
                <span><b>{{ $judul }}</b><small>{{ $sub }}</small></span>
            </a>
        @endforeach
    </nav>
</div>
