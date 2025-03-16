@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_validasi')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Kotak Pesan
        <small>{{ $pesan ? 'Balas' : 'Tambah' }} Kotak Pesan </small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('komentar') }}"> Daftar Kotak Pesan </a></li>
    <li class="active">{{ $pesan ? 'Balas' : 'Tambah' }} Kotak Pesan </li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('mailbox'), 'label' => 'Daftar Kotak Pesan'])
                        @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('mailbox/2'), 'label' => 'Daftar Kotak Keluar'])

                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="owner">Penerima</label>
                            <div class="col-sm-9">
                                <select class="form-control input-sm select2-nik-ajax required" id="nik" style="width:100%" name="penduduk_id" data-url="{{ ci_route('mailbox.list_pendaftar_mandiri_ajax') }}">
                                    @if ($individu)
                                        <option value="{{ $individu['id'] }} " selected>{{ $individu['nik'] . ' - ' . $individu['nama'] . ' ' . $individu['alamat_wilayah'] }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="subjek">Subjek</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm required" id="subjek" name="subjek" value="{{ $pesan['subjek'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="komentar">Pesan </label>
                            <div class="col-sm-9">
                                <textarea id="komentar" name="komentar" class="form-control input-sm required" placeholder="Isi Kotak Pesan " style="height: 200px;">{{ $pesan['komentar'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if ($readonly)
                        <div class='box-footer'>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right confirm"><i class="fa fa-reply"></i> Balas Pesan</button>
                        </div>
                    @else
                        <div class='box-footer'>
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>Batal</button>
                            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#nik').trigger('change')
        })
    </script>
@endpush
