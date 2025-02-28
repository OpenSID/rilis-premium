@include('admin.pengaturan_surat.asset_tinymce')

@extends('admin.layouts.index')

@php
    $label = 'Konsep Surat';
    $urlDaftar = ci_route('surat');
    $cetak = 'Cetak';

    if ($urlSurat = old('url_surat')) {
        $cetak = 'Form Isian';
        $hash = $penduduk?->nik ? "#{$penduduk->id}#{$penduduk->nik}#{$penduduk->nama}" : '';
        $urlDaftar = site_url("surat/form/{$urlSurat}{$hash}");
    }
@endphp

@isset($ubah)
    @php
        $label = 'Ubah Surat';
        $urlDaftar = ci_route('keluar');
        $cetak = 'Arsip Layanan';
    @endphp
@endisset

@section('title')
    <h1>
        {{ $label }} {{ ucwords($surat->nama) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ $urlDaftar }}">Daftar {{ $cetak }} Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat->nama) }}</li>
    <li class="active"> {{ $label }} {{ ucwords($surat->nama) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box">
        {!! form_open(null, 'id="validasi"') !!}
        <div class="nav-tabs-custom">
            <!-- Tabs navigation -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#{{ $surat->formatSurat->url_surat ?? $surat->url_surat }}" data-toggle="tab">{{ $surat->formatSurat->judul_surat ?? $surat->judul_surat }}</a></li>
                @foreach ($lampiran as $key => $tab)
                    <li><a href="#{{ $loop->index }}" data-toggle="tab">{{ $key }}</a></li>
                @endforeach
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="{{ $surat->formatSurat->url_surat ?? $surat->url_surat }}">
                    <div class="box-body">
                        <input type="hidden" id="id_surat" value="{{ $id_surat }}">
                        <div class="form-group">
                            <textarea name="isi_surat" data-filemanager='<?= json_encode(['external_filemanager_path'=> base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) ?>' data-salintemplate="isi" class="form-control input-sm editor required">{{ $isi_surat }}</textarea>
                        </div>
                    </div>
                </div>
                @foreach ($lampiran as $kode => $isiLampiran)
                    <div class="tab-pane" id="{{ $loop->index }}">
                        <div class="box-body">
                            <div class="form-group">
                                <textarea name="isi_lampiran[]" data-filemanager='<?= json_encode(['external_filemanager_path'=> base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) ?>' 
                                        data-salintemplate="isi" 
                                        class="form-control input-sm lampiran required">
                                    {{ $isiLampiran }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="box-footer text-center">
            <a href="{{ $urlDaftar }}" id="back" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i>Kembali ke {{ $cetak }} Surat
            </a>
            @if ($tolak != '-1' && !$ubah)
                <a onclick="formAction('validasi', '{{ $aksi_konsep }}')" id="konsep" class="btn btn-social btn-warning btn-sm"><i class="fa fa-file-code-o"></i>
                    Konsep</a>
            @endif
            <button type="button" id="preview-pdf" class="btn btn-social btn-vk btn-success btn-sm"><i class="fa fa-eye"></i>Tinjau PDF</button>
            <button type="button" id="pengaturan" title="Pengaturan PDF" data-toggle="modal" data-target="#modal-pengaturan" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-gear"></i> Pengaturan
            </button>
            @if ($tolak != '-1')
                <a href="{{ ci_route('keluar/masuk') }}" id="next" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hide">
                    ke Permohonan Surat <i class="fa fa-arrow-circle-right"></i>
                @else
                    <a href="{{ ci_route('keluar/ditolak') }}" id="next" style="display:none" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        Ke Daftar Surat Ditolak <i class="fa fa-arrow-circle-right"></i>
            @endif

            </a>
        </div>
        </form>
    </div>

    <div id="modal-pengaturan" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form id="form-pengaturan">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Pengaturan</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tinggi Header Surat</label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    name="tinggi_header"
                                    class="form-control input-sm required"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="{{ $ci->session->pengaturan_surat['tinggi_header'] ?? setting('tinggi_header') }}"
                                />
                                <span class="input-group-addon input-sm">cm</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tinggi Footer Surat</label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    name="tinggi_footer"
                                    class="form-control input-sm required"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    value="{{ $ci->session->pengaturan_surat['tinggi_footer'] ?? setting('tinggi_footer') }}"
                                />
                                <span class="input-group-addon input-sm">cm</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis Font Bawaan </label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="select2 form-control" name="font_surat">
                                        @foreach ($font_option as $font)
                                            <option value="{{ $font }}" @selected($font == ($ci->session->pengaturan_surat['font_surat'] ?? setting('font_surat')))>
                                                {{ $font }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Margin</label>
                            <div class="row">
                                @foreach ($margins as $key => $value)
                                    <div class="col-sm-6">
                                        <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                                            <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                                            <input
                                                type="number"
                                                class="form-control input-sm required"
                                                min="0"
                                                name="surat_margin[{{ $key }}]"
                                                min="0"
                                                max="10"
                                                step="0.01"
                                                style="text-align:right;"
                                                value="{{ json_decode($ci->session->pengaturan_surat['surat_margin'])->{$key} ?? $value }}"
                                            >
                                            <span class="input-group-addon input-sm">cm</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! batal() !!}
                        <button type="submit" class="btn btn-social btn-info btn-sm confirm"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var ubah = `{{ $ubah ? '?ubah=1' : '' }}`;

        function cetak_pdf() {
            tinymce.triggerSave();
            Swal.fire({
                title: 'Membuat Surat..',
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: () => false
            })
            $.ajax({
                    url: `{{ $aksi_cetak }}` + ubah,
                    type: 'POST',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: $("#validasi").serialize(),
                    success: function(response, status, xhr) {
                        // https://stackoverflow.com/questions/34586671/download-pdf-file-using-jquery-ajax
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(
                                /['"]/g, '');
                        }
                        var linkelem = document.createElement('a');
                        try {
                            var blob = new Blob([response], {
                                type: 'application/octet-stream'
                            });
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);

                                if (filename) {
                                    // use HTML5 a[download] attribute to specify filename
                                    var a = document.createElement("a");

                                    // safari doesn't support this yet
                                    if (typeof a.download === 'undefined') {
                                        window.location = downloadUrl;
                                    } else {
                                        a.href = downloadUrl;
                                        a.download = filename;
                                        document.body.appendChild(a);
                                        a.target = "_blank";
                                        a.click();
                                    }
                                } else {
                                    window.location = downloadUrl;
                                }
                            }
                        } catch (ex) {
                            alert(ex); // This is an error
                        }
                    }
                })
                .done(function(response, textStatus, xhr) {
                    if (xhr.status == 200) {
                        $('#pengaturan').remove();
                        $('#draft-pdf').hide();
                        $('#preview-pdf').hide();
                        $('#konsep').hide();
                        $('#back').remove();
                        $('#next').show();
                        $('#next').removeClass('hide');
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Surat Selesai Dibuat',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
                .fail(function(response, status, xhr) {

                    Swal.fire({
                        title: xhr.statusText,
                        icon: 'error',
                        text: response.statusText,
                    })
                });
        }

        $(function() {

            $('#preview-pdf').click(function(e) {
                e.preventDefault();
                tinymce.triggerSave();

                Swal.fire({
                    title: 'Membuat pratinjau..',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => false
                });

                $.ajax({
                    url: `{{ $aksi_cetak . '/true' }}` + ubah,
                    type: 'POST',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: $("#validasi").serialize(),
                    success: function(response, status, xhr) {
                        // https://stackoverflow.com/questions/34586671/download-pdf-file-using-jquery-ajax
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(
                                /['"]/g, '');
                        }
                        try {
                            var blob = new Blob([response], {
                                type: 'application/pdf'
                            });
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);
                                Swal.fire({
                                    customClass: {
                                        popup: 'swal-lg'
                                    },
                                    title: 'Pratinjau',
                                    html: `
                                        <object data="${downloadUrl}#toolbar=0" style="width: 100%;min-height: 400px;" type="application/pdf"></object>
                                    `,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    footer: '<button onclick="Swal.close()" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Tutup</button>&ensp;<button onclick="cetak_pdf()" class="btn btn-social btn-success btn-sm"><i class="fa fa-print"></i> Cetak</button>',
                                    allowOutsideClick: () => false
                                });
                            }
                        } catch (ex) {
                            alert(ex); // This is an error
                        }
                    }
                }).fail(function(response, status, xhr) {

                    Swal.fire({
                        title: xhr.statusText,
                        icon: 'error',
                        text: response.statusText,
                    })
                })
            });

            $('#form-pengaturan').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting via the browser

                $.ajax({
                    type: 'POST',
                    url: '{{ ci_route('surat_master.pengaturan_sementara') }}',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        // Handle success response
                        $('#modal-pengaturan').modal('hide'); // Hide the modal
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Berhasil mengubah data',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(response, status, xhr) {
                        // Handle error response
                        Swal.fire({
                            title: xhr.statusText,
                            icon: 'error',
                            text: response.statusText,
                        });
                    }
                });
            });
        });
    </script>
@endpush
