@php $tanggal = static fn (?string $tgl, string $kosong = '-'): string => ($tgl && $tgl !== '0000-00-00') ? date('d-m-Y', strtotime($tgl)) : $kosong; @endphp
<div class="text-center">
    <h3> DATA PENDUDUK YANG TELAH MENERIMA VAKSIN COVID-19</h3>
    <h4>{{ strtoupper(setting('sebutan_desa')) }} {{ strtoupper($config['nama_desa']) }} {{ strtoupper(setting('sebutan_kecamatan')) }} {{ strtoupper($config['nama_kecamatan']) }}</h4>
    <h4>{{ strtoupper(setting('sebutan_kabupaten')) }} {{ strtoupper($config['nama_kabupaten']) }}</h4>
</div>
<br>
<table id="example" class="list border thick">
    <thead>
        <tr class="text-center">
            <th rowspan="2">No</th>
            <th rowspan="2">No KK</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Nik</th>
            <th rowspan="2">Alamat {{ ucwords(setting('sebutan_dusun')) }}</th>
            <th rowspan="2">Jenis Kelamin</th>
            <th rowspan="2">Tempat Lahir</th>
            <th rowspan="2">Tanggal Lahir</th>
            <th rowspan="2">Umur</th>
            <th colspan="6">Vaksin</th>
        </tr>
        <tr class="text-center">
            <th>I</th>
            <th>II</th>
            <th>III</th>
            <th>Belum</th>
            <th>Tunda</th>
            <th>Ket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $key => $data)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td class="textx">{{ $data->no_kk }}</td>
                <td>{{ $data->nama }}</td>
                <td class="textx">{{ $data->nik }}</td>
                <td>{{ $data->dusun }}</td>
                <td>{{ $data->jenis_kelamin }}</td>
                <td>{{ $data->tempatlahir }}</td>
                <td class="padat">{{ $tanggal($data->tanggallahir) }}</td>
                <td>{{ $data->umur }}</td>
                <td class="padat">
                    @if ($data->vaksin_1 == 1 && $data->tunda == 0)
                        V
                    @endif
                </td>
                <td class="padat">
                    @if ($data->vaksin_2 == 1 && $data->tunda == 0)
                        V
                    @endif
                </td>
                <td class="padat">
                    @if ($data->vaksin_3 == 1 && $data->tunda == 0)
                        V
                    @endif
                </td>
                <td class="padat">
                    @if ($data->vaksin_1 == null || ($data->tunda == 0 && $data->vaksin_1 == 0))
                        V
                    @endif
                </td>
                <td class="padat">
                    @if ($data->tunda == 1)
                        V
                    @endif
                </td>
                <td class="padat">
                    @if ($data->tunda == 1)
                        {{ $data->keterangan }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
