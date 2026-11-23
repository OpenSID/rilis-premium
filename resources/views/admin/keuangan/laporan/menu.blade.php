<div id="penduduk" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Grafik Laporan Keuangan</h3>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@active($submenu == 'Grafik Keuangan')"><a href="{{ route('keuangan_laporan.index') }}?jenis=grafik-RP-APBD-manual&tahun={{ $tahun }}">Grafik Pelaksanaan APBDes</a></li>
        </ul>
    </div>
</div>
<div id="penduduk" class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Tabel Laporan (Belanja Per Bidang)</h3>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li class="@active($submenu == 'Laporan Keuangan Akhir Bidang Manual')"><a href="{{ route('keuangan_laporan.index') }}?jenis=rincian_realisasi_bidang_manual&tahun={{ $tahun }}">Laporan Pelaksanaan APBDes Manual</a></li>
        </ul>
    </div>
</div>
