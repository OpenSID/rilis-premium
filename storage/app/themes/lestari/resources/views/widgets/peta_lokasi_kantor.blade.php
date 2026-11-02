@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
@php
    $petaDesa = $desa ?? $data_config ?? [];
    // Data peta bisa digambar manual atau diisi otomatis modul Batas Wilayah (Pantau): keduanya bisa kosong sebagian
    $lvAdaKantor = is_numeric($petaDesa['lat'] ?? null) && is_numeric($petaDesa['lng'] ?? null);
@endphp

<div id="map_canvas" class="map-front"></div>

<script>
    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if ($lvAdaKantor)
        var posisi = [{{ $petaDesa['lat'] }}, {{ $petaDesa['lng'] }}];
        // Citra satelit umumnya kosong (hitam) di atas zoom 18
        var zoom = Math.min({{ ($petaDesa['zoom'] ?? null) ?: 10 }}, 18);
    @else
        var posisi = [-1.0546279422758742, 116.71875000000001];
        var zoom = 10;
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

    // Dipanggil saat tab peta dibuka kembali (lihat partials/beranda/script)
    lokasi_kantor.lvFokus = (function (pusat, z) {
        return function () { lokasi_kantor.setView(pusat, z); };
    })(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(lokasi_kantor, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");

    L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: true
    }).addTo(lokasi_kantor);

    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if ($lvAdaKantor)
        var kantor_desa = L.marker(posisi).addTo(lokasi_kantor);
    @endif
</script>
