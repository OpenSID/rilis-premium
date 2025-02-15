@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-blue">
            <h4 class="box-title">Profil</h4>
        </div>
        <div class="box-body box-line">
            <h4><b>BIODATA PENDUDUK</b></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data">
                    <tbody>
                        <tr>
                            <th colspan="3" class="judul">Data Dasar</th>
                        </tr>
                        <tr>
                            <td width="30%">NIK</td>
                            <td class="padat">:</td>
                            <td>{{ $penduduk->nik }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->nama) }}</td>
                        </tr>
                        <tr>
                            <td>Status Kepemilikan KTP</td>
                            <td>:</td>
                            <td>
                                <table class="table table-bordered table-hover table-data">
                                    <tbody>
                                        <tr class="judul">
                                            <th>Wajib KTP</th>
                                            <th>KTP-EL</th>
                                            <th>Status Rekam</th>
                                            <th>Tag ID Card</th>
                                        </tr>
                                        <tr>
                                            <td>{{ strtoupper($penduduk->wajib_ktp) }}</td>
                                            <td>{{ strtoupper(array_flip(unserialize(KTP_EL))[$penduduk->ktp_el]) }}</td>
                                            <td>{{ strtoupper(App\Enums\StatusKTPEnum::valueOf($penduduk->status_rekam)) }}</td>
                                            <td>{{ $penduduk->tag_id_card }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Kartu Keluarga</td>
                            <td>:</td>
                            <td>
                                {{ $penduduk->keluarga->no_kk }}
                                @if ($penduduk->status_dasar != '1' && $penduduk->no_kk != $penduduk->log_no_kk)
                                    ( waktu peristiwa [{{ $penduduk->status_dasar }}]: [{{ $penduduk->log_no_kk }}] )
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor KK Sebelumnya</td>
                            <td>:</td>
                            <td>{{ $penduduk->no_kk_sebelumnya }}</td>
                        </tr>
                        <tr>
                            <td>Hubungan Dalam Keluarga</td>
                            <td>:</td>
                            <td>{{ App\Enums\SHDKEnum::valueOf($penduduk->kk_level) }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($penduduk->sex)) }}</td>
                        </tr>
                        <tr>
                            <td>Agama</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->agama->nama) }}</td>
                        </tr>
                        <tr>
                            <td>Status Penduduk</td>
                            <td>:</td>
                            <td>{{ strtoupper(App\Enums\StatusPendudukEnum::valueOf($penduduk->status)) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Kelahiran</th>
                        </tr>
                        <tr>
                            <td>Akta Kelahiran</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->akta_lahir) }}</td>
                        </tr>
                        <tr>
                            <td>Tempat / Tanggal Lahir</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->tempatlahir) }} / {{ strtoupper($penduduk->tanggallahir?->format('d-m-Y')) }}</td>
                        </tr>
                        <tr>
                            <td>Tempat Dilahirkan</td>
                            <td>:</td>
                            <td>{{ $penduduk->dilahirkan }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelahiran</td>
                            <td>:</td>
                            <td>{{ $penduduk->jenisLahir }}</td>
                        </tr>
                        <tr>
                            <td>Kelahiran Anak Ke</td>
                            <td>:</td>
                            <td>{{ $penduduk->kelahiran_anak_ke }}</td>
                        </tr>
                        <tr>
                            <td>Penolong Kelahiran</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->penolongLahir) }}</td>
                        </tr>
                        <tr>
                            <td>Berat Lahir</td>
                            <td>:</td>
                            <td>{{ $penduduk->berat_lahir }} Gram</td>
                        </tr>
                        <tr>
                            <td>Panjang Lahir</td>
                            <td>:</td>
                            <td>{{ $penduduk->panjang_lahir }} cm</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Pendidikan dan Pekerjaan</th>
                        </tr>
                        <tr>
                            <td>Pendidikan dalam KK</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->pendidikanKK->nama) }}</td>
                        </tr>
                        <tr>
                            <td>Pendidikan sedang ditempuh</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->pendidikan) }}</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->pekerjaan->nama) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Kewarganegaraan</th>
                        </tr>
                        <tr>
                            <td>Suku/Etnis</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->suku) }}</td>
                        </tr>
                        <tr>
                            <td>Warga Negara</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->warganegara->nama) }}</td>
                        </tr>
                        <tr>
                            <td>Nomor Paspor</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->dokumen_pasport) }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Berakhir Paspor</td>
                            <td>:</td>
                            <td>{{ strtoupper(tgl_indo_out($penduduk->tanggal_akhir_paspor)) }}</td>
                        </tr>
                        <tr>
                            <td>Nomor KITAS/KITAP</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->dokumen_kitas) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Orang Tua</th>
                        </tr>
                        <tr>
                            <td>NIK Ayah</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->ayah_nik) }}</td>
                        </tr>
                        <tr>
                            <td>Nama Ayah</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->nama_ayah) }}</td>
                        </tr>
                        <tr>
                            <td>NIK Ibu</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->ibu_nik) }}</td>
                        </tr>
                        <tr>
                            <td>Nama Ibu</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->nama_ibu) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Alamat</th>
                        </tr>
                        <tr>
                            <td>Nomor Telepon</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->telepon) }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Email</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->email) }}</td>
                        </tr>
                        <tr>
                            <td>Telegram</td>
                            <td>:</td>
                            <td>{{ $penduduk->telegram }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->keluarga->alamat) }}</td>
                        </tr>
                        <tr>
                            <td>Dusun</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->wilayah->dusun) }}</td>
                        </tr>
                        <tr>
                            <td>RT/ RW</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->wilayah->rt) }} / {{ $penduduk->wilayah->rw }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Sebelumnya</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->alamat_sebelumnya) }}</td>
                        </tr>
                        <tr>
                            <th colspan="3" class="judul">Data Perkawinan</th>
                        </tr>
                        <tr>
                            <td>Status Kawin</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->statusPerkawinan) }}</td>
                        </tr>
                        @if ($penduduk->status_kawin != 1)
                            <tr>
                                <td>Akta perkawinan</td>
                                <td>:</td>
                                <td>{{ strtoupper($penduduk->akta_perkawinan) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal perkawinan</td>
                                <td>:</td>
                                <td>{{ $penduduk->tanggalperkawinan ? date('d-m-Y', strtotime($penduduk->tanggalperkawinan)) : '' }}</td>
                            </tr>
                        @endif
                        @if ($penduduk->status_kawin != 1 && $penduduk->status_kawin != 2)
                            <tr>
                                <td>Akta perceraian</td>
                                <td>:</td>
                                <td>{{ strtoupper($penduduk->akta_perceraian) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal perceraian</td>
                                <td>:</td>
                                <td>{{ strtoupper($penduduk->tanggalperceraian) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="3" class="judul">Data Kesehatan</th>
                        </tr>
                        <tr>
                            <td>Golongan Darah</td>
                            <td>:</td>
                            <td>{{ $penduduk->golonganDarah->nama ?? 'TIDAK TAHU' }}</td>
                        </tr>
                        <tr>
                            <td>Cacat</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->cacat->nama) }}</td>
                        </tr>
                        <tr>
                            <td>Sakit Menahun</td>
                            <td>:</td>
                            <td>{{ strtoupper($penduduk->sakit_menahun) }}</td>
                        </tr>
                        @if ($penduduk->status_kawin == App\Enums\StatusKawinEnum::KAWIN)
                            <tr>
                                <td>Akseptor KB</td>
                                <td>:</td>
                                <td>{{ strtoupper($penduduk->cara_kb) }}</td>
                            </tr>
                        @endif
                        @if ($penduduk->id_sex == App\Enums\JenisKelaminEnum::PEREMPUAN)
                            <tr>
                                <td>Status Kehamilan</td>
                                <td>:</td>
                                <td>{{ strtoupper(App\Enums\HamilEnum::valueOf($penduduk->hamil)) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>Nama/Nomor Asuransi Kesehatan</td>
                            <td>:</td>
                            <td>{{ $penduduk->asuransi->nama . ' / ' . strtoupper($penduduk->no_asuransi) }}</td>
                        </tr>
                        <tr>
                            <td>Nomor BPJS Ketenagakerjaan</td>
                            <td>:</td>
                            <td>{{ $penduduk->bpjs_ketenagakerjaan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box-body box-line">
            <h4><b>KEANGGOTAAN KELOMPOK</b></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-data datatable-polos">
                    <thead>
                        <tr>
                            <th width="padat">No</th>
                            <th width="80%">Nama Kelompok</th>
                            <th width="20%">Kategori Kelompok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($kelompok)
                            @foreach ($kelompok as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item?->kelompok->nama }}</td>
                                    <td>{{ $item?->kelompok?->kelompokMaster?->kelompok }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="3">Data tidak tersedia</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
