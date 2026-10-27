{{-- Lestari memakai posisi absolute/fixed pada .mainarea, .homepage, dan header, jadi widget tidak dirender di sini, melainkan oleh partials/widget/bagian (di bawah menu, di beranda setelah hero, dan sebelum footer) serta kolom kiri di baris detail artikel. --}}
@yield('layout')
@if (function_exists('widget_aktif'))
    <style>
        .opensid-widget-lestari { width:100%; float:left; padding-top:50px; }
        .opensid-widget-lestari-atas { padding-top:30px; }
        .opensid-widget-lestari-atas + .contentpage { padding-top:20px !important; }
        .opensid-widget-area { display:grid; gap:20px; grid-template-columns:minmax(0,1fr); align-items:start; }
        .opensid-widget-area + .opensid-widget-area { margin-top:20px; }
        .opensid-widget-item { min-width:0; background:#fff; }
        .opensid-widget-item .head-module { margin:0 0 10px; }
        .opensid-widget-item .head-module h1 { font-size:130%; }
        /* Widget sidebar sudah berbingkai sendiri; di dalam kartu posisi bingkai dan jaraknya dilepas agar tidak bertumpuk. */
        .opensid-widget-item .widget-column { margin:0; }
        .opensid-widget-item .widget-column > .box-shadow { box-shadow:none; border-radius:0; }
        .opensid-widget-item .widget-column .widget-padding { padding:0; overflow:visible; }
        /* Widget bergaya lama (jam kerja, jadwal sholat): judul dan daftar disamakan dengan widget lain. */
        .opensid-widget-item .box-title { font-family:'Poppins-Medium', Arial; font-size:130%; color:var(--color1); margin:0 0 10px; line-height:1.3; }
        .opensid-widget-item .ants-right-headline { list-style:none; margin:0; padding:0; }
        /* Kode embed dari admin sering berukuran tetap: selalu selebar kartu, video YouTube dengan rasio 16:9. */
        .opensid-widget-item iframe { width:100%; max-width:100%; border:0; }
        .opensid-widget-item iframe[src*="youtube.com/embed"], .opensid-widget-item iframe[src*="youtube-nocookie.com/embed"], .opensid-widget-item iframe[src*="player.vimeo.com"] { height:auto; aspect-ratio:16 / 9; }
        /* Atas, bawah, dan kiri di luar baris konten: jumlah kolom mengikuti jumlah widget (maks. 4 per baris di desktop). */
        @media (min-width:768px) { .opensid-widget-lestari .opensid-widget-area { grid-template-columns:repeat(auto-fit,minmax(max(260px,calc((100% - 60px) / 4)),1fr)); } }
        /* Satu widget saja: tidak direntang selebar konten, cukup selebar kartu yang nyaman dibaca di tengah. */
        .opensid-widget-lestari .opensid-widget-area:has(> .opensid-widget-item:only-child) { grid-template-columns:minmax(0,760px); justify-content:center; }
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
