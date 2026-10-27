{{-- Lestari memakai posisi absolute/fixed pada .mainarea, .homepage, dan header, jadi widget tidak dirender di sini, melainkan oleh partials/widget/bagian (di bawah menu, di beranda setelah hero, dan sebelum footer) serta kolom kiri di baris detail artikel. --}}
@yield('layout')
@if (function_exists('widget_aktif'))
    <style>
        .opensid-widget-lestari { width:100%; float:left; padding-top:50px; }
        .opensid-widget-lestari-atas { padding-top:30px; }
        .opensid-widget-lestari-atas + .contentpage { padding-top:20px !important; }
        .opensid-widget-area { display:grid; gap:20px; grid-template-columns:minmax(0,1fr); }
        .opensid-widget-area + .opensid-widget-area { margin-top:20px; }
        .opensid-widget-item { min-width:0; background:#fff; }
        .opensid-widget-item .head-module { margin:0 0 10px; }
        .opensid-widget-item .head-module h1 { font-size:130%; }
        /* Atas, bawah, dan kiri di luar baris konten: kartu 3 dari 12 kolom, 4 widget per baris di desktop. */
        @media (min-width:768px) { .opensid-widget-lestari .opensid-widget-area { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (min-width:993px) { .opensid-widget-lestari .opensid-widget-area { grid-template-columns:repeat(4,minmax(0,1fr)); } }
        .opensid-widget-baris { display:grid !important; grid-template-columns:minmax(0,1fr) !important; }
        .opensid-widget-baris > * { width:auto !important; max-width:none !important; min-width:0; flex:none !important; }
        .opensid-widget-kolom-kiri .opensid-widget-item { margin-bottom:20px; }
        @media (min-width:993px) {
            .opensid-widget-baris { grid-template-columns:var(--opensid-kolom) !important; }
            .opensid-widget-kolom-kiri { padding-right:30px; }
        }
        @media (max-width:992px) {
            .opensid-widget-lestari-atas { padding-top:0; margin-top:calc(9vh + 40px); }
            .opensid-widget-lestari-atas + .contentpage { margin-top:0 !important; }
        }
    </style>
@endif
