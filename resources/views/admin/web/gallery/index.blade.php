@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@section('title')
    <h1>
        <h1>{{ $parent ? 'Rincian Album' : 'Daftar Album' }}</h1>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $parent ? 'Rincian Album' : 'Daftar Album' }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('gallery.form', $parentEncrypt) }}"
                    class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                        class='fa fa-plus'></i> Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data"
                    onclick="deleteAllBox('mainform', '{{ ci_route('gallery.delete', $parentEncrypt) }}')"
                    class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'></i>
                    Hapus</a>
            @endif
            @if ($parent)
                @include('admin.layouts.components.tombol_kembali', [
                    'url' => ci_route('gallery'),
                    'label' => 'Daftar Album',
                ])
            @endif
        </div>
        @if ($subtitle)
            <div class="box-header with-border">
                <strong>Nama Album : {{ $subtitle }}</strong>
            </div>
        @endif
        <div class="box-body">
            <div class="row mepet">
                @include('admin.layouts.components.select_status')
            </div>
            <hr class="batas">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">#</th>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th nowrap>Gambar</th>
                            <th nowrap>Nama {{ $parent ? 'Gambar' : 'Album' }}</th>
                            <th>Dimuat Pada</th>
                            <th nowrap>Status</th>
                            <th></th>
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
            $('#status').val(1).trigger('change');

            var parent = '{{ $parent }}';
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [
                    [8, 'asc']
                ],
                ajax: {
                    url: "{{ ci_route('gallery.datatables') }}",
                    data: function(req) {
                        req.parent = parent;
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
                        name: 'gambar',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'status_label',
                        name: 'enabled',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'urut',
                        name: 'urut',
                        searchable: false,
                        orderable: true,
                        visible: false
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                    $(row).addClass('dragable-handle');
                },
                drawCallback: function() {
                    $('[data-rel="popover"]').popover({
                        html: true,
                        trigger: "hover",
                    });
                }
            });

            $('#status').change(function() {
                TableData.ajax.reload();
            })

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(0).visible(false);

                if (parent) {
                    TableData.column(3).visible(false);
                }
            }

            @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('gallery.tukar')])

        });
    </script>
@endpush
