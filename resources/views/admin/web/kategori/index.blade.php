@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@section('title')
    <h1>
        Kategori
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Kategori</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button :url="'kategori/ajax_form/' . $parent" modal="true" />
            <x-hapus-button :url="'kategori/delete/' . $parent" :confirmDelete="true" :selectData="true" />
            
            @if ($parent)
                <x-kembali-button judul="Kembali ke Daftar Kategori" url="{{ci_route('kategori')}}" />
            @endif
        </div>
        @if ($subtitle)
            <div class="box-header with-border">
                <strong>{!! $subtitle !!}</strong>
            </div>
        @endif
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select id="status" class="form-control input-sm select2" name="status">
                        <option value="">Pilih Status</option>
                        @foreach ($status as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">#</th>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">Aksi</th>
                            <th nowrap>Nama {{ $parent ? 'Sub Kategori' : 'Kategori' }}</th>
                            <th>Aktif</th>
                            <th nowrap>Link</th>
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
            $('#status').val('1').trigger('change');

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('kategori.datatables') }}?parent={{ $parent }}",
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
                        data: 'kategori',
                        name: 'kategori',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'enabled',
                        name: 'enabled',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.enabled) {
                                return 'Ya'
                            }
                            return 'Tidak';
                        }
                    },
                    {
                        data: 'link',
                        name: 'link',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-'
                    },
                ],
                aaSorting: [],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                    $(row).addClass('dragable-handle');
                },
            });

            $('#status').change(function() {
                TableData.column(5).search($(this).val()).draw()
            })


            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(0).visible(false);
                TableData.column(3).visible(false);
            }

            // harus diletakkan didalam blok ini, jika tidak maka object TableData tidak dikenal
            @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('kategori.tukar')])
        });
    </script>
@endpush
