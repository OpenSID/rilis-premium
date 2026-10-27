{{-- Data dimuat setelah halaman tampil dari API pembangunan; bagian ini tetap tersembunyi bila kosong. --}}
<section class="lv-bg-white" data-lv-section-lazy hidden aria-labelledby="lv-bangun-judul">
    <div class="lv-wrap lv-section">
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-bangun-judul">Pembangunan {{ $sebutanDesa }}</h2>
            <a class="lv-more" href="{{ site_url('pembangunan') }}">Semua kegiatan pembangunan →</a>
        </div>
        <div class="lv-grid lv-grid-3" data-lv-lazy="pembangunan" data-lv-url="{{ route('api.pembangunan') }}?page[size]=3" data-lv-base="{{ site_url('pembangunan') }}"></div>
    </div>
</section>
