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
<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
    <div class="opensid-widget-baris gap-8" style="--opensid-kolom: {{ $kolomBaris }}">
        @if ($adaKiri)
            <aside class="opensid-widget-kolom-kiri">
                @include('theme::partials.widget.posisi', ['posisi' => 'kiri'])
            </aside>
        @endif
        <main>
            @yield('content')
        </main>
        @if ($adaKanan)
            <aside class="space-y-6">
                @include('theme::partials.sidebar')
            </aside>
        @endif
    </div>
</div>
@endsection
