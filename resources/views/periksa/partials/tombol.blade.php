{{--
    Tombol perbaikan yang dijalankan lewat AJAX (lihat handler [data-perbaiki] di periksa/index).

    Variabel:
      $url     : endpoint perbaikan
      $label   : (opsional) teks tombol, default "Perbaiki Data"
      $ikon    : (opsional) kelas Font Awesome, default fa-wrench
      $warna   : (opsional) danger | warning | primary, default danger
      $pesan   : (opsional) teks konfirmasi
      $method  : (opsional) POST | GET, default POST
      $params  : (opsional) array data tambahan yang dikirim
      $sertakan: (opsional) selector elemen yang isian di dalamnya ikut dikirim
      $tetap   : (opsional) true bila halaman tidak perlu dimuat ulang setelah berhasil
      $kecil   : (opsional) true untuk tombol kecil
--}}
<button type="button"
    class="pk-btn pk-btn-{{ $warna ?? 'danger' }} {{ ! empty($kecil) ? 'pk-btn-sm' : '' }}"
    data-perbaiki="{{ $url }}"
    data-method="{{ $method ?? 'POST' }}"
    data-pesan="{{ $pesan ?? 'Pastikan sudah melakukan backup database/folder desa sebelum melanjutkan.' }}"
    @isset($params) data-params="{{ json_encode($params) }}" @endisset
    @isset($sertakan) data-sertakan="{{ $sertakan }}" @endisset
    @if (! empty($tetap)) data-tetap="1" @endif>
    <i class="fa {{ $ikon ?? 'fa-wrench' }}"></i> {{ $label ?? 'Perbaiki Data' }}
</button>
