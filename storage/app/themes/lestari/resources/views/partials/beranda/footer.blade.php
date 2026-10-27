@php
    $lvJamFooter = collect($jam_kerja ?? []);
@endphp

<footer class="lv-footer">
    <div class="lv-wrap">
        <div class="lv-footer__grid">
            <div class="lv-footer__col">
                <span class="lv-footer__name">{{ $namaDesa }}</span>
                <span>{{ $desa['alamat_kantor'] }}, {{ $wilayahDesa }}</span>
                @if (!empty($desa['telepon']) || !empty($desa['email_desa']))
                    <span>{{ collect([$desa['telepon'] ?? null, $desa['email_desa'] ?? null])->filter()->implode(' · ') }}</span>
                @endif
            </div>
            <div class="lv-footer__col">
                <b>Tautan</b>
                <a href="{{ site_url('pemerintah') }}">Pemerintah {{ $sebutanDesa }}</a>
                <a href="{{ site_url('informasi-publik') }}">Informasi Publik</a>
                <a href="{{ site_url('peraturan-desa') }}">Produk Hukum</a>
                <a href="{{ site_url('pengaduan') }}">Pengaduan</a>
                <a href="{{ site_url('sitemap') }}">Peta Situs</a>
            </div>
            @if ($lvJamFooter->isNotEmpty())
                <div class="lv-footer__col">
                    <b>Jam Pelayanan</b>
                    @foreach ($lvJamFooter as $jam)
                        <span>{{ $jam->nama_hari }}: {{ $jam->status ? substr($jam->jam_masuk, 0, 5) . '–' . substr($jam->jam_keluar, 0, 5) : 'Libur' }}</span>
                    @endforeach
                </div>
            @endif
            <div class="lv-footer__col">
                <b>Statistik Pengunjung</b>
                <div class="lv-footer__row"><span>Hari ini</span><b>{{ number_format($statistik_pengunjung['hari_ini'] ?? 0, 0, ',', '.') }}</b></div>
                <div class="lv-footer__row"><span>Kemarin</span><b>{{ number_format($statistik_pengunjung['kemarin'] ?? 0, 0, ',', '.') }}</b></div>
                <div class="lv-footer__row"><span>Total</span><b>{{ number_format($statistik_pengunjung['total'] ?? 0, 0, ',', '.') }}</b></div>
            </div>
        </div>
        <div class="lv-footer__bottom">
            <span>© {{ date('Y') }} Pemerintah {{ $namaDesa }}</span>
            <span><a href="https://github.com/OpenSID/OpenSID" target="_blank" rel="noopener noreferrer">OpenSID {{ AmbilVersi() }}</a> · Tema Lestari</span>
        </div>
    </div>
</footer>

@if ($on('tampil_whatsapp') && strlen($lvWa) >= 10)
    <a class="lv-wa" href="https://wa.me/{{ $lvWa }}?text={{ rawurlencode('Halo ' . $namaDesa . ', saya ingin bertanya.') }}" target="_blank" rel="noopener noreferrer" aria-label="Hubungi {{ $namaDesa }} lewat WhatsApp">
        {!! $svg('wa') !!}<span>Tanya {{ $sebutanDesa }}</span>
    </a>
@endif
