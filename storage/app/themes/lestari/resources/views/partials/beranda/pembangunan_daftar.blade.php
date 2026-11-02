@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman daftar pembangunan (tampilan Modern). Data dari api.pembangunan (semua halaman), dirender skrip beranda
    (pemuat "pembangunan_daftar"); filter tahun, pencarian, ringkasan, dan "tampilkan lebih banyak" dikerjakan di browser
    karena API belum menyediakan filter tahun & ringkasan anggaran. Kartu sama dengan bagian Pembangunan di beranda.
--}}
@php $lvTelpDesa = trim((string) ($desa['telepon'] ?? '')); @endphp

<main class="lv-wrap lv-section" style="padding-top: 40px" data-lv-pemb>
    <nav class="lv-crumb" aria-label="Lokasi halaman">
        <a href="{{ site_url() }}">Beranda</a>
        <span aria-hidden="true">›</span>
        <span aria-current="page">Pembangunan</span>
    </nav>

    <div class="lv-head">
        <div>
            <h1 class="lv-h2">Pembangunan {{ $sebutanDesa }}</h1>
            <p class="lv-daftar__ket">Transparansi kegiatan pembangunan {{ $namaDesa }}: anggaran, realisasi, dan progres di lapangan.</p>
        </div>
        <div class="lv-search lv-lapak__cari" data-lv-pemb-isi hidden>
            <form role="search" data-lv-pemb-cari>
                <label class="lv-sr" for="lv-pemb-cari">Cari kegiatan pembangunan</label>
                <input id="lv-pemb-cari" type="search" maxlength="50" placeholder="Cari kegiatan atau lokasi, mis. irigasi" autocomplete="off">
                <button class="lv-btn" type="submit">Cari</button>
            </form>
        </div>
    </div>

    {{-- Ringkasan mengikuti tahun yang dipilih --}}
    <div class="lv-pemb-ringkas" aria-live="polite" data-lv-pemb-isi hidden>
        <div><span>Kegiatan</span><b data-lv-pemb-jumlah>0</b></div>
        <div><span>Total anggaran</span><b data-lv-pemb-anggaran>Rp 0</b></div>
        <div><span>Realisasi anggaran</span><b data-lv-pemb-realisasi>Rp 0</b></div>
        <div><span>Selesai</span><b data-lv-pemb-selesai>0</b></div>
    </div>

    <div class="lv-tabs lv-lapak__kategori" role="group" aria-label="Tahun anggaran" data-lv-pemb-tahun hidden>
        <button type="button" aria-pressed="true" data-tahun="">Semua tahun</button>
    </div>

    <p class="lv-meta lv-lapak__info" data-lv-pemb-info aria-live="polite"></p>

    <div class="lv-grid lv-grid-3 lv-pemb__grid" data-lv-pemb-grid data-lv-lazy="pembangunan_daftar" data-lv-url="{{ route('api.pembangunan') }}"
        data-lv-base="{{ site_url('pembangunan') }}" aria-busy="true">
        @for ($i = 0; $i < 6; $i++)
            <div class="lv-skeleton lv-lapak__kerangka" aria-hidden="true"></div>
        @endfor
    </div>

    <div class="lv-kosong" data-lv-pemb-kosong hidden>
        <span class="lv-kosong__ikon">{!! $svg('search') !!}</span>
        <div>
            <h2 class="lv-h3">Kegiatan tidak ditemukan</h2>
            <p data-lv-pemb-kosong-pesan></p>
            <div class="lv-kosong__aksi"><button type="button" class="lv-btn" data-lv-pemb-reset>Tampilkan semua kegiatan</button></div>
        </div>
    </div>

    <div class="lv-kosong" data-lv-pemb-keadaan="belum" hidden>
        <span class="lv-kosong__ikon">{!! $svg('build') !!}</span>
        <div>
            <h2 class="lv-h3">Belum ada data pembangunan</h2>
            <p>Kegiatan pembangunan {{ strtolower($sebutanDesa) }} akan tampil di sini beserta anggaran dan progresnya setelah dicatat oleh perangkat {{ strtolower($sebutanDesa) }}.</p>
            <div class="lv-kosong__aksi">
                @if ($lvTelpDesa !== '')
                    <a class="lv-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $lvTelpDesa) }}">Hubungi {{ $lvTelpDesa }}</a>
                @endif
                <a class="lv-btn lv-btn--ghost" href="{{ site_url() }}">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <div class="lv-kosong" data-lv-pemb-keadaan="gagal" hidden>
        <span class="lv-kosong__ikon">{!! $svg('build') !!}</span>
        <div>
            <h2 class="lv-h3">Data pembangunan belum dapat dimuat</h2>
            <p>Periksa koneksi internet Anda, lalu muat ulang halaman.</p>
        </div>
    </div>

    <div class="lv-pemb__lagi">
        <button type="button" class="lv-btn lv-btn--ghost" data-lv-pemb-lagi hidden>Tampilkan lebih banyak</button>
    </div>
</main>
