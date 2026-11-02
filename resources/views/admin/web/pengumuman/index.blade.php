@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengumuman
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengumuman</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.buttons.tambah', ['url' => 'pengumuman/form'])
            @include('admin.layouts.components.buttons.hapus', [
                'url' => route('pengumuman.delete_all'),
                'confirmDelete' => true,
                'selectData' => true,
            ])
        </div>
        <div class="box-body">
            <div class="row mepet">
                @include('admin.layouts.components.select_status')
            </div>
            <hr class="batas">
            <form id="mainform" name="mainform" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabeldata">
                        <thead>
                            <tr>
                                <th class="padat">#</th>
                                <th><input type="checkbox" id="checkall" /></th>
                                <th class="padat">NO</th>
                                <th class="padat">AKSI</th>
                                <th class="padat">GAMBAR</th>
                                <th>JUDUL</th>
                                <th>TAUTAN</th>
                                <th class="padat">PERIODE TAYANG</th>
                                <th class="padat">STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="dragable">
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Filter bawaan: Aktif.
            $('#status').val('{{ \App\Enums\AktifEnum::AKTIF }}').trigger('change');

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengumuman.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.status = $('#status').val();
                    }
                },
                columns: [{
                        data: 'drag-handle',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
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
                        data: 'gambar',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'tautan',
                        name: 'tautan',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'periode',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_label',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                aaSorting: [],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id);
                    $(row).addClass('dragable-handle');
                },
            });

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(0).visible(false);
                TableData.column(3).visible(false);
            }

            $('#status').change(function() {
                TableData.draw();
            });

            @include('admin.layouts.components.draggable', ['urlDraggable' => route('pengumuman.tukar')])
        });
    </script>
@endpush
