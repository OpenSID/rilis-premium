@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        <h1>Teks Berjalan
            <small>{{ $teks ? 'Ubah' : 'tambah' }} Data</small>
        </h1>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('teks_berjalan.index') }}">Teks Berjalan</a></li>
    {{ $teks ? 'Ubah' : 'tambah' }} Data
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @php
        $tipeTerpilih = (int) old('tipe', $teks?->tipe) ?: \App\Models\TeksBerjalan::TIPE_INTERNAL;
        $adaTautan    = old('tautan_internal', old('tautan_eksternal', $teks?->tautan));
    @endphp

    <form action="{{ $form_action }}" method="POST" accept-charset="utf-8" class="form-horizontal" id="validasi">
    @csrf
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => route('teks_berjalan.index'), 'label' => 'Daftar Teks Berjalan'])
        </div>
        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="isi_teks_berjalan">Isi teks berjalan</label>
                    <textarea id="teks" class="form-control input-sm required" placeholder="Isi teks berjalan" name="teks" rows="5" style="resize:none;">{{ old('teks', $teks?->teks) }}</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label">Tipe</label>
                    <select class="form-control select2" id="tipe" name="tipe">
                        <option value="{{ \App\Models\TeksBerjalan::TIPE_INTERNAL }}" @selected($tipeTerpilih == \App\Models\TeksBerjalan::TIPE_INTERNAL)>Internal</option>
                        <option value="{{ \App\Models\TeksBerjalan::TIPE_EKSTERNAL }}" @selected($tipeTerpilih == \App\Models\TeksBerjalan::TIPE_EKSTERNAL)>Eksternal</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12" id="internal" style="display: {{ $tipeTerpilih == \App\Models\TeksBerjalan::TIPE_EKSTERNAL ? 'none' : '' }}">
                <div class="form-group">
                    <label class="control-label">Tautan ke artikel</label>
                    <select class="form-control select2 " id="tautan_internal" name="tautan_internal">
                        <option value="">-- Cari Judul Artikel --</option>
                        @foreach ($list_artikel as $artikel)
                            <option value="{{ $artikel->id }}" @selected($artikel->id == old('tautan_internal', $teks?->tautan))>
                                {{ tgl_indo($artikel->tgl_upload) . ' | ' . $artikel->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12" id="eksternal" style="display: {{ $tipeTerpilih == \App\Models\TeksBerjalan::TIPE_INTERNAL ? 'none' : '' }}">
                <div class="form-group">
                    <label class="control-label">Tautan Luar</label>
                    <input class="form-control input-sm url short_url" placeholder="Contoh: https://opendesa.id" id="tautan_eksternal" name="tautan_eksternal" value="{{ old('tautan_eksternal', $teks?->tautan) }}"></input>
                </div>
            </div>
            <div class="col-md-12" id="box_judul_tautan" style="display: {{ $adaTautan ? '' : 'none' }}">
                <div class="form-group">
                    <label class="control-label">Judul tautan</label>
                    <input
                        {{ $adaTautan ? '' : 'disabled' }}
                        class="form-control input-sm required"
                        placeholder="Judul tautan ke artikel atau url"
                        name="judul_tautan"
                        id="input_judul_tautan"
                        value="{{ old('judul_tautan', $teks?->judul_tautan) }}"
                        maxlength="150"
                    />
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label">Status</label>
                    <select class="form-control select2" id="status" name="status">
                        @foreach (\App\Enums\StatusEnum::all() as $key => $data)
                            <option value="{{ $key }}" @selected($key == old('status', $teks?->status))>
                                {{ $data }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class='box-footer'>
            <button type='reset' class='btn btn-social  btn-danger btn-sm'><i class='fa fa-times'></i>
                Batal</button>
            <button type='submit' class='btn btn-social  btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() { 
            tipe({{ $tipeTerpilih }});

            $('#tipe').on('change', function() {
                tipe(this.value);
            });

            $('#tautan_internal').on('change', function() {
                tautan($(this).val(), 'internal');
            });

            $('#tautan_eksternal').on('input', function() {
                tautan($(this).val(), 'eksternal');
            });

            function tipe(param) {
                if (param == 1) {
                    // INTERNAL
                    $('#internal').show();
                    $('#eksternal').hide();

                    $("#tautan_internal").addClass("required");
                    $("#tautan_eksternal").removeClass("required url short_url");

                    // judul wajib untuk internal
                    $('#box_judul_tautan').show();
                    $('#input_judul_tautan').prop("disabled", false).addClass("required");

                } else {
                    // EKSTERNAL
                    $('#internal').hide();
                    $('#eksternal').show();

                    $("#tautan_internal").removeClass("required");
                    $("#tautan_eksternal").addClass("url short_url");

                    tautan($("#tautan_eksternal").val(), 'eksternal');
                }
            }

            function tautan(val, tipe) {
                if (tipe === 'internal') {
                    // internal selalu wajib
                    $('#box_judul_tautan').show();
                    $('#input_judul_tautan').prop("disabled", false).addClass("required");
                } else {
                    // eksternal: kalau ada isi maka wajib judul, kalau kosong tidak wajib
                    if (val === "") {
                        $('#box_judul_tautan').hide();
                        $('#input_judul_tautan').prop("disabled", true).removeClass("required");
                    } else {
                        $('#box_judul_tautan').show();
                        $('#input_judul_tautan').prop("disabled", false).addClass("required");
                    }
                }
            }
        });

    </script>
@endpush
