@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Halaman daftar artikel (tampilan Modern): hasil pencarian (?cari=) dan kategori (artikel/kategori/{slug}).
    Memakai bagian "Kabar desa" dalam mode daftar, dengan judul & pesan kosong sesuai halaman.
--}}
@php
    $lvKataKunci = trim((string) ($cari ?? ''));
    $lvNamaKategori = is_array($judul_kategori ?? null) ? trim((string) ($judul_kategori['kategori'] ?? '')) : '';

    if ($lvNamaKategori !== '') {
        $lvDaftar = [
            'judul' => $lvNamaKategori,
            'keterangan' => $lvKataKunci !== '' ? 'Hasil pencarian "' . $lvKataKunci . '" di kategori ini' : 'Kategori artikel',
            'kosong' => $lvKataKunci !== '' ? 'Tidak ada artikel yang cocok dengan "' . $lvKataKunci . '" di kategori ini.' : 'Belum ada artikel di kategori ini.',
        ];
    } else {
        $lvDaftar = [
            'judul' => 'Hasil Pencarian',
            'keterangan' => 'Kata kunci: "' . $lvKataKunci . '"',
            // Pencarian dari kotak cari: halaman juga menampilkan hasil dari sumber lain (lihat bagian kabar)
            'cari' => $lvKataKunci,
            'kosong' => 'Tidak ada artikel yang cocok dengan "' . $lvKataKunci . '". Coba kata kunci lain.',
        ];
    }
@endphp

@include('theme::partials.beranda.kerangka', [
    'kerangkaId' => 'lv-beranda',
    'kerangkaSkrip' => true,
    'lvDaftar' => $lvDaftar,
    'bagian' => ['topbar', 'header', 'widget_atas', 'kabar', 'widget_bawah', 'footer'],
])
