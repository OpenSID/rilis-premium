@php($widgetsPosisi = widget_aktif($posisi))
@if ($widgetsPosisi->isNotEmpty())
    <section class="opensid-widget-area opensid-widget-{{ $posisi }}" aria-label="Widget {{ $posisi }}">
        @foreach ($widgetsPosisi as $widget)
            @php($judul_widget = strip_tags($widget['judul']))
            <div class="opensid-widget-item">
                @if ($widget['jenis_widget'] == 3)
                    <h3>{{ $judul_widget }}</h3>
                    {!! html_entity_decode($widget['isi']) !!}
                @else
                    @includeIf("theme::widgets.{$widget['isi']}", ['judul_widget' => $judul_widget])
                @endif
            </div>
        @endforeach
    </section>
@endif
