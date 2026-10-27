@php($widgetsPosisi = widget_aktif($posisi))
@if ($widgetsPosisi->isNotEmpty())
    <section class="sidebar opensid-widget-area opensid-widget-{{ $posisi }}" aria-label="Widget {{ $posisi }}">
        @foreach ($widgetsPosisi as $widget)
            @php($judul_widget = strip_tags($widget['judul']))
            <div class="opensid-widget-item shadow rounded-lg bg-white overflow-hidden">
                @if ($widget['jenis_widget'] == 3)
                    <div class="box box-primary box-solid">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fas fa-folder mr-1"></i>{{ $judul_widget }}</h3>
                        </div>
                        <div class="box-body">
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
