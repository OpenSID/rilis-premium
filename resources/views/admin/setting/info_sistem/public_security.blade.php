{{-- Status penjaga public/.htaccess (premium#7534) --}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Keutuhan Berkas public/</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr><th>Berkas</th><th>Isi</th><th>Readonly</th><th>Pemilik berbeda dari user PHP</th></tr>
            </thead>
            <tbody>
                @foreach ($public_guard['files'] as $nama => $b)
                    <tr>
                        <td><code>public/{{ $nama }}</code></td>
                        <td>
                            @if ($b['intact'])
                                <span class="label label-success">Utuh</span>
                            @else
                                <span class="label label-danger">Berbeda dari bawaan</span>
                            @endif
                        </td>
                        <td>{{ $b['readonly'] ? 'Ya' : 'Tidak' }}</td>
                        <td>
                            @if ($b['owner_differs'] === null)
                                <span class="text-muted">tidak diketahui</span>
                            @else
                                {{ $b['owner_differs'] ? 'Ya (lebih aman)' : 'Tidak (tema masih bisa mengubah izin)' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (! ($public_guard['enabled'] ?? true))
            <div class="alert alert-warning">Penjaga dimatikan (<code>$config['penjaga_public'] = false;</code> di <code>desa/config/config.php</code>). <code>public/.htaccess</code> dan <code>public/index.php</code> <strong>tidak</strong> dipulihkan otomatis; pastikan Anda memeriksanya sendiri.
                @if (can('u'))
                    <br><a href="#" onclick="submitPostRequest('{{ ci_route('info_sistem.pulihkan_public') }}', 'POST'); return false;" class="btn btn-flat btn-sm btn-warning" style="margin-top: 10px;"><i class="fa fa-undo"></i> Pulihkan ke Bawaan Rilis</a>
                @endif
            </div>
        @endif
        @foreach ($public_guard['files'] as $nama => $b)
            @if (! ($b['stub_available'] ?? true))
                <div class="alert alert-danger">Berkas bawaan <code>resources/stubs</code> untuk <code>public/{{ $nama }}</code> hilang atau kosong (pembaruan tidak lengkap?). Pemulihan dihentikan agar berkas tidak ditimpa kosong; unggah ulang paket pembaruan secara utuh.</div>
            @endif
        @endforeach
        @if (! empty($public_guard['rewrite_base']))
            <p>RewriteBase aktif: <code>{{ $public_guard['rewrite_base'] }}</code></p>
        @endif
        @if (! $public_guard['writable'])
            <div class="alert alert-warning">Folder <code>public/</code> tidak dapat ditulis oleh PHP, sehingga pemulihan otomatis tidak bisa berjalan. Periksa izin folder.</div>
        @endif
    </div>
</div>

<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Kejadian Penimpaan Terakhir</h3>
    </div>
    <div class="box-body">
        @if (count($public_guard['events']) === 0)
            <p class="text-muted">Belum ada berkas yang perlu dipulihkan.</p>
        @else
            <div class="alert alert-warning">
                Berkas <code>public/</code> pernah ditimpa. Periksa isi <code>public/</code> untuk berkas yang tidak dikenal, karena berkas bridge bisa saja sudah tertinggal.
            </div>
            <table class="table table-bordered table-striped">
                <thead><tr><th>Waktu</th><th>Berkas</th><th>Pemicu</th><th>Tema aktif</th><th>Hasil</th></tr></thead>
                <tbody>
                    @foreach ($public_guard['events'] as $k)
                        <tr>
                            <td>{{ $k['last_time'] ?? ($k['time'] ?? '-') }}@if (($k['count'] ?? 1) > 1) <span class="text-muted">({{ $k['count'] }}x sejak {{ $k['time'] ?? '-' }})</span>@endif</td>
                            <td><code>public/{{ $k['file'] ?? '-' }}</code></td>
                            <td>{{ $k['trigger'] ?? '-' }}</td>
                            <td>{{ $k['theme'] ?? '-' }}</td>
                            <td>{{ ($k['failed'] ?? false) ? 'Gagal: ' . ($k['message'] ?? '') : 'Dipulihkan' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
