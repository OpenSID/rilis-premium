@foreach ($items as $menu)
    @php
        $anak = $menu['childrens'] ?? [];
        $aktif = isset($lvMenuAktif) && $lvMenuAktif($menu);
    @endphp
    @if ($drawer ?? false)
        {{-- Menu HP (akordeon): submenu tertutup, kecuali yang berisi halaman yang sedang dibuka --}}
        @php
            $tanpaHalaman = blank($menu['link_url'] ?? null) || str_ends_with(trim((string) $menu['link_url']), '#');
            $idSub = 'lv-sub-' . substr(md5(($induk ?? '') . '/' . $loop->index . '/' . $menu['nama']), 0, 10);
        @endphp
        <li @class(['is-active' => $aktif, 'has-sub' => !empty($anak)])>
            @if (!empty($anak))
                <div class="lv-drawer__row">
                    @if ($tanpaHalaman)
                        <button type="button" class="lv-drawer__toggle lv-drawer__toggle--teks" aria-expanded="{{ $aktif ? 'true' : 'false' }}" aria-controls="{{ $idSub }}">
                            <span>{{ $menu['nama'] }}</span>{!! $svg('down', 'lv-caret') !!}
                        </button>
                    @else
                        <a href="{{ $menu['link_url'] }}">{{ $menu['nama'] }}</a>
                        <button type="button" class="lv-drawer__toggle" aria-expanded="{{ $aktif ? 'true' : 'false' }}" aria-controls="{{ $idSub }}" aria-label="Submenu {{ $menu['nama'] }}">{!! $svg('down', 'lv-caret') !!}</button>
                    @endif
                </div>
                <ul id="{{ $idSub }}" @unless ($aktif) hidden @endunless>
                    @include('theme::partials.beranda.menu_item', ['items' => $anak, 'drawer' => true, 'induk' => $idSub])
                </ul>
            @else
                <a href="{{ $menu['link_url'] }}" @if ($aktif) aria-current="page" @endif>{{ $menu['nama'] }}</a>
            @endif
        </li>
    @else
        <li @class(['is-active' => $aktif])>
            <a href="{{ $menu['link_url'] }}" @if ($aktif && empty($anak)) aria-current="page" @endif>
                {{ $menu['nama'] }}
                @if (!empty($anak) && ($caret ?? true))
                    {!! $svg('down', 'lv-caret') !!}
                @endif
            </a>
            @if (!empty($anak))
                <ul>
                    @include('theme::partials.beranda.menu_item', ['items' => $anak])
                </ul>
            @endif
        </li>
    @endif
@endforeach
