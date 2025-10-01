@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        {{ $action }} Data Mutasi Inventaris Tanah
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('inventaris_tanah_mutasi') }}">Daftar Mutasi Inventaris Tanah</a></li>
    <li class="active">{{ $action }} Data Mutasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-kembali-button judul="Kembali Ke Daftar Mutasi Inventaris Tanah" url="inventaris_tanah_mutasi" />
                </div>
                <form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="{{ $form_action }}">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label required" for="nama_barang">Nama Barang</label>
                            <div class="col-sm-8">
                                <input type="hidden" name="id_inventaris_tanah" id="id_inventaris_tanah" value="{{ $main->id }}">
                                <input
                                    maxlength="50"
                                    value="{{ $main->inventaris->nama_barang }}"
                                    class="form-control input-sm required"
                                    name="nama_barang"
                                    id="nama_barang"
                                    type="text"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="kode_barang">Kode
                                Barang</label>
                            <div class="col-sm-8">
                                <input
                                    maxlength="50"
                                    value="{{ $main->inventaris->kode_barang }}"
                                    class="form-control input-sm required"
                                    name="kode_barang"
                                    id="kode_barang"
                                    type="text"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nomor_register">Nomor
                                Register</label>
                            <div class="col-sm-8">
                                <input
                                    maxlength="50"
                                    value="{{ $main->inventaris->register }}"
                                    class="form-control input-sm required"
                                    name="register"
                                    id="register"
                                    type="text"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="mutasi">Status Asset</label>
                            <div class="col-sm-4">
                                <select name="status_mutasi" id="status" class="form-control input-sm required" @disabled($view_mark)>
                                    <option value="Baik" @selected($main->status_mutasi == 'Baik')>Baik</option>
                                    <option value="Rusak" @selected($main->status_mutasi == 'Rusak')>Rusak</option>
                                    <option value="Diperbaiki" @selected($main->status_mutasi == 'Diperbaiki')>Diperbaiki</option>
                                    <option value="Hapus" @selected($main->status_mutasi == 'Hapus')>Dihapus</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" style="text-align:left;" for="mutasi">Jenis Mutasi</label>
                            <div class="col-sm-4">
                                <select name="mutasi" id="mutasi" class="form-control input-sm" @disabled($view_mark)>
                                    <optgroup label="Penghapusan">
                                        <option value="Baik" @selected($main->jenis_mutasi == 'Baik')>Status Baik</option>
                                        <option value="Rusak" @selected($main->jenis_mutasi == 'Rusak')>Status Rusak</option>
                                    </optgroup>
                                    <optgroup label="Disumbangkan">
                                        <option value="Masih Baik Disumbangkan" @selected($main->jenis_mutasi == 'Masih Baik Disumbangkan')>Masih Baik</option>
                                        <option value="Barang Rusak Disumbangkan" @selected($main->jenis_mutasi == 'Barang Rusak Disumbangkan')>Rusak</option>
                                    </optgroup>
                                    <optgroup label="Jual">
                                        <option value="Masih Baik Dijual" @selected($main->jenis_mutasi == 'Masih Baik Dijual')>Masih Baik</option>
                                        <option value="Barang Rusak Dijual" @selected($main->jenis_mutasi == 'Barang Rusak Dijual')>Rusak</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group disumbangkan">
                            <label class="col-sm-3 control-label" for="sumbangkan">Disumbangkan
                                ke-</label>
                            <div class="col-sm-8">
                                <input
                                    maxlength="50"
                                    class="form-control input-sm"
                                    @disabled($view_mark)
                                    name="sumbangkan"
                                    id="sumbangkan"
                                    type="text"
                                    value="{{ $main->sumbangkan }}"
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="tahun">Tahun Pengadaan
                            </label>
                            <div class="col-sm-4">
                                <input
                                    maxlength="50"
                                    class="form-control input-sm required"
                                    name="tahun"
                                    id="tahun"
                                    type="text"
                                    value="{{ $main->inventaris->tahun_pengadaan }}"
                                    disabled
                                />
                            </div>
                        </div>
                        <div class="form-group harga_jual">
                            <label class="col-sm-3 control-label " for="harga_jual">Harga
                                Penjualan</label>
                            <div class="col-sm-4">
                                <input
                                    maxlength="50"
                                    class="form-control input-sm number"
                                    name="harga_jual"
                                    id="harga_jual"
                                    type="text"
                                    value="{{ $main->harga_jual }}"
                                    @disabled($view_mark)
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label required" for="tahun_mutasi">Tanggal Mutasi</label>
                            <div class="col-sm-4">
                                <input
                                    type="text"
                                    maxlength="50"
                                    class="form-control input-sm required datepicker"
                                    name="tahun_mutasi"
                                    id="tahun_mutasi"
                                    value="{{ date('d-m-Y', strtotime($main->tahun_mutasi ?? 'now')) }}"
                                    @disabled($view_mark)
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="5" class="form-control input-sm required" name="keterangan" @disabled($view_mark) id="keterangan">{{ $main->keterangan }}</textarea>
                            </div>
                        </div>

                    </div>
                    @if (!$view_mark)
                        <div class="box-footer">
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                                Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.inventaris.js_mutasi')
@endpush
