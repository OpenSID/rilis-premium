{{-- Area widget per posisi (Admin Web > Widget). Atas tampil di bawah menu dan bawah di baris baru setelah konten, sebagai kartu 4 per baris selebar container tema. Kiri dirender sebagai kolom di baris konten oleh layout yang mendukungnya (right-sidebar); di halaman lain kiri tampil di atas konten. --}}
@if (! function_exists('widget_aktif'))
    @yield('layout')
@else
    <div class="w-full">
        @include('theme::partials.widget.posisi', ['posisi' => 'atas'])
        @sectionMissing('widget_kiri_di_baris')
            @include('theme::partials.widget.posisi', ['posisi' => 'kiri'])
        @endif
    </div>
    @yield('layout')
    <div class="w-full">
        @include('theme::partials.widget.posisi', ['posisi' => 'bawah'])
    </div>
    <style>
        .opensid-widget-area { display:grid; gap:1.5rem; margin-top:1.5rem; }
        .opensid-widget-item { min-width:0; background:#fff; border-radius:.75rem; box-shadow:0 1px 2px rgba(0,0,0,.05); padding:1rem 1.5rem; overflow:hidden; }
        /* Atas & bawah: kartu 3 dari 12 kolom, 4 widget per baris di desktop. */
        .opensid-widget-atas, .opensid-widget-bawah { grid-template-columns:minmax(0,1fr); }
        @media (min-width:768px) { .opensid-widget-atas, .opensid-widget-bawah { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (min-width:1024px) { .opensid-widget-atas, .opensid-widget-bawah { grid-template-columns:repeat(4,minmax(0,1fr)); } }
        .opensid-widget-baris { display:grid !important; grid-template-columns:minmax(0,1fr) !important; }
        .opensid-widget-baris > * { width:auto !important; max-width:none !important; min-width:0; flex:none !important; grid-column:auto !important; }
        .opensid-widget-baris .opensid-widget-area { margin-top:0; }
        @media (min-width:1024px) {
            .opensid-widget-baris { grid-template-columns:var(--opensid-kolom) !important; }
        }
    </style>
@endif
