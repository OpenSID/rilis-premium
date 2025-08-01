@include('admin.pengaturan_surat.asset_tinymce', ['height' => 350])
@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Daftar Surat
        <small>{{ $action }} Pengaturan Surat</small>
    </h1>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_dinas') }}">Daftar Surat</a></li>
    <li class="active">{{ $action }} Pengaturan Surat</li>
@endsection
@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($formAksi, ['id' => 'validasi', 'enctype' => 'multipart/form-data']) !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#header" data-toggle="tab">Header</a></li>
            <li><a href="#footer" data-toggle="tab">Footer</a></li>
            <li><a href="#alur" data-toggle="tab">Alur Surat</a></li>
            <li><a href="#tte" data-toggle="tab">Pengaturan TTE</a></li>
            <li><a href="#kode-isian" data-toggle="tab">Kode Isian Alias</a></li>
            <li><a href="#lainnya" data-toggle="tab">Lainnya</a></li>
        </ul>
        <div class="tab-content">
            @include('admin.surat_dinas.pengaturan.kembali')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_header')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_footer')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_alur')
            @include('admin.pengaturan_surat.partials.pengaturan_tte')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_kodeisian')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_lainnya')
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                    Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {

        
            ganti_tte();
            ganti_visual();
            $('input[name="tte"]').on('change', function(e) {
                ganti_tte()
            });

            function ganti_tte() {
                var tte_password = "{{ $list_setting->firstWhere('key', 'tte_password')?->value }}";
                if ($('input[name="tte"]').filter(':checked').val() == 1) {
                    $('input[name="tte_api"]');
                    if (tte_password == "") {
                        $('input[name="tte_password"]').attr("required", true);
                        $('#info-tte-password').hide();
                    } else {
                        $('#info-tte-password').show();
                    }
                    $('input[name="tte_username"]').attr("required", true);
                    $('#modul-tte').show();
                } else {
                    $('input[name="tte_api"]').attr("required", false);
                    $('input[name="tte_password"]').attr("required", false);
                    $('input[name="tte_username"]').attr("required", false);
                    $('#modul-tte').hide();
                }
            }
            $('input[name="visual_tte"]').change(function(e) {
                ganti_visual();
            })

            function ganti_visual() {
                if ($('input[name="visual_tte"]').filter(':checked').val() == 1) {
                    $('#visual-tte-form').show();
                } else {
                    $('#visual-tte-form').hide();
                }
            }
            $('#standar').click(function(event) {
                Swal.fire({
                    title: 'Informasi',
                    icon: 'question',
                    text: 'Apakah Anda yakin ingin mengubah ke standar spesifikasi surat?',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Sedang menyesuaikan...',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,

                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });

                        $('input[name="tinggi_header_surat_dinas"]').val('{{ \App\Libraries\TinyMCE::TOP }}');
                        $('input[name="tinggi_footer_surat_dinas"]').val('{{ \App\Libraries\TinyMCE::BOTTOM }}');
                        $('select[name="font_surat_dinas"]').val('{{ \App\Libraries\TinyMCE::DEFAULT_FONT }}').trigger('change');
                        $('input[name="surat_dinas_margin[kiri]"]').val('{{ \App\Models\SuratDinas::MARGINS['kiri'] }}');
                        $('input[name="surat_dinas_margin[kanan]"]').val('{{ \App\Models\SuratDinas::MARGINS['kanan'] }}');
                        $('input[name="surat_dinas_margin[atas]"]').val('{{ \App\Models\SuratDinas::MARGINS['atas'] }}');
                        $('input[name="surat_dinas_margin[bawah]"]').val('{{ \App\Models\SuratDinas::MARGINS['bawah'] }}');

                        $('#validasi').submit();
                    }
                })
            });
        });
    </script>
@endpush
