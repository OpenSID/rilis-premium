@php
    // Helper function to display data for print vs web
    if (! function_exists('displayData')) {
        function displayData($value, $url = null, $aksi = null) {
            if (isset($aksi) && $aksi == 'cetak') {
                return show_zero_as($value, '-');
            } else {
                return $value ? '<a href="' . $url . '">' . $value . '</a>' : '-';
            }
        }
    }
@endphp

@push('css')
    <style type="text/css">
        .italic {
            font-style: italic;
        }
    </style>
@endpush

@if (($aksi ?? null) == 'unduh')
    {{-- Excel (HTML table trick) tidak konsisten membaca CSS class
    bersarang seperti "table.tftable td". Aturan di bawah ini memakai
    selector dasar (tag) supaya tetap terbaca saat dibuka sebagai file
    Excel, dan HANYA aktif saat $aksi == 'unduh' sehingga tampilan
    browser biasa & cetak PDF tidak berubah sama sekali. --}}
    <style type="text/css">
        #tfhover, #tfhover th, #tfhover td {
            border: 1px solid #000000 !important;
            border-collapse: collapse;
        }
    </style>
@endif

<div class="table-responsive">
    <table id="tfhover" class="table table-bordered table-hover tftable lap-bulanan"@if (($aksi ?? null) == 'unduh') border="1" cellspacing="0" cellpadding="3" @endif>
        <thead class="bg-gray">
            <tr>
                <th rowspan="3" width='2%' class="text-center">No</th>
                <th rowspan="3" colspan="2" width='30%' class="text-center">Perincian</th>
                <th colspan="7" width='45%' class="text-center">Penduduk</th>
                <th colspan="3" rowspan="2" width='23%'class="text-center">Keluarga (KK)</th>
            </tr>
            <tr>
                <th colspan="2" class="text-center">WNI</th>
                <th colspan="2" class="text-center">WNA</th>
                <th colspan="3" class="text-center">Jumlah</th>
            </tr>
            <tr>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L+P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L+P</th>
            </tr>
            <tr>
                <th class="text-center italic">1</th>
                <th class="text-center italic" colspan="2">2</th>
                <th class="text-center italic">3</th>
                <th class="text-center italic">4</th>
                <th class="text-center italic">5</th>
                <th class="text-center italic">6</th>
                <th class="text-center italic">7</th>
                <th class="text-center italic">8</th>
                <th class="text-center italic">9</th>
                <th class="text-center italic">10</th>
                <th class="text-center italic">11</th>
                <th class="text-center italic">12</th>
            </tr </thead>
        <tbody>
            <tr>
                <td class="no_urut">1</td>
                <td colspan="2">Penduduk/Keluarga awal bulan ini</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'] + ($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK'], route('laporan.detail_penduduk', ['rincian' => 'awal', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">2</td>
                <td colspan="2">Kelahiran/Keluarga baru bulan ini</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'] + $kelahiran['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_P'] + $kelahiran['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'] + $kelahiran['WNA_L'] + ($kelahiran['WNI_P'] + $kelahiran['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK'], route('laporan.detail_penduduk', ['rincian' => 'lahir', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">3</td>
                <td colspan="2">Kematian bulan ini</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'] + $kematian['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_P'] + $kematian['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'] + $kematian['WNA_L'] + ($kematian['WNI_P'] + $kematian['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK'], route('laporan.detail_penduduk', ['rincian' => 'mati', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">4</td>
                <td colspan="2">Pendatang bulan ini</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'] + $pendatang['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_P'] + $pendatang['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'] + $pendatang['WNA_L'] + ($pendatang['WNI_P'] + $pendatang['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK'], route('laporan.detail_penduduk', ['rincian' => 'datang', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">5</td>
                <td colspan="2">Pindah/Keluarga pergi bulan ini</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'] + $pindah['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_P'] + $pindah['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'] + $pindah['WNA_L'] + ($pindah['WNI_P'] + $pindah['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK'], route('laporan.detail_penduduk', ['rincian' => 'pindah', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">6</td>
                <td colspan="2">Penduduk hilang bulan ini</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'] + $hilang['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_P'] + $hilang['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'] + $hilang['WNA_L'] + ($hilang['WNI_P'] + $hilang['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK'], route('laporan.detail_penduduk', ['rincian' => 'hilang', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">7</td>
                <td colspan="2">Penduduk/Keluarga akhir bulan ini</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'wni_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_P'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'wni_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'wna_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'wna_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'jml_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'jml_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'] + ($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P']), route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'jml']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK_L'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'kk_l']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK_P'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'kk_p']), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK'], route('laporan.detail_penduduk', ['rincian' => 'akhir', 'tipe' => 'kk']), $aksi ?? null) !!}</td>
            </tr>
        </tbody>
    </table>
</div>