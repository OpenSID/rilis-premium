@php
    $aktif = static fn (int $menu): string => ($tip ?? null) == $menu ? "class='active'" : '';
@endphp
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Pengaturan Peta</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li {!! $aktif(3) !!}><a href="{{ route('plan.index-default') }}">Lokasi</a></li>
            <li {!! $aktif(0) !!}><a href="{{ route('point.index') }}">Tipe Lokasi</a></li>
            <li {!! $aktif(6) !!}><a href="{{ route('simbol.index') }}">Simbol Lokasi</a></li>
            <li {!! $aktif(1) !!}><a href="{{ route('garis.index-default') }}">Garis</a></li>
            <li {!! $aktif(2) !!}><a href="{{ route('line.index-default') }}">Tipe Garis</a></li>
            <li {!! $aktif(4) !!}><a href="{{ route('area.index-default') }}">Area</a></li>
            <li {!! $aktif(5) !!}><a href="{{ route('polygon.index-default') }}">Tipe Area</a></li>
        </ul>
    </div>
</div>
