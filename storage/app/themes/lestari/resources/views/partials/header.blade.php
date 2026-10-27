@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{-- Header semua halaman: tampilan Modern, atau tampilan lama bila "Tampilan Tema" diatur ke Klasik --}}
@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
    @include('theme::partials.header_klasik')
@else
    @include('theme::partials.beranda.kerangka', [
        'bagian' => ['topbar', 'header', 'widget_atas'],
        'kerangkaId' => 'lv-beranda',
        'kerangkaKelas' => 'lv-shell',
        'kerangkaSkrip' => true,
    ])
@endif
