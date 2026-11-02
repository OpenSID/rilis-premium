@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman lapak (tampilan Modern): produk warga dengan filter kategori, pencarian, dan navigasi halaman.
    Kartu sama dengan bagian Lapak di beranda; data dimuat skrip beranda (pemuat "lapak_daftar").
    Tiga keadaan kosong: lapak belum punya produk, pencarian/kategori tanpa hasil, dan gagal memuat.
--}}
@php
    $lvTelpDesa = trim((string) ($desa['telepon'] ?? ''));
    $lvUkuranLapak = max(4, (int) setting('jumlah_produk_perhalaman') ?: 12);
@endphp

<main class="lv-wrap lv-section" style="padding-top: 40px" data-lv-lapak>
    <nav class="lv-crumb" aria-label="Lokasi halaman">
        <a href="{{ site_url() }}">Beranda</a>
        <span aria-hidden="true">›</span>
        <span aria-current="page">Lapak</span>
    </nav>

    <div class="lv-head">
        <div>
            <h1 class="lv-h2">Lapak {{ $sebutanDesa }}</h1>
            <p class="lv-daftar__ket">Produk dan jasa warga {{ $namaDesa }}. Hubungi penjual langsung lewat WhatsApp.</p>
        </div>
        <div class="lv-search lv-lapak__cari">
            <form role="search" data-lv-lapak-cari>
                <label class="lv-sr" for="lv-lapak-cari">Cari produk</label>
                <input id="lv-lapak-cari" type="search" name="cari" maxlength="50" placeholder="Cari produk, mis. kopi" autocomplete="off">
                <button class="lv-btn" type="submit">Cari</button>
            </form>
        </div>
    </div>

    <div class="lv-tabs lv-lapak__kategori" role="group" aria-label="Kategori produk" data-lv-lapak-kategori data-lv-url="{{ route('api.lapak.kategori') }}" hidden>
        <button type="button" aria-pressed="true" data-kategori="">Semua</button>
    </div>

    <p class="lv-meta lv-lapak__info" data-lv-lapak-info aria-live="polite"></p>

    <div
        class="lv-grid lv-grid-4 lv-lapak__grid"
        data-lv-lazy="lapak_daftar"
        data-lv-url="{{ route('api.lapak.produk') }}"
        data-lv-ukuran="{{ $lvUkuranLapak }}"
        aria-busy="true"
    >
        @for ($i = 0; $i < 8; $i++)
            <div class="lv-skeleton lv-lapak__kerangka" aria-hidden="true"></div>
        @endfor
    </div>

    {{-- Keadaan kosong: satu blok, isi diganti skrip sesuai penyebab --}}
    <div class="lv-kosong" data-lv-lapak-kosong hidden>
        <span class="lv-kosong__ikon">{!! $svg('store') !!}</span>
        <div data-lv-kosong="belum" hidden>
            <h2 class="lv-h3">Belum ada produk di Lapak {{ $sebutanDesa }}</h2>
            <p>Produk dan jasa warga akan tampil di sini setelah didaftarkan oleh perangkat {{ strtolower($sebutanDesa) }}.
                Ingin berjualan? Hubungi kantor {{ strtolower($sebutanDesa) }} untuk didaftarkan sebagai pelapak.</p>
            <div class="lv-kosong__aksi">
                @if ($lvTelpDesa !== '')
                    <a class="lv-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $lvTelpDesa) }}">Hubungi {{ $lvTelpDesa }}</a>
                @endif
                <a class="lv-btn lv-btn--ghost" href="{{ site_url() }}">Kembali ke Beranda</a>
            </div>
        </div>
        <div data-lv-kosong="cari" hidden>
            <h2 class="lv-h3">Produk tidak ditemukan</h2>
            <p data-lv-kosong-pesan></p>
            <div class="lv-kosong__aksi">
                <button type="button" class="lv-btn" data-lv-lapak-reset>Tampilkan semua produk</button>
            </div>
        </div>
        <div data-lv-kosong="gagal" hidden>
            <h2 class="lv-h3">Produk belum dapat dimuat</h2>
            <p>Periksa koneksi internet Anda, lalu coba lagi.</p>
            <div class="lv-kosong__aksi">
                <button type="button" class="lv-btn" data-lv-lapak-ulang>Coba lagi</button>
            </div>
        </div>
    </div>

    <div class="lv-pager" data-lv-lapak-pager hidden></div>
</main>
