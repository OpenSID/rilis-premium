@extends('admin.layouts.print_layout')

@section('title', "DOKUMENTASI TINDAK LANJUT PENGADUAN MASYARAKAT")

@section('styles')
    <style>
        img.gambar-pengaduan {
            width: 600px;
            height: 300px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection

@section('content')
   <table>
        <tbody>
            <tr>
                <td class="text-center">
                    <img class="logo " src="{{ gambar_desa($desa['logo']) }}" alt="logo-desa">
                    <h1 class="judul">DOKUMENTASI TINDAK LANJUT PENGADUAN MASYARAKAT</h1>
                    <h1 class="judul">
                        PEMERINTAH {!! strtoupper(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten'] . ' <br>' . setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan'] . ' <br>' . setting('sebutan_desa') . ' ' . $desa['nama_desa']) !!}
                    </h1>
                </td>
            </tr>
            <tr>
                <td>
                    <hr class="garis">
                </td>
            </tr>
            <table>
                <tbody>
                    <tr>
                        <td width="20%"><strong>Judul Pengaduan</strong></td>
                        <td width="1%">:</td>
                        <td><b>{{ $pengaduan->judul }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Pelapor</strong></td>
                        <td> : </td>
                        <td><b>{{ $pengaduan->nama }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengaduan</strong></td>
                        <td> : </td>
                        <td><b>{{ tgl_indo2($pengaduan->created_at) }}</b></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td> : </td>
                        <td> {{ \App\Enums\StatusPengaduanEnum::valueOf($pengaduan->status) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Penyelesaian</strong></td>
                        <td> : </td>
                        <td> {{ $pengaduan->tanggal_selesai ? tgl_indo2($pengaduan->tanggal_selesai) : '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Isi Pengaduan</strong></td>
                        <td> : </td>
                        <td> {{ $pengaduan->isi }}</td>
                    </tr>
                </tbody>
            </table>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <table>
                <tbody>
                    @foreach ($dokumentasi as $value)
                        <tr>
                            <td class="text-center">
                                <h4>{{ \App\Enums\JenisDokumentasiPengaduanEnum::valueOf($value->jenis) }}</h4>
                                <img class="gambar-pengaduan" src="{{ to_base64(LOKASI_PENGADUAN . $value->gambar) }}" width="400" height="200" alt="{{ $pengaduan->judul }}">
                                <p>{{ $value->keterangan }}</p>
                                <p><small>Petugas: {{ $value->petugas?->nama ?? '-' }} | {{ tgl_indo2($value->created_at) }}</small></p>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('admin.layouts.components.blok_ttd_pamong', ['total_col' => 12, 'spasi_kiri' => 2, 'spasi_tengah' => 6])
        </tbody>
    </table>
@endsection
