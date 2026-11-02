@extends('theme::layouts.full-content')

@section('content')
	@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
		@include('theme::partials.pembangunan.index_klasik')
	@else
		@include('theme::partials.header')
		@include('theme::partials.beranda.kerangka', [
			'bagian' => ['pembangunan_daftar'],
			'kerangkaKelas' => 'lv-shell lv-shell--isi',
		])
		@include('theme::partials.footer')
	@endif
@endsection
