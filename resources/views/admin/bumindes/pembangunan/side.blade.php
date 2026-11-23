<div class="box box-widget widget-user-2">
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li @class(['active' => $selectedNav == 'rencana'])><a href="{{ route('bumindes_rencana_pembangunan.index') }}">Buku Rencana Kerja Pembangunan</a></li>
            <li @class(['active' => $selectedNav == 'kegiatan'])><a href="{{ route('bumindes_kegiatan_pembangunan.index') }}">Buku Kegiatan Pembangunan</a></li>
            <li @class(['active' => $selectedNav == 'hasil'])><a href="{{ route('bumindes_hasil_pembangunan.index') }}">Buku Inventaris Hasil-Hasil Pembangunan</a></li>
            <li @class(['active' => $selectedNav == 'kader'])><a href="{{ route('bumindes_kader.index') }}">Buku Kader Pemberdayaan Masyarakat</a></li>
        </ul>
    </div>
</div>
