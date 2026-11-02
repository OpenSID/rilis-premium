@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_highcharts')

@section('content')
    <div style="margin:0 0 20px;width:100%;">
        <div class="heading-module l-flex">
            <div class="heading-module-inner l-flex">
                <i class="fa fa-pie-chart"></i><h1>Data Statistik</h1>
            </div>
        </div>
        <div class="c-flex" style="margin:20px 0 20px;text-align:center;width:100%;">
            <h1>{{ $heading }}</h1>
        </div>
        <div class="box-body">
            <form id="filter-laporan" class="mb-3" style="display:flex;flex-wrap:nowrap;gap:8px;align-items:center;overflow-x:auto">
                <select name="jenis" class="form-control" style="width:auto;flex:0 0 auto">
                    <option value="penduduk">Penduduk</option>
                    <option value="keluarga">Keluarga</option>
                </select>
                <select name="periode" class="form-control" style="width:auto;flex:0 0 auto">
                    <option value="bulan">Per Bulan</option>
                    <option value="tahun">Per Tahun</option>
                </select>
                <select name="tahun" class="form-control" data-periode="bulan" style="width:auto;flex:0 0 auto"></select>
                <select name="tahun_awal" class="form-control" data-periode="tahun" style="width:auto;flex:0 0 auto;display:none"></select>
                <span data-periode="tahun" style="display:none">s/d</span>
                <select name="tahun_akhir" class="form-control" data-periode="tahun" style="width:auto;flex:0 0 auto;display:none"></select>
                <select id="tipe-grafik" class="form-control" style="width:auto;flex:0 0 auto">
                    <option value="line">Grafik Garis</option>
                    <option value="column">Grafik Batang</option>
                </select>
            </form>

            <div id="chart-perkembangan" style="height:380px;margin-bottom:16px"></div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered w-full text-sm" id="tabel-laporan">
                    <thead></thead>
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
            var thead = document.querySelector('#tabel-laporan thead');
            var tbody = document.querySelector('#tabel-laporan tbody');
            var tahunIni = new Date().getFullYear();
            var angka = function(n) { return Number(n).toLocaleString('id-ID'); };

            ['tahun', 'tahun_awal', 'tahun_akhir'].forEach(function(nama) {
                var select = form.elements[nama];
                for (var t = tahunIni; t >= 2000; t--) {
                    select.add(new Option(t, t));
                }
            });
            form.elements['tahun_awal'].value = tahunIni - 1;

            function toggle() {
                var periode = form.elements['periode'].value;
                form.querySelectorAll('[data-periode]').forEach(function(el) {
                    el.style.display = el.dataset.periode === periode ? '' : 'none';
                });
            }

            var chart = Highcharts.chart('chart-perkembangan', {
                chart: { type: 'line' },
                title: { text: null },
                xAxis: { categories: [] },
                yAxis: { title: { text: 'Jumlah' }, allowDecimals: false },
                tooltip: { shared: true },
                credits: { enabled: false },
                series: []
            });

            function gambarGrafik(res) {
                var tipe = document.getElementById('tipe-grafik').value;
                var jenis = res.filter.jenis === 'keluarga' ? 'Keluarga' : 'Penduduk';
                chart.update({
                    chart: { type: tipe },
                    yAxis: { title: { text: 'Jumlah ' + jenis } }
                }, false);
                chart.xAxis[0].setCategories(res.labels, false);
                while (chart.series.length) {
                    chart.series[0].remove(false);
                }
                res.series.forEach(function(s) {
                    chart.addSeries({ name: s.name, data: s.data, visible: s.visible }, false);
                });
                chart.redraw();
            }

            var dataTerakhir = null;

            function muat() {
                toggle();
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">Memuat data...</td></tr>';
                var params = new URLSearchParams(new FormData(form));
                fetch('{{ route('api.laporan-perkembangan') }}?' + params.toString())
                    .then(function(r) {
                        return r.json().then(function(res) {
                            if (!r.ok) {
                                throw { pesan: res.message };
                            }
                            return res;
                        });
                    })
                    .then(function(res) {
                        dataTerakhir = res;
                        gambarGrafik(res);
                        var keys = Object.keys(res.kolom);
                        thead.innerHTML = '<tr><th>' + (res.filter.periode === 'tahun' ? 'Tahun' : 'Bulan') + '</th>' +
                            keys.map(function(k) { return '<th class="text-center">' + res.kolom[k] + '</th>'; }).join('') + '</tr>';
                        tbody.innerHTML = res.data.map(function(row) {
                            return '<tr><td>' + row.label + '</td>' +
                                keys.map(function(k) { return '<td class="text-right">' + angka(row[k]) + '</td>'; }).join('') + '</tr>';
                        }).join('');
                    })
                    .catch(function(e) {
                        tbody.innerHTML = '<tr><td colspan="9" class="text-center"></td></tr>';
                        tbody.querySelector('td').textContent = (e && e.pesan) || 'Data gagal dimuat.';
                    });
            }

            form.addEventListener('change', function(e) {
                if (e.target.id === 'tipe-grafik' && dataTerakhir) {
                    gambarGrafik(dataTerakhir);
                    return;
                }
                muat();
            });
            muat();
        });
    </script>
@endpush