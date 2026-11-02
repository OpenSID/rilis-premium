@php
    // Widget yang sudah punya bagian sendiri di beranda Modern tidak diulang di area ini.
    $lvSudahAda = ['keuangan', 'galeri', 'statistik', 'aparatur_desa', 'peta_lokasi_kantor', 'peta_wilayah_desa', 'statistik_pengunjung', 'arsip_artikel', 'menu_kategori', 'sinergi_program', 'layanan_mandiri'];
    // Jadwal mingguan sudah tampil sebagai Jam Pelayanan di footer.
    if ($lvJamKerja->isNotEmpty()) {
        $lvSudahAda[] = 'jam_kerja';
    }
    // Widget yang ditempatkan admin di posisi atas/kiri/bawah selalu tampil di sana, jadi tidak diulang di area ini.
    if (function_exists('widget_aktif')) {
        $lvSudahAda = array_merge($lvSudahAda, collect(['atas', 'kiri', 'bawah'])
            ->flatMap(static fn ($posisi) => widget_aktif($posisi))
            ->reject(static fn ($w) => $w['jenis_widget'] == 3)
            ->pluck('isi')
            ->all());
    }

    $lvWidget = collect($widgetAktif ?? [])->filter(static function ($w) use ($lvSudahAda) {
        if ($w['jenis_widget'] == 3) {
            return filled($w['isi']);
        }

        return ! in_array($w['isi'], $lvSudahAda, true);
    });

    // Setiap widget memakai kartu bersama (widget_kartu); widget tanpa isi tidak menghasilkan kartu.
    $lvDataKartu = \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']);
    $lvKartuWidget = $lvWidget
        ->map(static fn ($widget) => trim($__env->make('theme::partials.beranda.widget_kartu', array_merge($lvDataKartu, ['widget' => $widget]))->render()))
        ->filter();
@endphp

@if ($lvKartuWidget->isNotEmpty())
    <section class="lv-bg-band" aria-labelledby="lv-widget-judul">
        <div class="lv-wrap lv-section">
            <div class="lv-head">
                <h2 class="lv-h2" id="lv-widget-judul">Informasi {{ $sebutanDesa }}</h2>
            </div>
            <div class="lv-widgets">
                @foreach ($lvKartuWidget as $lvKartu)
                    {!! $lvKartu !!}
                @endforeach
            </div>
        </div>
    </section>
@endif
