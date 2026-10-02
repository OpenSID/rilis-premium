@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_highcharts')

@section('content')
	@include('theme::partials.header')
	<div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>Statistik Desil DTSEN</h1>
				<p>Distribusi tingkat kesejahteraan keluarga berdasarkan data Kemensos (SIKNg) dan hasil analisis/pendataan DTSEN desa</p>
			</div>
		</div>

		<div class="margin-page align-center" id="dtsen-desil-loading">
			<div class="box-body">
				@include('theme::commons.loading')
			</div>
		</div>

		<div class="margin-page" id="dtsen-desil-kosong" style="display:none;">
			<div class="box-body align-center">
				<p>Data statistik desil DTSEN belum tersedia untuk saat ini.</p>
			</div>
		</div>

		<div class="margin-page" id="dtsen-desil-isi" style="display:none;">
			<div class="row">
				<div class="col-lg-4 col-sm-12 population-data">
					<div class="population-grid box-shadow brd-10">
						<div class="population-left align-center flex-center">
							<div class="population-inner" id="desil-total-keluarga">0</div>
						</div>
						<div class="population-right align-center flex-center">
							<div class="population-inner">Total Keluarga</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-sm-12 population-data">
					<div class="population-grid box-shadow brd-10">
						<div class="population-left align-center flex-center">
							<div class="population-inner" id="desil-total-terdaftar">0</div>
						</div>
						<div class="population-right align-center flex-center">
							<div class="population-inner">Terdaftar DTSEN</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-sm-12 population-data">
					<div class="population-grid box-shadow brd-10">
						<div class="population-left align-center flex-center">
							<div class="population-inner" id="desil-total-belum-terdaftar">0</div>
						</div>
						<div class="population-right align-center flex-center">
							<div class="population-inner">Belum Terdaftar DTSEN</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-20">
				<div class="col-lg-6 col-sm-12">
					<div class="box-body">
						<div class="head-module align-center mt-20 mb-20"><h2>Desil Kemensos (SIKNg)</h2></div>
						<div class="box-stats flex-center">
							<a style="margin:10px 2px 0;color:#fff!important;" class="btn bgblue-navy btn-sm" onclick="switchTypeDesil(this, chartDesilKemensos);">Bar Graph</a>
							<a style="margin:10px 2px 0;color:#fff!important;" class="btn bgorange btn-sm" onclick="switchTypeDesil(this, chartDesilKemensos);">Pie Chart</a>
						</div>
						<div id="container-desil-kemensos"></div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-12">
					<div class="box-body">
						<div class="head-module align-center mt-20 mb-20"><h2>Desil Analisis Desa</h2></div>
						<div class="box-stats flex-center">
							<a style="margin:10px 2px 0;color:#fff!important;" class="btn bgblue-navy btn-sm" onclick="switchTypeDesil(this, chartDesilAnalisis);">Bar Graph</a>
							<a style="margin:10px 2px 0;color:#fff!important;" class="btn bgorange btn-sm" onclick="switchTypeDesil(this, chartDesilAnalisis);">Pie Chart</a>
						</div>
						<div id="container-desil-analisis"></div>
					</div>
				</div>
			</div>
		</div>

		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection

@push('scripts')
<script type="text/javascript">
	let chartDesilKemensos, chartDesilAnalisis;

	function switchTypeDesil(obj, chart) {
		const chartType = chart.series[0].type;
		chart.series[0].update({
			type: (chartType === 'pie') ? 'column' : 'pie'
		});
		$(obj).toggleClass('btn-primary btn-default');
		$(obj).siblings().toggleClass('btn-primary btn-default');
	}

	function buatChartDesil(renderTo, warna) {
		return new Highcharts.Chart({
			chart: {
				renderTo: renderTo
			},
			title: 0,
			yAxis: {
				showEmpty: false
			},
			xAxis: {
				categories: []
			},
			colors: warna,
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: -0.1,
					borderWidth: 0,
					showInLegend: false
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					showInLegend: true
				}
			},
			legend: {
				enabled: false
			},
			series: [{
				type: 'column',
				name: 'Jumlah Keluarga',
				data: []
			}]
		});
	}

	$(document).ready(function() {
		const warnaTema = [
			'{{ theme_config('color1', '#005dfa') }}',
			'{{ theme_config('color2', '#ffb200') }}',
			'{{ theme_config('color3', '#003793') }}'
		];

		chartDesilKemensos = buatChartDesil('container-desil-kemensos', warnaTema);
		chartDesilAnalisis = buatChartDesil('container-desil-analisis', warnaTema);

		$.ajax({
			url: '{{ ci_route('internal_api.dtsen.statistik-desil') }}',
			method: 'GET',
			dataType: 'json',
			success: function(json) {
				const attrs = json.data && json.data[0] && json.data[0].attributes;

				$('#dtsen-desil-loading').hide();

				if (!attrs || !attrs.total_terdaftar) {
					$('#dtsen-desil-kosong').show();
					return;
				}

				$('#desil-total-keluarga').text(attrs.total_keluarga);
				$('#desil-total-terdaftar').text(attrs.total_terdaftar);
				$('#desil-total-belum-terdaftar').text(attrs.total_belum_terdaftar);

				const kategoriKemensos = (attrs.chart_data.kemensos || []).map(item => item[0]);
				const kategoriAnalisis = (attrs.chart_data.analisis || []).map(item => item[0]);

				chartDesilKemensos.xAxis[0].update({
					categories: kategoriKemensos
				});
				chartDesilKemensos.series[0].setData(attrs.chart_data.kemensos || []);

				chartDesilAnalisis.xAxis[0].update({
					categories: kategoriAnalisis
				});
				chartDesilAnalisis.series[0].setData(attrs.chart_data.analisis || []);

				$('#dtsen-desil-isi').show();
			},
			error: function() {
				$('#dtsen-desil-loading').hide();
				$('#dtsen-desil-kosong').show();
			}
		});
	});
</script>
@endpush
