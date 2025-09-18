@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Lokasi
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('plan.index') }}"> Lokasi</a></li>
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
                    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('plan.index'), 'label' => 'Lokasi'])

                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Lokasi / Properti</label>
                        <div class="col-sm-7">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $plan->nama }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Kategori</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2 required" id="ref_point" name="ref_point">
                                <option value="">Pilih Kategori</option>
                                @foreach ($list_point as $data)
                                    <option value="{{ $data->id }}" @selected($data->id == $plan->ref_point)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <?php if ($plan->foto_lokasi) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7">
                            <img class="attachment-img img-responsive img-circle" src="{{ $plan->foto_lokasi }}" alt="Foto">
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
                            <textarea id="desk" name="desk" class="form-control input-sm required" style="height: 200px;white-space: pre-wrap;">{{ $plan->desk }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="enabled">Status</label>
                        <div class="col-sm-6">
                            <select name="enabled" id="enabled" class="form-control input-sm required">
                                @foreach (\App\Enums\AktifEnum::all() as $value => $label)
                                <option value="{{ $value }}" @selected($plan->enabled==$value)>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
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