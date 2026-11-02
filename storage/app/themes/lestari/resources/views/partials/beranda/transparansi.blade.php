@php
    $lvRp = static fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');

    // APB Desa semua tahun yang punya angka, dari variabel $widget_keuangan yang dikirim controller inti
    // (data per tahun dalam JSON: res_pelaksanaan berisi nama, anggaran, realisasi). Maksimal 10 tahun terbaru.
    // Tetap mengikuti sakelar admin "Apbdes Footer" seperti tampilan bawaan OpenSID.
    $lvApbTahun = [];
    if ($on('tampil_transparansi') && setting('apbdes_footer')) {
        $lvApbData = json_decode((string) ($widget_keuangan['data'] ?? ''), true) ?: [];
        krsort($lvApbData);
        foreach (array_slice($lvApbData, 0, 10, true) as $tahun => $isi) {
            $baris = collect($isi['res_pelaksanaan'] ?? [])
                // Baris PEMBIAYAAN menyimpan realisasinya di "realisasi_pendapatan"
                ->map(static fn ($r) => is_array($r) ? ['judul' => $r['nama'] ?? null, 'anggaran' => (float) ($r['anggaran'] ?? 0), 'realisasi' => (float) ($r['realisasi'] ?? $r['realisasi_pendapatan'] ?? 0)] : null)
                ->filter(static fn ($r) => $r && filled($r['judul']) && ($r['anggaran'] != 0 || $r['realisasi'] != 0))
                ->values()
                ->all();
            if ($baris !== []) {
                $lvApbTahun[(string) $tahun] = $baris;
            }
        }
    }

    // Tahun awal: pilihan admin bila ada datanya, selain itu tahun terbaru yang berisi angka
    $lvApbAwal = (string) setting('apbdes_tahun');
    if (! isset($lvApbTahun[$lvApbAwal])) {
        $lvApbAwal = (string) array_key_first($lvApbTahun);
    }
    $lvBarisApb = static function (array $r) use ($sebutanDesa) {
        $persen = $r['anggaran'] > 0 ? round($r['realisasi'] / $r['anggaran'] * 100) : 0;

        return $r + [
            'label' => str_replace(['Desa', 'DESA'], $sebutanDesa, ucwords(strtolower($r['judul']))),
            'persen' => $persen,
            'lebar' => max(0, min(100, $persen)),
        ];
    };
    $lvTampilApb = $lvApbTahun !== [];
    $lvTampilKinerja = $on('tampil_kinerja');
@endphp

@if ($lvTampilApb || $lvTampilKinerja)
    <section class="lv-wrap lv-section" id="transparansi" aria-labelledby="lv-transp-judul" @unless ($lvTampilApb) data-lv-section-lazy hidden @endunless>
        <div class="lv-head">
            <h2 class="lv-h2" id="lv-transp-judul">Transparansi &amp; Kinerja {{ $sebutanDesa }}</h2>
        </div>
        <div class="lv-transp">
            @if ($lvTampilApb)
                <div class="lv-card lv-transp__main" data-lv-apb>
                    <div class="lv-apb__row" style="margin: 0">
                        @if (count($lvApbTahun) > 1)
                            <label class="lv-apb__tahun">
                                <span class="lv-h3">APB {{ $sebutanDesa }}</span>
                                <span class="lv-sr">Tahun anggaran</span>
                                <select class="lv-select" data-lv-apb-pilih>
                                    @foreach (array_keys($lvApbTahun) as $tahun)
                                        <option value="{{ $tahun }}" @selected((string) $tahun === $lvApbAwal)>{{ $tahun }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @else
                            <h3 class="lv-h3">APB {{ $sebutanDesa }} {{ $lvApbAwal }}</h3>
                        @endif
                        <span class="lv-legend"><span><i style="background: var(--c1-100)"></i>Anggaran</span><span><i style="background: var(--c1)"></i>Realisasi</span></span>
                    </div>
                    @foreach ($lvApbTahun as $tahun => $daftar)
                        <div class="lv-apb__panel" data-lv-apb-tahun="{{ $tahun }}" aria-live="polite" @if ((string) $tahun !== $lvApbAwal) hidden @endif>
                            @foreach (array_map($lvBarisApb, $daftar) as $baris)
                                <div>
                                    <div class="lv-apb__row"><b>{{ $baris['label'] }}</b><span class="lv-apb__pct">{{ $baris['persen'] }}%</span></div>
                                    <div class="lv-bar" role="img" aria-label="{{ $baris['label'] }} {{ $tahun }}: realisasi {{ $baris['persen'] }} persen"><span style="width: {{ $baris['lebar'] }}%"></span></div>
                                    <div class="lv-apb__foot lv-meta">
                                        <span>Realisasi <strong style="color: var(--ink)">{{ $lvRp($baris['realisasi']) }}</strong></span>
                                        <span>Anggaran {{ $lvRp($baris['anggaran']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($lvTampilKinerja)
                <div class="lv-transp__side">
                    <div class="lv-card" data-lv-lazy="idm" data-lv-url="{{ route('api.idm', date('Y')) }}" data-lv-url-alt="{{ route('api.idm', date('Y') - 1) }}" hidden style="display: flex; align-items: center; gap: 22px">
                        <div class="lv-ring" data-lv-ring><span data-lv-field="skor">–</span></div>
                        <div style="display: flex; flex-direction: column; gap: 4px">
                            <span class="lv-meta">Indeks {{ $sebutanDesa }} Membangun <span data-lv-field="tahun"></span></span>
                            <span class="lv-big" style="color: var(--c1-700)" data-lv-field="status">–</span>
                            <a href="{{ site_url('status-idm') }}" style="font-size: 14px; font-weight: 700">Rincian IDM →</a>
                        </div>
                    </div>
                    <div class="lv-card" data-lv-lazy="sdgs" data-lv-url="{{ route('api.sdgs') }}" hidden style="display: flex; flex-direction: column; gap: 14px">
                        <div style="display: flex; justify-content: space-between; align-items: baseline"><span class="lv-meta">Skor SDGs {{ $sebutanDesa }}</span><span class="lv-big" data-lv-field="skor">–</span></div>
                        <div class="lv-sdgs" data-lv-field="bar"></div>
                        <a href="{{ site_url('status-sdgs') }}" style="font-size: 14px; font-weight: 700">18 tujuan SDGs {{ $sebutanDesa }} →</a>
                    </div>
                    <div class="lv-card" data-lv-lazy="stunting" data-lv-url="{{ route('api.stunting') }}" hidden style="display: flex; flex-direction: column; gap: 14px">
                        <span class="lv-meta">Kesehatan · Pemantauan Stunting</span>
                        <div class="lv-stats3">
                            <div><span class="lv-big" data-lv-field="periksa">–</span><span class="lv-meta" style="display: block">Anak diperiksa</span></div>
                            <div><span class="lv-big" style="color: var(--warn)" data-lv-field="stunting">–</span><span class="lv-meta" style="display: block">Stunting</span></div>
                            <div><span class="lv-big" data-lv-field="posyandu">–</span><span class="lv-meta" style="display: block">Posyandu</span></div>
                        </div>
                        <a href="{{ site_url('data-kesehatan') }}" style="font-size: 14px; font-weight: 700">Data kesehatan →</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
