
@if ($cari)
	@include('theme::partials.widget.bagian', ['kelas' => 'beranda'])
@elseif (!empty($judul_kategori))
	@include('theme::partials.widget.bagian', ['kelas' => 'beranda'])
@else
	@include('theme::partials.intro')
	@include('theme::partials.widget.bagian', ['kelas' => 'beranda'])
	@include('theme::partials.jelajah')
	@include('theme::partials.homemap')
	@include('theme::widgets.aparatur_desa')
	@include('theme::widgets.statistik')
	@include('theme::widgets.galeri')
	@if (!is_null($transparansi))
		@include('theme::partials.anggaran', $transparansi)
	@endif
	@include('theme::partials.artikel.headline')
@endif
@include('theme::partials.artikel.list')
@include('theme::partials.mandiri')
@include('theme::partials.widgets')