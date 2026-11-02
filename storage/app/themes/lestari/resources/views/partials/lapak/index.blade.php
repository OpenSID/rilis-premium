@extends('theme::layouts.full-content')

@section('content')
	@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
		@include('theme::partials.lapak.index_klasik')
	@else
		@include('theme::partials.header')
		@include('theme::partials.beranda.kerangka', [
			'bagian' => ['lapak_daftar'],
			'kerangkaKelas' => 'lv-shell lv-shell--isi',
		])
		@include('theme::partials.footer')
	@endif
@endsection
