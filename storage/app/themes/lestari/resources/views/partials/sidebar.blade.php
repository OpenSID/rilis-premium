@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="sidebar">
	<div class="head-module">
		<h2>Terbaru</h2>
	</div>
	@foreach (['terkini' => 'arsip_terkini'] as $jenis => $jenis_arsip)
		@foreach ($$jenis_arsip as $arsip)
			<a href="{{ site_url('artikel/' . buat_slug($arsip)) }}">
			<div class="arsipsmall">
				<div class="arsip-grid">
					<div class="arsipsmall-image">
						<div class="image-small imagefull">
						@if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
							<img src="{{ AmbilFotoArtikel($arsip['gambar'], 'sedang') }}"/>
						@else
							<img src="{{ asset('images/404-image-not-found.jpg') }}"/>
						@endif
						</div>
					</div>
					<div class="arsipsmall-text">
						<p><font class="color1">{{ hit($arsip['hit']) }} dibuka</font><br/>{{ $arsip['judul'] }}</p>
					</div>
				</div>	
			</div>
			</a>
		@endforeach	
	@endforeach		
</div>							
