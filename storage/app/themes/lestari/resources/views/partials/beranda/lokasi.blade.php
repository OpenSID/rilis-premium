@php
    $lvTelp = $desa['telepon'] ?? null;
    // Titik kantor & batas wilayah bisa digambar manual atau diisi modul Batas Wilayah (Pantau), dan bisa kosong sebagian.
    // Tab hanya ditampilkan untuk peta yang datanya ada; tanpa keduanya, cukup kartu alamat kantor.
    $lvAdaKantor = is_numeric($desa['lat'] ?? null) && is_numeric($desa['lng'] ?? null);
    $lvBatas = json_decode(html_entity_decode((string) ($desa['path'] ?? ''), ENT_QUOTES), true);
    $lvAdaBatas = is_array($lvBatas) && isset($lvBatas[0]);
@endphp

<section class="lv-wrap lv-section" style="padding-bottom: 72px" aria-labelledby="lv-lokasi-judul">
    <div class="lv-head" data-lv-tabs>
        <h2 class="lv-h2" id="lv-lokasi-judul">Lokasi &amp; Wilayah</h2>
        @if ($lvAdaKantor && $lvAdaBatas)
            <div class="lv-tabs" role="tablist" aria-label="Peta" style="margin: 0; background: var(--band)">
                <button type="button" role="tab" id="lv-peta-tab-kantor" aria-controls="lv-peta-kantor" aria-selected="true" data-lv-map="lokasi_kantor">Kantor {{ $sebutanDesa }}</button>
                <button type="button" role="tab" id="lv-peta-tab-wilayah" aria-controls="lv-peta-wilayah" aria-selected="false" tabindex="-1" data-lv-map="wilayah_desa">Wilayah {{ $sebutanDesa }}</button>
            </div>
        @endif
    </div>
    <div @class(['lv-map', 'lv-map--tanpa-peta' => !$lvAdaKantor && !$lvAdaBatas])>
        @if ($lvAdaKantor)
            <div class="lv-map__pane" @if ($lvAdaBatas) role="tabpanel" aria-labelledby="lv-peta-tab-kantor" @endif id="lv-peta-kantor">
                @include('theme::widgets.peta_lokasi_kantor')
            </div>
        @endif
        @if ($lvAdaBatas)
            <div class="lv-map__pane" @if ($lvAdaKantor) role="tabpanel" aria-labelledby="lv-peta-tab-wilayah" hidden @endif id="lv-peta-wilayah">
                @include('theme::widgets.peta_wilayah_desa')
            </div>
        @endif
        <div class="lv-map__info">
            <h3 class="lv-h3">Kantor {{ $namaDesa }}</h3>
            <p style="font-size: 15px; color: var(--body)">{{ $desa['alamat_kantor'] }}, {{ $wilayahDesa }}</p>
            <p style="font-size: 15px">
                @if ($lvTelp)<span style="display: block">Telp. {{ $lvTelp }}</span>@endif
                @if (!empty($desa['email_desa']))<span style="display: block">{{ $desa['email_desa'] }}</span>@endif
            </p>
            @if ($lvAdaKantor)
                <a class="lv-btn" style="margin-top: auto" href="https://www.google.com/maps/dir/?api=1&destination={{ $desa['lat'] }},{{ $desa['lng'] }}" target="_blank" rel="noopener noreferrer">Petunjuk Arah</a>
            @endif
        </div>
    </div>
</section>
