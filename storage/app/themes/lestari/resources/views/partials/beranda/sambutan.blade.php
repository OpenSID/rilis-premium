@php
    $sebutanKades = strtolower(setting('sebutan_kepala_desa') ?: 'kepala desa');
    $lvKades = $lvPerangkat->first(static fn ($p) => str_contains(strtolower($p['jabatan'] ?? ''), $sebutanKades))
        ?? $lvPerangkat->first();

    $lvSambutan = $cfg('sambutan_kades')
        ?: "Assalamu'alaikum warahmatullahi wabarakatuh. Selamat datang di website {$namaDesa}. Melalui website ini, kami berupaya menghadirkan informasi secara aktual dan efektif terkait program dan kegiatan pembangunan Pemerintah {$namaDesa}, {$wilayahDesa}.";

    // Kartu "Kantor Hari Ini": hal praktis yang dicari setelah membaca sambutan (kantor buka, kepala desa ada, agenda terdekat, cara menghubungi).
    $lvKadesKehadiran = $lvKades && setting('tampilkan_kehadiran') && $on('tampil_kehadiran') && ($lvKades['kehadiran'] ?? null) == 1;
    $lvKadesHadir = ($lvKades['status_kehadiran'] ?? null) === 'hadir';
    // Hanya agenda hari ini dan yang akan datang; agenda lama tidak ditampilkan agar beranda tidak terlihat usang.
    $lvAgendaDekat = collect($hari_ini ?? [])->map(static fn ($a) => $a + ['lv_hari_ini' => true])
        ->concat($yad ?? [])
        ->take(2);
    $lvAdaWidgetAgenda = collect($widgetAktif ?? [])->contains('isi', 'agenda');
    $lvTelpKantor = $desa['telepon'] ?? null;
    $lvAdaTitikKantor = is_numeric($desa['lat'] ?? null) && is_numeric($desa['lng'] ?? null);
    $lvWaKantor = $on('tampil_whatsapp') && strlen($lvWa) >= 10;
    $lvAdaKontak = $lvWaKantor || $lvTelpKantor || $lvAdaTitikKantor || !empty($desa['alamat_kantor']);
    $lvKartuKantor = $lvJamHariIni || $lvKadesKehadiran || $lvAgendaDekat->isNotEmpty() || $lvAdaKontak;
@endphp

@if ($lvKades)
    <section class="lv-wrap lv-section" aria-labelledby="lv-sambutan-judul">
        <div @class(['lv-welcome', 'lv-welcome--kartu' => $lvKartuKantor])>
            <div class="lv-welcome__photo">
                <img src="{{ $lvKades['foto'] }}" alt="Foto {{ $lvKades['nama'] }}" loading="lazy">
            </div>
            <div class="lv-welcome__text">
                <h2 class="lv-h2" id="lv-sambutan-judul">Sambutan {{ ucwords($sebutanKades) }}</h2>
                @if ($cfg('kutipan_sambutan'))
                    <p class="lv-welcome__quote">“{{ $cfg('kutipan_sambutan') }}”</p>
                @endif
                {{-- Dilipat oleh skrip hanya bila teksnya panjang; tanpa JavaScript teks tampil utuh --}}
                <p class="lv-welcome__body" id="lv-sambutan-isi" style="white-space: pre-line" data-lv-lipat>{{ $lvSambutan }}</p>
                <button type="button" class="lv-welcome__lipat" aria-controls="lv-sambutan-isi" aria-expanded="false" hidden>Baca selengkapnya</button>
                <div class="lv-welcome__sign">
                    <div>
                        <b>{{ $lvNamaGelar($lvKades) }}</b>
                        <span class="lv-meta">{{ $lvKades['jabatan'] }} {{ ucwords($desa['nama_desa']) }}</span>
                    </div>
                </div>
            </div>

            @if ($lvKartuKantor)
                <aside class="lv-office" aria-labelledby="lv-office-judul">
                    <h3 class="lv-h3" id="lv-office-judul">Kantor {{ $sebutanDesa }} Hari Ini</h3>

                    <ul class="lv-office__status">
                        @if ($lvJamKerja->isNotEmpty())
                            <li>
                                <span class="lv-status {{ $lvKantorBuka ? 'lv-status--ok' : 'lv-status--off' }}">{{ $lvKantorBuka ? 'Buka' : 'Tutup' }}</span>
                                <span class="lv-meta">
                                    @if ($lvLiburNasional)
                                        Libur nasional
                                    @elseif ($lvJamHariIni && $lvJamHariIni->status)
                                        {{ substr($lvJamHariIni->jam_masuk, 0, 5) }}–{{ substr($lvJamHariIni->jam_keluar, 0, 5) }} WIB
                                    @else
                                        Hari ini libur
                                    @endif
                                </span>
                            </li>
                        @endif
                        @if ($lvKadesKehadiran)
                            <li>
                                <span>{{ ucwords($sebutanKades) }}</span>
                                <span class="lv-status {{ $lvKadesHadir ? 'lv-status--ok' : 'lv-status--off' }}">{{ $lvKadesHadir ? $cfg('teks_hadir', 'Ada di kantor') : $cfg('teks_tidak_hadir', 'Tidak di kantor') }}</span>
                            </li>
                        @endif
                    </ul>

                    @if ($lvAgendaDekat->isNotEmpty())
                        <div class="lv-office__agenda">
                            <span class="lv-office__label">Agenda terdekat</span>
                            @foreach ($lvAgendaDekat as $agenda)
                                @php $tgl = \Carbon\Carbon::parse($agenda['tgl_agenda'])->locale('id'); @endphp
                                <a class="lv-agenda" href="{{ site_url('artikel/' . buat_slug($agenda)) }}">
                                    <span class="lv-date"><b>{{ $tgl->format('d') }}</b><span>{{ $tgl->translatedFormat('M') }}</span></span>
                                    <span style="display: flex; flex-direction: column; gap: 2px; min-width: 0">
                                        @if (!empty($agenda['lv_hari_ini']))
                                            <span class="lv-office__hari-ini">Hari ini</span>
                                        @endif
                                        <span style="font-weight: 600; line-height: 1.4">{{ $agenda['judul'] }}</span>
                                        <span class="lv-meta">{{ $tgl->format('H.i') }} WIB{{ !empty($agenda['lokasi_kegiatan']) ? ' · ' . $agenda['lokasi_kegiatan'] : '' }}</span>
                                    </span>
                                </a>
                            @endforeach
                            @if ($lvAdaWidgetAgenda)
                                <a class="lv-more" href="#lv-widget-judul">Lihat semua agenda →</a>
                            @endif
                        </div>
                    @endif

                    @if ($lvAdaKontak)
                        <div class="lv-office__kontak">
                            @if (!empty($desa['alamat_kantor']))
                                <p class="lv-office__alamat">{!! $svg('pin') !!}<span>{{ $desa['alamat_kantor'] }}</span></p>
                            @endif
                            @if ($lvWaKantor || $lvTelpKantor || $lvAdaTitikKantor)
                                <div class="lv-office__aksi">
                                    @if ($lvWaKantor)
                                        <a class="lv-btn" href="https://wa.me/{{ $lvWa }}?text={{ rawurlencode('Halo ' . $namaDesa . ', saya ingin bertanya.') }}" target="_blank" rel="noopener noreferrer">{!! $svg('wa') !!}WhatsApp</a>
                                    @endif
                                    @if ($lvTelpKantor)
                                        <a class="lv-btn lv-btn--ghost" href="tel:{{ preg_replace('/[^\d+]/', '', $lvTelpKantor) }}">{!! $svg('phone') !!}Telepon</a>
                                    @endif
                                    @if ($lvAdaTitikKantor)
                                        <a class="lv-btn lv-btn--ghost" href="https://www.google.com/maps/dir/?api=1&destination={{ $desa['lat'] }},{{ $desa['lng'] }}" target="_blank" rel="noopener noreferrer">{!! $svg('map') !!}Petunjuk arah</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    </section>
@endif
