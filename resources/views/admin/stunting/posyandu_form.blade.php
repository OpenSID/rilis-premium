@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small> {{ $action }} Data Posyandu</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('stunting.index') }}">Posyandu</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => route('stunting.index'), 'label' => 'Posyandu'])

                </div>
                <form action="{{ $formAction }}" method="POST" class="form-horizontal" id="validasi">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm required" name="nama" placeholder="Masukkan nama posyandu" value="{{ old('nama', $posyandu->nama) }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-8">
                            <textarea name="alamat" class="form-control input-sm required" rows="5" placeholder="Masukkan alamat posyandu">{{ old('alamat', $posyandu->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
