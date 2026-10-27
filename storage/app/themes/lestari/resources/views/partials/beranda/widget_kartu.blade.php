@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp
{{--
    Satu kartu widget tampilan Modern (Admin Web › Widget), dipakai bagian Informasi Desa (widget.blade.php)
    dan area widget posisi atas/kiri/bawah (widget_posisi.blade.php), agar widget yang sama tampil identik di semua area.
    Parameter $widget: satu widget aktif. Tidak menghasilkan apa pun bila widget tidak punya isi untuk ditampilkan.
--}}
@php
    $lvJudulKartu = str_replace('Desa', $sebutanDesa, strip_tags($widget['judul']));
    $lvIdKartu = 'lv-w' . ($widget['id'] ?? substr(md5($widget['isi'] . $lvJudulKartu), 0, 8));
    $lvJenisKartu = $widget['jenis_widget'] == 3 ? 'statis' : $widget['isi'];
    $lvTglKartu = static fn ($tgl) => \Carbon\Carbon::parse($tgl)->locale('id');

    // Ikon kepala kartu sesuai jenis widget; widget statis menurut isinya (video, peta, formulir).
    $lvIkonStatis = static function ($isi) {
        return match (true) {
            (bool) preg_match('/youtube|youtu\.be|vimeo/i', $isi) => 'play',
            (bool) preg_match('/google\.[a-z.]+\/maps|maps\.google/i', $isi) => 'map',
            (bool) preg_match('/docs\.google\.com\/forms|<form/i', $isi) => 'file',
            default => 'info',
        };
    };
    $lvIkonKartu = match ($lvJenisKartu) {
        'statis' => $lvIkonStatis($widget['isi']),
        'agenda' => 'calendar',
        'jam_kerja', 'jadwal_sholat' => 'clock',
        'komentar' => 'message',
        'media_sosial' => 'globe',
        'arsip_artikel' => 'layers',
        'statistik_pengunjung' => 'users',
        'menu_kategori' => 'menu',
        'video' => 'play',
        'peta_lokasi_kantor', 'peta_wilayah_desa' => 'map',
        'keuangan' => 'wallet',
        'aparatur_desa' => 'users',
        'statistik' => 'chart',
        'sinergi_program' => 'link',
        default => 'info',
    };

    // Isi kartu dirender lebih dulu; kartu tidak dibuat bila isinya kosong.
    $lvAksiKartu = '';
    $lvIsiKartu = '';
    switch ($lvJenisKartu) {
        case 'statis':
            $lvIsiKartu = trim($widget['isi']) === '' ? '' : '<div class="lv-widget__body">' . html_entity_decode($widget['isi']) . '</div>';
            break;

        case 'jam_kerja':
            if ($lvJamKerja->isNotEmpty()) {
                $lvAksiKartu = '<span class="lv-status ' . ($lvKantorBuka ? 'lv-status--ok' : 'lv-status--off') . '">' . ($lvKantorBuka ? 'Buka sekarang' : 'Tutup') . '</span>';
            }
            break;

        case 'video':
            $lvVideoSetting = json_decode($widget['setting'] ?? '{}', true) ?? [];
            $lvVideoId = function_exists('widget_video_id') ? widget_video_id((string) ($lvVideoSetting['video_id'] ?? '')) : null;
            if ($lvVideoId) {
                $lvVideoOtomatis = ! empty($lvVideoSetting['autoplay']);
                $lvIsiKartu = '<div class="lv-widget__body"><iframe src="https://www.youtube-nocookie.com/embed/' . e($lvVideoId) . '?autoplay=' . ($lvVideoOtomatis ? 1 : 0) . '&amp;mute=' . ($lvVideoOtomatis ? 1 : 0) . '&amp;playsinline=1" title="' . e($lvJudulKartu) . '" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen loading="lazy"></iframe></div>';
            }
            break;

        case 'agenda':
        case 'komentar':
        case 'media_sosial':
        case 'arsip_artikel':
        case 'statistik_pengunjung':
        case 'menu_kategori':
            // Dirender oleh blok Blade di bawah.
            break;

        default:
            // Widget lain memakai isi bawaannya di dalam kartu; judul bawaannya disembunyikan karena kartu sudah berjudul.
            if (view()->exists("theme::widgets.{$widget['isi']}")) {
                $lvIsiBawaan = $__env->make("theme::widgets.{$widget['isi']}", array_merge(\Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), ['judul_widget' => $lvJudulKartu]))->render();
                $lvIsiKartu = trim($lvIsiBawaan) === '' ? '' : '<div class="lv-widget__body">' . $lvIsiBawaan . '</div>';
            }
    }

    $lvSosmedKartu = collect($sosmed ?? [])->filter(static fn ($s) => filled($s['link'] ?? null));
    $lvArsipKartu = collect(['terkini' => ['Terbaru', $arsip_terkini ?? []], 'populer' => ['Populer', $arsip_populer ?? []]])
        ->filter(static fn ($a) => count($a[1]) > 0);
    $lvAdaKartu = match ($lvJenisKartu) {
        'agenda', 'komentar' => true,
        'jam_kerja' => $lvJamKerja->isNotEmpty(),
        'media_sosial' => $lvSosmedKartu->isNotEmpty(),
        'arsip_artikel' => $lvArsipKartu->isNotEmpty(),
        'statistik_pengunjung' => ! empty($statistik_pengunjung),
        'menu_kategori' => ! empty($menu_kiri),
        default => $lvIsiKartu !== '',
    };
@endphp

@if ($lvAdaKartu)
    <div class="lv-widget lv-widget--{{ $lvJenisKartu === 'statis' ? 'statis' : str_replace('_', '-', $lvJenisKartu) }}" @if (in_array($lvJenisKartu, ['agenda', 'arsip_artikel'], true)) data-lv-tabs @endif>
        <div class="lv-widget__head">
            <h3 class="lv-h3 lv-widget__judul"><span class="lv-widget__ikon">{!! $svg($lvIkonKartu) !!}</span>{{ $lvJudulKartu }}</h3>
            {!! $lvAksiKartu !!}
        </div>

        @switch($lvJenisKartu)
            @case('agenda')
                @php
                    $lvAgenda = ['hari_ini' => ['Hari ini', $hari_ini ?? []], 'yad' => ['Akan datang', $yad ?? []], 'lama' => ['Sebelumnya', $lama ?? []]];
                    $lvAgendaAktif = collect($lvAgenda)->search(static fn ($a) => count($a[1]) > 0) ?: 'hari_ini';
                @endphp
                <div class="lv-utabs" role="tablist" aria-label="{{ $lvJudulKartu }}">
                    @foreach ($lvAgenda as $kunci => [$label, $daftar])
                        <button type="button" role="tab" id="{{ $lvIdKartu }}-tab-{{ $kunci }}" aria-controls="{{ $lvIdKartu }}-{{ $kunci }}" aria-selected="{{ $kunci === $lvAgendaAktif ? 'true' : 'false' }}" tabindex="{{ $kunci === $lvAgendaAktif ? '0' : '-1' }}">{{ $label }}</button>
                    @endforeach
                </div>
                @foreach ($lvAgenda as $kunci => [$label, $daftar])
                    <div class="lv-widget__daftar" role="tabpanel" id="{{ $lvIdKartu }}-{{ $kunci }}" aria-labelledby="{{ $lvIdKartu }}-tab-{{ $kunci }}" @if ($kunci !== $lvAgendaAktif) hidden @endif>
                        @forelse (array_slice($daftar, 0, 3) as $agenda)
                            @php $tgl = $lvTglKartu($agenda['tgl_agenda']); @endphp
                            <a class="lv-agenda" href="{{ site_url('artikel/' . buat_slug($agenda)) }}">
                                <span class="lv-date"><b>{{ $tgl->format('d') }}</b><span>{{ $tgl->translatedFormat('M') }}</span></span>
                                <span class="lv-widget__teks">
                                    <span class="lv-widget__judul-item">{{ $agenda['judul'] }}</span>
                                    <span class="lv-meta">{{ $tgl->format('H.i') }} WIB{{ !empty($agenda['lokasi_kegiatan']) ? ' · ' . $agenda['lokasi_kegiatan'] : '' }}</span>
                                </span>
                            </a>
                        @empty
                            <p class="lv-empty">Belum ada agenda {{ strtolower($label) }}.</p>
                        @endforelse
                    </div>
                @endforeach
            @break

            @case('jam_kerja')
                @if ($lvLiburNasional)
                    <p class="lv-widget__catatan">Hari ini libur nasional</p>
                @endif
                <div class="lv-hours">
                    @foreach ($lvJamKerja as $jam)
                        <div class="{{ strtolower($jam->nama_hari) === $lvNamaHari ? 'is-today' : '' }} {{ $jam->status ? '' : 'is-closed' }}">
                            <span>{{ $jam->nama_hari }}</span>
                            <span class="lv-angka">{{ $jam->status ? substr($jam->jam_masuk, 0, 5) . '–' . substr($jam->jam_keluar, 0, 5) : 'Libur' }}</span>
                        </div>
                    @endforeach
                </div>
            @break

            @case('komentar')
                <div class="lv-widget__daftar">
                    @forelse (array_slice($komen ?? [], 0, 3) as $komentar)
                        @php $nama = $komentar['pengguna']['nama'] ?? 'Pengunjung'; @endphp
                        <a class="lv-comment" href="{{ site_url('artikel/' . buat_slug($komentar)) }}">
                            <span class="lv-avatar" aria-hidden="true">{{ mb_substr($nama, 0, 2) }}</span>
                            <span class="lv-widget__teks">
                                <span class="lv-meta"><strong>{{ $nama }}</strong> · {{ $lvTglKartu($komentar['tgl_upload'])->translatedFormat('j M Y') }}</span>
                                <span class="lv-widget__kutipan">“{{ \Illuminate\Support\Str::limit(strip_tags($komentar['komentar']), 100) }}”</span>
                            </span>
                        </a>
                    @empty
                        <p class="lv-empty">Belum ada komentar.</p>
                    @endforelse
                </div>
            @break

            @case('media_sosial')
                <div class="lv-social">
                    @foreach ($lvSosmedKartu as $sosial)
                        <a href="{{ $sosial['link'] }}" target="_blank" rel="noopener noreferrer"><img src="{{ $sosial['icon'] }}" alt="">{{ $sosial['nama'] }}</a>
                    @endforeach
                </div>
            @break

            @case('arsip_artikel')
                @if ($lvArsipKartu->count() > 1)
                    <div class="lv-utabs" role="tablist" aria-label="{{ $lvJudulKartu }}">
                        @foreach ($lvArsipKartu as $kunci => [$label, $daftar])
                            <button type="button" role="tab" id="{{ $lvIdKartu }}-tab-{{ $kunci }}" aria-controls="{{ $lvIdKartu }}-{{ $kunci }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $label }}</button>
                        @endforeach
                    </div>
                @endif
                @foreach ($lvArsipKartu as $kunci => [$label, $daftar])
                    <div class="lv-widget__daftar" role="tabpanel" id="{{ $lvIdKartu }}-{{ $kunci }}" aria-labelledby="{{ $lvIdKartu }}-tab-{{ $kunci }}" @unless ($loop->first) hidden @endunless>
                        @foreach (array_slice($daftar, 0, 4) as $arsip)
                            @php $lvFotoArsip = filled($arsip['gambar'] ?? null) && is_file(LOKASI_FOTO_ARTIKEL . 'kecil_' . $arsip['gambar']) ? AmbilFotoArtikel($arsip['gambar'], 'kecil') : $lvFotoKosong; @endphp
                            <a class="lv-mini" href="{{ site_url('artikel/' . buat_slug($arsip)) }}">
                                <img src="{{ $lvFotoArsip }}" alt="" loading="lazy">
                                <span class="lv-widget__teks">
                                    <span class="lv-widget__judul-item">{{ \Illuminate\Support\Str::limit($arsip['judul'], 80) }}</span>
                                    <span class="lv-meta">{{ ribuan((int) ($arsip['hit'] ?? 0)) }} kali dibaca</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
                <a class="lv-more" href="{{ site_url('arsip') }}">Lihat arsip artikel →</a>
            @break

            @case('statistik_pengunjung')
                <dl class="lv-angka-baris">
                    @foreach (['hari_ini' => 'Hari ini', 'kemarin' => 'Kemarin', 'total' => 'Total pengunjung'] as $kunci => $label)
                        <div>
                            <dt>{{ $label }}</dt>
                            <dd class="lv-angka">{{ number_format($statistik_pengunjung[$kunci] ?? 0, 0, ',', '.') }}</dd>
                        </div>
                    @endforeach
                </dl>
            @break

            @case('menu_kategori')
                @php [$lvKategoriUtama, $lvKategoriLain] = collect($menu_kiri)->partition(static fn ($k, $i) => $i < 6); @endphp
                <div class="lv-cats">
                    <ul>
                        @foreach ($lvKategoriUtama as $kategori)
                            <li><a href="{{ ci_route('artikel/kategori/' . $kategori['slug']) }}">{{ $kategori['kategori'] }}</a></li>
                        @endforeach
                    </ul>
                    @if ($lvKategoriLain->isNotEmpty())
                        <details class="lv-more-cats">
                            <summary>Lihat {{ $lvKategoriLain->count() }} kategori lainnya</summary>
                            <ul>
                                @foreach ($lvKategoriLain as $kategori)
                                    <li><a href="{{ ci_route('artikel/kategori/' . $kategori['slug']) }}">{{ $kategori['kategori'] }}</a></li>
                                @endforeach
                            </ul>
                        </details>
                    @endif
                </div>
            @break

            @default
                {!! $lvIsiKartu !!}
        @endswitch
    </div>
@endif
