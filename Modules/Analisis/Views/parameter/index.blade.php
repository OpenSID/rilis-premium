@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Ukuran/Nilai Indikator Analisis
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Indikator</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li>Pengaturan Indikator Analisis</li>
    <li class="active">Pengaturan Nilai</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">

            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a
                            href="{{ $baseRoute . '/form' }}"
                            class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Tambah Ukuran Ukuran/Nilai Baru"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Tambah Data Parameter"
                        ><i class="fa fa-plus"></i> Tambah Ukuran Ukuran/Nilai Baru</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ $baseRoute . '/delete' }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i>
                            Hapus</a>
                    @endif
                    <a href="{{ ci_route('analisis_indikator', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i>Kembali Indikator Analisis</a>
                </div>
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="tabeldata" class="table table-bordered table-striped dataTable table-hover">
                                                <thead class="bg-gray disabled color-palette">
                                                    <tr>
                                                        <th>#</th>
                                                        <th><input type="checkbox" id="checkall" /></th>
                                                        <th>NO</th>
                                                        <th>AKSI</th>
                                                        <th>KODE</th>
                                                        <th>JAWABAN</th>
                                                        <th>NILAI/UKURAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dragable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ $baseRoute . '/' . 'datatables' }}",
                    method: 'POST',
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
                        data: 'kode_jawaban',
                        name: 'kode_jawaban',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'jawaban',
                        name: 'jawaban',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nilai',
                        name: 'nilai',
                        searchable: true,
                        orderable: true,
                        class: 'padat'
                    },
                ],
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'Semua']
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-id', data.id)
                    $(row).addClass('dragable-handle');
                },
            });

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(3).visible(false);
            }

            @if ($bisaUrut)
                // harus diletakkan didalam blok ini, jika tidak maka object TableData tidak dikenal
                @include('admin.layouts.components.draggable', ['urlDraggable' => $baseRoute . '/tukar'])

                // Urutan hanya bisa digeser saat tabel tidak diurutkan berdasarkan kolom lain dan tanpa pencarian,
                // karena hanya pada kondisi itu baris yang tampil sesuai urutan sebenarnya
                TableData.on('draw', function() {
                    var urutan = TableData.order();
                    var berdasarkanUrut = urutan.length === 0;

                    $('#dragable').sortable('option', 'disabled', !(berdasarkanUrut && TableData.search() === ''));
                });
            @else
                TableData.column(0).visible(false);
            @endif
        });
    </script>
@endpush
