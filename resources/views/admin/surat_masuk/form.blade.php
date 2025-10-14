@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Surat Masuk
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_masuk') }}">Daftar Surat Masuk</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('surat_masuk'), 'label' => 'Daftar Surat Masuk'])
        </div>
        {!! form_open($form_action, 'class="form-horizontal" enctype="multipart/form-data" id="validasi"') !!}
        <div class="box-body">
            <input type="hidden" id="nomor_urut_lama" name="nomor_urut_lama" value="{{ $surat_masuk->nomor_urut }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat_masuk/nomor_surat_duplikat') }}">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nomor_urut">Nomor Urut</label>
                <div class="col-sm-8">
                    <input id="nomor_urut" name="nomor_urut" class="form-control input-sm number required" type="text" placeholder="Nomor Urut" value="{{ $surat_masuk['nomor_urut'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_penerimaan">Tanggal Penerimaan</label>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right required" id="tgl_1" name="tanggal_penerimaan" type="text" value="<?= tgl_indo_out($surat_masuk['tanggal_penerimaan']) ?>">
                    </div>
                </div>
            </div>
            @if (null !== $surat_masuk['berkas_scan'] && $surat_masuk['berkas_scan'] != '.')
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="kode_pos"></label>
                    <div class="col-sm-8">
                        @if (get_extension($surat_masuk['berkas_scan']) == '.pdf')
                            <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas {{ $surat_masuk->nomor_surat }}" data-url="{{ site_url("surat_masuk/berkas/{$surat_masuk->id}/1") }}"></i>
                        @else
                            <i class="fa fa-picture-o pop-up-images" style="font-size: 60px;" aria-hidden="true" data-title="Berkas {{ $surat_masuk->nomor_surat }}" data-url="{{ site_url("surat_masuk/berkas/{$surat_masuk->id}") }}" src="{{ site_url("'surat_masuk/berkas/{$surat_masuk->id}") }}"></i>
                        @endif
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kode_pos">Berkas Scan Surat Masuk</label>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm col-sm-12">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" class="hidden @if ($action === 'Tambah') required @endif" id="file" name="satuan" accept=".gif,.jpg,.jpeg,.png,.pdf">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                    <span class="help-block"><code>(Kosongkan jika tidak ingin mengubah berkas)</code></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm select2-tags required" id="kode_surat" name="kode_surat" style="width: 100%;">
                        <option value=''>-- Pilih Kode/Klasifikasi Surat --</option>
                        @if ($surat_masuk['kode_surat'])
                            <option value="{{ $surat_masuk['kode_surat'] }}" selected>{{ $surat_masuk['kode_surat'] }}</option>
                        @endif
                        @foreach ($klasifikasi as $item)
                            <option value="{{ $item->kode }}" @selected($item->kode == $surat_masuk['kode_surat'])>{{ $item->kode . ' - ' . $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nomor_surat">Nomor Surat</label>
                <div class="col-sm-8">
                    <input
                        id="nomor_surat"
                        name="nomor_surat"
                        maxlength="35"
                        class="form-control input-sm required nomor_sk"
                        type="text"
                        placeholder="Nomor Surat"
                        value="{{ $surat_masuk->nomor_surat }}"
                    ></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_surat">Tanggal Surat</label>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right required" id="tgl_2" name="tanggal_surat" type="text" value="{{ tgl_indo_out($surat_masuk['tanggal_surat']) }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="pengirim">Pengirim</label>
                <div class="col-sm-8">
                    <input id="pengirim" name="pengirim" class="form-control input-sm required" type="text" placeholder="Pengirim" value="{{ $surat_masuk['pengirim'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="disposisi_kepada">Isi Singkat/Perihal</label>
                <div class="col-sm-8">
                    <textarea id="isi_singkat" name="isi_singkat" class="form-control input-sm required" placeholder="Isi Singkat/Perihal" rows="5">{{ $surat_masuk['isi_singkat'] }}</textarea>
                </div>
            </div>
            <div class="form-group" id="grp_disposisi">
                <label class="col-sm-3 control-label" for="disposisi_kepada">Disposisi Kepada</label>
                <div class="col-sm-8 col-lg-8">
                    <div id="op_item" class="checkbox-group required">
                        <table class="table table-borderless" style="margin-bottom: 0;">
                            <tbody>
                                @foreach ($ref_disposisi as $id => $nama)
                                    @if ($loop->iteration % 2 == 1)
                                        <tr>
                                    @endif
                                        <td style="padding: 5px 10px; vertical-align: top;">
                                            <label class="checkbox-inline" style="font-weight: normal;">
                                                <input type="checkbox" name="disposisi_kepada[]" value="{{ $id }}"
                                                    {{ selected(is_array($disposisi_surat_masuk) && in_array($id, $disposisi_surat_masuk), true, true) }}>
                                                {{ strtoupper($nama) }}
                                            </label>
                                        </td>
                                    @if ($loop->iteration % 2 == 0 || $loop->last)
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-8 col-lg-8">
                    <label id="msg_disposisi" class="has-error">Kolom ini diperlukan.</label>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="isi_disposisi">Isi Disposisi</label>
                <div class="col-sm-8">
                    <textarea id="isi_disposisi" name="isi_disposisi" class="form-control input-sm required" placeholder="Isi Disposisi" rows="5"><?= $surat_masuk['isi_disposisi'] ?></textarea>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right" onclick="submit_form()"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
@push('scripts')
<script src="{{ asset('js/custom-select2.js') }}"></script>
<script>
    $(function () {
        var keyword = @json($pengirim);
        $("#pengirim").autocomplete({
            source: keyword,
            maxShowItems: 10,
        });

        $("#msg_disposisi").hide();

        // Hide error saat checkbox disposisi diubah
        $('input[name="disposisi_kepada[]"]').on('change', function () {
            if ($('input[name="disposisi_kepada[]"]:checked').length > 0) {
                $("#msg_disposisi").hide();
                $("#grp_disposisi").removeClass("has-error");
            }
        });

        // Cegah submit default (karena kita submit manual)
        $('#validasi').on('submit', function (e) {
            e.preventDefault(); // agar tidak double submit
        });
    });

    function resetValidationErrors() {
        $("#msg_disposisi").hide();
        $(".form-group").removeClass("has-error");
        $(".error-message").remove();
    }

    function validateForm() {
        resetValidationErrors();
        let isValid = true;

        // Validasi input/select/textarea (kecuali checkbox group)
        $('.form-control.required, .required select, .required textarea').each(function () {
            const el = $(this);
            if (!el.val() || el.val().trim() === '') {
                el.closest('.form-group').addClass('has-error');
                isValid = false;
            }
        });

        // Validasi Disposisi Kepada (minimal 1 checkbox dicentang)
        if ($('input[name="disposisi_kepada[]"]:checked').length === 0) {
            $("#msg_disposisi").show();
            $("#grp_disposisi").addClass("has-error");
            isValid = false;
        }

        if (!isValid) {
            scrollToError();
        }

        return isValid;
    }

    function scrollToError() {
        const firstError = $('.has-error').first();
        if (firstError.length) {
            $('html, body').animate({
                scrollTop: firstError.offset().top - 100
            }, 500);
        }
    }

    function submit_form() {
        if (validateForm()) {
            document.getElementById('validasi').submit();
        }
    }
</script>
@endpush