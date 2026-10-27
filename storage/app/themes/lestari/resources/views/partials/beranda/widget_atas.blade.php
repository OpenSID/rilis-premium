@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{-- Widget posisi atas (dan kiri) di bawah header atau hero; lihat widget_posisi --}}
@include('theme::partials.beranda.widget_posisi', ['lvPosisiWidget' => ['atas', 'kiri']])
