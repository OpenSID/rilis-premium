@php
    $lvTeks = collect($teks_berjalan ?? [])->filter(static fn ($t) => filled($t['teks'] ?? null));
@endphp

<div class="lv-topbar">
    <div class="lv-wrap">
        <span class="lv-topbar__date">
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
            · <span data-lv-clock>{{ date('H.i') }}</span> WIB
        </span>

        @if ($on('tampil_teks_berjalan'))
            <div class="lv-ticker">
                <span class="lv-ticker__label">Sekilas Info</span>
                <div class="lv-ticker__track">
                    <div class="lv-ticker__move">
                        @foreach ([false, true] as $salinan)
                        <span class="lv-ticker__set" @if ($salinan) aria-hidden="true" @endif>
                        @forelse ($lvTeks as $teks)
                            <span>
                                {{ $teks['teks'] }}
                                @if (!empty($teks['tautan']))
                                    <a href="{{ $teks['tautan'] }}" rel="noopener noreferrer">{{ $teks['judul_tautan'] ?: 'Selengkapnya' }}</a>
                                @endif
                            </span>
                        @empty
                            <span>Selamat datang di website resmi {{ $namaDesa }}, {{ $wilayahDesa }}.</span>
                        @endforelse
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="lv-ticker"></div>
        @endif

        @if ($on('pintasan_masuk'))
            <a class="lv-topbar__link" href="{{ site_url('siteman') }}">Masuk Admin</a>
        @endif
    </div>
</div>
