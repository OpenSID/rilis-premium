@extends('theme::template')

@section('layout')
    <div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <div class="lg:w-1/3 w-full">
            @include('theme::partials.statistik.sidenav')
        </div>
        <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            <div class="breadcrumb">
                <ol>
                    <li><a href="{{ site_url() }}">Beranda</a></li>
                    <li>Data Statistik</li>
                </ol>
            </div>
            <h1 class="text-h2">{{ $heading }}</h1>

            <div class="content py-3">
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
        </main>
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