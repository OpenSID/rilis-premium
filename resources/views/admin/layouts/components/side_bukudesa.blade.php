<div class="box box-widget">
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li class="@active($selected_nav == 'peraturan')"><a href="{{ url('dokumen_sekretariat/peraturan') }}">{{ SebutanDesa('Buku Peraturan di [Desa]') }}</a>
            </li>
            <li class="@active($selected_nav == 'keputusan')"><a href="{{ url('dokumen_sekretariat/keputusan') }}">Buku Keputusan {{ ucwords(setting('sebutan_kepala_desa')) }}</a></li>
            <li class="@active($selected_nav == 'inventaris')"><a href="{{ url('bumindes_inventaris_kekayaan') }}">{{ SebutanDesa('Buku Inventaris dan Kekayaan [Desa]') }}</a>
            </li>
            <li class="@active($selected_nav == 'pengurus')"><a href="{{ url('pengurus') }}">{{ 'Buku ' . ucwords(setting('sebutan_pemerintah_desa')) }}</a></li>
            <li class="@active($selected_nav == 'tanah_kas')"><a href="{{ url('bumindes_tanah_kas_desa/clear') }}">{{ SebutanDesa('Buku Tanah Kas [Desa]') }}</a>
            </li>
            <li class="@active($selected_nav == 'tanah')"><a href="{{ url('bumindes_tanah_desa') }}">{{ SebutanDesa('Buku Tanah di [Desa]') }}</a>
            </li>
            <li class="@active($selected_nav == 'agenda_keluar')"><a href="{{ url('surat_keluar') }}">Buku Agenda - Surat Keluar</a>
            </li>
            <li class="@active($selected_nav == 'agenda_masuk')"><a href="{{ url('surat_masuk') }}">Buku Agenda - Surat Masuk</a></li>
            <li class="@active($selected_nav == 'ekspedisi')"><a href="{{ url('ekspedisi/clear') }}">Buku Ekspedisi</a></li>
            <li class="@active($selected_nav == 'lembaran')"><a href="{{ url('lembaran_desa') }}">{{ SebutanDesa('Buku Lembaran [Desa] dan Berita [Desa]') }}</a>
            </li>
        </ul>
    </div>
</div>
