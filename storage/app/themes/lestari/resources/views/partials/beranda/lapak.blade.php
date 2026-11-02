{{-- Produk dimuat dari API Lapak setelah halaman tampil; bagian ini tetap tersembunyi bila belum ada produk. --}}
<section class="lv-bg-white" data-lv-section-lazy hidden aria-labelledby="lv-lapak-judul">
    <div class="lv-wrap lv-section">
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-lapak-judul">Lapak {{ $sebutanDesa }}</h2>
            <a class="lv-more" href="{{ site_url('lapak') }}">Semua produk warga →</a>
        </div>
        <div class="lv-grid lv-grid-4" data-lv-lazy="lapak" data-lv-url="{{ route('api.lapak.produk') }}?page[size]={{ (int) $cfg('jumlah_lapak', 4) }}"></div>
    </div>
</section>
