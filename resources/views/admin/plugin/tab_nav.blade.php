{{-- Bilah tab halaman Paket Tambahan. Tab ke-5 "Sumber (Dev)" hanya tampil di
     lingkungan development dan bila servis dev tersedia (di-export-ignore →
     absen di rilis), sehingga build rilis menampilkan 4 tab seperti biasa. --}}
<ul class="nav nav-tabs">
    <li {!! $act_tab == 1 ? 'class="active"' : '' !!}><a href="{{ route('plugin.index') }}">Paket Tersedia</a></li>
    @if (can('u'))
        <li {!! $act_tab == 2 ? 'class="active"' : '' !!}><a href="{{ route('plugin.installed') }}">Paket Terpasang</a></li>
        @if (! config('opensid.demo_mode'))
            <li {!! $act_tab == 3 ? 'class="active"' : '' !!}><a href="{{ route('plugin.pendaftaran') }}">Form Pendaftaran</a></li>
            <li {!! $act_tab == 4 ? 'class="active"' : '' !!}><a href="{{ route('plugin.pemesanan') }}">Riwayat Pemesanan</a></li>
        @endif
    @endif
    @if (app()->environment(['local', 'development']) && app()->bound('bursa.lokal'))
        <li {!! $act_tab == 5 ? 'class="active"' : '' !!}>
            <a href="{{ url('dev-modul') }}">
                <i class="fa fa-flask"></i> Sumber
                @if (app('bursa.lokal')::aktif())
                    <span class="label label-warning">Lokal</span>
                @else
                    <span class="label label-default">Layanan</span>
                @endif
            </a>
        </li>
    @endif
</ul>
