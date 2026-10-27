<section class="lv-bg-deep lv-mandiri" aria-labelledby="lv-mandiri-judul">
    <svg class="lv-contour" aria-hidden="true" style="left: -120px; right: auto; top: auto; bottom: -120px" width="640" height="420" viewBox="0 0 640 420" fill="none" stroke="rgba(255,178,0,.12)" stroke-width="1.5"><path d="M20 400C140 320 200 180 360 140s220 10 260-60M20 370C130 290 180 150 340 110s220 10 260-60M20 340C120 260 160 120 320 80s220 10 260-60"/></svg>
    <div class="lv-wrap lv-section">
        <div class="lv-mandiri__text">
            <h2 class="lv-h2" id="lv-mandiri-judul">Urus surat dari rumah, tanpa antre.</h2>
            <p>Layanan Mandiri {{ $namaDesa }} untuk pengajuan surat, cek bantuan, dan memantau status permohonan.</p>
            <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap; padding-top: 8px">
                <a class="lv-btn lv-btn--accent" href="{{ site_url('layanan-mandiri/masuk') }}">Masuk Layanan Mandiri</a>
                <span class="lv-meta" style="color: var(--on-dark); max-width: 240px">Belum punya PIN? Hubungi perangkat {{ strtolower($sebutanDesa) }}.</span>
            </div>
        </div>
        <ol class="lv-steps">
            <li><b>01</b><span><strong>Masuk dengan NIK &amp; PIN</strong><span>PIN diberikan oleh operator {{ strtolower($sebutanDesa) }}.</span></span></li>
            <li><b>02</b><span><strong>Pilih dan ajukan surat</strong><span>Isi formulir dan unggah dokumen pendukung.</span></span></li>
            <li><b>03</b><span><strong>Pantau &amp; ambil surat</strong><span>Cek status hingga surat siap diambil atau diunduh.</span></span></li>
        </ol>
    </div>
</section>
