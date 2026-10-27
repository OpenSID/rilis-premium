@extends('theme::template')
@php
    // Kolom baris konten: kiri + kanan 3/6/3, hanya kiri 3/9, hanya kanan 9/3.
    $adaKiri    = function_exists('widget_aktif') && widget_aktif('kiri')->isNotEmpty();
    $adaKanan   = count($widgetAktif ?? []) > 0;
    $kolomUtama = 12 - ($adaKiri ? 3 : 0) - ($adaKanan ? 3 : 0);
    $kolomBaris = implode(' ', array_filter([$adaKiri ? 'minmax(0,3fr)' : null, "minmax(0,{$kolomUtama}fr)", $adaKanan ? 'minmax(0,3fr)' : null]));
@endphp
@section('widget_kiri_di_baris', '1')

@section('layout')
<div class="default-row mt-20">
	<div class="container-custom" style="margin-bottom:10px;">
        <div class="row-custom mlr-min-20 opensid-widget-baris" style="--opensid-kolom: {{ $kolomBaris }}">
            @if ($adaKiri)
            <div class="pageright">
            @include('theme::partials.widget.posisi', ['posisi' => 'kiri'])
            </div>
            @endif
            <div class="pageleft">
            {{-- Content --}}
            @yield('content')
            </div>
            @if ($adaKanan)
            <div class="pageright">
            @include('theme::partials.sidebar')
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
