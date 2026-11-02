@extends('theme::layouts.full-content')

@section('content')
    <div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
        <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
            <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
                <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $heading }}</li>
            </ol>
        </nav>
        <h1 class="text-2xl lg:text-3xl font-bold uppercase text-center my-6">{{ $heading }}</h1>
        <div class="overflow-x-auto mt-8">
            <form id="filter-laporan" class="mb-3" style="display:flex;flex-wrap:nowrap;gap:8px;align-items:center;overflow-x:auto">
                <select name="bulan" class="form-control" style="width:auto;flex:0 0 auto"></select>
                <select name="tahun" class="form-control" style="width:auto;flex:0 0 auto"></select>
            </form>
            <p id="pesan-laporan" style="display:none"></p>

            <div class="table-responsive">
                <table class="table table-striped table-bordered w-full text-sm" id="tabel-laporan">
                    <thead>
                        <tr>
                            <th rowspan="3">No</th>
                            <th rowspan="3">Perincian</th>
                            <th colspan="7" class="text-center">Penduduk</th>
                            <th colspan="3" rowspan="2" class="text-center">Keluarga (KK)</th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">WNI</th>
                            <th colspan="2" class="text-center">WNA</th>
                            <th colspan="3" class="text-center">Jumlah</th>
                        </tr>
                        <tr>
                            <th class="text-center">L</th><th class="text-center">P</th>
                            <th class="text-center">L</th><th class="text-center">P</th>
                            <th class="text-center">L</th><th class="text-center">P</th><th class="text-center">L+P</th>
                            <th class="text-center">L</th><th class="text-center">P</th><th class="text-center">L+P</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('filter-laporan');
            var tbody = document.querySelector('#tabel-laporan tbody');
            var tabel = document.getElementById('tabel-laporan');
            var pesan = document.getElementById('pesan-laporan');
            var now = new Date();
            var namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            var baris = [
                ['Penduduk/Keluarga awal bulan ini', 'penduduk_awal'],
                ['Kelahiran/Keluarga baru bulan ini', 'kelahiran'],
                ['Kematian bulan ini', 'kematian'],
                ['Pendatang bulan ini', 'pendatang'],
                ['Pindah/Keluarga pergi bulan ini', 'pindah'],
                ['Penduduk hilang bulan ini', 'hilang'],
                ['Penduduk/Keluarga akhir bulan ini', 'penduduk_akhir']
            ];
            var angka = function(n) { return Number(n).toLocaleString('id-ID'); };

            namaBulan.forEach(function(nama, i) { form.elements['bulan'].add(new Option(nama, i + 1)); });
            for (var t = now.getFullYear(); t >= 2000; t--) { form.elements['tahun'].add(new Option(t, t)); }
            form.elements['bulan'].value = now.getMonth() + 1;
            form.elements['tahun'].value = now.getFullYear();

            function muat() {
                var params = new URLSearchParams(new FormData(form));
                tbody.innerHTML = '<tr><td colspan="12" class="text-center">Memuat data...</td></tr>';
                pesan.style.display = 'none';
                fetch('{{ route('api.laporan-bulanan') }}?' + params.toString())
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (!res.data) {
                            tabel.style.display = 'none';
                            pesan.textContent = res.pesan || 'Data tidak tersedia.';
                            pesan.style.display = '';
                            return;
                        }
                        tabel.style.display = '';
                        tbody.innerHTML = baris.map(function(b, i) {
                            var d = res.data[b[1]];
                            var cells = [
                                d.WNI_L, d.WNI_P, d.WNA_L, d.WNA_P,
                                d.WNI_L + d.WNA_L, d.WNI_P + d.WNA_P, d.WNI_L + d.WNA_L + d.WNI_P + d.WNA_P,
                                d.KK_L, d.KK_P, d.KK
                            ];
                            return '<tr><td class="text-center">' + (i + 1) + '</td><td>' + b[0] + '</td>' +
                                cells.map(function(c) { return '<td class="text-right">' + angka(c) + '</td>'; }).join('') + '</tr>';
                        }).join('');
                    })
                    .catch(function() {
                        tabel.style.display = 'none';
                        pesan.textContent = 'Data gagal dimuat.';
                        pesan.style.display = '';
                    });
            }

            form.addEventListener('change', muat);
            muat();
        });
    </script>
@endpush