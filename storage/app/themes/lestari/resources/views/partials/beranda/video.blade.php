@php
    // Terima ID video atau tautan YouTube penuh; satu video per baris, format opsional "ID | Judul".
    $lvIdYoutube = static function ($nilai) {
        $nilai = trim((string) $nilai);
        if (preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([\w-]{11})~', $nilai, $m)) {
            return $m[1];
        }

        return preg_match('~^[\w-]{11}$~', $nilai) ? $nilai : null;
    };
    $lvVideo = collect(preg_split('/\r\n|\r|\n|,/', (string) $cfg('video_galeri', '')))
        ->map(static function ($baris) use ($lvIdYoutube) {
            [$id, $judul] = array_pad(array_map('trim', explode('|', $baris, 2)), 2, null);

            return ($id = $lvIdYoutube($id)) ? ['id' => $id, 'judul' => $judul] : null;
        })
        ->filter()
        ->values();
    $lvUtama = $lvIdYoutube($cfg('video_utama'));
    if ($lvUtama) {
        $lvVideo->prepend(['id' => $lvUtama, 'judul' => $cfg('video_utama_judul')]);
    }
    $lvVideo = $lvVideo->unique('id')->take(4)->values();
@endphp

@if ($lvVideo->isNotEmpty())
    @php $utama = $lvVideo->first(); @endphp
    <section class="lv-wrap lv-section" style="padding-top: 48px" aria-labelledby="lv-video-judul">
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-video-judul">Video {{ $sebutanDesa }}</h2>
        </div>
        <div class="lv-video">
            <div class="lv-video__main" data-lv-video="{{ $utama['id'] }}">
                <img src="https://i.ytimg.com/vi/{{ $utama['id'] }}/hqdefault.jpg" alt="" loading="lazy">
                <button type="button" class="lv-play" data-lv-play aria-label="Putar video {{ $utama['judul'] }}">{!! $svg('play') !!}</button>
            </div>
            @if ($lvVideo->count() > 1)
                <div class="lv-video__list">
                    @foreach ($lvVideo as $i => $video)
                        <button type="button" class="lv-video__item" data-lv-pick="{{ $video['id'] }}">
                            <img src="https://i.ytimg.com/vi/{{ $video['id'] }}/mqdefault.jpg" alt="" loading="lazy">
                            <span>{{ $video['judul'] ?: 'Video ' . ($i + 1) }}</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
