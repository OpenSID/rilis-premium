@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Kerangka bersama tampilan Modern Lestari: memuat aset, menyiapkan pengaturan & helper,
    lalu merender daftar bagian. Dipakai beranda (semua bagian) serta header/footer
    halaman lain (partials/header & partials/footer).

    Parameter:
    - $bagian         : daftar bagian; string nama view di partials/beranda, atau
                        [nama, kunci sakelar tema, syarat tambahan (bool, opsional)]
    - $kerangkaId     : id pembungkus (beranda & header memakai "lv-beranda" agar skrip aktif)
    - $kerangkaKelas  : kelas tambahan pembungkus
    - $kerangkaSkrip  : true untuk menyertakan skrip interaksi
--}}
@once
@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ theme_asset('css/lestari-modern.css', ['v' => @filemtime(theme_full_path() . '/assets/css/lestari-modern.css') ?: VERSION]) }}">
@endpush
@endonce

@php
    // Pengaturan tema dibaca sekali: nilai bawaan dari config.json, ditimpa pilihan admin.
    $lvBawaan = collect(json_decode(file_get_contents(theme_full_path() . '/config.json'), true))->pluck('value', 'key')->all();
    $lvOpsi = array_merge($lvBawaan, (array) (theme_config() ?? []));
    $cfg = static fn (string $key, $default = null) => filled($lvOpsi[$key] ?? null) ? $lvOpsi[$key] : $default;
    $on = static fn (string $key) => (string) ($lvOpsi[$key] ?? '1') === '1';

    $sebutanDesa = ucwords(setting('sebutan_desa'));
    $namaDesa = $sebutanDesa . ' ' . ucwords($desa['nama_desa']);
    $wilayahDesa = ucwords(setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan']) . ', ' . ucwords(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']);

    $lvIkon = [
        'search' => 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4',
        'menu' => 'M4 7h16M4 12h16M4 17h16',
        'close' => 'M6 6l12 12M18 6L6 18',
        'down' => 'M6 9l6 6 6-6',
        'left' => 'M15 18l-6-6 6-6',
        'right' => 'M9 18l6-6-6-6',
        'file' => 'M6 3h9l4 4v14H6zM14 3v5h5M9 13h7M9 17h5',
        'message' => 'M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z',
        'chart' => 'M4 20V10M10 20V4M16 20v-7M22 20H2',
        'map' => 'M9 4 3 6v14l6-2 6 2 6-2V4l-6 2-6-2zM9 4v14M15 6v14',
        'store' => 'M3 9l1.5-5h15L21 9M3 9h18v2a3 3 0 0 1-6 0 3 3 0 0 1-6 0 3 3 0 0 1-6 0V9zM5 13v7h14v-7',
        'wallet' => 'M3 7h18v13H3zM3 7l3-4h12l3 4M16 13.5h.01',
        'layers' => 'M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5',
        'target' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
        'globe' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18',
        'heart' => 'M12 20s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 9c0 6.5-8 11-8 11z',
        'book' => 'M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2V5zM4 19a2 2 0 0 1 2-2h13',
        'info' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 11v6M12 7.5h.01',
        'build' => 'M3 18h18M5 18v-3a7 7 0 0 1 14 0v3M10 8V5h4v3',
        'box' => 'M3 7l9-4 9 4v10l-9 4-9-4zM3 7l9 4 9-4M12 11v10',
        'users' => 'M17 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM21 20v-1a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8',
        'vote' => 'M9 4h6v3H9zM7 5H5v16h14V5h-2M9 13l2 2 4-4',
        'gift' => 'M4 11h16v10H4zM3 7h18v4H3zM12 7v14M12 7S10.5 3 8 3a2 2 0 0 0 0 4M12 7s1.5-4 4-4a2 2 0 0 1 0 4',
        'clock' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 7v5l3 2',
        'calendar' => 'M4 6h16v15H4zM4 10h16M8 3v4M16 3v4',
        'leaf' => 'M5 19c0-8 6-14 15-14 0 9-6 15-14 15M5 19l7-7',
        'bag' => 'M5 8h14l-1 13H6zM9 8V6a3 3 0 0 1 6 0v2',
        'wa' => 'M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12zM9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2z',
        'facebook' => 'M15 3h-2a4 4 0 0 0-4 4v3H7v4h2v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h2z',
        'instagram' => 'M4 4h16v16H4zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM17 7h.01',
        'youtube' => 'M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5zM10 9l5 3-5 3z',
        'twitter' => 'M4 4l16 16M20 4L4 20',
        'tiktok' => 'M14 3v11a4 4 0 1 1-4-4M14 3c0 3 2 5 5 5',
        'play' => 'M8 5v14l11-7z',
        'send' => 'M22 2 11 13M22 2l-7 20-4-9-9-4z',
        'mail' => 'M4 5h16v14H4zM4 7l8 6 8-6',
        'pin' => 'M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12zM12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
        'phone' => 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z',
        'link' => 'M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1',
        'print' => 'M7 9V3h10v6M7 17H4v-7h16v7h-3M7 14h10v7H7z',
    ];
    $svg = static fn (string $nama, string $kelas = '') => '<svg class="lv-ico ' . $kelas . '" viewBox="0 0 24 24" aria-hidden="true"><path d="' . ($lvIkon[$nama] ?? '') . '"/></svg>';

    // Gambar pengganti saat foto tidak ada: mengikuti pengaturan tema "Foto Tidak Tersedia" (sama dengan commons/meta)
    $lvFotoKosong = theme_config('foto_tidak_tersedia') ? base_url(theme_config('foto_tidak_tersedia')) : asset('images/404-image-not-found.jpg');

    // Tautan menu yang aktif: kolom "link" dari menu_tema() (helper inti, hanya menu enabled di semua tingkat).
    // Dipakai agar tautan ke halaman yang menunya belum aktif tidak ditampilkan (halaman itu berakhir di 404).
    $lvLinkMenuAktif = static function () {
        static $aktif = null;
        if ($aktif === null) {
            $aktif = [];
            $rata = static function ($items) use (&$rata, &$aktif) {
                foreach ($items as $m) {
                    if (filled($m['link'] ?? null)) {
                        $aktif[$m['link']] = true;
                    }
                    $rata($m['childrens'] ?? []);
                }
            };
            $rata(menu_tema() ?? []);
        }

        return $aktif;
    };

    // URL halaman statistik dari daftar core (daftar_statistik), hanya yang menunya aktif.
    // data-statistik tanpa slug tidak punya halaman, dan statistik bantuan hanya terbuka lewat first/statistik/{key},
    // jadi tautan statistik selalu memakai url dari core. Tanpa key: pengaturan tema "Statistik Default", lalu statistik aktif pertama.
    $lvUrlStatistik = static function ($key = null) use ($cfg) {
        static $aktif = null;
        if ($aktif === null) {
            $menu = function_exists('menu_statistik_aktif') ? menu_statistik_aktif() : collect();
            $aktif = collect(function_exists('daftar_statistik') ? daftar_statistik() : [])
                ->flatMap(static fn ($items, $grup) => collect($items)->filter(static fn ($item) => isset($menu[$grup === 'lainnya' ? $item['key'] : 'statistik/' . $item['key']])))
                ->keyBy(static fn ($item) => (string) $item['key']);
        }
        $item = $aktif->get((string) ($key ?? $cfg('statistik_default', '13'))) ?? ($key === null ? $aktif->first() : null);

        return $item ? site_url($item['url']) : null;
    };

    $lvPerangkat = collect($aparatur_desa['daftar_perangkat'] ?? []);
    // Sebagian data nama pamong sudah memuat gelar; gelar hanya ditambahkan bila belum ada di nama.
    $lvNamaGelar = static function ($p) {
        $nama = trim((string) ($p['nama'] ?? ''));
        $depan = trim((string) ($p['gelar_depan'] ?? ''));
        $belakang = trim((string) ($p['gelar_belakang'] ?? ''));
        if ($depan !== '' && stripos($nama, $depan) === false) {
            $nama = $depan . ' ' . $nama;
        }
        if ($belakang !== '' && stripos($nama, $belakang) === false) {
            $nama .= ', ' . $belakang;
        }

        return $nama;
    };

    // Status kantor hari ini, dipakai kartu di samping sambutan dan widget Jam Kerja.
    $lvNamaHari = strtolower(\Carbon\Carbon::now()->locale('id')->translatedFormat('l'));
    $lvJamKerja = collect($jam_kerja ?? []);
    $lvJamHariIni = $lvJamKerja->first(static fn ($j) => strtolower($j->nama_hari) === $lvNamaHari);
    $lvLiburNasional = ! ($tampilkan_status_kehadiran ?? true);
    $lvKantorBuka = ! $lvLiburNasional && $lvJamHariIni && $lvJamHariIni->status
        && date('H:i') >= substr($lvJamHariIni->jam_masuk, 0, 5)
        && date('H:i') <= substr($lvJamHariIni->jam_keluar, 0, 5);

    // Nomor WhatsApp: pengaturan tema, bila kosong telepon kantor; format internasional untuk wa.me.
    $lvWa = preg_replace('/\D/', '', (string) $cfg('nomor_whatsapp', $desa['telepon'] ?? ''));
    if (str_starts_with($lvWa, '0')) {
        $lvWa = '62' . substr($lvWa, 1);
    }
@endphp

<div class="lv2 {{ $kerangkaKelas ?? '' }}" @if (!empty($kerangkaId)) id="{{ $kerangkaId }}" @endif data-lv-foto-kosong="{{ $lvFotoKosong }}">
    @foreach ($bagian as $item)
        @php
            [$lvNamaBagian, $lvSakelar, $lvSyarat] = array_pad((array) $item, 3, true);
            $lvTampil = ($lvSakelar === true || $on($lvSakelar)) && $lvSyarat;
        @endphp
        @if ($lvTampil)
            @include('theme::partials.beranda.' . $lvNamaBagian)
        @endif
    @endforeach
</div>

@if (!empty($kerangkaSkrip))
    @include('theme::partials.beranda.script')
@endif
