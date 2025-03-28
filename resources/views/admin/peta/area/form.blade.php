@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Area
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('area.index') }}"> Area</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('area.index'), 'label' => 'Area'])
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Area / Properti</label>
                        <div class="col-sm-7">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $area->nama }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Kategori</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2 required" id="ref_polygon" name="ref_polygon">
                                <option value="">Pilih Kategori</option>
                                @foreach ($list_polygon as $data)
                                    <option value="{{ $data->id }}" @selected($data->id == $area->ref_polygon)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <?php if ($area->foto_area) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7">
                            <img class="attachment-img img-responsive img-circle" src="{{ $area->foto_area }}" alt="Foto">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Ganti Foto</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path">
                                <input id="file" type="file" class="hidden" name="foto" accept=".gif,.jpg,.jpeg,.png,.webp">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                            <p class="help-block small text-red">Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-7">
                            <textarea id="desk" name="desk" class="form-control input-sm required" style="height: 200px;white-space: pre-wrap;">{{ $area->desk }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
                        <div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
                            <label id="sx3" class="btn btn-info  btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @active($area->enabled == \App\Models\Area::UNLOCK || $area->enabled == null)">
                                <input
                                    id="sx1"
                                    type="radio"
                                    name="enabled"
                                    class="form-check-input"
                                    type="radio"
                                    value="{{ \App\Models\Area::UNLOCK }}"
                                    @checked($area->enabled == \App\Models\Area::UNLOCK || $area->enabled == null)
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-info  btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @active($area->enabled == \App\Models\Area::LOCK)">
                                <input
                                    id="sx2"
                                    type="radio"
                                    name="enabled"
                                    class="form-check-input"
                                    type="radio"
                                    value="{{ \App\Models\Area::LOCK }}"
                                    @checked($area->enabled == \App\Models\Area::LOCK)
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

        })
    </script>
@endpush
