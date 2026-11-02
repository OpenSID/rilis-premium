{{--
    Insight desa: angka agregat yang tidak tampil di bagian lain beranda (data penduduk ada di "Data Desa",
    APB/IDM/SDGs/stunting di "Transparansi"), dimuat skrip beranda (pemuat "insight") dari API inti:
    - Pemilih: internal_api/dpt (jumlah per dusun & jenis kelamin).
    - Aset: internal_api/inventaris (jumlah per jenis & asal perolehan).
    - Dokumen publik: internal_api/produk-hukum & internal_api/informasi-publik (jumlah dari meta).
    - Artikel: internal_api/arsip (jumlah dari meta, posting pertama/terakhir lewat sort); tren per bulan dimuat saat tab Artikel dibuka.
    Sumber hanya dipakai bila menu halamannya aktif (kunci menu yang diperiksa inti, bukan URL), agar angka dan
    tautannya tidak berakhir di halaman "belum tersedia". Tab tanpa data disembunyikan; bagian tetap tersembunyi bila semua kosong.
--}}
@php
    $lvMenuInsight = $lvLinkMenuAktif();
    $lvSumberInsight = array_filter([
        'dpt'       => isset($lvMenuInsight['dpt']) ? site_url('internal_api/dpt') : null,
        'aset'      => isset($lvMenuInsight['inventaris']) ? site_url('internal_api/inventaris') : null,
        'hukum'     => isset($lvMenuInsight['peraturan-desa']) ? site_url('internal_api/produk-hukum') : null,
        'publik'    => isset($lvMenuInsight['informasi_publik']) ? site_url('internal_api/informasi-publik') : null,
        'artikel'   => site_url('internal_api/arsip'),
    ]);
    $lvTabInsight = array_filter([
        'ringkasan' => 'Ringkasan',
        'wilayah'   => isset($lvSumberInsight['dpt']) ? 'Pemilih' : null,
        'aset'      => isset($lvSumberInsight['aset']) ? 'Aset' : null,
        'artikel'   => 'Artikel',
    ]);
@endphp

<section class="lv-wrap lv-section lv-insight" aria-labelledby="lv-insight-judul" data-lv-tabs data-lv-section-lazy hidden>
    <div class="lv-head">
        <div>
            <h2 class="lv-h2" id="lv-insight-judul">Insight {{ $sebutanDesa }}</h2>
            <p class="lv-meta" style="margin-top: 6px">Ringkasan otomatis dari data pemilih, aset, dokumen publik, dan publikasi {{ strtolower($sebutanDesa) }}</p>
        </div>
        <div class="lv-tabs" role="tablist" aria-label="Insight {{ $sebutanDesa }}" style="margin: 0">
            @foreach ($lvTabInsight as $kunci => $label)
                <button type="button" role="tab" id="lv-in-tab-{{ $kunci }}" aria-controls="lv-in-{{ $kunci }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <div data-lv-lazy="insight" data-lv-segera
        @foreach ($lvSumberInsight as $kunci => $url) data-lv-url-{{ $kunci }}="{{ $url }}" @endforeach
        data-lv-tautan-dpt="{{ site_url('data-dpt') }}" data-lv-tautan-hukum="{{ site_url('peraturan-desa') }}" data-lv-tautan-publik="{{ site_url('informasi-publik') }}" data-lv-tautan-arsip="{{ site_url('arsip') }}" data-lv-tautan-aset="{{ site_url('inventaris') }}"
        hidden>
        {{-- Ringkasan --}}
        <div role="tabpanel" id="lv-in-ringkasan" aria-labelledby="lv-in-tab-ringkasan" class="lv-in-panel">
            <div class="lv-kpis" data-lv-in="kpi"></div>
            <div class="lv-in-grid">
                <div class="lv-card">
                    <h3 class="lv-h3" style="margin-bottom: 14px">Catatan cepat</h3>
                    <ul class="lv-notes" data-lv-in="catatan"></ul>
                </div>
                <div class="lv-card">
                    <h3 class="lv-h3" style="margin-bottom: 8px">Jelajahi data</h3>
                    <div data-lv-in="jelajah"></div>
                </div>
            </div>
        </div>

        {{-- Pemilih per dusun --}}
        @isset($lvTabInsight['wilayah'])
            <div role="tabpanel" id="lv-in-wilayah" aria-labelledby="lv-in-tab-wilayah" class="lv-in-panel" hidden>
                <div class="lv-kpis" data-lv-in="kpi_dpt"></div>
                <div class="lv-card">
                    <div class="lv-apb__row" style="margin-bottom: 16px">
                        <h3 class="lv-h3">Pemilih terdaftar per dusun</h3>
                        <ul class="lv-in-legenda" aria-hidden="true">
                            <li><i style="background: var(--c1)"></i>Laki-laki</li>
                            <li><i style="background: var(--c2)"></i>Perempuan</li>
                        </ul>
                    </div>
                    <div class="lv-in-rentang lv-in-rentang--lebar" data-lv-in="dusun"></div>
                </div>
            </div>
        @endisset

        {{-- Aset --}}
        @isset($lvTabInsight['aset'])
            <div role="tabpanel" id="lv-in-aset" aria-labelledby="lv-in-tab-aset" class="lv-in-panel" hidden>
                <div class="lv-in-grid">
                    <div class="lv-card">
                        <h3 class="lv-h3" style="margin-bottom: 16px">Aset tercatat per jenis</h3>
                        <div class="lv-in-rentang lv-in-rentang--lebar" data-lv-in="aset_jenis"></div>
                    </div>
                    <div class="lv-card">
                        <h3 class="lv-h3" style="margin-bottom: 8px">Asal perolehan</h3>
                        <div data-lv-in="aset_asal"></div>
                    </div>
                </div>
            </div>
        @endisset

        {{-- Artikel --}}
        <div role="tabpanel" id="lv-in-artikel" aria-labelledby="lv-in-tab-artikel" class="lv-in-panel" hidden>
            <div class="lv-kpis">
                <div class="lv-kpi"><span class="lv-meta">Artikel tayang</span><b data-lv-in="artikel_total">–</b><small>di website</small></div>
                <div class="lv-kpi"><span class="lv-meta">Posting pertama</span><b class="lv-kpi__text" data-lv-in="artikel_awal">–</b></div>
                <div class="lv-kpi"><span class="lv-meta">Posting terakhir</span><b class="lv-kpi__text" data-lv-in="artikel_akhir">–</b></div>
                <div class="lv-kpi"><span class="lv-meta">Bulan teraktif</span><b class="lv-kpi__text" data-lv-in="artikel_puncak">–</b></div>
            </div>
            <div class="lv-in-grid">
                <div class="lv-card">
                    <div class="lv-apb__row" style="margin-bottom: 16px">
                        <h3 class="lv-h3">Tren artikel per bulan</h3>
                        <div class="lv-range" data-lv-range="lv-chart-artikel">
                            @foreach ([12 => '1 thn', 36 => '3 thn', 0 => 'Semua'] as $n => $label)
                                <button type="button" data-n="{{ $n }}" aria-pressed="{{ $n === 0 ? 'true' : 'false' }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="lv-ichart" id="lv-chart-artikel" data-n="0" role="img" aria-label="Grafik jumlah artikel per bulan"><p class="lv-meta">Memuat tren artikel…</p></div>
                </div>
                <div class="lv-card">
                    <h3 class="lv-h3" style="margin-bottom: 12px">5 bulan paling aktif</h3>
                    <div data-lv-in="teraktif"><p class="lv-meta">Memuat…</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
