@php
    $lvSejarah = $cfg('sejarah_singkat');
    $lvVisi = $cfg('visi');
    $lvMisi = collect(preg_split('/\r\n|\r|\n/', (string) $cfg('misi', '')))
        ->map(static fn ($baris) => trim(preg_replace('/^\s*(\d+[\.\)]|[-*•])\s*/u', '', $baris)))
        ->filter();
@endphp

@if ($lvSejarah || $lvVisi || $lvMisi->isNotEmpty())
    <section class="lv-bg-white">
        <div class="lv-wrap lv-section lv-story">
            @if ($lvSejarah)
                <div>
                    <h2 class="lv-h2">Sejarah Singkat</h2>
                    <p class="lv-story__text">{{ $lvSejarah }}</p>
                </div>
            @endif
            @if ($lvVisi || $lvMisi->isNotEmpty())
                <div>
                    <div class="lv-vision">
                        @if ($lvVisi)
                            <span class="lv-vision__label">Visi</span>
                            <p class="lv-vision__text">“{{ $lvVisi }}”</p>
                        @endif
                        @if ($lvMisi->isNotEmpty())
                            <span class="lv-vision__label">Misi</span>
                            <ol>
                                @foreach ($lvMisi as $misi)
                                    <li>{{ $misi }}</li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
