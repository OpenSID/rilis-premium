@php
    $videoSetting = json_decode($widget['setting'] ?? '{}', true) ?? [];
    $videoId = function_exists('widget_video_id') ? widget_video_id((string) ($videoSetting['video_id'] ?? '')) : null;
    $videoAutoplay = ! empty($videoSetting['autoplay']);
    $videoTitle = is_string($judul_widget ?? null) ? $judul_widget : $widget['judul'];
@endphp
@if ($videoId)
    <div class="box-def widget-video">
        <div class="head-widget l-flex">
            <div class="head-widget-title l-flex">
                <i class="fa fa-youtube-play"></i><h1>{{ $videoTitle }}</h1>
            </div>
        </div>
        <div class="widgetbox">
            <iframe
                src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?autoplay={{ $videoAutoplay ? '1' : '0' }}&amp;mute={{ $videoAutoplay ? '1' : '0' }}&amp;playsinline=1"
                title="{{ $videoTitle }}"
                style="width:100%;min-height:200px;aspect-ratio:16/9;border:0"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>
    </div>
@endif
