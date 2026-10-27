@extends('theme::layouts.' . $layout)

@section('content')
	@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
		@include('theme::partials.artikel.detail_klasik')
	@else
		@include('theme::partials.header')
		@if ($single_artikel['id'])
			@include('theme::partials.beranda.kerangka', [
				'bagian' => ['artikel_detail'],
				'kerangkaKelas' => 'lv-shell lv-shell--isi',
			])
		@else
			@include('theme::partials.beranda.kerangka', [
				'bagian' => ['tidak_ditemukan'],
				'kerangkaKelas' => 'lv-shell lv-shell--isi',
				'lvTidakDitemukan' => [
					'judul' => 'Artikel tidak ditemukan',
					'pesan' => 'Artikel yang Anda buka tidak ada, belum diterbitkan, atau sudah dihapus.',
				],
			])
		@endif
		@include('theme::partials.footer')
	@endif
@endsection
