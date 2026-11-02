@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman detail pembangunan (tampilan Modern). Data dari api.pembangunan (filter[slug]), dirender skrip beranda
    (pemuat "pembangunan_detail"). Kiri: foto, progres, keterangan & manfaat, lokasi. Kanan: anggaran, sumber biaya, data kegiatan, bagikan.
--}}
@php $lvTautan = site_url('pembangunan/' . ($slug ?? '')); @endphp

<main class="lv-wrap lv-section" style="padding-top: 40px" data-lv-pemb-detail>
    <nav class="lv-crumb" aria-label="Lokasi halaman">
        <a href="{{ site_url() }}">Beranda</a>
        <span aria-hidden="true">›</span>
        <a href="{{ site_url('pembangunan') }}">Pembangunan</a>
        <span aria-hidden="true">›</span>
        <span aria-current="page" data-lv-pemb-crumb>Detail kegiatan</span>
    </nav>

    <div class="lv-pemb-kepala" data-lv-pemb-kepala>
        <div class="lv-skeleton" style="width: 180px" aria-hidden="true"></div>
        <div class="lv-skeleton" style="width: min(100%, 640px); min-height: 44px" aria-hidden="true"></div>
    </div>

    <div class="lv-pemb-detail" data-lv-lazy="pembangunan_detail" data-lv-url="{{ route('api.pembangunan') }}" data-lv-base="{{ site_url('pembangunan') }}"
        data-lv-slug="{{ $slug ?? '' }}" aria-busy="true"
        {{-- Pengaturan peta dikirim per key (tanpa objek setting global) --}}
        data-lv-peta-zoom="{{ setting('default_zoom') }}" data-lv-peta-maks="{{ setting('max_zoom_peta') }}" data-lv-peta-min="{{ setting('min_zoom_peta') }}"
        data-lv-peta-ikon="{{ setting('icon_pembangunan_peta') }}" data-lv-peta-jenis="{{ setting('jenis_peta') }}" data-lv-peta-mapbox="{{ setting('mapbox_key') }}">
        <div class="lv-pemb-detail__utama"><div class="lv-skeleton" style="aspect-ratio: 16 / 9; border-radius: 18px" aria-hidden="true"></div></div>
        <aside class="lv-pemb-detail__samping"><div class="lv-skeleton" style="height: 320px; border-radius: 18px" aria-hidden="true"></div></aside>
    </div>

    <div class="lv-bagikan" aria-label="Bagikan kegiatan" data-lv-pemb-bagikan hidden>
        <span class="lv-bagikan__label">Bagikan</span>
        <a href="https://www.facebook.com/sharer.php?u={{ urlencode($lvTautan) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Facebook">{!! $svg('facebook') !!}</a>
        <a href="https://api.whatsapp.com/send?text={{ urlencode($lvTautan) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke WhatsApp">{!! $svg('wa') !!}</a>
        <a href="https://telegram.me/share/url?url={{ urlencode($lvTautan) }}" target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Telegram">{!! $svg('send') !!}</a>
        <button type="button" data-lv-salin="{{ $lvTautan }}" aria-label="Salin tautan">{!! $svg('link') !!}</button>
        <span class="lv-bagikan__info" data-lv-salin-info role="status" aria-live="polite"></span>
    </div>

    <template data-lv-ikon="map">{!! $svg('map') !!}</template>
    <template data-lv-ikon="users">{!! $svg('users') !!}</template>
</main>

<div data-lv-pemb-tidak-ada hidden>
    @include('theme::partials.beranda.tidak_ditemukan', ['lvTidakDitemukan' => [
        'judul' => 'Kegiatan pembangunan tidak ditemukan',
        'pesan' => 'Kegiatan yang Anda buka tidak ada, belum dipublikasikan, atau sudah dihapus.',
    ]])
</div>

@include('theme::commons.asset_peta')
