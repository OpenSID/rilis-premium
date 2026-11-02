@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{-- Footer semua halaman: tampilan Modern, atau tampilan lama bila "Tampilan Tema" diatur ke Klasik --}}
@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
    @include('theme::partials.footer_klasik')
@else
    @include('theme::partials.beranda.kerangka', [
        'bagian' => ['widget_bawah', 'footer'],
        'kerangkaKelas' => 'lv-shell lv-shell--footer',
    ])
@endif
