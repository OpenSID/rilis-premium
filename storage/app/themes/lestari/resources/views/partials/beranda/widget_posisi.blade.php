@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Widget posisi atas/kiri/bawah (Admin Web › Widget) pada tampilan Modern, selebar konten.
    Modern tidak punya kolom kiri, jadi widget kiri ikut tampil bersama widget atas.
    Parameter $lvPosisiWidget: daftar posisi yang dirender.
--}}
@php
    // Setiap widget memakai kartu bersama (widget_kartu), sama dengan bagian Informasi Desa; widget tanpa isi tidak menghasilkan kartu.
    $lvDataKartu = \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']);
    $lvKartuWidget = collect(function_exists('widget_aktif') ? $lvPosisiWidget : [])
        ->flatMap(static fn ($posisi) => widget_aktif($posisi))
        ->map(static fn ($widget) => trim($__env->make('theme::partials.beranda.widget_kartu', array_merge($lvDataKartu, ['widget' => $widget]))->render()))
        ->filter();
@endphp

@if ($lvKartuWidget->isNotEmpty())
    <section class="lv-bg-band lv-widget-posisi" aria-label="Widget {{ implode(' & ', $lvPosisiWidget) }}">
        <div class="lv-wrap lv-section">
            <div class="lv-widgets">
                @foreach ($lvKartuWidget as $lvKartu)
                    {!! $lvKartu !!}
                @endforeach
            </div>
        </div>
    </section>
@endif
