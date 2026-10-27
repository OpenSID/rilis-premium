{{-- Bagian widget selebar konten. $kelas 'atas' (di bawah menu), 'beranda' (setelah hero), atau 'bawah' (sebelum footer). Kiri ikut di atas bila halaman tidak memiliki baris konten. --}}
@if (function_exists('widget_aktif'))
    @php
        $daftarPosisi = $kelas === 'bawah' ? ['bawah'] : ($__env->hasSection('widget_kiri_di_baris') ? ['atas'] : ['atas', 'kiri']);
        // Dirender lebih dulu agar jarak bagian tidak muncul bila semua widgetnya kosong.
        $htmlWidget = collect($daftarPosisi)->contains(static fn ($posisi) => widget_aktif($posisi)->isNotEmpty())
            ? trim($__env->make('theme::partials.widget.posisi', array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), ['posisi' => $daftarPosisi]))->render())
            : '';
    @endphp
    @if ($htmlWidget !== '')
        <div class="opensid-widget-lestari opensid-widget-lestari-{{ $kelas }}">
            <div class="margin-page">
                {!! $htmlWidget !!}
            </div>
        </div>
    @endif
@endif
