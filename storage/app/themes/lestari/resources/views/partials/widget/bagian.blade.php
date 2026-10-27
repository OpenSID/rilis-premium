{{-- Bagian widget selebar konten. $kelas 'atas' (di bawah menu), 'beranda' (setelah hero), atau 'bawah' (sebelum footer). Kiri ikut di atas bila halaman tidak memiliki baris konten. --}}
@if (function_exists('widget_aktif'))
    @php $daftarPosisi = $kelas === 'bawah' ? ['bawah'] : ($__env->hasSection('widget_kiri_di_baris') ? ['atas'] : ['atas', 'kiri']); $adaWidget = collect($daftarPosisi)->contains(static fn ($posisi) => widget_aktif($posisi)->isNotEmpty()); @endphp
    @if ($adaWidget)
        <div class="opensid-widget-lestari opensid-widget-lestari-{{ $kelas }}">
            <div class="margin-page">
                @foreach ($daftarPosisi as $posisiWidget)
                    @include('theme::partials.widget.posisi', ['posisi' => $posisiWidget])
                @endforeach
            </div>
        </div>
    @endif
@endif
