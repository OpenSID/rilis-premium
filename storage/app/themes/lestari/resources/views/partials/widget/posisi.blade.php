@php($widgetsPosisi = widget_aktif($posisi))
@if ($widgetsPosisi->isNotEmpty())
    <section class="opensid-widget-area opensid-widget-{{ $posisi }}" aria-label="Widget {{ $posisi }}">
        @foreach ($widgetsPosisi as $widget)
            @php($judul_widget = strip_tags($widget['judul']))
            <div class="opensid-widget-item box-shadow brd-10">
                <div class="widget-padding">
                    @if ($widget['jenis_widget'] == 3)
                        <div class="head-module">
                            <h1>{{ $judul_widget }}</h1>
                        </div>
                        {!! html_entity_decode($widget['isi']) !!}
                    @else
                        @includeIf("theme::widgets.{$widget['isi']}", ['judul_widget' => $judul_widget])
                    @endif
                </div>
            </div>
        @endforeach
    </section>
@endif
