<div class="tab-pane active">
    <div class="callout callout-warning">
        <h4><i class="fa fa-flask"></i> Mode Pengembangan — bukan bagian rilis</h4>
        <p style="margin-bottom:0">
            Bursa paket lokal adalah <strong>gudang ZIP paket</strong> (seperti Layanan). Isi lewat
            <strong>Daftarkan paket</strong> di bawah, lalu pilih sumber <em>Bursa paket lokal</em> agar seluruh
            tab (Paket Tersedia, Form Pendaftaran, Riwayat Pemesanan) beroperasi atasnya — untuk menguji alur
            ambil/lepas (get/release) tanpa server Layanan.
        </p>
    </div>

    {!! form_open($form_action, 'id="form-sumber" class="form-horizontal"') !!}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Sumber paket</h3>
            <div class="pull-right">
                Aktif:
                @if ($mode === 'lokal')
                    <span class="label label-warning">Bursa paket lokal</span>
                @elseif ($mode === 'lokal-layanan')
                    <span class="label label-primary">Layanan lokal (Herd)</span>
                @elseif ($mode === 'staging')
                    <span class="label label-info">Layanan staging</span>
                @else
                    <span class="label label-default">Layanan produksi</span>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Sumber</label>
                <div class="col-sm-9">
                    <div class="radio" style="margin-top:5px">
                        <label>
                            <input type="radio" name="sumber" value="produksi" {{ $mode === 'produksi' ? 'checked' : '' }}>
                            Layanan produksi — server nyata
                            <code>{{ $server_layanan !== '' ? $server_layanan : '(default)' }}</code>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="sumber" value="staging" {{ $mode === 'staging' ? 'checked' : '' }}>
                            Layanan staging — verifikasi sebelum rilis
                            <code>{{ $staging_url }}</code>
                            <small class="text-muted">(token Layanan asli digunakan; pemeriksaan premium <strong>tidak</strong> di-bypass)</small>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="sumber" value="lokal-layanan" {{ $mode === 'lokal-layanan' ? 'checked' : '' }}>
                            Layanan lokal (Herd) — server Laravel <code>Layanan_OpenDESA</code> nyata di mesin ini
                            <code>{{ $lokal_layanan_url }}</code>
                            <small class="text-muted">(server sungguhan, dikuasai penuh oleh Anda — untuk menguji endpoint `refaktor-oss` end-to-end saat digarap; token Layanan asli tetap dibutuhkan, pemeriksaan premium <strong>tidak</strong> di-bypass)</small>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="sumber" value="lokal" {{ $mode === 'lokal' ? 'checked' : '' }}>
                            Bursa paket lokal — gudang ZIP tanpa server eksternal
                            <small class="text-muted">(token premium di-bypass; cocok untuk iterasi cepat)</small>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @if (can('u'))
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Terapkan sumber</button>
            </div>
        @endif
    </div>
    {!! form_close() !!}

    @if (can('u'))
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Data langganan pelanggan (simulasi Layanan)</h3>
                <div class="pull-right">
                    Status:
                    @if ($langganan_aktif)
                        <span class="label label-success">Terisi</span>
                    @else
                        <span class="label label-default">Kosong</span>
                    @endif
                </div>
            </div>
            <div class="box-body">
                <p class="help-block" style="margin-bottom:10px">
                    Mengisi cache <code>status_langganan</code> dengan data pemesanan simulasi (Premium + Hosting)
                    yang dibangun dari identitas desa ini — cache yang sama yang dibaca halaman
                    <a href="{{ $link_pelanggan }}"><strong>Info Desa &raquo; Pelanggan</strong></a>. Dengan begitu
                    halaman menampilkan status langganan <em>seolah datang dari Layanan</em>, tanpa server Layanan nyata.
                </p>
            </div>
            <div class="box-footer">
                {!! form_open($form_langganan, 'style="display:inline"') !!}
                <button type="submit" class="btn btn-social btn-warning btn-sm">
                    <i class="fa fa-magic"></i> Isi data langganan simulasi
                </button>
                {!! form_close() !!}
                @if ($langganan_aktif)
                    {!! form_open($form_langganan_kosong, 'style="display:inline"') !!}
                    <button type="submit" class="btn btn-social btn-default btn-sm">
                        <i class="fa fa-eraser"></i> Kosongkan
                    </button>
                    {!! form_close() !!}
                @endif
            </div>
        </div>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan paket dari URL repo</h3>
            </div>
            {!! form_open($form_daftar, 'class="form-horizontal" id="form-daftar-url"') !!}
            <div class="box-body">
                <p class="help-block">
                    Mengunduh ZIP repo (mis. GitHub) ke bursa paket (<code>storage/app/dev-marketplace</code>) —
                    seperti Layanan menyimpan ZIP paket. Repo privat memakai <code>gh</code> (login GitHub Anda).
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">URL repo</label>
                    <div class="col-sm-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">https://github.com/</span>
                            <input type="text" name="url" class="form-control" required
                                   placeholder="OpenSID/modul-anjungan">
                        </div>
                        <small class="text-muted">Cukup <code>owner/repo</code> (atau tempel URL lengkap / bentuk <code>owner/repo/tree/&lt;ref&gt;</code>).</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ref (opsional)</label>
                    <div class="col-sm-4">
                        <input type="text" name="ref" class="form-control input-sm" placeholder="cabang/tag (mis. rilis-dev)">
                        <small class="text-muted">Kosong = cabang utama repo. Isi tag/cabang untuk versi spesifik.</small>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-success btn-sm pull-right"><i class="fa fa-download"></i> Unduh &amp; daftarkan</button>
            </div>
            {!! form_close() !!}
        </div>

        <div class="box box-default collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan dari folder lokal (opsional)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! form_open($form_daftar_lokal, 'class="form-horizontal" id="form-daftar-lokal"') !!}
            <div class="box-body">
                <p class="help-block">
                    Snapshot <em>working-tree</em> folder paket lokal (berisi <code>module.json</code>, termasuk perubahan
                    belum-commit) ke gudang. Berguna saat Anda sendiri sedang menggarap paket itu.
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Paket terpasang</label>
                    <div class="col-sm-6">
                        <select id="kandidat" class="form-control input-sm">
                            <option value="">-- pilih paket terpasang --</option>
                            @foreach ($kandidat as $k)
                                <option value="{{ $k['path'] }}">{{ $k['name'] }} ({{ $k['path'] }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Atau isi path folder paket di bawah.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Path folder paket</label>
                    <div class="col-sm-6">
                        <input type="text" name="path" id="path-daftar" class="form-control input-sm"
                               placeholder="/path/ke/modul-anjungan">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Snapshot &amp; daftarkan</button>
            </div>
            {!! form_close() !!}
        </div>
    @endif

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Isi bursa paket lokal (gudang ZIP)</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Paket</th>
                        <th>Versi</th>
                        <th>Sumber</th>
                        <th>Ref</th>
                        <th>Status</th>
                        <th>Didaftarkan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paket_repo as $m)
                        <tr>
                            <td><strong>{{ $m['name'] }}</strong></td>
                            <td>{{ $m['version'] !== '' ? $m['version'] : '-' }}</td>
                            <td><code>{{ $m['sumber'] !== '' ? $m['sumber'] : '-' }}</code></td>
                            <td>{{ $m['ref'] !== '' ? $m['ref'] : '-' }}</td>
                            <td>
                                @if ($m['installed'])
                                    <span class="label label-success">terpasang</span>
                                @else
                                    <span class="label label-default">belum</span>
                                @endif
                            </td>
                            <td>{{ $m['waktu'] !== '' ? $m['waktu'] : '-' }}</td>
                            <td class="text-right">
                                @if (can('u'))
                                    {!! form_open($form_perbarui, 'style="display:inline"') !!}
                                    <input type="hidden" name="name" value="{{ $m['name'] }}">
                                    <button type="submit" class="btn btn-warning btn-xs">
                                        <i class="fa fa-refresh"></i> Perbarui
                                    </button>
                                    {!! form_close() !!}
                                    {!! form_open($form_batal, 'style="display:inline" onsubmit="return confirm(\'Keluarkan paket ' . $m['name'] . ' dari bursa paket lokal?\')"') !!}
                                    <input type="hidden" name="name" value="{{ $m['name'] }}">
                                    <button type="submit" class="btn btn-danger btn-xs">
                                        <i class="fa fa-times"></i> Keluarkan
                                    </button>
                                    {!! form_close() !!}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="alert alert-warning" style="margin:10px">
                                    Bursa paket lokal kosong. Daftarkan paket dari URL repo di atas — mis.
                                    <code>https://github.com/OpenSID/modul-anjungan</code>.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <small class="text-muted">
                Setelah mode lokal aktif: pasang lewat <strong>Paket Tersedia</strong> atau <strong>Form Pendaftaran</strong>,
                lepas lewat <strong>Paket Terpasang → Hapus</strong>; jejaknya muncul di <strong>Riwayat Pemesanan</strong>.
            </small>
        </div>
    </div>
</div>

    @if (can('u'))
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan tema dari URL repo GitHub</h3>
            </div>
            {!! form_open($form_daftar_tema, 'class="form-horizontal" id="form-daftar-tema"') !!}
            <div class="box-body">
                <p class="help-block">
                    Mengunduh ZIP repo tema (mis. GitHub) ke gudang tema lokal
                    (<code>storage/app/dev-themes</code>). Repo privat memakai <code>gh</code>
                    (login GitHub Anda). Tema di gudang muncul di katalog <code>/api/v1/themes</code>
                    emulator Layanan saat mode lokal aktif.
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">URL repo</label>
                    <div class="col-sm-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">https://github.com/</span>
                            <input type="text" name="url" class="form-control" required
                                   placeholder="OpenSID/tema-silir">
                        </div>
                        <small class="text-muted">Cukup <code>owner/repo</code> atau tempel URL lengkap / <code>owner/repo/tree/&lt;ref&gt;</code>.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Ref (opsional)</label>
                    <div class="col-sm-4">
                        <input type="text" name="ref" class="form-control input-sm" placeholder="cabang/tag (mis. rilis-dev)">
                        <small class="text-muted">Kosong = cabang utama repo.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Alias <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" name="alias" class="form-control input-sm" required placeholder="silir">
                        <small class="text-muted">Slug tema (huruf, angka, tanda hubung). Dipakai sebagai nama berkas dan URL unduhan.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama tampilan</label>
                    <div class="col-sm-5">
                        <input type="text" name="nama" class="form-control input-sm" placeholder="Tema Silir">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Versi</label>
                    <div class="col-sm-3">
                        <input type="text" name="versi" class="form-control input-sm" placeholder="1.0.0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Deskripsi</label>
                    <div class="col-sm-7">
                        <input type="text" name="deskripsi" class="form-control input-sm" placeholder="Deskripsi singkat tema">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Harga</label>
                    <div class="col-sm-3">
                        <input type="text" name="harga" class="form-control input-sm" placeholder="Premium (kosong = Gratis)">
                        <small class="text-muted">Mis. <code>Premium</code> atau nominal. Kosong = gratis.</small>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-success btn-sm pull-right"><i class="fa fa-download"></i> Unduh &amp; daftarkan tema</button>
            </div>
            {!! form_close() !!}
        </div>
        <div class="box box-default collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title">Daftarkan tema dari folder lokal (opsional)</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! form_open($form_daftar_lokal_tema, 'class="form-horizontal" id="form-daftar-lokal-tema"') !!}
            <div class="box-body">
                <p class="help-block">
                    Buat snapshot working-tree folder tema lokal (berisi <code>resources/views/template.blade.php</code>)
                    ke gudang. Berguna untuk tema yang belum punya repo GitHub — mis. mengambil langsung dari
                    <code>desa/themes/batuah/</code>. ZIP dibuat dengan alias sebagai folder puncak agar instalasi
                    bersih di <code>desa/themes/&lt;alias&gt;/</code>.
                </p>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Tema terpasang</label>
                    <div class="col-sm-6">
                        <select id="kandidat-tema" class="form-control input-sm">
                            <option value="">-- pilih folder tema --</option>
                            @foreach ($kandidat_tema as $k)
                                <option value="{{ $k['path'] }}" data-alias="{{ $k['alias'] }}">{{ $k['alias'] }} ({{ $k['path'] }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Atau isi path folder tema di bawah.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Path folder tema</label>
                    <div class="col-sm-6">
                        <input type="text" name="path" id="path-daftar-tema" class="form-control input-sm"
                               placeholder="/path/ke/desa/themes/batuah">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Alias <span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" name="alias" id="alias-daftar-tema" class="form-control input-sm" required placeholder="batuah">
                        <small class="text-muted">Diisi otomatis dari pemilihan folder di atas.</small>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama tampilan</label>
                    <div class="col-sm-5">
                        <input type="text" name="nama" class="form-control input-sm" placeholder="Tema Batuah">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Versi</label>
                    <div class="col-sm-3">
                        <input type="text" name="versi" class="form-control input-sm" placeholder="1.0.0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Deskripsi</label>
                    <div class="col-sm-7">
                        <input type="text" name="deskripsi" class="form-control input-sm" placeholder="Deskripsi singkat tema">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Harga</label>
                    <div class="col-sm-3">
                        <input type="text" name="harga" class="form-control input-sm" placeholder="Premium (kosong = Gratis)">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-social btn-default btn-sm pull-right"><i class="fa fa-plus"></i> Snapshot &amp; daftarkan tema</button>
            </div>
            {!! form_close() !!}
        </div>
    @endif

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Isi gudang tema lokal</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Alias</th>
                        <th>Nama</th>
                        <th>Versi</th>
                        <th>Harga</th>
                        <th>Sumber</th>
                        <th>Didaftarkan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tema_repo as $t)
                        <tr>
                            <td><code>{{ $t['alias'] }}</code></td>
                            <td><strong>{{ $t['nama'] }}</strong></td>
                            <td>{{ $t['versi'] !== '' ? $t['versi'] : '-' }}</td>
                            <td>{{ $t['harga'] !== null ? $t['harga'] : 'Gratis' }}</td>
                            <td><code>{{ $t['sumber'] !== '' ? $t['sumber'] : '-' }}</code></td>
                            <td>{{ $t['waktu'] !== '' ? $t['waktu'] : '-' }}</td>
                            <td class="text-right">
                                @if (can('u'))
                                    {!! form_open($form_perbarui_tema, 'style="display:inline"') !!}
                                    <input type="hidden" name="alias" value="{{ $t['alias'] }}">
                                    <button type="submit" class="btn btn-warning btn-xs">
                                        <i class="fa fa-refresh"></i> Perbarui
                                    </button>
                                    {!! form_close() !!}
                                    {!! form_open($form_batal_tema, 'style="display:inline" onsubmit="return confirm(\'Keluarkan tema ' . $t['alias'] . ' dari gudang lokal?\')"') !!}
                                    <button type="submit" name="alias" value="{{ $t['alias'] }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-times"></i> Keluarkan
                                    </button>
                                    {!! form_close() !!}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="alert alert-warning" style="margin:10px">
                                    Gudang tema lokal kosong. Daftarkan tema berbayar dari URL repo di atas —
                                    mis. <code>OpenSID/tema-silir</code>. Tema terdaftar akan muncul di
                                    katalog <strong>Tema &raquo; Bursa</strong> saat mode lokal aktif.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="box-footer">
            <small class="text-muted">
                Tema terdaftar di sini muncul di endpoint <code>/api/v1/themes</code> emulator Layanan lokal.
                Pasang tema dari menu <strong>Admin Web &raquo; Tema</strong> saat mode lokal aktif.
            </small>
        </div>
    </div>

@push('scripts')
    <script>
        $(function () {
            $('#kandidat').on('change', function () {
                if ($(this).val()) {
                    $('#path-daftar').val($(this).val());
                }
            });

            $('#kandidat-tema').on('change', function () {
                var opt = $(this).find('option:selected');
                if (opt.val()) {
                    $('#path-daftar-tema').val(opt.val());
                    $('#alias-daftar-tema').val(opt.data('alias'));
                }
            });
        });
    </script>
@endpush
