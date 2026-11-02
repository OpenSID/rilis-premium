@php
    $lvTglSamping = static fn ($tgl) => $tgl ? \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j F Y') : '';
    $lvArsip = [
        'terkini' => ['Terbaru', $arsip_terkini ?? []],
        'populer' => ['Populer', $arsip_populer ?? []],
        'acak' => ['Acak', $arsip_acak ?? []],
    ];
@endphp

<aside class="lv-news__aside" aria-label="Arsip dan kategori">
    <div class="lv-card" data-lv-tabs>
        <div class="lv-tabs" role="tablist" aria-label="Arsip artikel">
            @foreach ($lvArsip as $kunci => [$label, $daftar])
                <button type="button" role="tab" id="lv-tab-{{ $kunci }}" aria-controls="lv-panel-{{ $kunci }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}">{{ $label }}</button>
            @endforeach
        </div>
        @foreach ($lvArsip as $kunci => [$label, $daftar])
            <div class="lv-rank" role="tabpanel" id="lv-panel-{{ $kunci }}" aria-labelledby="lv-tab-{{ $kunci }}" @unless ($loop->first) hidden @endunless>
                @forelse (array_slice($daftar, 0, 5) as $i => $arsip)
                    <a href="{{ site_url('artikel/' . buat_slug($arsip)) }}">
                        <span class="lv-rank__n">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>
                            <span class="lv-rank__t">{{ $arsip['judul'] }}</span>
                            <span class="lv-meta">{{ $lvTglSamping($arsip['tgl_upload']) }} · {{ ribuan((int) ($arsip['hit'])) }} kali dibaca</span>
                        </span>
                    </a>
                @empty
                    <p class="lv-meta">Belum ada artikel.</p>
                @endforelse
            </div>
        @endforeach
    </div>

    @if (!empty($menu_kiri))
        <div class="lv-card lv-cats">
            <h3 class="lv-h3" style="margin-bottom: 8px">Kategori</h3>
            @php [$kategoriUtama, $kategoriLain] = collect($menu_kiri)->partition(static fn ($k, $i) => $i < 8); @endphp
            <ul>
                @foreach ($kategoriUtama as $kategori)
                    <li><a href="{{ ci_route('artikel/kategori/' . $kategori['slug']) }}">{{ $kategori['kategori'] }}</a></li>
                @endforeach
            </ul>
            @if ($kategoriLain->isNotEmpty())
                <details class="lv-more-cats">
                    <summary>Lihat {{ $kategoriLain->count() }} kategori lainnya</summary>
                    <ul>
                        @foreach ($kategoriLain as $kategori)
                            <li><a href="{{ ci_route('artikel/kategori/' . $kategori['slug']) }}">{{ $kategori['kategori'] }}</a></li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    @endif
</aside>
