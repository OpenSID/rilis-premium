{{--
    Kartu satu masalah pada halaman Periksa.

    Variabel:
      $kode    : kode masalah, dipakai untuk anchor #masalah-{kode}
      $judul   : judul masalah
      $jumlah  : (opsional) jumlah data bermasalah
      $ikon    : (opsional) kelas Font Awesome, default fa-exclamation-triangle
      $warna   : (opsional) danger | warning | info, default danger
      $jenis   : (opsional) otomatis | manual
      $slot    : isi kartu
      $aksi    : (opsional, @slot) tombol di kaki kartu
--}}
@php
    $warna = $warna ?? 'danger';
@endphp
<section id="masalah-{{ $kode }}" class="pk-card">
    <header class="pk-card-head">
        <span class="pk-icon pk-icon-{{ $warna }}"><i class="fa {{ $ikon ?? 'fa-exclamation-triangle' }}"></i></span>
        <div class="tw-min-w-0 tw-flex-1">
            <h3 class="pk-card-title">{{ $judul }}</h3>
            <div class="tw-mt-1.5 tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                @isset($jumlah)
                    <span class="pk-badge pk-badge-{{ $warna }}">{{ number_format($jumlah, 0, ',', '.') }} data</span>
                @endisset
                @if (($jenis ?? null) === 'otomatis')
                    <span class="pk-badge pk-badge-info"><i class="fa fa-magic"></i> Perbaikan otomatis</span>
                @elseif (($jenis ?? null) === 'manual')
                    <span class="pk-badge pk-badge-slate"><i class="fa fa-hand-pointer-o"></i> Perlu tindakan manual</span>
                @endif
            </div>
        </div>
    </header>

    <div class="pk-card-body">
        {{ $slot }}
    </div>

    @isset($aksi)
        <footer class="pk-card-foot">
            {{ $aksi }}
        </footer>
    @endisset
</section>
