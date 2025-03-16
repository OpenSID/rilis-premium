@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')

@section('title')
    <h1>
        Database
        <small>{{ $action }} Backup Inkremental</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('database') }}">Pengaturan Database</a></li>
    <li class="active">{{ $action }} Backup Inkremental</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('database'), 'label' => 'Pengaturan Database'])
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-data">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Ukuran (MB)</th>
                            <th>Tanggal Backup</th>
                            <th>Tanggal Terakhir Download</th>
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
            $('#tabel-data').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'ajax': {
                    'url': "{{ ci_route('database.desa_inkremental') }}",
                    'method': 'get',
                    'data': function(d) {
                        d.tahun = $('#tahun').val();
                    }
                },
                'columns': [{
                        'data': 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        'data': 'aksi',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        'data': 'ukuran',
                        render: function(data, type, row) {
                            if (row.status == '3') {
                                return `<span class="label label-danger">Backup Dibatalkan</span>`
                            }
                            return row.ukuran;
                        }
                    },
                    {
                        'data': 'created_at'
                    },
                    {
                        'data': 'downloaded_at'
                    },
                ],
                'order': [
                    [2, 'desc'],
                ],
                'language': {
                    'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
                }
            });


        });
    </script>
@endpush
