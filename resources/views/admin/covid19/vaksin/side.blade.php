<div class="box-footer no-padding">
    <ul class="nav nav-stacked">
        <li @class(['active' => $selected_nav == 'daftar'])><a href="{{ url('vaksin_covid') }}">Daftar Penerima</a></li>
        <li @class(['active' => $selected_nav == 'laporan'])><a href="{{ url('vaksin_covid/laporan_penduduk/1') }}">Laporan Penduduk</a></li>
        <li @class(['active' => $selected_nav == 'rekap'])><a href="{{ url('vaksin_covid/laporan_rekap') }}">Laporan Rekap</a></li>
    </ul>
</div>
