<div class="col-md-3">
    @if (!$kk_baru)
        <input name="no_kk" type="hidden" value="{{ $penduduk['no_kk'] }}">
    @endif
    @include('admin.layouts.components.ambil_foto', ['id_sex' => $penduduk['id_sex'], 'foto' => $penduduk['foto']])
</div>
<div class="col-md-9">
    <div class="box box-primary">
        <div class="box-header with-border">
            @if (preg_match('/keluarga/i', $_SERVER['HTTP_REFERER']))
                @include('admin.layouts.components.tombol_kembali', ['url' => $_SERVER['HTTP_REFERER'], 'label' => 'Daftar Anggota Keluarga'])
            @endif
            @if (preg_match('/rtm/i', $_SERVER['HTTP_REFERER']))
                @include('admin.layouts.components.tombol_kembali', ['url' => $_SERVER['HTTP_REFERER'], 'label' => 'Anggota Rumah Tangga'])
            @endif
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('penduduk.clear'), 'label' => 'Daftar Penduduk'])
        </div>
        <div class="box-body">
            @include('admin.penduduk.penduduk_form_isian_bersama')
        </div>
        @if ($penduduk['status_dasar_id'] == 1 || !isset($penduduk['status_dasar_id']))
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right" onclick="$('#'+'mainform').attr('action', '{{ $form_action }}');$('#'+'mainform').submit();"><i class="fa fa-check"></i> Simpan</button>
            </div>
        @endif
    </div>
</div>
