<div class="tab-pane {{ $act_tab == 4 ? 'active' : '' }}">
    <div class="row">
        <div class="col-md-10">

<div class="alert alert-info">
    <i class="fa fa-info-circle"></i>
    <strong>Khusus pengembangan lokal.</strong> Tombol di bawah menambah/menghapus satu baris
    <code>config</code> "desa uji" sentinel di database yang sedang dipakai situs ini, supaya
    perilaku Database Gabungan (guard restore, tab <code>.sid</code>, dst.) bisa diverifikasi
    langsung tanpa server staging SiapPakai. <strong>Nonaktifkan</strong> menghapus data desa uji
    secara menyeluruh dan mengembalikan database ke kondisi standalone.
</div>

<p>
    Mode saat ini:
    @if ($isGabungan)
        <span class="label label-warning"><i class="fa fa-sitemap"></i> Multi-desa (Database Gabungan)</span>
    @else
        <span class="label label-default"><i class="fa fa-home"></i> Standalone</span>
    @endif
</p>

<table class="table table-condensed table-hover" style="max-width: 640px;">
    <thead>
        <tr>
            <th style="width: 60px;">ID</th>
            <th>Kode Desa</th>
            <th>Nama Desa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($configs as $config)
        <tr>
            <td>{{ $config->id }}</td>
            <td><code>{{ $config->kode_desa ?: '—' }}</code></td>
            <td>
                {{ $config->nama_desa ?: '—' }}
                @if ($config->kode_desa === \App\Services\Database\GabunganDevService::SENTINEL_KODE_DESA)
                    <span class="label label-warning">uji, dev</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="form-group" style="margin-top: 16px;">
    @if ($sentinelAktif)
        <form id="form-gabungan-dev-nonaktifkan" action="{{ route('database.gabungan_dev.nonaktifkan') }}" method="POST" style="display:inline;">
            <button type="submit" id="btn-nonaktifkan" class="btn btn-danger btn-social">
                <i class="fa fa-toggle-off"></i> Nonaktifkan mode multi-desa
            </button>
        </form>
    @else
        <form action="{{ route('database.gabungan_dev.aktifkan') }}" method="POST" style="display:inline;">
            <button type="submit" class="btn btn-primary btn-social" onclick="showLoadingForm('Menambahkan desa uji…')">
                <i class="fa fa-toggle-on"></i> Aktifkan mode multi-desa
            </button>
        </form>
    @endif
</div>

<script>
(function () {
    var btn  = document.getElementById('btn-nonaktifkan');
    var form = document.getElementById('form-gabungan-dev-nonaktifkan');
    if (!btn || !form) {
        return;
    }

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Nonaktifkan mode multi-desa?',
            html: 'Seluruh data desa uji akan <strong>dihapus permanen</strong> dari database ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Nonaktifkan',
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }
            showLoadingForm('Menghapus desa uji…');
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        });
    });
})();
</script>

        </div>
    </div>
</div>
