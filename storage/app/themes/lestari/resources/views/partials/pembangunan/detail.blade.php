@extends('theme::layouts.full-content')

@section('content')
	@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
		@include('theme::partials.pembangunan.detail_klasik')
	@else
		@include('theme::partials.header')
		@include('theme::partials.beranda.kerangka', [
			'bagian' => ['pembangunan_detail'],
			'kerangkaKelas' => 'lv-shell lv-shell--isi',
		])
		@include('theme::partials.footer')
	@endif
@endsection
