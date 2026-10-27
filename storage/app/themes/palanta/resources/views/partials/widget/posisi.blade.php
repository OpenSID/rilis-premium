@php($widgetsPosisi = widget_aktif($posisi))
@if ($widgetsPosisi->isNotEmpty())
    <section class="opensid-widget-area opensid-widget-{{ $posisi }}" aria-label="Widget {{ $posisi }}">
        @foreach ($widgetsPosisi as $widget)
            @php($judul_widget = strip_tags($widget['judul']))
            <div class="opensid-widget-item">
                @if ($widget['jenis_widget'] == 3)
                    <div class="box-def">
                        <div class="head-widget l-flex">
                            <div class="head-widget-title l-flex">
                                <i class="fa fa-folder"></i><h1>{{ $judul_widget }}</h1>
                            </div>
                        </div>
                        <div class="widgetbox">
                            {!! html_entity_decode($widget['isi']) !!}
                        </div>
                    </div>
                @else
                    @includeIf("theme::widgets.{$widget['isi']}", ['judul_widget' => $judul_widget])
                @endif
            </div>
        @endforeach
    </section>
@endif
