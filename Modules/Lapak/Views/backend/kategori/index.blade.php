@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Kategori
        <small>Daftar Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Kategori</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    @include('lapak::backend.navigasi', $navigasi)

    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button modal="true" :url="'lapak_admin/kategori_form/'.$main->id" />
            <x-hapus-button :url="'lapak_admin/kategori_delete_all'" :confirmDelete="true" :selectData="true" />
            @php
                $listCetakUnduh = [
                    ['url' => 'lapak_admin/kategori/dialog/cetak', 'modal' => true, 'judul' => 'Cetak', 'icon' => 'fa fa-print'],
                    ['url' => 'lapak_admin/kategori/dialog/unduh', 'modal' => true, 'judul' => 'Unduh', 'icon' => 'fa fa-download']
                ];
            @endphp
            <x-split-button judul="Cetak/Unduh" :list="$listCetakUnduh" :icon="'fa fa-arrow-circle-down'" :type="'bg-purple'" :target="true" />
        </div>
        <form id="mainform" name="mainform" method="post">
            <div class="box-body">
                <div class="row mepet">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="status" name="status">
                            <option value="">Pilih Status</option>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <hr class="batas">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-kategori">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Kategori</th>
                                <th>Jumlah Produk</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let tabel_produk = $('#tabel-kategori').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'order': [
                    [3, 'desc']
                ],
                'columnDefs': [{
                        'orderable': false,
                        'targets': [0, 1, 2]
                    },
                    {
                        'searchable': false,
                        'targets': [0, 1, 2, 4]
                    },
                    {
                        'className': 'padat',
                        'targets': [0, 1, 4]
                    },
                    {
                        'className': 'aksi',
                        'targets': [2]
                    }
                ],
                'ajax': {
                    'url': "{{ ci_route('lapak_admin/kategori') }}",
                    'method': 'get',
                    'data': function(d) {
                        d.status = $('#status').val();
                    }
                },
                'columns': [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        orderable: false,
                        searchable: false,
                        'data': 'DT_RowIndex'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        'data': 'kategori'
                    },
                    {
                        'data': 'jumlah'
                    },
                ],
                'language': {
                    'url': "{{ base_url('/assets/bootstrap/js/dataTables.indonesian.lang') }}"
                }
            });

            if (hapus == 0) {
                tabel_produk.column(0).visible(false);
            }

            if (ubah == 0) {
                tabel_produk.column(2).visible(false);
            }

            $('#status').on('select2:select', function(e) {
                tabel_produk.ajax.reload();
            });
        });
    </script>
@endpush
