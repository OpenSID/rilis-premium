@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Daftar Inventaris Peralatan Dan Mesin
    </h1>
@endsection

@push('css')
    <style>
        .table .btn {
            margin-right: 2px;
        }
    </style>
@endpush

@section('breadcrumb')
    <li class="active">Daftar Inventaris Peralatan Dan Mesin</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="tabel-data" class="table table-bordered dataTable table-hover">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Aksi</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Kode Barang / Nomor Registrasi</th>
                                    <th class="text-center">Tahun Pengadaan</th>
                                    <th class="text-center">Tanggal Mutasi</th>
                                    <th class="text-center">Status Peralatan</th>
                                    <th class="text-center">Jenis Mutasi</th>
                                    <th class="text-center" width="300px">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabel-data').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('inventaris_peralatan_mutasi.datatables') }}",
                    data: function(req) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'inventaris.nama_barang',
                        name: 'inventaris.nama_barang',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'kode_barang_register',
                        name: 'inventaris.kode_barang',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'inventaris.tahun_pengadaan',
                        name: 'inventaris.tahun_pengadaan',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal_mutasi',
                        name: 'tahun_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(row) {
                            return row.status_mutasi ?? '-';
                        },
                        name: 'status_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(row) {
                            if (row.status_mutasi == 'Hapus') {
                                return row.jenis_mutasi;
                            }
                            return '-';
                        },
                        name: 'mutasi.jenis_mutasi',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(row) {
                            return row.keterangan ?? '-';
                        },
                        name: 'mutasi.keterangan',
                        empty: '-',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    [4, 'desc']
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                }
            });
            if (hapus == 0) {
                TableData.column(1).visible(false);
            }
            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
