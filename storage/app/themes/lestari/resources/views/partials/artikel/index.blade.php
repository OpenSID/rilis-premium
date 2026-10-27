@extends('theme::layouts.right-sidebar')
@section('widget_atas_di_konten', '1')
@section('content')

	@php $lvKlasik = theme_config('tampilan_beranda', 'modern') === 'klasik'; @endphp
	@if (!$lvKlasik && !$cari && empty($judul_kategori))
		@include('theme::partials.beranda.index')
	@elseif (!$lvKlasik)
		{{-- Hasil pencarian & kategori artikel --}}
		@include('theme::partials.beranda.daftar_artikel')
	@else
		<div class="head-home">
			@include('theme::partials.header')
		</div>
		<div class="homepage">
			@if ($cari)
			@elseif (!empty($judul_kategori))
			@else
				@include('theme::partials.slider')
				@include('theme::partials.running_text')
			@endif
			@include('theme::partials.home')
			@include('theme::partials.footer')
		</div>
	@endif

	@include('theme::partials.pengumuman')
@endsection
