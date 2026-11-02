@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
<div class="heading-module l-flex">
    <div class="heading-module-inner l-flex">
        <i class="fa fa-book"></i>
        <h1>{{ $judul }}</h1>
    </div>
</div>
<div class="box-def">
    <div class="box-def-inner">
        <div class="c-flex" style="margin:20px 0 20px;text-align:center;width:100%;">
            <h1>{{ $judul }}</h1>
            <p>{{ $tanggal }}</p>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered customtables" id="tabelData">
                <thead>
                    <tr>
                        <th><center>No</center></th>
                        <th><center>Jam</center></th>
                        <th><center>Nama</center></th>
                        <th><center>Instansi</center></th>
                        <th><center>Bertemu</center></th>
                        <th><center>Keperluan</center></th>
                    </tr>
                </thead>
            </table>
        </div>
        <p style="margin-top: 10px; font-size: 12px;">Nama tamu disamarkan untuk melindungi data pribadi.</p>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Data tamu diisi publik lewat kios: selalu tampilkan sebagai teks, bukan HTML.
            var teks = value => $('<div>').text(value || '-').html();

            var tabelData = $('#tabelData').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: true,
                ajax: {
                    url: `{{ route('api.data-tamu') }}`,
                    method: 'POST',
                    data: row => ({
                        "page[size]": row.length,
                        "page[number]": (row.start / row.length) + 1,
                        "filter[search]": row.search.value,
                        "sort": `${row.order[0]?.dir === "asc" ? "" : "-"}${row.columns[row.order[0]?.column]?.name}`
                    }),
                    dataSrc: json => {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;
                        return json.data;
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                    }
                },
                columnDefs: [{
                    targets: '_all',
                    className: 'text-nowrap'
                }, ],
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jam',
                        name: 'jam',
                        render: (data, type, row) => teks(row.attributes.jam)
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: false,
                        render: (data, type, row) => teks(row.attributes.nama)
                    },
                    {
                        data: 'instansi',
                        name: 'instansi',
                        render: (data, type, row) => teks(row.attributes.instansi)
                    },
                    {
                        data: 'bertemu',
                        name: 'bertemu',
                        render: (data, type, row) => teks(row.attributes.bertemu)
                    },
                    {
                        data: 'keperluan',
                        name: 'keperluan',
                        className: 'text-wrap',
                        render: (data, type, row) => teks(row.attributes.keperluan)
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    $(api.table().body()).find('td.dataTables_empty').text('Belum ada tamu hari ini.');
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = api.page.info().start + i + 1;
                    });
                }
            });
        });
    </script>
@endpush
