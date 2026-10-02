<style>
    .navbar-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .navbar-menu li {
        position: relative;
    }

    /* Default sembunyi */
    .navbar-menu ul ul {
        display: none;
    }

    /* Hover tampilkan anak */
    .navbar-menu li:hover > ul {
        display: block;
    }

    /* Dropdown level 1 */
    .navbar-menu > ul > li > ul {
        top: 100%;
        left: 0;
    }

    /* Dropdown level 2 & seterusnya */
    .navbar-menu ul ul {
        top: 0;
        left: 100%;
        min-width: 180px;
    }

    /* Pastikan dropdown selalu di atas */
    .navbar-menu ul ul {
        z-index: 9999;
    }

    /*
     * Tampil/sembunyi + layout desktop ditulis manual (bukan utility Tailwind
     * spt "lg:flex"/"lg:items-center"/"lg:justify-between") krn CSS tema ini
     * di-build & di-purge sebelumnya (assets/css/style.min.css) -- kelas yang
     * tak dipakai di source blade SAAT build tidak ikut ter-compile, jadi
     * kelas baru yang ditambah belakangan (spt di sini) jadi no-op diam-diam.
     * "hidden"/"lg:block" sudah ada di bundle (dipakai tema sebelumnya) jadi
     * aman, tapi "lg:flex" dkk TIDAK -- makanya breakpoint di sini ditulis
     * sendiri, disamakan persis dgn breakpoint `lg` Tailwind (min-width:1024px).
     */
    .navbar-menu {
        display: none;
    }
    .navbar-menu-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
    }
    @media (min-width: 1024px) {
        .navbar-menu {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    }
</style>

<nav class="navbar-menu bg-primary-100 text-white" role="navigation">
    <ul>
        <!-- Home -->
        <li class="inline-block">
            <a href="{{ site_url('/') }}" class="inline-block py-3 px-4 hover:bg-primary-200">
                <i class="fa fa-home"></i>
            </a>
        </li>

        <!-- Menu Dinamis -->
        @if (menu_tema())
            @foreach (menu_tema() as $menu)
                @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                <li class="inline-block relative group">
                    <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}"
                       class="p-3 inline-block hover:bg-primary-200">
                        {!! $menu['nama'] !!}
                        @if ($has_dropdown)
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        @endif
                    </a>

                    @if ($has_dropdown)
                        <!-- LEVEL 1 dropdown -->
                        <ul class="absolute bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                            @foreach ($menu['childrens'] as $child)
                                @php $child_has_dropdown = count($child['childrens'] ?? []) > 0 @endphp
                                <li class="relative group">
                                    <a href="{{ $child_has_dropdown ? '#!' : $child['link_url'] }}"
                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                        <span class="flex-1">{!! $child['nama'] !!}</span>
                                        @if ($child_has_dropdown)
                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                        @endif
                                    </a>

                                    @if ($child_has_dropdown)
                                        <!-- LEVEL 2 dropdown -->
                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                            @foreach ($child['childrens'] as $grandchild)
                                                @php $grandchild_has_dropdown = count($grandchild['childrens'] ?? []) > 0 @endphp
                                                <li class="relative group">
                                                    <a href="{{ $grandchild_has_dropdown ? '#!' : $grandchild['link_url'] }}"
                                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                        <span class="flex-1">{!! $grandchild['nama'] !!}</span>
                                                        @if ($grandchild_has_dropdown)
                                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                                        @endif
                                                    </a>

                                                    @if ($grandchild_has_dropdown)
                                                        <!-- LEVEL 3 dropdown -->
                                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                                            @foreach ($grandchild['childrens'] as $greatgrandchild)
                                                                <li>
                                                                    <a href="{{ $greatgrandchild['link_url'] }}"
                                                                       class="block py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                                        {!! $greatgrandchild['nama'] !!}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>

    {{-- Login/Layanan Mandiri: hanya di sini untuk lebar desktop -- versi mobile
         ada di category_menu.blade.php (dalam <section class="lg:hidden">). --}}
    <div class="navbar-menu-actions">
        @if (setting('layanan_mandiri') == 1)
            <a href="{{ site_url('layanan-mandiri') }}" class="btn btn-primary text-sm text-center">Layanan
                Mandiri <i class="fas fa-external-link-alt ml-1"></i></a>
        @endif
        <a href="{{ site_url('siteman') }}" class="btn btn-accent text-sm text-center">Login Admin <i class="fas fa-external-link-alt ml-1"></i></a>
    </div>
</nav>
