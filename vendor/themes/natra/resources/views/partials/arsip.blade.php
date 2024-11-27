@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_page_area">
	<div style="margin-top:0px;">
		@if (!empty($teks_berjalan))
		<marquee onmouseover="this.stop()" onmouseout="this.start()">
			@include("layouts.teks_berjalan")
		</marquee>
		@endif
	</div>
	<div class="single_category wow fadeInDown">
		<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span
				class="title_text">Arsip Konten Situs Web {{ $desa["nama_desa"] }}</span> </h2>
	</div>
	<div style="margin-top:50px;">
		<div class="box-body">
			@if(count($farsip ?? [])>0)
			<table class="table table-striped">
				<thead>
					<tr>
						<td width="3%"><b>No.</b></td>
						<td width="20%"><b>Tanggal Artikel</b></td>
						<td><b>Judul Artikel</b></td>
						<td width="20%"><b>Penulis</b></td>
						<td width="10%"><b>Dibaca</b></td>
					</tr>
				</thead>
				<tbody>
					@foreach($farsip as $data)
					<tr>
						<td style="text-align:center;">
							{{ $data["no"] }}
						</td>
						<td>
							{{ tgl_indo($data["tgl_upload"]) }}
						</td>
						<td>
							<a href="{{ site_url('artikel/'.buat_slug($data)) }}">{{ $data["judul"] }}</a>
						</td>
						<td style="text-align:center;">
							{{ $data["owner"] }}
						</td>
						<td style="text-align:center;">
							{{ hit($data['hit']) }}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			@include("commons.page", $data);

			@else
			Belum ada arsip konten web.
			@endif
		</div>
	</div>
</div>