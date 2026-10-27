@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
@php
    $petaDesa = $desa ?? $data_config ?? [];
    // Data peta bisa digambar manual atau diisi otomatis modul Batas Wilayah (Pantau): keduanya bisa kosong sebagian
    $lvAdaKantor = is_numeric($petaDesa['lat'] ?? null) && is_numeric($petaDesa['lng'] ?? null);
    $lvBatas = json_decode(html_entity_decode((string) ($petaDesa['path'] ?? ''), ENT_QUOTES), true);
    $lvAdaBatas = is_array($lvBatas) && isset($lvBatas[0]);
@endphp

<div id="map_wilayah" class="map-front"></div>

<script>
    //Jika posisi kantor desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
    @if ($lvAdaKantor)
        var posisi = [{{ $petaDesa['lat'] }}, {{ $petaDesa['lng'] }}];
        var zoom = {{ ($petaDesa['zoom'] ?? null) ?: 10 }};
    @else
        var posisi = [-1.0546279422758742, 116.71875000000001];
        var zoom = 10;
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    //Style polygon
    var style_polygon = {
        stroke: true,
        color: '#FF0000',
        opacity: 1,
        weight: 2,
        fillColor: '#8888dd',
        fillOpacity: 0.5
    };
    var wilayah_desa = L.map('map_wilayah', options).setView(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var baseLayers = getBaseLayers(wilayah_desa, "{{ setting('mapbox_key') }}", "{{ setting('jenis_peta') }}");

    L.control.layers(baseLayers, null, {
        position: 'topright',
        collapsed: true
    }).addTo(wilayah_desa);

    @if ($lvAdaBatas)
        var polygon_desa = @json($lvBatas);
        // Jangan pakai nama kantor_desa: menimpa marker milik peta kantor
        var batas_wilayah = L.polygon(polygon_desa, style_polygon).bindTooltip("Wilayah Desa").addTo(wilayah_desa);
        wilayah_desa.fitBounds(batas_wilayah.getBounds());

        // Peta dibuat di dalam tab tersembunyi (ukuran 0), sehingga fitBounds di atas
        // menghasilkan zoom 1. Hitung ulang saat tab dibuka (lihat partials/beranda/script)
        wilayah_desa.lvFokus = function () { wilayah_desa.fitBounds(batas_wilayah.getBounds()); };
    @endif
</script>
