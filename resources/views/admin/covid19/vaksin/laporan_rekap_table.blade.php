@php
    $kosong = ['vaksin_1' => 0, 'vaksin_2' => 0, 'vaksin_3' => 0, 'belum' => 0];
    $persen = static fn (int|float $nilai, int|float $pembagi): string => persen($pembagi > 0 ? $nilai / $pembagi : 0, '', 2);
@endphp
<div class="table-responsive">
    <table id="tabel-data" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
        <thead class="bg-gray color-palette">
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3">{{ ucwords(setting('sebutan_dusun')) }}</th>
                <th rowspan="3">Jumlah Penduduk</th>
                <th rowspan="3">Jumlah Sasaran</th>
                <th rowspan="1" colspan="5">Sasaran Vaksin</th>
                <th rowspan="1" colspan="4">Presentase</th>
            </tr>
            <tr>
                <th colspan="4">Sudah</th>
                <th colspan="1" rowspan="2">Belum</th>
                <th colspan="1" rowspan="2">% V1</th>
                <th colspan="1" rowspan="2">% V2</th>
                <th colspan="1" rowspan="2">% V3</th>
                <th colspan="1" rowspan="2">% Belum Vaksin</th>
            </tr>
            <tr>
                <td>v1</td>
                <td>v2</td>
                <td>v3</td>
                <td>Jumlah</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($main['detail'] as $key => $data)
                @php
                    $sasaranVaksin = $sasaran['detail'][$key] ?? $kosong;
                    $jumlahSasaran = $sasaranVaksin['vaksin_1'] + $sasaranVaksin['belum'];
                @endphp
                <tr>
                    <td class="padat">{{ $loop->iteration }}</td>
                    <td>{{ $key }}</td>
                    <td class="padat">{{ $data['vaksin_1'] + $data['belum'] }}</td>
                    <td class="padat">{{ $jumlahSasaran }}</td>
                    <td class="padat">{{ $sasaranVaksin['vaksin_1'] }}</td>
                    <td class="padat">{{ $sasaranVaksin['vaksin_2'] }}</td>
                    <td class="padat">{{ $sasaranVaksin['vaksin_3'] }}</td>
                    <td class="padat">{{ $sasaranVaksin['vaksin_1'] }}</td>
                    <td class="padat">{{ $sasaranVaksin['belum'] }}</td>
                    <td class="padat">{{ $persen($sasaranVaksin['vaksin_1'], $jumlahSasaran) }}</td>
                    <td class="padat">{{ $persen($sasaranVaksin['vaksin_2'], $jumlahSasaran) }}</td>
                    <td class="padat">{{ $persen($sasaranVaksin['vaksin_3'], $jumlahSasaran) }}</td>
                    <td class="padat">{{ $persen($sasaranVaksin['belum'], $jumlahSasaran) }}</td>
                </tr>
            @endforeach
            @php $totalSasaran = $sasaran['total_v1'] + $sasaran['total_belum']; @endphp
            <tr class="text-bold">
                <td colspan="2">JUMLAH</td>
                <td class="padat">{{ $main['total_v1'] + $main['total_belum'] }}</td>
                <td class="padat">{{ $totalSasaran }}</td>
                <td class="padat">{{ $sasaran['total_v1'] }}</td>
                <td class="padat">{{ $sasaran['total_v2'] }}</td>
                <td class="padat">{{ $sasaran['total_v3'] }}</td>
                <td class="padat">{{ $sasaran['total_v1'] }}</td>
                <td class="padat">{{ $sasaran['total_belum'] }}</td>
                <td class="padat">{{ $persen($sasaran['total_v1'], $totalSasaran) }}</td>
                <td class="padat">{{ $persen($sasaran['total_v2'], $totalSasaran) }}</td>
                <td class="padat">{{ $persen($sasaran['total_v3'], $totalSasaran) }}</td>
                <td class="padat">{{ $persen($sasaran['total_belum'], $totalSasaran) }}</td>
            </tr>
        </tbody>
    </table>
</div>
