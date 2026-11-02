@extends('theme::layouts.full-content')

@push('styles')
    <style>
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            @apply mb-4;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            @apply text-sm font-medium text-gray-700 dark:text-gray-300;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 shadow-sm px-3 py-1.5 ml-2 focus:ring-blue-500 focus:border-blue-500;
            border-radius: 0 !important;
        }

        .dataTables_wrapper .dataTables_info {
            @apply text-sm text-gray-600 dark:text-gray-400 pt-4;
        }

        .dataTables_wrapper .dataTables_paginate {
            @apply pt-4;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors duration-200;
            margin-left: -1px;
            border-radius: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            @apply bg-gray-100 dark:bg-gray-700 z-10;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white border-blue-600 dark:bg-blue-500 dark:border-blue-500 z-20;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            @apply bg-blue-700 dark:bg-blue-600;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-50;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:first-child {
            margin-left: 0;
        }
    </style>
@endpush

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $judul }}</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">{{ $judul }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">{{ $tanggal }}</p>

    <div class="overflow-x-auto mt-8">
        <table class="w-full text-sm border-collapse" id="tabel-data-tamu">
            <thead class="bg-gray-100 dark:bg-gray-700/50">
                <tr>
                    <th class="p-3 text-center text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">No.</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Jam</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Nama</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Instansi</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Bertemu</th>
                    <th class="p-3 text-left text-xs font-bold uppercase tracking-wider border border-gray-300 dark:border-gray-600">Keperluan</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">Nama tamu disamarkan untuk melindungi data pribadi.</p>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data tamu diisi publik lewat kios: selalu tampilkan sebagai teks, bukan HTML.
        const teks = value => $('<div>').text(value || '-').html();
        const sel = 'p-2 border border-gray-300 dark:border-gray-600';

        $('#tabel-data-tamu').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `{{ route('api.data-tamu') }}`,
                method: 'POST',
                data: d => ({
                    "page[size]": d.length,
                    "page[number]": (d.start / d.length) + 1,
                    "filter[search]": d.search.value,
                    "sort": d.order.length > 0 ? (d.order[0].dir === 'asc' ? '' : '-') + d.columns[d.order[0].column].name : ''
                }),
                dataSrc: json => {
                    json.recordsTotal = json.meta.pagination.total;
                    json.recordsFiltered = json.meta.pagination.total;
                    return json.data;
                }
            },
            columns: [
                { data: null, searchable: false, orderable: false, className: `text-center ${sel}` },
                { data: 'attributes.jam', name: 'jam', className: `whitespace-nowrap ${sel}`, render: data => teks(data) },
                { data: 'attributes.nama', name: 'nama', orderable: false, className: sel, render: data => teks(data) },
                { data: 'attributes.instansi', name: 'instansi', className: sel, render: data => teks(data) },
                { data: 'attributes.bertemu', name: 'bertemu', className: sel, render: data => teks(data) },
                { data: 'attributes.keperluan', name: 'keperluan', className: `whitespace-normal ${sel}`, render: data => teks(data) }
            ],
            order: [[1, 'desc']],
            language: { url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}" },
            drawCallback: function() {
                var api = this.api();
                $(api.table().body()).find('td.dataTables_empty').text('Belum ada tamu hari ini.');
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    cell.innerHTML = api.page.info().start + i + 1;
                });
            }
        });
    });
</script>
@endpush
