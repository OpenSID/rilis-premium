@php
    $pengembang = '-';
    $composerPath = $full_path ? FCPATH . $full_path . '/composer.json' : null;

    if ($composerPath && file_exists($composerPath)) {
        $composerTema = json_decode(file_get_contents($composerPath), true);
        $pengembang = $composerTema['authors'][0]['name'] ?? '-';
    }

    $detailModalId = 'detail-tema-' . ($id ?? $slug);
@endphp
<div class="box box-{{ $status == 1 ? 'success' : ($sistem == 1 ? 'info' : 'danger') }}">
    <div class="box-header with-border text-center">
        <strong>{{ $nama }}</strong>
        <div class="ribbon-wrapper">
            @php
                // Label kategori pakai `kategori` (distribusi), BUKAN `sistem`
                // (lokasi folder -- tema bundel sistem yg berbayar/Tema Pro
                // bagi Umum, mis. Lestari, tak boleh ikut label "Umum" hanya
                // krn dibundel). Tema hasil merge bursa (belum terpasang,
                // dari BursaTema::daftar(), lihat donjo-app/controllers/
                // Theme.php) tak punya kolom `kategori` sama sekali --
                // default ke Tema Pro, sama seperti perilaku lama ($sistem
                // selalu 0 utknya).
                //
                // KATEGORI_PREMIUM_EKSKLUSIF (mis. Wira) SAMA tab filter
                // "Tema Pro" dgn KATEGORI_PREMIUM (mis. Lestari, lihat
                // donjo-app/controllers/Theme.php) -- beda HANYA teks ribbon:
                // "Premium" (bonus eksklusif langganan, tak bisa dibeli
                // satuan) vs "Tema Pro" (bisa dibeli satuan).
                //
                // KATEGORI_MITRA (mis. Tema Tabanan): tema kerja sama
                // kabupaten/kota -- gratis untuk semua (ribbon biru seperti
                // "Umum"), teks ribbon "Tema Mitra" supaya asal kemitraan
                // terlihat. Lihat App\Models\Theme::KATEGORI_MITRA.
                $kategoriTema = $kategori ?? \App\Models\Theme::KATEGORI_PREMIUM;
                $isKategoriUmum = $kategoriTema === \App\Models\Theme::KATEGORI_UMUM;
                $isKategoriMitra = $kategoriTema === \App\Models\Theme::KATEGORI_MITRA;
                $isKategoriEksklusif = $kategoriTema === \App\Models\Theme::KATEGORI_PREMIUM_EKSKLUSIF;
                $isKategoriGratis = $isKategoriUmum || $isKategoriMitra;
                $ribbonClass = $status == 1 ? 'btn-success' : ($isKategoriGratis ? 'btn-info' : 'btn-danger');
                $ribbonText = $status == 1 ? 'Aktif' : ($isKategoriMitra ? 'Tema Mitra' : ($isKategoriUmum ? 'Umum' : ($isKategoriEksklusif ? 'Premium' : 'Tema Pro')));
            @endphp
            <div class="{{ $ribbonClass }} ribbon">
                {{ $ribbonText }}
            </div>
        </div>
    </div>

    <div class="box-body">
        <div class="theme-thumbnail-wrapper" style="width: 100%; height: 180px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5; border-radius: 4px; margin-bottom: 15px;">
            @php $file = $asset_path . '/thumbnail/preview-1.jpg' @endphp
            {{-- Thumbnail disajikan lewat route theme_asset, bukan URL berkas
                 statis. Folder storage/app/themes dan desa/themes berada di luar
                 document root sejak index.php pindah ke public/. --}}
            @if (file_exists(FCPATH . $file))
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ url('theme_asset/' . $slug . '?file=thumbnail/preview-1.jpg') }}"
                    alt="{{ $nama }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/404-image-not-found.jpg') }}';"
                >
            @elseif ($thumbnail)
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ $thumbnail }}"
                    alt="{{ $nama }}"
                    onerror="this.onerror=null; this.src='{{ asset('images/404-image-not-found.jpg') }}';"
                >
            @else
                <img
                    style="width: 100%; height: 100%; object-fit: cover;"
                    src="{{ asset('images/404-image-not-found.jpg') }}"
                    alt="{{ $nama }}"
                >
            @endif
        </div>
        <br>
        @if (! $marketplace && $sistem != 1 && ! empty($versi_terbaru))
            {{-- Tema desa sudah terpasang, tetapi bursa punya versi lebih baru (issue #7088).
                 Tema bundel sistem diperbarui bersama rilis aplikasi. --}}
            <p class="text-center text-muted">
                <i class="fa fa-arrow-circle-up text-yellow"></i>
                Versi baru <strong>v{{ ltrim($versi_terbaru, 'vV') }}</strong> tersedia
            </p>
        @endif
        <div class="text-center">
            @if ($status == 1)
                <a href="#" class="btn btn-social btn-success btn-sm" readonly><i class="fa fa-toggle-on"></i>Aktif</a>
            @elseif ($marketplace)
                @if ($providers)
                    <a href="{{ $providers }}" class="btn btn-social btn-info btn-sm" target="_blank"><i class="fa fa-eye"></i>Preview</a>
                @endif
                @if ($themeOrder?->firstWhere('nama', $nama))
                    <form action="{{ site_url('theme/unduh') }}" method="POST" style="display:inline;">
                        <input type="hidden" name="nama" value="{{ $nama }}">
                        <input type="hidden" name="url" value="{{ $url }}">
                        <button type="submit" class="btn btn-social bg-navy btn-sm" title="Unduh Tema">
                            <i class="fa fa-download"></i> Unduh
                        </button>
                    </form>
                @elseif (ENVIRONMENT === 'development')
                    <form action="{{ site_url('dev-modul/beli-tema') }}" method="POST" style="display:inline;">
                        <input type="hidden" name="nama" value="{{ $nama }}">
                        <button type="submit" class="btn btn-social btn-warning btn-sm" title="Simulasi pembelian tema (dev mode)">
                            <i class="fa fa-shopping-cart"></i> Beli
                        </button>
                    </form>
                @else
                    <a href="{{ config_item('website') . '/tema-pro-opensid' }}" class="btn btn-social btn-warning btn-sm" target="_blank"><i class="fa fa-info"></i>Hubungi</a>
                @endif
            @else
                @php
                    // rencana-refaktor-tema-siappakai.md §1.4/Fase 2: khusus
                    // tenant SiapPakai, Fase 1 menaruh SELURUH katalog (gratis
                    // maupun Tema Pro) ke storage/app/themes/ yang sama untuk
                    // semua tenant -- tema `premium` yang belum dipesan desa
                    // ini kini bisa tampil "terpasang lokal" (!$marketplace)
                    // padahal belum berhak. Tanpa cek ini tombol "Aktifkan"
                    // akan tampil untuk SEMUA tema premium tanpa jalan
                    // pemesanan, beda dari instalasi mandiri yang tak pernah
                    // sampai di sini kecuali sudah lolos unduhan berbayar.
                    $berhakAktivasi = \App\Actions\Theme\ActivateTheme::berhakAktivasi($kategoriTema, $slug, $nama);
                @endphp
                @if ($berhakAktivasi && can('u'))
                    <a href="{{ site_url('theme/aktifkan/' . $id) }}" class="btn btn-info btn-sm" title="Aktifkan Tema"><i class="fa fa-toggle-off"></i></a>
                @elseif (! $berhakAktivasi)
                    {{-- Belum berhak (khusus SiapPakai, lihat berhakAktivasi()) --
                         tautan pemesanan, bukan penolakan tanpa jalan keluar,
                         meniru pola "Hubungi" yang sudah ada utk tema belum
                         terpasang. TODO (Layanan_OpenDESA#1371): endpoint
                         pemesanan mandiri genuinely self-service ADA
                         (POST /api/v1/pemesanan, jwt.auth -- beda dari
                         routes/web.php yang staf-only) tapi baru mendukung
                         modul, belum tema; juga belum ada client produksi
                         (non-dev) yang memanggilnya sama sekali. Sampai
                         #1371 + client-nya selesai, tautan ini memakai
                         jalur kontak generik yang sama seperti instalasi
                         mandiri. --}}
                    <a href="{{ config_item('website') . '/tema-pro-opensid' }}" class="btn btn-social btn-warning btn-sm" target="_blank" title="Pesan Tema Ini"><i class="fa fa-info"></i>Hubungi</a>
                @endif
                @if ($sistem != 1 && ! empty($versi_terbaru) && ! empty($url) && can('u') && $themeOrder?->firstWhere('nama', $nama_bursa ?? $nama))
                    <form action="{{ site_url('theme/unduh') }}" method="POST" style="display:inline;">
                        <input type="hidden" name="nama" value="{{ $nama_bursa ?? $nama }}">
                        <input type="hidden" name="url" value="{{ $url }}">
                        <button type="submit" class="btn bg-navy btn-sm" title="Perbarui Tema ke v{{ ltrim($versi_terbaru, 'vV') }}">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>
                @endif
                @if (!cache('siappakai') && !setting('multi_desa') && can('h') && $sistem !== 1)
                    <a href="#" data-href="{{ site_url('theme/delete/' . $id) }}" class="btn btn-danger btn-sm" title="Hapus Tema" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a>
                @endif
            @endif
            @if (!$marketplace && can('u'))
                <a href="{{ site_url('theme/pengaturan/' . $id) }}" class="btn bg-navy btn-sm" title="Pengaturan Tema"><i class="fa fa-cog"></i></a>
                <a href="#" class="btn btn-primary btn-sm" title="Detail Tema" data-toggle="modal" data-target="#{{ $detailModalId }}"><i class="fa fa-info-circle"></i></a>
            @endif
        </div>
    </div>

</div>

<div class="modal fade modal-detail-tema" id="{{ $detailModalId }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Detail Tema</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 150px;"><i class="fa fa-bookmark-o"></i> Nama</th>
                        <td>{{ $nama }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-user-o"></i> Pengembang</th>
                        <td>{{ $pengembang }}</td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-code-fork"></i> Versi</th>
                        <td><span class="label label-info">{{ $versi }}</span></td>
                    </tr>
                    <tr>
                        <th><i class="fa fa-align-left"></i> Deskripsi</th>
                        <td>{{ $keterangan }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
