@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengaturan {{ $utama ? 'Modul' : 'Submodul' }}
    </h1>
@endsection

@section('breadcrumb')
    @if (!$utama)
        <li><a href="{{ route('modul.index-default') }}">Daftar Modul</a></li>
    @endif
    <li class="active">Pengaturan {{ $utama ? 'Modul' : 'Submodul' }}</li>
@endsection

@section('content')
    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        <div class="box box-primary">
            <div class="box-header with-border">
                @include('admin.layouts.components.tombol_kembali', ['url' => route('modul.index-default'), 'label' => 'Daftar Modul'])

                @if ($item->parent != '0')
                    @include('admin.layouts.components.tombol_kembali', ['url' => route('modul.index', $item->parent), 'label' => 'Daftar Sub Modul'])
                @endif
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="pamong_nama">
                        @if ($item->parent != '0')
                            Nama Sub Modul
                        @else
                            Nama Modul
                        @endif
                    </label>
                    <div class="col-sm-6">
                        <input
                            id="modul"
                            name="modul"
                            class="form-control input-sm strip_tags required"
                            type="text"
                            placeholder="Nama Modul/Sub Modul"
                            value="{{ old('modul', $item->modul) }}"
                            minlength="3"
                            maxlength="50"
                        />
                        <label class="error" id="tag_error" style="display: none;">Tidak boleh ada tag.</label>
                        <code>Isi dengan [Desa] untuk menyesuaikan sebutan desa berdasarkan pengaturan aplikasi.</code>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label" for="ikon">Ikon</label>
                    <div class="col-sm-6">
                        <select class="form-control select2-ikon required" id="ikon" name="ikon" data-placeholder="Pilih Icon">
                            @foreach ($list_icon as $icon)
                                <option value="{{ $icon }}" @selected($icon == old('ikon', $item->ikon))>{!! $icon !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-4 col-lg-4 control-label" for="status">Status</label>
                    <div class="btn-group col-xs-12 col-sm-7" data-toggle="buttons">
                        <label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @if ((string) old('aktif', $item->raw_aktif) === '1') active @endif">
                            <input
                                id="g1"
                                type="radio"
                                name="aktif"
                                class="form-check-input"
                                type="radio"
                                value="1"
                                @checked((string) old('aktif', $item->raw_aktif) === '1')
                                autocomplete="off"
                            > Aktif
                        </label>
                        <label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @if ((string) old('aktif', $item->raw_aktif) === '0') active @endif">
                            <input
                                id="g2"
                                type="radio"
                                name="aktif"
                                class="form-check-input"
                                type="radio"
                                value="0"
                                @checked((string) old('aktif', $item->raw_aktif) === '0')
                                autocomplete="off"
                            > Tidak Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class='box-footer'>
                <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
                <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
@endpush
