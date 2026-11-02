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
    <div class="w-full opensid-widget-baris my-6 gap-6 text-gray-600" style="--opensid-kolom: {{ $kolomBaris }}">
        @if ($adaKiri)
            <aside class="opensid-widget-kolom-kiri">
                @include('theme::partials.widget.posisi', ['posisi' => 'kiri'])
            </aside>
        @endif
        {{-- Content --}}
        <main class="w-full bg-white rounded-xl shadow-sm overflow-hidden p-4 lg:p-6">
            @yield('content')
        </main>
        <!-- Widget -->
        @if ($adaKanan)
            <div class="w-full">
                @include('theme::partials.sidebar')
            </div>
        @endif
    </div>
@endsection
