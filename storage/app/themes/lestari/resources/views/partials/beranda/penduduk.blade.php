@php
    $lvStat = collect($stat_widget ?? []);
    $lvTotal = $lvStat->first(static fn ($s) => strtoupper($s['nama'] ?? '') === 'JUMLAH');
    $lvRinci = $lvStat->filter(static fn ($s) => ($s['jumlah'] ?? 0) > 0 && ! in_array(strtoupper($s['nama'] ?? ''), ['JUMLAH', 'TOTAL', 'BELUM MENGISI'], true));

    // Statistik beranda: whitelist terkurasi (data umum & penting bagi warga).
    // Statistik sensitif (penyakit, disabilitas, kehamilan, KB, covid, suku/marga/adat, desil)
    // dan DPT sengaja tidak ada di sini; tetap tersedia di halaman data-statistik.
    $lvGrupStat = [
        'kependudukan' => ['label' => 'Kependudukan', 'key' => ['13', '0', '1', '3', '2']],
        'bantuan'      => ['label' => 'Bantuan Sosial', 'key' => ['bantuan_penduduk', 'bantuan_keluarga']],
        'dokumen'      => ['label' => 'Dokumen & Jaminan', 'key' => ['18', '17', 'kia', '19', 'bpjs-tenagakerja']],
    ];
    $lvSatuanKeluarga = ['bantuan_keluarga'];

    // Hanya statistik yang menunya aktif (Admin Web > Menu), sama seperti halaman data-statistik
    $lvMenuAktif = function_exists('menu_statistik_aktif') ? collect(menu_statistik_aktif()) : null;
    $lvEnum = collect(\App\Enums\Statistik\StatistikPendudukEnum::$data)
        ->merge(\App\Enums\Statistik\StatistikJenisBantuanEnum::$data)
        ->keyBy(static fn ($s) => (string) $s['key']);

    $lvPilihan = collect();
    foreach ($lvGrupStat as $grup => $info) {
        foreach ($info['key'] as $key) {
            if (! $lvEnum->has($key) || ($lvMenuAktif !== null && ! $lvMenuAktif->has('statistik/' . $key))) {
                continue;
            }
            $lvPilihan[$key] = [
                'key'    => $key,
                'grup'   => $grup,
                'slug'   => $lvEnum[$key]['slug'],
                'label'  => $lvEnum[$key]['label'],
                'satuan' => in_array($key, $lvSatuanKeluarga, true) ? 'keluarga' : 'jiwa',
                'bantuan' => str_starts_with($key, 'bantuan_'),
                // Tautan "Lihat detail": url dari core (statistik bantuan tidak terbuka lewat data-statistik/{slug})
                'url'    => $lvUrlStatistik($key),
            ];
        }
    }
    $lvGrupStat = collect($lvGrupStat)->filter(static fn ($g, $grup) => $lvPilihan->contains('grup', $grup));

    $lvStatAwal = (string) $cfg('statistik_default', '13');
    $lvStatAwal = $lvPilihan->has($lvStatAwal) ? $lvStatAwal : (string) $lvPilihan->keys()->first();
    $lvGrupAwal = $lvPilihan[$lvStatAwal]['grup'] ?? null;

    // Pilihan tahun: API statistik menerima ?tahun= (kondisi per 31 Desember tahun itu;
    // untuk bantuan: program yang aktif di tahun itu). 10 tahun terakhir; tahun tanpa data tampil "Belum ada data."
    // (tahun data tertua tidak dibaca dari basis data: tema tidak boleh query langsung)
    $lvTahun = range((int) date('Y') - 1, (int) date('Y') - 10);
@endphp

@if ($lvRinci->isNotEmpty() || $lvPilihan->isNotEmpty())
    <section class="lv-bg-navy lv-pop" aria-labelledby="lv-pop-judul">
        <svg class="lv-contour" aria-hidden="true" width="720" height="420" viewBox="0 0 720 420" fill="none" stroke="rgba(255,255,255,.08)" stroke-width="1.5"><path d="M40 380C160 300 220 160 380 120s260 20 320-60M40 410C170 330 240 190 400 150s260 20 320-60M40 350C150 270 200 130 360 90s260 20 320-60M40 320C140 240 180 100 340 60s260 20 320-60"/></svg>
        <div class="lv-wrap lv-section">
            <div class="lv-head">
                <h2 class="lv-h2" id="lv-pop-judul">Data {{ $namaDesa }}</h2>
                @if ($lvUrlStatistikLengkap = $lvUrlStatistik())
                    <a class="lv-more" href="{{ $lvUrlStatistikLengkap }}">Lihat statistik lengkap →</a>
                @endif
            </div>
            @if ($lvRinci->isNotEmpty())
                <div class="lv-pop__grid">
                    @if ($lvTotal && $lvTotal['jumlah'] > 0)
                        <div class="lv-pop__item">
                            <span class="lv-pop__num lv-pop__num--accent">{{ number_format($lvTotal['jumlah'], 0, ',', '.') }}</span>
                            <span class="lv-pop__label">Total penduduk</span>
                        </div>
                    @endif
                    @foreach ($lvRinci as $stat)
                        <div class="lv-pop__item">
                            <span class="lv-pop__num">{{ number_format($stat['jumlah'], 0, ',', '.') }}</span>
                            <span class="lv-pop__label">{{ ucwords(strtolower($stat['nama'])) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($lvPilihan->isNotEmpty())
                <div class="lv-stat" data-lv-stat data-lv-url="{{ site_url('internal_api/statistik') }}" data-lv-awal="{{ $lvStatAwal }}">
                    <div class="lv-stat__nav">
                        @if ($lvGrupStat->count() > 1)
                            <div class="lv-stat__grup" role="tablist" aria-label="Kelompok statistik">
                                @foreach ($lvGrupStat as $grup => $info)
                                    <button type="button" role="tab" aria-selected="{{ $grup === $lvGrupAwal ? 'true' : 'false' }}" data-lv-stat-grup="{{ $grup }}">{{ $info['label'] }}</button>
                                @endforeach
                            </div>
                        @endif
                        <div class="lv-stat__tabs" role="tablist" aria-label="Pilih statistik">
                            @foreach ($lvPilihan as $key => $s)
                                <button type="button" role="tab" aria-selected="{{ $key === $lvStatAwal ? 'true' : 'false' }}" data-lv-stat-key="{{ $key }}" data-grup="{{ $s['grup'] }}" @if ($s['grup'] !== $lvGrupAwal) hidden @endif>{{ $s['label'] }}</button>
                            @endforeach
                        </div>
                        @if ($lvTahun)
                            <label class="lv-stat__tahun">
                                <span>Kondisi</span>
                                <select data-lv-stat-tahun>
                                    <option value="">Terkini</option>
                                    @foreach ($lvTahun as $thn)
                                        <option value="{{ $thn }}">Akhir {{ $thn }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                    </div>

                    <div class="lv-stat__panel" role="tabpanel">
                        <div class="lv-stat__head">
                            <div>
                                <h3 class="lv-h3" data-lv-stat-judul>{{ $lvPilihan[$lvStatAwal]['label'] }}</h3>
                                <p class="lv-stat__sub" data-lv-stat-sub aria-live="polite">Memuat data…</p>
                            </div>
                            <div class="lv-stat__aksi">
                                <button type="button" class="lv-stat__toggle" data-lv-stat-tabel aria-pressed="false">Tampilkan tabel</button>
                                <a class="lv-more" data-lv-stat-link href="{{ $lvPilihan[$lvStatAwal]['url'] }}">Lihat detail →</a>
                            </div>
                        </div>

                        {{-- Grafik batang horizontal: satu seri, diurutkan, nilai di ujung batang --}}
                        <figure class="lv-chart" data-lv-chart>
                            <div class="lv-chart__grid" data-lv-chart-grid aria-hidden="true"></div>
                            <div class="lv-chart__axis" data-lv-chart-axis aria-hidden="true"></div>
                            <ol class="lv-chart__rows" data-lv-chart-rows></ol>
                            <div class="lv-chart__tip" data-lv-chart-tip role="tooltip" hidden></div>
                        </figure>

                        {{-- Padanan aksesibel: seluruh kategori tanpa digabung --}}
                        <div class="lv-stat__tabel" data-lv-stat-tabelwrap hidden>
                            <table>
                                <caption class="lv-sr" data-lv-stat-caption></caption>
                                <thead><tr><th scope="col">Kategori</th><th scope="col">Jumlah</th><th scope="col">Persen</th></tr></thead>
                                <tbody data-lv-stat-tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <script type="application/json" data-lv-stat-meta>@json($lvPilihan)</script>
                </div>
            @endif
        </div>
    </section>
@endif
