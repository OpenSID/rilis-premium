@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>Daftar Pemudik Saat Pandemi Covid-19</h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Pemudik</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.buttons.tambah', ['url' => 'covid19/form_pemudik', 'judul' => 'Tambah Warga Pemudik'])
            <a href="{{ route('covid19.daftar', 'cetak') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak</a>
            <a href="{{ route('covid19.daftar', 'unduh') }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh</a>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            @if (can('u'))
                                <th class="padat">AKSI</th>
                            @endif
                            <th>NIK</th>
                            <th>NAMA</th>
                            <th>USIA</th>
                            <th>JK</th>
                            <th>ALAMAT</th>
                            <th>PANTAU WARGA</th>
                            <th>ASAL PEMUDIK</th>
                            <th>TANGGAL TIBA</th>
                            <th>TUJUAN PEMUDIK</th>
                            <th>KONTAK</th>
                            <th>STATUS</th>
                            <th>KELUHAN</th>
                            <th>WAJIB PANTAU</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('covid19.datatables') }}",
                    method: 'POST',
                },
                order: [],
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    @if (can('u'))
                        {
                            data: 'aksi',
                            class: 'aksi',
                            searchable: false,
                            orderable: false
                        },
                    @endif
                    {
                        data: 'terdata_id',
                        name: 'o.nik'
                    },
                    {
                        data: 'nama',
                        name: 'o.nama'
                    },
                    {
                        data: 'umur',
                        name: 'umur',
                        searchable: false
                    },
                    {
                        data: 'jk',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'info',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pantau_warga',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'asal_mudik',
                        name: 's.asal_mudik'
                    },
                    {
                        data: 'tanggal_datang',
                        name: 's.tanggal_datang',
                        searchable: false
                    },
                    {
                        data: 'tujuan_mudik',
                        name: 's.tujuan_mudik'
                    },
                    {
                        data: 'kontak',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_covid_nama',
                        name: 'status_covid_nama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keluhan_kesehatan',
                        name: 's.keluhan_kesehatan'
                    },
                    {
                        data: 'is_wajib_pantau',
                        searchable: false,
                        orderable: false
                    }
                ],
            });
        });
    </script>
@endpush
