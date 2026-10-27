@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Blok tambahan di bawah halaman dalam (peta, aparatur, statistik, galeri, APBDes, layanan mandiri, widget).
    Di tampilan Modern isinya sudah ada di beranda, jadi halaman dalam cukup menampilkan isinya sendiri.
    Tampilan lama tetap dipakai bila "Tampilan Tema" diatur ke Klasik.
--}}
@if (theme_config('tampilan_beranda', 'modern') === 'klasik')
    @include('theme::partials.modulepage_klasik')
@endif
