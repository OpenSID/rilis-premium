@include('admin.layouts.components.datetime_picker')

@extends('admin.layouts.index')

@section('title')
    <h1>Form Pendataan Vaksin Covid 19</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('vaksin_covid') }}"> Daftar Penduduk Penerima Vaksin Covid 19</a></li>
    <li class="active">Penambahan Pemudik Covid-19</li>
@endsection

@php
    $tanggal = static fn (?string $tgl, string $kosong = '-'): string => ($tgl && $tgl !== '0000-00-00') ? date('d-m-Y', strtotime($tgl)) : $kosong;
    $tunda = $penduduk?->tunda;
    $tidakTunda = $tunda == '0' || $tunda == null;
@endphp

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        @if (can('u'))
            <div class="box-header with-border">
                @include('admin.layouts.components.tombol_kembali', ['url' => url('vaksin_covid'), 'label' => 'Penduduk Penerima Vaksin Covid-19'])
            </div>
        @endif
        <div class="box-header with-border">
            <h3 class="box-title">Tambahkan Warga Penerima Vaksin</h3>
        </div>
        <div class="box-body">
            <form id="main" name="main" method="GET" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label required" for="terdata">NIK / Nama</label>
                    <div class="col-sm-4">
                        <select class="form-control select2 required" id="vaksin_penerima" name="terdata" onchange="formAction('main')" style="width: 100%;">
                            <option selected>-- Silakan Masukan NIK/Nama -- </option>
                            @if ($penduduk)
                                <option value="{{ $penduduk->id }}" selected>{{ 'NIK: ' . $penduduk->nik . ' - ' . $penduduk->nama . ' - ' . ($penduduk->alamat_wilayah ?? '') }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </form>
            <div>
                <form id="validasi" action="{{ url('vaksin_covid/update') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="nik" value="{{ $penduduk?->nik }}">
                    <input type="hidden" name="id_penduduk" value="{{ $penduduk?->id }}">

                    <div class="form-group">
                        <label for="tunda" class="col-xs-12 col-sm-3 col-lg-3 control-label"> Tunda Vaksin</label>
                        <input type="hidden" name="tunda" value="{{ (int) $tunda }}">
                        <div class="btn-group col-xs-12 col-sm-4" data-toggle="buttons">
                            <label class="btn btn-info btn-flat btn-sm form-check-label col-xs-6 col-sm-6 {{ $tunda == 1 ? 'active' : '' }}">
                                <input id="tunda_v" type="radio" name="tunda_radio" class="form-check-input" value="1" autocomplete="off" {{ $tunda == 1 ? 'selected' : '' }}> Ya
                            </label>
                            <label id="tidak" class="btn btn-info btn-flat btn-sm form-check-label col-xs-6 col-sm-6 {{ $tunda == 0 ? 'active' : '' }}">
                                <input type="radio" name="tunda_radio" class="form-check-input" value="0" autocomplete="off" {{ $tidakTunda ? 'selected' : '' }}> Tidak
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Keterangan" rows="3" @disabled($tidakTunda)>{{ old('keterangan', $penduduk?->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="surat_dokter" class="col-sm-3 control-label"></label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path4" placeholder="Upload Dokumen/Surat Dokter" @disabled($tidakTunda)>
                                <input id="file4" type="file" class="hidden" name="berkas_surat_dokter" accept=".jpg, .jpeg, .png, .pdf">
                                <input type="hidden" name="surat_dokter" value="{{ $penduduk?->surat_dokter }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="file_browser4" @disabled($tidakTunda)><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>

                    @for ($ke = 1; $ke <= 3; $ke++)
                        @php
                            $vaksinKe = $penduduk?->{"vaksin_{$ke}"};
                            $tglVaksin = $penduduk?->{"tgl_vaksin_{$ke}"};
                            $jenisVaksin = old("jenis_vaksin_{$ke}", $penduduk?->{"jenis_vaksin_{$ke}"});
                            $dokumenVaksin = $penduduk?->{"dokumen_vaksin_{$ke}"};
                        @endphp
                        <div class="form-group">
                            <label for="centang_vaksin_{{ $ke }}" class="col-sm-3 control-label">Vakin Dosis {{ $ke }}</label>
                            <div class="col-sm-4">
                                <div class="input-group input-group-sm date ">
                                    <span class="input-group-addon">
                                        @if ($ke === 1)
                                            <input type="checkbox" title="Centang jika sudah vaksin dosis 1" id="centang_vaksin_1" data-ke="1" class="centang_vaksin" checked="checked" @disabled($tunda == 1) value="1" name="vaksin_1">
                                        @else
                                            <input type="checkbox" title="Centang jika sudah vaksin dosis {{ $ke }}" id="centang_vaksin_{{ $ke }}" data-ke="{{ $ke }}" class="centang_vaksin" @checked($vaksinKe == 1) value="1" name="vaksin_{{ $ke }}" @disabled($ke === 3)>
                                        @endif
                                    </span>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i> Tanggal Vaksin
                                    </div>
                                    <input type="text" class="form-control input-sm pull-right tgl-datepicker" id="tanggal_vaksin_{{ $ke }}" name="tgl_vaksin_{{ $ke }}" value="{{ old("tgl_vaksin_{$ke}", $tanggal($tglVaksin, '')) }}" @if ($ke === 1) @disabled($tunda == 1) @else disabled @endif>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="input-group input-group-sm date ">
                                    <div class="input-group-addon">Jenis Vaksin</div>
                                    <select class="form-control input-sm select2-tags" data-url="{{ url('vaksin_covid') }}" data-placeholder="-- Pilih Jenis Vaksin --" id="jenis_vaksin_{{ $ke }}" name="jenis_vaksin_{{ $ke }}" disabled>
                                        <option value="">-- Pilih Jenis vaksin -- </option>
                                        @foreach ($list_vaksin as $vaksin)
                                            <option value="{{ $vaksin }}" @selected($vaksin == $jenisVaksin)>{{ $vaksin }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="file_path{{ $ke }}" placeholder="Upload Dokumen/Sertifikat" @if ($ke === 3) disabled @else @disabled($tunda == 1) @endif>
                                    <input id="file{{ $ke }}" type="file" class="hidden" name="berkas_vaksin_{{ $ke }}" accept=".jpg, .jpeg, .png, .pdf">
                                    <input type="hidden" name="dokumen_vaksin_{{ $ke }}" value="{{ $dokumenVaksin }}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat" id="file_browser{{ $ke }}" @if ($ke === 3) disabled @else @disabled($tunda == 1) @endif><i class="fa fa-search"></i> Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endfor
                </form>
            </div>
            <div class="box-footer">
                <div class="col-xs-12">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            function cek_vaksin() {
                var tunda = $('input[name="tunda"]').val();
                if (tunda == 1) {
                    for (var i = 1; i <= 3; i++) {
                        $(`#tanggal_vaksin_${i}`).prop("disabled", true);
                        $(`#file_path${i}`).prop("disabled", true);
                        $(`#file_browser${i}`).prop("disabled", true);
                        $(`#jenis_vaksin_${i}`).prop("disabled", true);
                        $(`#centang_vaksin_${i}`).prop("disabled", true);
                    }
                } else {
                    $(`#centang_vaksin_1`).prop("disabled", false);
                    for (var i = 1; i <= 3; i++) {

                        if ($(`#centang_vaksin_${i}`).is(':checked')) {
                            $(`#tanggal_vaksin_${i}`).prop("disabled", false);
                            $(`#file_path${i}`).prop("disabled", false);
                            $(`#file_browser${i}`).prop("disabled", false);
                            $(`#centang_vaksin_${i+1}`).prop("disabled", false);
                            $(`#jenis_vaksin_${i}`).prop("disabled", false);
                        }
                    }
                }
            }
            cek_vaksin();

            function enable_vaksin() {
                $(`#centang_vaksin_1`).prop("disabled", false);
                $(`#tanggal_vaksin_1`).prop("disabled", false);
                $(`#file_path1`).prop("disabled", false);
                $(`#file_browser1`).prop("disabled", false);
                $(`#jenis_vaksin_1`).prop("disabled", false);
                for (var i = 2; i <= 3; i++) {
                    if ($(`#centang_vaksin_${i-1}`).is(':checked')) {
                        $(`#centang_vaksin_${i}`).prop("disabled", false);
                    }

                    if ($(`#centang_vaksin_${i}`).is(':checked')) {
                        $(`#tanggal_vaksin_${i}`).prop("disabled", false);
                        $(`#file_path${i}`).prop("disabled", false);
                        $(`#file_browser${i}`).prop("disabled", false);
                        $(`#jenis_vaksin_${i}`).prop("disabled", false);
                    }
                }
            }

            $('input[name="tunda_radio"]').change(function(event) {
                $('input[name="tunda"]').val($(this).val());
                if ($(this).val() == 0) {
                    enable_vaksin();
                    $(`#file_path4`).prop("disabled", true);
                    $(`#file_browser4`).prop("disabled", true);
                    $('#keterangan').prop("disabled", true);
                } else {
                    $('.centang_vaksin').prop("disabled", true);
                    $('#keterangan').prop("disabled", false);
                    $(`#file_path4`).prop("disabled", false);
                    $(`#file_browser4`).prop("disabled", false);
                    for (var i = 1; i <= 3; i++) {
                        $(`#tanggal_vaksin_${i}`).prop("disabled", true);
                        $(`#file_path${i}`).prop("disabled", true);
                        $(`#file_browser${i}`).prop("disabled", true);
                        $(`#jenis_vaksin_${i}`).prop("disabled", true);
                    }
                }
            });

            $('#validasi').find('.centang_vaksin').change(function(event) {
                var ke = $(this).data('ke');
                if ($(this).is(':checked')) {
                    $(`#tanggal_vaksin_${ke}`).prop("disabled", false);
                    $(`#file_path${ke}`).prop("disabled", false);
                    $(`#file_browser${ke}`).prop("disabled", false);
                    $(`#jenis_vaksin_${ke}`).prop("disabled", false);
                    $(`#centang_vaksin_${ke+1}`).prop("disabled", false);
                } else {
                    $(`#tanggal_vaksin_${ke}`).prop("disabled", true);
                    $(`#file_path${ke}`).prop("disabled", true);
                    $(`#file_browser${ke}`).prop("disabled", true);
                    $(`#jenis_vaksin_${ke}`).prop("disabled", true);
                    $(`#centang_vaksin_${ke+1}`).prop("disabled", true);
                }
            });

            $('.tgl-datepicker').datetimepicker({
                format: 'DD-MM-YYYY'
            });
        });
    </script>
@endpush
