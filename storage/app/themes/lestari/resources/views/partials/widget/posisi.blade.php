{{-- $posisi: satu posisi atau daftar posisi; semua widgetnya dirender dalam satu kisi agar tidak terpecah menjadi beberapa baris. --}}
@php
    $daftarPosisiWidget = (array) $posisi;
    // Isi widget dirender lebih dulu: widget yang tidak menghasilkan apa pun (mis. butuh pengaturan yang belum diisi) tidak ditampilkan sebagai kartu kosong.
    $kartuWidget = [];
    foreach (collect($daftarPosisiWidget)->flatMap(static fn ($p) => widget_aktif($p)) as $widget) {
        $judul_widget = strip_tags($widget['judul']);
        if ($widget['jenis_widget'] == 3) {
            $isiWidget = trim($widget['isi']) === '' ? '' : '<div class="head-module"><h1>' . e($judul_widget) . '</h1></div>' . html_entity_decode($widget['isi']);
        } else {
            $isiWidget = $__env->exists("theme::widgets.{$widget['isi']}")
                ? $__env->make("theme::widgets.{$widget['isi']}", array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), ['judul_widget' => $judul_widget]))->render()
                : '';
        }
        if (trim($isiWidget) !== '') {
            $kartuWidget[] = $isiWidget;
        }
    }
@endphp
@if ($kartuWidget)
    <section class="opensid-widget-area {{ collect($daftarPosisiWidget)->map(static fn ($p) => 'opensid-widget-' . $p)->implode(' ') }}" aria-label="Widget {{ implode(' & ', $daftarPosisiWidget) }}">
        @foreach ($kartuWidget as $isiWidget)
            <div class="opensid-widget-item box-shadow brd-10">
                <div class="widget-padding">
                    {!! $isiWidget !!}
                </div>
            </div>
        @endforeach
    </section>
@endif
