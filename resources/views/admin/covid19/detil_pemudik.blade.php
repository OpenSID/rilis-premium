@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>Detil Pemudik</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('covid19.index') }}">Data Pemudik</a></li>
    <li class="active">Detil Pemudik</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => route('covid19.index'), 'label' => 'Data Pemudik'])

            @if (in_array((int) $penduduk?->status, [2, 3], true))
                <a href="#" class="btn btn-social btn-success btn-sm" data-toggle="modal" data-target="#edit-warga">
                    <i class="fa fa-edit"></i>
                    Ubah Data Penduduk Non Domisili
                </a>
            @endif
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Detil Pemudik</h3>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; width: 20%;">NIK /Nama</td>
                            <td> {{ $terdata->terdata_id . ' / ' . $terdata->nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; width: 20%;">Alamat</td>
                            <td> {{ $individu?->alamat_wilayah }} </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; width: 20%;">Tempat Tanggal Lahir (Umur)</td>
                            <td> {{ $individu?->tempatlahir }} {{ tgl_indo($individu?->tanggallahir) }} ({{ $individu?->umur }} Tahun) </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; width: 20%;">Pendidikan</td>
                            <td> {{ $individu?->pendidikan }} </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px; width: 20%;">Warganegara / Agama</td>
                            <td> {{ $individu?->warganegara }} / {{ $individu?->agama }} </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Asal Pemudik</td>
                            <td> {{ $terdata->asal_mudik }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Tiba Tanggal</td>
                            <td> {{ $terdata->tanggal_datang }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Tujuan Mudik</td>
                            <td> {{ $terdata->tujuan_mudik }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Durasi Mudik</td>
                            <td> {{ $terdata->durasi_mudik }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">HP</td>
                            <td> {{ $terdata->no_hp }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Email</td>
                            <td> {{ $terdata->email }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Status Covid-19</td>
                            <td> {{ $terdata->status_covid_nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Keluhan Kesehatan</td>
                            <td> {{ $terdata->keluhan_kesehatan }}</td>
                        </tr>
                        <tr>
                            <td style="padding-top: 10px; padding-bottom: 10px;">Keterangan</td>
                            <td> {{ $terdata->keterangan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-warga" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus text-green"></i> Ubah Penduduk Pendatang / Tidak Tetap</h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        @include('admin.covid19.form_isian_penduduk')
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-success btn-sm" onclick="$('#form_penduduk').submit();"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.covid19.js_isian_penduduk')
    @if (old('nik') !== null)
        <script>
            $(document).ready(function() {
                $('#edit-warga').modal('show');
            });
        </script>
    @endif
@endpush
