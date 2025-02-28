@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Permohonan Surat
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('permohonan_surat_admin') }}"> Daftar Permohonan Surat</a></li>
    <li class="active">Surat Keterangan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Pemohon</h3>
                </div>
                <div class="box-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-3">NIK / Nama Penduduk</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm" readonly="readonly" value="{{ $individu['nik'] . ' - ' . $individu['nama'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Keterangan tambahan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control input-sm" readonly="readonly" rows="3">{{ $periksa['keterangan'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">No HP Aktif</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm" readonly="readonly" value="{{ $periksa['no_hp_aktif'] }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Kelengkapan Dokumen</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokumen</th>
                                    <th>Kelengkapan Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($syarat_permohonan) > 0)
                                    @foreach ($syarat_permohonan as $key => $syarat)
                                        <tr>
                                            <td class="padat">{{ $key + 1 }}</td>
                                            <td>{{ $syarat['ref_syarat_nama'] }}</td>
                                            <td class="padat">
                                                @if ($syarat['dok_id'] == '-1')
                                                    <strong class="text-red"><i class="fa fa-exclamation-triangle text-red"></i>Bawa bukti fisik ke Kantor Desa</strong>
                                                @else
                                                    <a
                                                        href="{{ ci_route("permohonan_surat_admin.tampilkan.{$syarat['dok_id']}.{$periksa['id_pemohon']}") }}"
                                                        class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                                        title="Tampilkan"
                                                        data-target="#modalBox"
                                                        data-remote="false"
                                                        data-toggle="modal"
                                                        data-backdrop="false"
                                                        data-keyboard="false"
                                                        data-title="Tampilkan Dokumen"
                                                    ><i class="fa fa-eye"></i> Cek Dokumen</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="9">Data Tidak Tersedia</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-warning collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Panduan Singkat</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <center><b>Kelengkapan Dokumen</b></center>
                    <p>Periksa setiap dokumen untuk memastikan sesuai dengan persyaratan surat ini. Kalau persyaratan belum lengkap:</p>
                    <ul>
                        <li>Klik tombol Belum Lengkap</li>
                        <li>Beritahu pemohon persyaratan mana yang belum lengkap melalui pesan singkat</li>
                    </ul>
                    <p>Status permohonan akan secara otomatis diubah menjadi 'Belum Lengkap'.</p>
                    <hr>
                    <center><b>Isian Form</b></center>
                    <p>Kalau isian sudah lengkap:</p>
                    <ul>
                        <li>Klik Unduh RTF/Cetak PDF untuk mencetak surat. Lampiran dapat diunduh di Arsip Layanan.</li>
                        <li>Berikan surat kepada petugas untuk ditandatangani</li>
                    </ul>
                    <p>Status permohonan akan secara otomatis diubah menjadi 'Menunggu Tandatangan'.</p>
                    Kalau isian belum lengkap:
                    <ul>
                        <li>Klik tombol Belum Lengkap</li>
                        <li>Beritahu pemohon isian mana yang belum lengkap melalui pesan singkat</li>
                    </ul>
                    <p>Status permohonan akan secara otomatis diubah menjadi 'Belum Lengkap'.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-surat" id="periksa-permohonan">
                @include('admin.permohonan_surat.form_surat_tinymce')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Di form surat ubah isian admin menjadi disabled
            $("#periksa-permohonan .readonly-periksa").attr('disabled', true);
            setTimeout(function() {
                isi_form();
            }, 100);
        });

        function isi_form() {
            var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {
                if (key) {
                    var elem = $('*[name="' + key + '"]');
                    elem.val(value);
                    elem.change();
                    // Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
                    if (elem.is(":hidden")) {
                        var show = $('#' + key + '_show');
                        show.val(value);
                        show.change();
                    }
                }
            });
        }
    </script>
@endpush
