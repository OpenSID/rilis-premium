@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_highcharts')

@section('content')
	@include('theme::partials.header')
	@php
		$lvModern = theme_config('tampilan_beranda', 'modern') !== 'klasik';
		// Desil DTSEN dihitung per keluarga: kolom laki-laki/perempuan dari API selalu 0
		$lvTanpaJk = str_starts_with((string) $key, 'desil_');
		$lvTahun = $selected_tahun ?? '';
		$tipe_awal = ($default_chart_type ?? 'pie') === 'column' ? 'column' : 'pie';

		// Daftar statistik untuk berpindah halaman: hanya yang menunya aktif (pola sidenav tema Esensi)
		$lvGrup = [];
		if ($lvModern) {
			$lvNamaGrup = ['penduduk' => 'Penduduk', 'keluarga' => 'Keluarga', 'dtsen' => 'DTSEN', 'bantuan' => 'Bantuan', 'lainnya' => 'Lainnya'];
			foreach (daftar_statistik() as $grup => $daftar) {
				$isi = [];
				foreach ($daftar as $item) {
					$link = $grup === 'lainnya' ? $item['key'] : 'statistik/' . $item['key'];
					if (! isset($statistik_aktif[$link])) {
						continue;
					}
					$isi[] = [
						'label' => $item['label'],
						// Pakai url dari core: statistik bantuan hanya bisa dibuka lewat first/statistik/{key}
						'url'   => site_url($item['url']),
						'aktif' => (string) $item['key'] === (string) $key,
					];
				}
				if ($isi) {
					$lvGrup[] = [
						'label' => $lvNamaGrup[$grup] ?? ucfirst($grup),
						'isi'   => $isi,
						'aktif' => in_array(true, array_column($isi, 'aktif'), true),
					];
				}
			}
			$lvGrupAktif = collect($lvGrup)->firstWhere('aktif', true)['label'] ?? null;
		}
	@endphp
	<div class="contentpage">
		@if ($lvModern)
			<div class="margin-page">
				<div class="lv2 lv-statistik">
					<nav class="lv-crumb" aria-label="Lokasi halaman">
						<a href="{{ site_url() }}">Beranda</a><span aria-hidden="true">›</span><span>Statistik</span>
					</nav>
					<div class="lv-statistik__grid {{ $lvGrup ? '' : 'lv-statistik__grid--penuh' }}">
						@if ($lvGrup)
							<aside class="lv-statistik__nav" aria-label="Daftar statistik">
								<h2 class="lv-statistik__nav-judul">Jelajahi Statistik</h2>
								@foreach ($lvGrup as $grup)
									<details class="lv-statistik__grup" @if ($grup['aktif']) open @endif>
										<summary>{{ $grup['label'] }} <span class="lv-statistik__jml">{{ count($grup['isi']) }}</span></summary>
										<ul>
											@foreach ($grup['isi'] as $item)
												<li><a href="{{ $item['url'] }}" @if ($item['aktif']) aria-current="page" @endif>{{ $item['label'] }}</a></li>
											@endforeach
										</ul>
									</details>
								@endforeach
							</aside>
						@endif
						<div class="lv-statistik__isi">
							<div class="lv-statistik__kepala">
								<div>
									@if ($lvGrupAktif)
										<p class="lv-chip">Statistik {{ $lvGrupAktif }}</p>
									@endif
									<h1 class="lv-statistik__judul">{{ $heading }}</h1>
									<p class="lv-statistik__sub">{{ $judul }}</p>
									<p class="lv-meta">Diperbarui {{ tgl_indo($last_update) }}</p>
								</div>
								<div class="lv-statistik__unduh">
									<a class="lv-btn lv-btn--ghost" href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $lvTahun }}" target="_blank" rel="noopener"><i class="fa fa-print" aria-hidden="true"></i> Cetak</a>
									<a class="lv-btn lv-btn--ghost" href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $lvTahun }}" target="_blank" rel="noopener"><i class="fa fa-download" aria-hidden="true"></i> Unduh</a>
								</div>
							</div>

							@if ($lvGrup)
								<label class="lv-statistik__pilih">
									<span>Pilih statistik</span>
									<select onchange="if (this.value) window.location.href = this.value;">
										@foreach ($lvGrup as $grup)
											<optgroup label="{{ $grup['label'] }}">
												@foreach ($grup['isi'] as $item)
													<option value="{{ $item['url'] }}" @selected($item['aktif'])>{{ $item['label'] }}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
								</label>
							@endif

							<div class="lv-statistik__ringkas" aria-live="polite">
								<div class="lv-statistik__angka">
									<span class="lv-statistik__label" data-lv-ringkas-label="888">Total</span>
									<strong data-lv-ringkas="888">–</strong>
									<span class="lv-statistik__ket" data-lv-ringkas-ket="888"></span>
								</div>
								<div class="lv-statistik__angka">
									<span class="lv-statistik__label" data-lv-ringkas-label="666">Terdata</span>
									<strong data-lv-ringkas="666">–</strong>
									<span class="lv-statistik__ket" data-lv-ringkas-ket="666"></span>
								</div>
								<div class="lv-statistik__angka">
									<span class="lv-statistik__label" data-lv-ringkas-label="777">Belum mengisi</span>
									<strong data-lv-ringkas="777">–</strong>
									<span class="lv-statistik__ket" data-lv-ringkas-ket="777"></span>
								</div>
							</div>

							<section class="lv-card lv-statistik__kartu" aria-labelledby="lv-stat-grafik">
								<div class="lv-statistik__alat">
									<h2 class="lv-h3" id="lv-stat-grafik">Grafik</h2>
									<div class="lv-statistik__kontrol">
										@if (isset($list_tahun))
											<label class="lv-statistik__tahun">
												<span>Tahun</span>
												<select id="tahun" name="tahun">
													<option value="">Semua</option>
													@foreach ($list_tahun as $item_tahun)
														<option @selected($item_tahun == $lvTahun) value="{{ $item_tahun }}">{{ $item_tahun }}</option>
													@endforeach
												</select>
											</label>
										@endif
										<div class="lv-tabs" role="group" aria-label="Jenis grafik">
											<button type="button" class="btn-tipe-grafik" data-tipe="column" aria-pressed="{{ $tipe_awal === 'column' ? 'true' : 'false' }}" onclick="switchType(this);">Batang</button>
											<button type="button" class="btn-tipe-grafik" data-tipe="pie" aria-pressed="{{ $tipe_awal === 'pie' ? 'true' : 'false' }}" onclick="switchType(this);">Lingkaran</button>
										</div>
									</div>
								</div>
								<div id="container" class="lv-statistik__grafik"></div>
							</section>

							<section class="lv-card lv-statistik__kartu" aria-labelledby="lv-stat-tabel">
								<div class="lv-statistik__alat">
									<h2 class="lv-h3" id="lv-stat-tabel">Rincian Data</h2>
									<button type="button" id="tampilkan" class="lv-statistik__toggle" aria-pressed="false" onclick="toggle_tampilkan();">Tampilkan Nol</button>
								</div>
								<div class="lv-statistik__tabel">
									@include('theme::partials.statistik.tabel')
								</div>
								<button type="button" class="lv-btn lv-btn--ghost lv-statistik__lagi" id="showData">Tampilkan semua kelompok</button>
							</section>

							@if (setting('daftar_penerima_bantuan') && $bantuan)
								<section class="lv-card lv-statistik__kartu" aria-labelledby="lv-stat-penerima">
									<div class="lv-statistik__alat">
										<h2 class="lv-h3" id="lv-stat-penerima">Daftar {{ $heading }}</h2>
									</div>
									@include('theme::partials.statistik.penerima')
								</section>
							@endif
						</div>
					</div>
				</div>
			</div>
		@else
			<div class="margin-page">
				<div class="head-module align-center mb-20">
					<h1>Statistik</h1>
				</div>
			</div>
			<div class="margin-page">
				<div class="head-module align-center mb-20">
					<h2>{{ $judul }}</h2>
				</div>
				@if (isset($list_tahun))
					<div class="row" style="margin-bottom: 20px;">
						<label style="padding-top: 5px;" class="col-sm-1 control-label">Tahun: </label>
						<div class="col-sm-12 flex-center">
							<select class="form-control input-sm" id="tahun" name="tahun">
								<option selected="" value="">Semua</option>
								@foreach ($list_tahun as $item_tahun)
									<option @selected($item_tahun == $lvTahun) value="{{ $item_tahun }}">
										{{ $item_tahun }}</option>
								@endforeach
							</select>
						</div>
					</div>
				@endif
				<div class="box-stats flex-center">
					<button type="button" style="margin:10px 2px 0;" class="btn btn-sm btn-tipe-grafik {{ $tipe_awal === 'column' ? 'btn-primary' : 'btn-default' }}" data-tipe="column" aria-pressed="{{ $tipe_awal === 'column' ? 'true' : 'false' }}" onclick="switchType(this);"><i class="fa fa-bar-chart"></i> Bar Graph</button>
					<button type="button" style="margin:10px 2px 0;" class="btn btn-sm btn-tipe-grafik {{ $tipe_awal === 'pie' ? 'btn-primary' : 'btn-default' }}" data-tipe="pie" aria-pressed="{{ $tipe_awal === 'pie' ? 'true' : 'false' }}" onclick="switchType(this);"><i class="fa fa-pie-chart"></i> Pie Chart</button>
					<a style="margin:10px 2px 0;color:#fff!important;" href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.cetak") }}?tahun={{ $lvTahun }}" class="btn bggreen btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" target="_blank"><i class="fa fa-print "></i> Cetak</a>
					<a style="margin:10px 2px 0;color:#fff!important;" href="{{ ci_route("data-statistik.{$slug_aktif}.cetak.unduh") }}?tahun={{ $lvTahun }}" class="btn bgmagenta btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" target="_blank"><i class="fa fa-download"></i> Unduh</a>
				</div>
				<div class="box-body">
					<div id="container" style="min-height: 400px;"></div>
					<div id="contentpane">
						<div class="ui-layout-north panel top"></div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						@include('theme::partials.statistik.tabel')
						<div style="margin:10px 0;"><p>Diperbarui pada : {{ tgl_indo($last_update) }}</p></div>
						<div class="aksi-statistik" style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;">
							<button type="button" class="btn btn-default btn-sm" id="showData">Selengkapnya...</button>
							<button type="button" id="tampilkan" style="margin-left:auto;" onclick="toggle_tampilkan();" class="btn btn-default btn-sm">Tampilkan Nol</button>
						</div>
					</div>
				</div>

				@if (setting('daftar_penerima_bantuan') && $bantuan)
					<section class="content">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-info">
									<div class="box-header with-border" style="margin-bottom: 15px;">
										<h2 class="post_titile">Daftar {{ $heading }}</h2>
									</div>
									@include('theme::partials.statistik.penerima')
								</div>
							</div>
						</div>
					</section>
				@endif
			</div>
		@endif
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>

	@push('scripts')
		<script type="text/javascript">
			let chart_penduduk;
			const type = @json($tipe_awal);
			const legend = @json((bool) ($tipe ?? false));
			const grafik3d = @json((bool) setting('statistik_chart_3d'));
			const tanpaJk = @json($lvTanpaJk);
			const barisRingkasan = ['666', '777', '888'];
			let i = 1;
			let status_tampilkan = true;

			let dataGrafik = [];

			const angka = function(nilai) {
				const n = Number(nilai);
				return Number.isFinite(n) ? n.toLocaleString('id-ID') : (nilai ?? '');
			};
			// Nama baris ringkasan dari API berhuruf kapital ("BELUM MENGISI"); "JUMLAH" = kelompok yang sudah terdata
			const labelRingkasan = function(nama) {
				const teks = String(nama || '').toLowerCase();
				if (teks === 'jumlah') return 'Terdata';
				return teks.charAt(0).toUpperCase() + teks.slice(1);
			};

			function tampilkan_nol(tampilkan = false) {
				if (tampilkan) {
					$(".nol").parent().show();
				} else {
					$(".nol").parent().hide();
				}
				perbarui_grafik(tampilkan);
			}

			// Grafik mengikuti tabel: kelompok bernilai 0 hanya ikut tampil saat "Tampilkan Nol" aktif
			function perbarui_grafik(tampilkan = false) {
				if (typeof chart_penduduk === 'undefined') return;

				const terpilih = dataGrafik.filter(item => tampilkan || item.jumlah > 0);
				// Warna dikunci per kode agar kelompok yang sama tidak berganti warna saat nol ditampilkan/disembunyikan
				const warna = chart_penduduk.options.colors || Highcharts.getOptions().colors;

				chart_penduduk.xAxis[0].update({
					categories: terpilih.map(item => item.kode)
				}, false);
				// Di pie, nilai 0 tidak punya irisan: labelnya ikut ditampilkan agar perubahan terlihat
				chart_penduduk.update({
					plotOptions: {
						pie: {
							dataLabels: {
								filter: {
									property: 'y',
									operator: tampilkan ? '>=' : '>',
									value: 0
								}
							}
						}
					}
				}, false);
				chart_penduduk.series[0].setData(terpilih.map(item => ({
					name: item.nama,
					y: item.jumlah,
					color: warna[(item.kode - 1) % warna.length]
				})));
			}

			function toggle_tampilkan() {
				$('#showData').click();
				tampilkan_nol(status_tampilkan);
				$('#tampilkan').attr('aria-pressed', status_tampilkan ? 'true' : 'false');
				status_tampilkan = !status_tampilkan;
				if (status_tampilkan) $('#tampilkan').text('Tampilkan Nol');
				else $('#tampilkan').text('Sembunyikan Nol');
			}

			function switchType(obj) {
				const tipe = $(obj).data('tipe');
				if (chart_penduduk.series[0].type !== tipe) {
					chart_penduduk.series[0].update({
						type: tipe
					});
				}
				$('.btn-tipe-grafik').each(function() {
					const aktif = $(this).data('tipe') === tipe;
					$(this).toggleClass('btn-primary', aktif).toggleClass('btn-default', !aktif).attr('aria-pressed', aktif ? 'true' : 'false');
				});
			}

			// Kartu ringkasan (hanya ada di tampilan Modern)
			function isi_ringkasan(dataStats) {
				const total = dataStats.find(item => item.id === '888');
				dataStats.filter(item => barisRingkasan.includes(item.id)).forEach(item => {
					$(`[data-lv-ringkas="${item.id}"]`).text(angka(item.jumlah));
					if (item.id !== '888') {
						$(`[data-lv-ringkas-label="${item.id}"]`).text(labelRingkasan(item.nama));
						$(`[data-lv-ringkas-ket="${item.id}"]`).text(`${item.persen} dari total`);
					} else if (!tanpaJk) {
						$(`[data-lv-ringkas-ket="888"]`).text(`${angka(item.laki)} laki-laki · ${angka(item.perempuan)} perempuan`);
					}
				});
				if (!total) $('.lv-statistik__ringkas').hide();
			}

			$(document).ready(function() {
				$('#tahun').change(function() {
					const current_url = window.location.href.split('?')[0]
					window.location.href = `${current_url}?tahun=${encodeURIComponent($(this).val())}`;
				});

				chart_penduduk = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						options3d: {
							enabled: grafik3d,
							alpha: 45
						}
					},
					title: 0,
					yAxis: {
						showEmpty: false,
						title: {
							text: 'Jumlah'
						}
					},
					xAxis: {
						categories: [],
					},
					plotOptions: {
						series: {
							colorByPoint: true
						},
						column: {
							pointPadding: -0.1,
							borderWidth: 0,
							showInLegend: false,
							depth: grafik3d ? 45 : 0
						},
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							showInLegend: true,
							size: '80%',
							dataLabels: {
								distance: 20,
								style: {
									fontSize: '12px',
									fontWeight: '600'
								},
								filter: {
									property: 'y',
									operator: '>',
									value: 0
								}
							},
							depth: grafik3d ? 45 : 0,
							innerSize: grafik3d ? 70 : 0
						}
					},
					legend: {
						enabled: legend
					},
					// Layar sempit: label irisan pie tidak muat, diganti legenda di bawah grafik
					responsive: {
						rules: [{
							condition: {
								maxWidth: 520
							},
							chartOptions: {
								legend: {
									enabled: true
								},
								plotOptions: {
									pie: {
										size: '100%',
										dataLabels: {
											enabled: false
										}
									}
								}
							}
						}]
					},
					series: [{
						type: type,
						name: 'Jumlah Populasi',
						shadow: 1,
						border: 1,
						data: []
					}]
				});

				$('#showData').click(function() {
					$('tr.lebih').show();
					$('#showData').hide();
					tampilkan_nol(false);
				});

				let dataStats = [];

				$.ajax({
					url: `{{ ci_route('internal_api.statistik', $key) }}?tahun={{ $lvTahun }}`,
					method: 'POST',
					data: {},
					beforeSend: function() {
						$('#showData').hide()
					},
					success: function(json) {
						dataStats = json.data.map(item => {
							const {
								id
							} = item;
							const {
								nama,
								jumlah,
								laki,
								perempuan,
								persen,
								persen1,
								persen2
							} = item.attributes;
							return {
								id: String(id),
								nama,
								jumlah,
								laki,
								perempuan,
								persen,
								persen1,
								persen2
							};
						});

						const table = document.getElementById('table-statistik')
						const tbody = table.querySelector('tbody')
						tbody.innerHTML = ''
						const kolom = tanpaJk ? ['id', 'nama', 'jumlah', 'persen'] : ['id', 'nama', 'jumlah', 'persen', 'laki', 'persen1', 'perempuan', 'persen2']
						let _showBtnSelengkapnya = false
						// Populate table rows
						dataStats.forEach((item, index) => {
							const row = document.createElement('tr');
							const ringkasan = barisRingkasan.includes(item['id'])
							if (index > 11 && !ringkasan) {
								row.className = 'lebih';
								_showBtnSelengkapnya = true
							}
							if (ringkasan) {
								row.className = 'total';
							}
							for (const key of kolom) {
								const cell = document.createElement('td');
								let text = item[key]
								let className = 'angka'
								if (key == 'id') {
									className = 'kode'
									text = ringkasan ? '' : index + 1
								}
								if (key == 'nama') {
									className = ''
								}
								if (['jumlah', 'laki', 'perempuan'].includes(key)) {
									text = angka(text)
								}
								if (key == 'jumlah' && item[key] <= 0 && !ringkasan) {
									className += ' nol'
								}
								cell.className = className
								cell.textContent = text;
								row.appendChild(cell);
							}

							tbody.appendChild(row);
						});

						tampilkan_nol(false);

						if (_showBtnSelengkapnya) {
							$('#showData').show()
						}

						isi_ringkasan(dataStats);

						for (const stat of dataStats) {
							if (!barisRingkasan.includes(stat.id)) {
								dataGrafik.push({
									kode: i,
									nama: stat.nama,
									jumlah: parseInt(stat.jumlah) || 0
								});
								i++;
							}
						}

						perbarui_grafik(!status_tampilkan);
					},
					error: function() {
						$('#table-statistik tbody').html(`<tr><td colspan="${tanpaJk ? 4 : 8}" style="text-align:center;">Data statistik gagal dimuat. Silakan muat ulang halaman.</td></tr>`);
						$('.lv-statistik__ringkas').hide();
					}
				})
			});
		</script>
	@endpush

	@if (setting('daftar_penerima_bantuan') && $bantuan)
		@push('scripts')
			<script type="text/javascript">
				$(document).ready(function() {
					const bantuanUrl = '{{ ci_route('internal_api.peserta_bantuan', $key) }}?filter[tahun]={{ $lvTahun }}'
					let pesertaDatatable = $('#peserta_program').DataTable({
						processing: true,
						serverSide: true,
						order: [],
						ajax: {
							url: bantuanUrl,
							type: 'POST',
							data: function(row) {
								return {
									"page[size]": row.length,
									"page[number]": (row.start / row.length) + 1,
									"filter[search]": row.search.value,
									"sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]?.column]?.name,
								};
							},
							dataSrc: function(json) {
								json.recordsTotal = json.meta.pagination.total
								json.recordsFiltered = json.meta.pagination.total

								return json.data
							},
						},
						columns: [{
								data: null,
							},
							{
								data: 'attributes.nama',
								name: 'nama'
							},
							{
								data: 'attributes.kartu_nama',
								name: 'kartu_nama'
							},
							{
								data: 'attributes.kartu_alamat',
								name: 'kartu_alamat',
								orderable: false,
								searchable: false
							},
						],
						order: [1, 'asc'],
						language: {
							url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
						},
						drawCallback: function drawCallback() {
							$('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
						}
					});

					pesertaDatatable.on('draw.dt', function() {
						var PageInfo = $('#peserta_program').DataTable().page.info();
						pesertaDatatable.column(0, {
							page: 'current'
						}).nodes().each(function(cell, i) {
							cell.innerHTML = i + 1 + PageInfo.start;
						});
					});
				});
			</script>
		@endpush
	@endif
@endsection
