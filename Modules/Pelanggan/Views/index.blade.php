@extends('admin.layouts.index')

@section('title')
    <h1>{{ $title }}</h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $title }}</li>
@endsection

@push('css')
    <style>
        .small-box {
            border-radius: 5px;
            padding-bottom: 27px;
        }

        .small-box .icon {
            top: -5px;
        }

        .small-box:hover {
            transform: scale(1.01);
            transition: 0.3s;
        }

        /* Pesan validasi token (Swal.showValidationMessage) di form Ganti Token
           -- bawaan SweetAlert2 abu-abu pucat & kecil, gampang tak terlihat.
           Dipertegas memakai warna "danger" AdminLTE agar konsisten dengan
           gerbang error lain di halaman ini. */
        .swal2-popup .pesan-validasi-gagal {
            background-color: #dd4b39 !important;
            color: #fff !important;
            font-size: 1.05em !important;
            font-weight: 600 !important;
            padding: 0.75em !important;
        }

        .swal2-popup .pesan-validasi-gagal::before {
            background-color: #fff !important;
            color: #dd4b39 !important;
        }
    </style>
@endpush

@section('content')

    @if ($error_premium)
        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="icon fa fa-ban"></i>
                @if ($error_premium)
                    <h3 class="box-title">{{ $error_premium }}</>
                    @elseif (!cek_koneksi_internet())
                        <h3 class="box-title">Tidak Terhubung Dengan Jaringan</h3>
                @endif
            </div>
            <div class="box-body">
                @if ($pesan)
                    <div class="callout callout-warning">
                        <h5>{{ $pesan }}</h5>
                    </div>
                @elseif (is_null($response))
                    <div class="callout callout-danger">
                        <h5>Data Gagal Dimuat, Harap Periksa Di bawah Ini</h5>
                        <h5>Fitur ini khusus untuk pelanggan Layanan {{ config_item('nama_lembaga') }} (hosting, Fitur Premium, dll) untuk menampilkan status langganan.</h5>
                        <li>Periksan koneksi anda, pastikan sudah terhubung dengan jaringan internet.</li>
                        <li>Periksa logs error terakhir di menu <strong><a href="{{ site_url('info_sistem#log_viewer') }}" style="text-decoration:none;">Pengaturan > Info Sistem > Logs</a></strong></li>
                        <li>Belum berlangganan Layanan {{ config_item('nama_lembaga') }}? Pesan layanan secara mandiri melalui <a href="{{ config_item('server_layanan') }}/pendaftaran-layanan" style="text-decoration:none;" target="_blank" rel="noopener noreferrer"><strong>Pendaftaran Layanan</strong></a>.</li>
                        <li>Sudah punya Token pelanggan? Masukkan di [Layanan {{ config_item('nama_lembaga') }} Token] lewat <a href="#" style="text-decoration:none;" class="atur-token"><strong>Pengaturan Pelanggan&nbsp;(<i class="fa fa-gear"></i>)</strong></a></li>
                        <li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($response)
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h4>PEMESANAN LAYANAN</h4>
                        <h6 style="padding-left: 10px;">
                            @foreach ($response->body->pemesanan as $pemesanan)
                                @if ($pemesanan->status_pemesanan == 'aktif')
                                    @foreach ($pemesanan->layanan as $layanan)
                                        @php
                                            if (preg_match('/Hosting|Domain/', $layanan->nama) && !file_exists('mitra')) {
                                                fopen('mitra', 'wb');
                                            }
                                        @endphp
                                        <li>{{ $layanan->nama }}</li>
                                    @endforeach
                                @endif
                            @endforeach
                        </h6>
                    </div>
                    <div class="icon">
                        <i class="ion ion-card"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h4>STATUS PELANGGAN</h4>
                        <h5> {{ ucwords($response->body->status_langganan) }}</h5>
                    </div>
                    <div class="icon">
                        <i class="ion-person-add"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h4>MULAI BERLANGGANAN</h4>
                        <h5>{{ tgl_indo($response->body->tanggal_berlangganan->mulai) }} (Premium)</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-unlocked"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h4>AKHIR BERLANGGANAN</h4>
                        <h5>{{ tgl_indo($response->body->tanggal_berlangganan->akhir) }} (Premium)</h5>
                    </div>
                    <div class="icon">
                        <i class="ion ion-locked"></i>
                    </div>
                </div>
            </div>

            {{-- Notifikasi Layanan Akan Kadaluarsa atau Sudah Kadaluarsa --}}
            @php
                $hariNotifikasi = 30;
                $notifikasiLayanan = collect();
            @endphp

            {{-- Kumpulkan semua layanan dari pemesanan aktif untuk diproses notifikasi --}}
            @foreach ($response->body->pemesanan as $pemesanan)
                {{-- Proses hanya pemesanan dengan status aktif --}}
                @if ($pemesanan->status_pemesanan !== 'aktif')
                    @continue
                @endif

                @php
                    // Filter layanan non-premium (kategori_id != 4)
                    $pemesananBukanPremium = collect($pemesanan->layanan ?? [])
                        ->filter(static fn($q) => $q->kategori_id != 4);

                    // Filter layanan yang memiliki tanggal akhir valid dan tidak unlimited
                    $semuaLayanan = $pemesananBukanPremium->filter(function($layanan) {
                        // Skip layanan tanpa tanggal akhir atau dengan tanggal unlimited
                        if (!isset($layanan->tanggal_akhir) || $layanan->tanggal_akhir == '9999-12-31') {
                            return false;
                        }

                        // Validasi format tanggal untuk mencegah error di tahap processing
                        try {
                            \Illuminate\Support\Carbon::parse($layanan->tanggal_akhir);
                            return true;
                        } catch (\Exception $e) {
                            return false;
                        }
                    });

                    // Transformasi data layanan dengan perhitungan sisa hari
                    $layananDiproses = $semuaLayanan->map(function($layanan) use ($pemesanan) {
                        $today = \Illuminate\Support\Carbon::now()->startOfDay();
                        $tanggalAkhir = \Illuminate\Support\Carbon::parse($layanan->tanggal_akhir)->startOfDay();
                        $sisaHari = (int) $today->diffInDays($tanggalAkhir, false); 

                        return [
                            'layanan' => $layanan,
                            'pemesanan' => $pemesanan,
                            'sisa_hari' => $sisaHari,
                            'tanggal_akhir' => $tanggalAkhir,
                            'sudah_kadaluarsa' => $sisaHari < 0,
                            'tanggal_akhir_value' => strtotime($layanan->tanggal_akhir)
                        ];
                    });

                    // Akumulasi layanan dari semua pemesanan
                    $notifikasiLayanan = $notifikasiLayanan->merge($layananDiproses);
                @endphp
            @endforeach

            {{-- Deduplikasi layanan: hanya pertahankan entry terbaru untuk setiap nama layanan --}}
            @php
                if ($notifikasiLayanan->isNotEmpty()) {
                    // Deduplication mapping untuk memastikan satu layanan hanya ditampilkan sekali dengan versi terbaru
                    $dedupMap = [];

                    foreach ($notifikasiLayanan as $item) {
                        $namaLayanan = $item['layanan']->nama;
                        $tanggalValue = $item['tanggal_akhir_value'];

                        // Pertahankan entry dengan tanggal akhir terbaru untuk layanan yang sama
                        if (!isset($dedupMap[$namaLayanan]) || $tanggalValue > $dedupMap[$namaLayanan]['tanggal_akhir_value']) {
                            $dedupMap[$namaLayanan] = $item;
                        }
                    }

                    // Konversi hasil deduplication kembali ke collection
                    $notifikasiLayanan = collect($dedupMap);

                    // Filter notifikasi yang akan ditampilkan berdasarkan rentang waktu 30 hari
                    $notifikasiLayanan = $notifikasiLayanan->filter(function($item) use ($hariNotifikasi) {
                        // Tampilkan notifikasi hanya jika layanan dalam rentang 30 hari sebelum dan sesudah kadaluarsa
                        return $item['sisa_hari'] > -$hariNotifikasi && $item['sisa_hari'] <= $hariNotifikasi;
                    });
                }
            @endphp

            {{-- Tampilkan Notifikasi --}}
            @if($notifikasiLayanan->isNotEmpty())
                @foreach($notifikasiLayanan->sortBy('sisa_hari') as $notif)
                    @php
                        $layanan = $notif['layanan'];
                        $pemesanan = $notif['pemesanan'];
                        $sisaHari = $notif['sisa_hari'];
                        $tanggalAkhir = $notif['tanggal_akhir'];
                        $sudahKadaluarsa = $notif['sudah_kadaluarsa'];

                        // Tentukan warna alert berdasarkan status
                        if ($sudahKadaluarsa) {
                            $alertClass = 'alert-warning';
                            $iconClass = 'fa-times-circle';
                            $judulStatus = 'LAYANAN KADALUARSA';
                            $pesanStatus = 'telah berakhir sejak ' . abs($sisaHari) . ' hari yang lalu';
                        } elseif ($sisaHari <= 30) {
                            $alertClass = 'alert-warning';
                            $iconClass = 'fa-exclamation-circle';
                            $judulStatus = 'PEMBERITAHUAN MENDESAK';
                            $pesanStatus = 'akan berakhir dalam waktu ' . $sisaHari . ' hari';
                        } else {
                            $alertClass = 'alert-info';
                            $iconClass = 'fa-exclamation-triangle';
                            $judulStatus = 'PEMBERITAHUAN';
                            $pesanStatus = 'akan berakhir dalam waktu ' . $sisaHari . ' hari';
                        }
                    @endphp

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="alert {{ $alertClass }} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa {{ $iconClass }}"></i> {{ $judulStatus }}</h4>
                            <p>
                                Layanan <strong>{{ $layanan->nama }}</strong>
                                <strong>{{ $pesanStatus }}</strong>
                            </p>
                            <div style="margin-top: 10px; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 3px;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td width="150"><i class="fa fa-tag"></i> Kategori</td>
                                        <td>: {{ $layanan->nama_kategori }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-calendar"></i> Tanggal Berakhir</td>
                                        <td>: {{ tgl_indo($layanan->tanggal_akhir) }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-file-text-o"></i> Faktur</td>
                                        <td>: <code>{{ $pemesanan->faktur }}</code></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-clock-o"></i> Periode Pemesanan</td>
                                        <td>: {{ tgl_indo($pemesanan->tgl_mulai ?? 'N/A') }} - {{ tgl_indo($pemesanan->tgl_akhir ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-info-circle"></i> Info Perpanjangan</td>
                                        <td>:
                                            @if(($layanan->kategori_id ?? null) == 9 || ($layanan->nama_kategori ?? '') === 'Dasbor SiapPakai')
                                                <em>Hubungi Pelaksana Layanan SiapPakai untuk informasi perpanjangan.</em>
                                            @else
                                                <em>Hubungi pelaksana layanan untuk informasi biaya.</em>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="margin-top: 15px;">
                                <i class="fa fa-info-circle"></i> <strong>Segera melakukan perpanjangan pemesanan.</strong>
                                <a href="{{ site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) }}"
                                class="btn btn-success btn-sm pull-right">
                                    <i class="fa fa-refresh"></i> Perpanjang Sekarang
                                </a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if ($response->body->status_langganan === 'aktif' || $response->body->status_langganan === 'suspended' || $response->body->status_langganan === 'tidak aktif' || $response->body->status_langganan === 'menunggu verifikasi email')
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <i class="icon fa fa-info"></i>
                            <h3 class="box-title">Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="callout callout-warning">
                                <h5>Silakan lakukan pendaftaran kerja sama minimal hingga verifikasi email agar Anda dapat mencetak nota faktur.</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($response->body->status_langganan === 'menunggu verifikasi email')
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="icon fa fa-info"></i>
                    <h3 class="box-title">Status Registrasi</h3> <a href="{{ site_url('pelanggan/perbarui') }}" title="Perbarui" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>
                        Perbarui</a>
                </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <h5>Silakan cek email Anda untuk verifikasi, atau kirim ulang pendaftaran kerja sama menggunakan email aktif untuk menerima tautan verifikasi yang baru.</h5>
                    </div>
                </div>
            </div>
        @elseif ($response->body->status_langganan === 'menunggu verifikasi pendaftaran')
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="icon fa fa-info"></i>
                    <h3 class="box-title">Status Registrasi</h3>
                </div>
                <div class="box-body">
                    <div class="callout callout-info">
                        <h5>Dokumen permohonan kerjasama Desa Anda sedang diperiksa oleh Pelaksana Layanan {{ config_item('nama_lembaga') }}.</h5>
                    </div>
                </div>
            </div>
        @endif
        <div class="box box-info">
            @if (can('u') || can('h'))
                <div class="box-header with-border">
                    <b>Rincian Pelanggan
                        @if (can('u'))
                            <a href="javascript:;" title="Perbarui" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block perbarui"><i class="fa fa-refresh"></i> Perbarui</a>
                        @endif
                        @if (can('h'))
                            <a href="javascript:;" title="Ganti Token" class="btn btn-social btn-danger btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block ganti-token"><i class="fa fa-key"></i> Ganti Token</a>
                        @endif
                    </b>
                </div>
            @endif
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover tabel-rincian">
                        <tbody>
                            <tr>
                                <td width="20%">ID Pelanggan</td>
                                <td width="1">:</td>
                                <td>{{ $response->body->id }}</td>
                            </tr>
                            <tr>
                                <td>KODE {{ strtoupper(setting('sebutan_desa')) }}</td>
                                <td> : </td>
                                <td>{{ $response->body->desa->kode_desa }}</td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                                <td> : </td>
                                <td>
                                    {{ ucwords(strtolower(setting('sebutan_desa'))) }} {{ Str::FormatNamaWilayah($response->body->desa->nama_desa) }},
                                    {{ ucwords(strtolower(setting('sebutan_kecamatan'))) }} {{ Str::FormatNamaWilayah($response->body->desa->nama_kec) }},
                                    {{ ucwords(strtolower(setting('sebutan_kabupaten'))) }} {{ Str::FormatNamaWilayah($response->body->desa->nama_kab) }},
                                    Provinsi {{ Str::FormatNamaWilayah($response->body->desa->nama_prov) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Domain Desa</td>
                                <td> : </td>
                                <td>{{ $response->body->domain }}</td>
                            </tr>
                            <tr>
                                <td>Nama Kontak</td>
                                <td> : </td>
                                <td>
                                    @foreach ($response->body->kontak as $kontak)
                                        <li>{{ $kontak->nama }}</li>
                                    @endforeach
                                </td>
                            </tr>
                            @if (!config_item('demo_mode') && $response->body->token)
                                <tr>
                                    <td>Token</td>
                                    <td> : </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <textarea id="token" rows="4" cols="180" type="text" class="form-control" readonly><?= $response->body->token ?></textarea>
                                                </td>
                                                <td>
                                                    <div class="input-group-text"><a href="#" id="copy" title="Copy"><i class="fa fa-copy"></i></a></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="box box-info" id="box-pemesanan-premium">
            <div class="box-header with-border">
                <b>Rincian Pemesanan Premium</b>
                @if ($permohonan = session('permohonan'))
                    <p class="error">{{ $permohonan }}</p>
                @endif
                <br><br>
                <span class="text-danger">Info: Nota faktur dapat dicetak hanya untuk pembayaran yang sudah lunas dan telah melakukan pendaftaran kerjasama sampai verifikasi email.</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-hover tabel-daftar">
                        <thead class="bg-gray">
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Layanan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status Pemesanan</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 0; @endphp

                            @foreach ($response->body->pemesanan as $number => $pemesanan)
                                @php
                                    $pemesananPremium = 0;
                                    $sisaHariPremium = null;
                                    $perluPerpanjangPremium = false;
                                    foreach ($pemesanan->layanan as $layanan) {
                                        if ($layanan->kategori_id == 4) {
                                            $pemesananPremium++;
                                            if (isset($layanan->tanggal_akhir) && $layanan->tanggal_akhir !== '9999-12-31') {
                                                $sisaHariPremium = round((strtotime($layanan->tanggal_akhir) - time()) / 86400);
                                                if ($sisaHariPremium <= 30) {
                                                    $perluPerpanjangPremium = true;
                                                }
                                            }
                                        }
                                    }
                                @endphp

                                @if ($pemesananPremium > 0)
                                    @php $counter++; @endphp
                                @endif

                                <tr id="tbl-premium-{{ $number }}" style="{{ $pemesananPremium > 0 ? '' : 'display:none;' }}">
                                    <td class="padat">{{ $counter }}</td>
                                    <td class="aksi">
                                        @if (($pemesanan->status_pembayaran == 1 && $response->body->status_langganan === 'terdaftar') || $response->body->status_langganan === 'menunggu verifikasi pendaftaran' || $response->body->status_langganan === 'email telah terverifikasi')
                                            @if(($pemesanan->tampilkan_faktur ?? 1) && empty($pemesanan->mitra_id))
                                            <a target="_blank" href="{{ "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" }}" class="btn btn-social bg-purple btn-sm" title="Cetak Nota Faktur">
                                                <i class="fa fa-print"></i> Cetak Nota Faktur
                                            </a>
                                            @endif
                                        @endif
                                        @if ($perluPerpanjangPremium)
                                            <a href="{{ site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) }}" class="btn btn-social bg-green btn-sm" title="Perpanjang Layanan">
                                                <i class="fa fa-refresh"></i> Perpanjang
                                            </a>
                                        @endif
                                    </td>
                                    @php
                                        $namaLayanan = '-';
                                        $tanggalMulaiPremium = '-';
                                        $tanggalAkhirPremium = '-';
                                    @endphp

                                    @foreach ($pemesanan->layanan as $layanan)
                                        @if ($layanan->kategori_id == 4)
                                            @php
                                                $namaLayanan = '<a href="#" data-parent="#layanan" data-target="#layanan' . $layanan->id . '" data-toggle="modal"
                                                    class="mt-5 btn btn-social btn-info btn-sm" title="Klik untuk melihat ketentuan ' . e($layanan->nama) . '">
                                                    <i class="fa fa-info"></i> ' . e($layanan->nama) . e($layanan->number) . '
                                                </a><br>';
                                                $tanggalMulaiPremium = tgl_indo($layanan->tanggal_mulai);
                                                $tanggalAkhirPremium = tgl_indo($layanan->tanggal_akhir);
                                            @endphp
                                        @endif
                                    @endforeach

                                    <td>{!! $namaLayanan !!}</td>
                                    <td class="padat">{{ $tanggalMulaiPremium }}</td>
                                    <td class="padat">{{ $tanggalAkhirPremium }}</td>
                                    <td class="padat">
                                        @if ($sisaHariPremium !== null && $sisaHariPremium >= 0 && $sisaHariPremium <= 30)
                                            <span class="label label-warning">perlu diperpanjang</span>
                                        @else
                                            <span class="label label-{{ $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' }}">{{ $pemesanan->status_pemesanan }}</span>
                                        @endif
                                    </td>
                                    <td class="padat">
                                        <span class="label label-{{ $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' }}">{{ $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' }}</span>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php $pemesananLainnya = 0 @endphp
        <div class="box box-info" id="box-pemesanan-lainnya">
            <div class="box-header with-border">
                <b>Rincian Pemesanan Lainnya</b>
                @if ($permohonan = session('permohonan'))
                    <p class="error">{{ $permohonan }}</p>
                @endif
                <br><br>
                <span class="text-danger">Info: Nota faktur dapat dicetak hanya untuk pembayaran yang sudah lunas dan telah melakukan pendaftaran kerjasama sampai verifikasi email.</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-hover tabel-daftar">
                        <thead class="bg-gray">
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Layanan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status Pemesanan</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1; @endphp
                            @foreach ($response->body->pemesanan as $pemesanan)
                                @php
                                    $pemesananBukanPremium = collect($pemesanan->layanan)->filter(static fn($q) => $q->kategori_id != 4);
                                    $totalLayanan = $pemesananBukanPremium->count();
                                    $pemesananLainnya += $totalLayanan;

                                    $perluPerpanjangLainnya = false;
                                    foreach ($pemesananBukanPremium as $l) {
                                        if (isset($l->tanggal_akhir) && $l->tanggal_akhir !== '9999-12-31') {
                                            $sHari = round((strtotime($l->tanggal_akhir) - time()) / 86400);
                                            if ($sHari <= 30) {
                                                $perluPerpanjangLainnya = true;
                                                break;
                                            }
                                        }
                                    }
                                @endphp

                                @if ($totalLayanan > 0)
                                    @foreach ($pemesananBukanPremium as $layanan)
                                        <tr>
                                            @if ($loop->first)
                                                <td rowspan="{{ $totalLayanan }}" class="padat">{{ $index }}</td>
                                                <td rowspan="{{ $totalLayanan }}" class="aksi">
                                                    @if (($pemesanan->status_pembayaran == 1 && $response->body->status_langganan === 'terdaftar') || $response->body->status_langganan === 'menunggu verifikasi pendaftaran' || $response->body->status_langganan === 'email telah terverifikasi')
                                                        @if(($pemesanan->tampilkan_faktur ?? 1) && empty($pemesanan->mitra_id))
                                                        <a target="_blank" href="{{ "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" }}" class="btn btn-social bg-purple btn-sm" title="Cetak Nota Faktur">
                                                            <i class="fa fa-print"></i> Cetak Nota Faktur
                                                        </a>
                                                        @endif
                                                    @endif
                                                    @if ($perluPerpanjangLainnya)
                                                        <a href="{{ site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) }}" class="btn btn-social bg-green btn-sm" title="Perpanjang Layanan">
                                                            <i class="fa fa-refresh"></i> Perpanjang
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                <a href="#" data-parent="#layanan" data-target="{{ '#layanan' . $layanan->id }}" data-toggle="modal" class="mt-5 btn btn-social btn-info btn-sm" title="Klik untuk melihat ketentuan {{ $layanan->nama }}">
                                                    <i class="fa fa-info"></i> {{ $layanan->nama }}
                                                </a>
                                            </td>
                                            <td class="padat">{{ tgl_indo($layanan->tanggal_mulai) }}</td>
                                            <td class="padat">{{ tgl_indo($layanan->tanggal_akhir) }}</td>
                                            <td class="padat">
                                                @php
                                                    $sisaHariLayanan = isset($layanan->tanggal_akhir) && $layanan->tanggal_akhir !== '9999-12-31'
                                                        ? round((strtotime($layanan->tanggal_akhir) - time()) / 86400)
                                                        : null;
                                                @endphp
                                                @if ($sisaHariLayanan !== null && $sisaHariLayanan >= 0 && $sisaHariLayanan <= 30)
                                                    <span class="label label-warning">perlu diperpanjang</span>
                                                @else
                                                    <span class="label label-{{ $layanan->tanggal_akhir >= date('Y-m-d') ? 'success' : 'danger' }}">
                                                        {{ $layanan->tanggal_akhir >= date('Y-m-d') ? 'aktif' : 'tidak aktif' }}
                                                    </span>
                                                @endif
                                            </td>
                                            @if ($loop->first)
                                                <td rowspan="{{ $totalLayanan }}" class="padat">
                                                    <span class="label label-{{ $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' }}">
                                                        {{ $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' }}
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @php $index++; @endphp
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if ($pemesananLainnya == 0)
            {!! '<style>#box-pemesanan-lainnya { display:none;}</style>' !!}
        @endif

        <div id="layanan">
            @foreach ($response->body->pemesanan as $pemesanan)
                @foreach ($pemesanan->layanan as $layanan)
                    <div class="modal fade" id="layanan{{ $layanan->id }}" style="">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">Ketentuan Layanan</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <div class="text-center">
                                                <b>Ketentuan {{ $layanan->nama }}
                                                @if(($pemesanan->tampilkan_faktur ?? 1) && empty($pemesanan->mitra_id))
                                                    ( {{ rupiah($layanan->harga) }} )
                                                @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            {!! $layanan->ketentuan ?? 'Belum tersedia' !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    @endif

@endsection

@include('admin.layouts.components.asset_moment')

@push('scripts')
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">

    <script type="text/javascript">
        var token_layanan = @json(config_item('demo_mode') ? '' : $list_setting->firstWhere('key', 'layanan_opendesa_token')?->value);
        $('#copy').on('click', function() {
            $('#token').select();
            document.execCommand('copy');
        });

        // Dipakai bersama oleh ikon gear (.atur-token) dan tombol "Ganti Token"
        // (.ganti-token) -- keduanya harus berperilaku identik: tampilkan token
        // yang tersimpan saat ini, validasi token baru SEBELUM menimpa yang lama,
        // dan jika gagal, token lama tetap utuh (tidak pernah dikosongkan lebih
        // dulu) sementara kesalahan ditampilkan di tempat (tanpa reload/redirect
        // halaman).
        $('.atur-token, .ganti-token').click(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Pengaturan Pelanggan',
                text: 'Layanan ' + `<?= config_item('nama_lembaga') ?>` + ' Token',
                customClass: {
                    popup: 'swal-lg',
                    validationMessage: 'pesan-validasi-gagal',
                },
                input: 'textarea',
                inputValue: token_layanan,
                inputAttributes: {
                    inputPlaceholder: 'Token pelanggan Layanan ' + `<?= config_item('nama_lembaga') ?>`,
                },
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                confirmButtonText: 'Simpan',
                showLoaderOnConfirm: true,
                preConfirm: (token) => {
                    token = (token || '').trim();

                    // Validasi BENTUK token dulu, sebelum diuraikan. Teks bebas (mis. sebuah
                    // URL) bukan JWT -- parseJwt()/atob() melemparkan exception yang tidak
                    // tertangani di sini, sehingga sebelumnya tombol Simpan macet berputar
                    // tanpa pesan apa pun sampai popup ditutup paksa.
                    //
                    // Ini SEKALIGUS gerbang keamanan: field hasil decode payload JWT dipakai
                    // di pesan Swal.showValidationMessage() di bawah, dan SweetAlert2
                    // me-render pesan itu sebagai HTML (bukan teks polos) -- payload token
                    // TIDAK PERNAH boleh diselipkan mentah ke pesan itu, karena JWT hanya
                    // di-decode (base64), bukan diverifikasi tanda tangannya di sisi klien,
                    // sehingga isinya sepenuhnya bisa direkayasa oleh siapa pun yang menempel
                    // "token" di form ini. Satu-satunya field yang diselipkan ke pesan
                    // (tanggal_berlangganan.akhir) divalidasi ketat dulu formatnya
                    // (YYYY-MM-DD) di bawah -- string sebebas itu tidak bisa membawa markup.
                    if (!/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/.test(token)) {
                        Swal.showValidationMessage(
                            'Format token tidak valid. Token pelanggan harus berupa JWT (tiga bagian dipisahkan tanda titik), bukan tautan atau teks lain. Token sebelumnya tetap tersimpan dan tidak berubah.'
                        )
                        return;
                    }

                    var parse_token;
                    try {
                        parse_token = parseJwt(token);
                    } catch (e) {
                        console.error('Gagal mengurai token:', e);
                        Swal.showValidationMessage(
                            'Token tidak dapat diuraikan (format JWT rusak/tidak lengkap). Token sebelumnya tetap tersimpan dan tidak berubah.'
                        )
                        return;
                    }

                    var akhirBerlangganan = parse_token && parse_token.tanggal_berlangganan && parse_token.tanggal_berlangganan.akhir;
                    var tanggalValid = typeof akhirBerlangganan === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(akhirBerlangganan);

                    if (!tanggalValid) {
                        Swal.showValidationMessage(
                            'Token tidak berisi data langganan yang dikenali. Pastikan Anda menyalin token pelanggan yang benar. Token sebelumnya tetap tersimpan dan tidak berubah.'
                        )
                        return;
                    }

                    var ambilversi = "<?= substr(str_replace('.', '', AmbilVersi()), 0, 4) ?>";
                    var ambiltanggal = (akhirBerlangganan.replace('-', '')).substr(2, 4);
                    if (ambilversi != ambiltanggal) {
                        if (moment(akhirBerlangganan, 'YYYY-MM-DD').diff(moment()) < 0) {

                            Swal.showValidationMessage(
                                `Token Berlangganan sudah berakhir. Tanggal berlangganan sampai : ${akhirBerlangganan}. Token sebelumnya tetap tersimpan dan tidak berubah.`
                            )
                            return;
                        }
                    }

                    // Token dikirim ke backend sebagai kredensial; backend
                    // (PelangganService::refreshLangganan()) yang melakukan panggilan
                    // server-to-server ke server layanan dan verifikasi signature-nya --
                    // bukan browser ini (lihat CWE-345).
                    return $.ajax({
                            url: `${SITE_URL}pelanggan/pemesanan`,
                            type: 'Post',
                            dataType: 'json',
                            data: {
                                token: token
                            },
                        })
                        .then(
                            (response) => response,
                            (jqXHR) => {
                                Swal.showValidationMessage(
                                    jqXHR.responseJSON?.message || 'Gagal menyimpan token. Token sebelumnya tetap tersimpan dan tidak berubah. Silakan coba lagi.'
                                )
                            }
                        )
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    let response = result.value
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            timer: 2000,
                            text: response.message || 'Token berhasil tersimpan.',
                        }).then((result) => {
                            window.location.replace('pelanggan');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Gagal menyimpan token. Token sebelumnya tetap tersimpan dan tidak berubah.',
                        });
                    }
                }
            })
        });

        $('.perbarui').click(function(event) {
            Swal.fire({
                title: 'Sedang Memproses',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
            // Backend (PelangganService::refreshLangganan()) memakai token yang sudah
            // tersimpan dan melakukan panggilan server-to-server sendiri ke server
            // layanan -- tidak perlu browser memanggil server layanan langsung
            // (lihat CWE-345).
            $.ajax({
                    url: `${SITE_URL}pelanggan/pemesanan`,
                    type: 'Post',
                    dataType: 'json',
                })
                .done(function(result) {
                    if (result.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: result.message || 'Terjadi kesalahan saat memperbarui data.'
                        })
                        return
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message || 'Data berhasil diperbarui.',
                        timer: 2000,
                    })
                    window.location.replace(`${SITE_URL}pelanggan`);
                })
                .fail(function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: e.responseJSON?.message || 'Terjadi kesalahan saat memperbarui data.'
                    })
                });
        });
    </script>
@endpush
