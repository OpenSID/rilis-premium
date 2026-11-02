@php
    $lvMenu = menu_tema() ?? [];
    // Menu aktif: tautan yang sama dengan halaman saat ini (menu induk ikut aktif bila salah satu submenunya cocok)
    $lvNorm = static fn ($u) => rtrim(preg_replace('~/index\.php~', '', (string) $u), '/');
    $lvSekarang = $lvNorm(url()->current());
    // Hasil pencarian (?cari=) memakai alamat beranda, tapi bukan beranda
    $lvDiBeranda = $lvSekarang === $lvNorm(site_url()) && blank($cari ?? null);
    $lvMenuAktif = static function ($menu) use (&$lvMenuAktif, $lvNorm, $lvSekarang) {
        $tautan = $lvNorm($menu['link_url'] ?? '');
        if ($tautan !== '' && ! str_ends_with($tautan, '#') && $tautan === $lvSekarang) {
            return true;
        }
        foreach ($menu['childrens'] ?? [] as $anak) {
            if ($lvMenuAktif($anak)) {
                return true;
            }
        }

        return false;
    };

    // Pencarian menyeluruh (panel cari header & kotak cari hero): halaman dari menu + data dari API publik.
    // Kelompok data hanya dipakai bila halamannya aktif di menu (tautan "Lihat semua" tidak berakhir di 404).
    $lvCariHalaman = [];
    $lvRataMenu = static function ($items, $induk = '') use (&$lvRataMenu, &$lvCariHalaman) {
        foreach ($items as $m) {
            $url = (string) ($m['link_url'] ?? '');
            if ($url !== '' && ! str_ends_with($url, '#')) {
                $lvCariHalaman[$url] = ['judul' => $m['nama'], 'induk' => $induk, 'url' => $url];
            }
            $lvRataMenu($m['childrens'] ?? [], $m['nama']);
        }
    };
    $lvRataMenu($lvMenu);
    $lvCariAktif = $lvLinkMenuAktif();
    $lvCariSumber = array_values(array_filter([
        ['kunci' => 'artikel', 'judul' => 'Artikel & Berita', 'api' => site_url('internal_api/arsip'), 'semua' => site_url() . '?cari='],
        isset($lvCariAktif['lapak']) ? ['kunci' => 'lapak', 'judul' => 'Produk Lapak', 'api' => route('api.lapak.produk'), 'semua' => site_url('lapak') . '?cari='] : null,
        isset($lvCariAktif['pembangunan']) ? ['kunci' => 'pembangunan', 'judul' => 'Pembangunan', 'api' => route('api.pembangunan'), 'semua' => site_url('pembangunan') . '?cari='] : null,
        isset($lvCariAktif['peraturan-desa']) ? ['kunci' => 'produk_hukum', 'judul' => 'Produk Hukum', 'api' => route('api.produk-hukum'), 'semua' => site_url('peraturan-desa') . '?cari='] : null,
        isset($lvCariAktif['informasi_publik']) ? ['kunci' => 'informasi_publik', 'judul' => 'Informasi Publik', 'api' => route('api.informasi-publik'), 'semua' => site_url('informasi-publik') . '?cari='] : null,
    ]));
@endphp

<header class="lv-header">
    <div class="lv-wrap">
        <a class="lv-brand" href="{{ site_url() }}">
            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo {{ $namaDesa }}">
            <span>
                <span class="lv-brand__name">{{ $namaDesa }}</span>
                <span class="lv-brand__sub">{{ ucwords(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }}</span>
            </span>
        </a>

        <nav class="lv-nav" aria-label="Menu utama">
            <ul>
                <li @class(['is-active' => $lvDiBeranda])><a href="{{ site_url() }}" @if ($lvDiBeranda) aria-current="page" @endif>Beranda</a></li>
                @include('theme::partials.beranda.menu_item', ['items' => $lvMenu])
            </ul>
        </nav>

        <div class="lv-header__actions">
            <button type="button" class="lv-icon-btn" data-lv-toggle="lv-search" aria-expanded="false" aria-controls="lv-search" aria-label="Cari">
                {!! $svg('search') !!}
            </button>
            @if (setting('layanan_mandiri'))
                <a class="lv-btn lv-header__cta" href="{{ site_url('layanan-mandiri/masuk') }}">Layanan Mandiri</a>
            @endif
            <button type="button" class="lv-icon-btn lv-menu-btn" data-lv-drawer="open" aria-controls="lv-drawer" aria-label="Buka menu">
                {!! $svg('menu') !!}
            </button>
        </div>
    </div>

    {{-- Panel cari melayang di bawah header (tidak mendorong isi halaman); gaya kotak sama dengan pencarian di hero --}}
    <div class="lv-search" id="lv-search" role="dialog" aria-label="Pencarian" hidden>
        <div class="lv-wrap">
            <form method="get" action="{{ site_url() }}" role="search" data-lv-cari>
                <label class="lv-sr" for="lv-cari">Cari halaman, berita, produk, atau dokumen</label>
                <div class="lv-search__kotak">
                    {!! $svg('search') !!}
                    <input id="lv-cari" type="search" name="cari" maxlength="50" value="{{ $cari ?? '' }}" placeholder="Cari layanan, berita, produk, atau dokumen {{ strtolower($sebutanDesa) }}…" autocomplete="off">
                    <button class="lv-btn" type="submit">Cari</button>
                </div>
                <button type="button" class="lv-icon-btn lv-search__tutup" data-lv-search-tutup aria-label="Tutup pencarian">{!! $svg('close') !!}</button>
            </form>
            <div class="lv-cari-hasil" data-lv-cari-hasil hidden></div>
            <p class="lv-search__hint">Gunakan <kbd>↑</kbd> <kbd>↓</kbd> untuk memilih, <kbd>Enter</kbd> untuk membuka, <kbd>Esc</kbd> untuk menutup.</p>
        </div>
    </div>
    @php
        $lvCariData = [
            'beranda' => site_url(),
            'halaman' => array_values($lvCariHalaman),
            'sumber' => $lvCariSumber,
            'ikon' => [
                'halaman' => $svg('layers'), 'artikel' => $svg('file'), 'lapak' => $svg('store'), 'pembangunan' => $svg('build'),
                'produk_hukum' => $svg('book'), 'informasi_publik' => $svg('info'), 'cari' => $svg('search'), 'luar' => $svg('link'),
            ],
        ];
    @endphp
    <script type="application/json" data-lv-cari-sumber>@json($lvCariData)</script>
</header>

<div class="lv-drawer" id="lv-drawer" data-lv-drawer="close">
    <div class="lv-drawer__panel" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="lv-drawer__head">
            <span>Menu</span>
            <button type="button" class="lv-icon-btn" data-lv-drawer="close" aria-label="Tutup menu">{!! $svg('close') !!}</button>
        </div>
        <nav aria-label="Menu seluler">
            <ul>
                <li><a href="{{ site_url() }}">Beranda</a></li>
                @include('theme::partials.beranda.menu_item', ['items' => $lvMenu, 'drawer' => true])
                @if (setting('layanan_mandiri'))
                    <li><a href="{{ site_url('layanan-mandiri/masuk') }}">Layanan Mandiri</a></li>
                @endif
                @if ($on('pintasan_masuk'))
                    <li><a href="{{ site_url('siteman') }}">Masuk Admin</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
