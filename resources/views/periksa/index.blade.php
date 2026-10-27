<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Periksa Database |
        {{ setting('admin_title') . ' ' . ucwords(setting('sebutan_desa') . ' ' . $header['nama_desa']) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    {{-- Bootstrap tetap dimuat untuk modal, DataTables dan Select2 --}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/dataTables.bootstrap.min.css') }}">
    {{-- Tailwind hasil build lokal (resources/periksa), tidak memerlukan koneksi internet --}}
    <link rel="stylesheet" href="{{ asset('css/periksa.min.css') }}">
</head>

@php
    $judulMasalah = [
        'keuangan_template_tidak_sama'      => 'Template keuangan tidak sesuai',
        'data_jabatan_tidak_ada'            => 'Data jabatan tidak tersedia',
        'autoincrement'                     => 'Tabel kehilangan auto increment',
        'collation'                         => 'Collation tabel tidak sesuai',
        'penduduk_tanpa_keluarga'           => 'Penduduk belum tercatat di data keluarga',
        'log_penduduk_tidak_sinkron'        => 'Log penduduk tidak sesuai status dasar',
        'log_penduduk_null'                 => 'Kode peristiwa log penduduk kosong',
        'log_penduduk_asing'                => 'Kode peristiwa log penduduk tidak terdaftar',
        'penduduk_tanpa_log_penduduk'       => 'Penduduk hidup tanpa log penduduk',
        'penduduk_tanpa_log_masuk'          => 'Penduduk punya log keluar tanpa log masuk',
        'log_keluarga_bermasalah'           => 'Keluarga tanpa log keluarga baru',
        'log_keluarga_ganda'                => 'Log keluarga ganda',
        'klasifikasi_surat_ganda'           => 'Kode klasifikasi surat ganda',
        'kepala_rtm_ganda'                  => 'Kepala rumah tangga ganda / tidak valid',
        'tgllahir_null_kosong'              => 'Tanggal lahir kosong',
        'menu_tanpa_parent'                 => 'Submenu tanpa menu utama',
        'data_null'                         => 'Data wajib penduduk kosong',
        'data_duplikatartikel'              => 'Slug artikel ganda',
        'artikel_kategori_orphan'           => 'Artikel dengan kategori tidak valid',
        'relasi_artikel_kategori_tidak_ada' => 'Relasi artikel ke kategori tidak ada',
        'artikel_user_orphan'               => 'Artikel dengan penulis tidak valid',
        'relasi_artikel_user_tidak_ada'     => 'Relasi artikel ke pengguna tidak ada',
        'data_cluster'                      => 'Nama dusun ganda',
        'suplemen_terdata_kosong'           => 'Terdata suplemen kosong',
        'kepala_keluarga_ganda'             => 'Kepala keluarga ganda / tidak valid',
        'keluarga_kepala_ganda'             => 'Kepala keluarga tercatat di lebih dari satu KK',
        'nik_kepala_bukan_kepala_keluarga'  => 'NIK kepala bukan berstatus kepala keluarga',
        'modul_asing'                       => 'Modul asing pada grup akses',
        'view'                              => 'View database tidak ada',
    ];

    $viewErrors = [
        'view_dokumen_hidup_tidak_ada'           => 'dokumen_hidup',
        'view_keluarga_aktif_tidak_ada'          => 'keluarga_aktif',
        'view_master_inventaris_tidak_ada'       => 'master_inventaris',
        'view_penduduk_hidup_tidak_ada'          => 'penduduk_hidup',
        'view_rekap_mutasi_inventaris_tidak_ada' => 'rekap_mutasi_inventaris',
    ];

    $masalah          = $masalah ?? [];
    $viewErrorsToShow = array_intersect_key($viewErrors, array_flip($masalah));

    // Masalah yang ditangani lewat formulir/halaman tersendiri, tidak ikut dihitung pada Perbaiki Semua
    $excludePerbaikiSemua = ['klasifikasi_surat_ganda', 'log_keluarga_ganda', 'log_penduduk_tidak_sinkron',
        'kepala_keluarga_ganda', 'tgllahir_null_kosong', 'suplemen_terdata_kosong'];
    $totalMasalah = count(array_diff($masalah, $excludePerbaikiSemua));

    // Daftar navigasi cepat; semua masalah view digabung menjadi satu kartu
    $navigasi = [];
    foreach ($masalah as $kodeMasalah) {
        $kodeNav = isset($viewErrors[$kodeMasalah]) ? 'view' : $kodeMasalah;
        if (isset($judulMasalah[$kodeNav])) {
            $navigasi[$kodeNav] = $judulMasalah[$kodeNav];
        }
    }

    $bawaanBackup = 'Pastikan sudah melakukan backup database/folder desa sebelum melanjutkan.';
@endphp

<body class="pk-body">
    <div id="periksa" class="tw-flex tw-min-h-screen tw-flex-col">

        <header class="tw-sticky tw-top-0 tw-z-30 tw-border-b tw-border-slate-800 tw-bg-slate-900/95 tw-backdrop-blur">
            <div class="tw-mx-auto tw-flex tw-h-14 tw-max-w-6xl tw-items-center tw-justify-between tw-gap-4 tw-px-4 sm:tw-px-6">
                <a href="{{ ci_route('/') }}" class="tw-flex tw-min-w-0 tw-items-center tw-gap-2.5 tw-text-white tw-no-underline hover:tw-text-white hover:tw-no-underline focus:tw-text-white">
                    <img src="{{ gambar_desa($header['logo']) }}" alt="Logo {{ ucwords(setting('sebutan_desa') . ' ' . $header['nama_desa']) }}" class="tw-h-9 tw-w-9 tw-shrink-0 tw-object-contain">
                    <span class="tw-truncate tw-text-base tw-font-semibold tw-tracking-tight">{{ ucwords(setting('sebutan_desa') . ' ' . $header['nama_desa']) }}</span>
                </a>
                <span class="tw-hidden tw-shrink-0 tw-items-center tw-gap-1.5 tw-text-sm tw-text-slate-300 sm:tw-flex">
                    <i class="fa fa-database"></i> Periksa Database
                </span>
            </div>
        </header>

        <main class="tw-mx-auto tw-w-full tw-max-w-6xl tw-flex-1 tw-space-y-6 tw-px-4 tw-py-8 sm:tw-px-6">

            @if (ci_auth()->id != super_admin())
                <div class="pk-card tw-mx-auto tw-max-w-xl tw-text-center">
                    <div class="tw-space-y-4 tw-px-6 tw-py-10">
                        <span class="pk-icon pk-icon-warning tw-mx-auto tw-h-14 tw-w-14 tw-text-2xl"><i class="fa fa-lock"></i></span>
                        <h1 class="tw-m-0 tw-text-xl tw-font-semibold tw-text-slate-800">Akses terbatas</h1>
                        <p class="tw-m-0 tw-text-sm tw-text-slate-500">Periksa database hanya diperbolehkan untuk Super Admin.</p>
                        <a href="{{ ci_route('siteman.logout') }}" class="pk-btn pk-btn-danger"><i class="fa fa-sign-out"></i> Kembali</a>
                    </div>
                </div>
            @else

                {{-- Ringkasan --}}
                <section class="tw-overflow-hidden tw-rounded-2xl tw-bg-gradient-to-br tw-from-indigo-600 tw-via-indigo-600 tw-to-violet-600 tw-text-white tw-shadow-lg">
                    <div class="tw-flex tw-flex-col tw-gap-6 tw-p-6 sm:tw-p-8 lg:tw-flex-row lg:tw-items-end lg:tw-justify-between">
                        <div class="tw-max-w-2xl tw-space-y-2">
                            <p class="tw-m-0 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-widest tw-text-indigo-200">Alat bantu Super Admin</p>
                            <h1 class="tw-m-0 tw-text-2xl tw-font-bold tw-tracking-tight sm:tw-text-3xl">Pemeriksaan Database</h1>
                            <p class="tw-m-0 tw-text-sm tw-leading-6 tw-text-indigo-100">
                                Halaman ini mendaftarkan masalah data yang terdeteksi. Setiap perbaikan dijalankan tanpa memuat
                                ulang halaman dan hasilnya langsung ditampilkan.
                            </p>
                        </div>
                        <dl class="tw-m-0 tw-grid tw-shrink-0 tw-grid-cols-2 tw-gap-3">
                            <div class="tw-rounded-xl tw-bg-white/10 tw-px-4 tw-py-3 tw-ring-1 tw-ring-inset tw-ring-white/20">
                                <dt class="tw-text-xs tw-font-medium tw-text-indigo-100">Masalah terdeteksi</dt>
                                <dd class="tw-m-0 tw-text-2xl tw-font-bold">{{ count($navigasi) }}</dd>
                            </div>
                            <div class="tw-rounded-xl tw-bg-white/10 tw-px-4 tw-py-3 tw-ring-1 tw-ring-inset tw-ring-white/20">
                                <dt class="tw-text-xs tw-font-medium tw-text-indigo-100">Versi</dt>
                                <dd class="tw-m-0 tw-text-2xl tw-font-bold">{{ AmbilVersi() }}</dd>
                            </div>
                        </dl>
                    </div>
                    @if ($totalMasalah)
                        <div class="tw-flex tw-flex-col tw-gap-3 tw-border-t tw-border-white/15 tw-bg-black/10 tw-px-6 tw-py-4 sm:tw-flex-row sm:tw-items-center sm:tw-justify-between sm:tw-px-8">
                            <p class="tw-m-0 tw-text-sm tw-text-indigo-100">
                                <i class="fa fa-info-circle"></i> Setelah diperbaiki, migrasi akan otomatis diulangi mulai dari versi
                                <b class="tw-text-white">{{ $migrasi_utk_diulang }}</b>.
                            </p>
                            <button type="button" class="pk-btn tw-bg-white tw-text-indigo-700 hover:tw-bg-indigo-50 hover:tw-text-indigo-800 focus:tw-text-indigo-700"
                                data-perbaiki="{{ ci_route('periksa.perbaiki') }}" data-method="POST"
                                data-judul="Perbaiki semua masalah?"
                                data-pesan="Semua masalah yang dapat diperbaiki otomatis akan dijalankan sekaligus. {{ $bawaanBackup }}">
                                <i class="fa fa-magic"></i> Perbaiki Semua
                            </button>
                        </div>
                    @endif
                </section>

                @if ($session->db_error)
                    <div class="pk-alert pk-alert-warning">
                        <i class="fa fa-exclamation-triangle tw-mt-1"></i>
                        <div class="tw-min-w-0 tw-space-y-1">
                            <p class="tw-font-semibold">{{ $session->heading }}</p>
                            <div>{!! $session->message !!}</div>
                            <p>Sepertinya database Anda tidak lengkap, yang mungkin disebabkan proses migrasi yang tidak sempurna.</p>
                            @if (ENVIRONMENT === 'development')
                                <pre class="tw-mt-2 tw-max-h-60 tw-overflow-auto tw-whitespace-pre-wrap tw-rounded-lg tw-bg-white/70 tw-p-3 tw-text-xs">{{ $session->message_query }}</pre>
                                <pre class="tw-max-h-60 tw-overflow-auto tw-whitespace-pre-wrap tw-rounded-lg tw-bg-white/70 tw-p-3 tw-text-xs">{{ $session->message_exception }}</pre>
                            @endif
                        </div>
                    </div>
                @endif

                @if (empty($masalah))
                    <div class="pk-card tw-text-center">
                        <div class="tw-space-y-4 tw-px-6 tw-py-12">
                            @if ($session->db_error)
                                <span class="pk-icon pk-icon-warning tw-mx-auto tw-h-14 tw-w-14 tw-text-2xl"><i class="fa fa-question"></i></span>
                                <h2 class="tw-m-0 tw-text-lg tw-font-semibold tw-text-slate-800">Penyebab masalah belum diketahui</h2>
                                <p class="tw-mx-auto tw-m-0 tw-max-w-lg tw-text-sm tw-text-slate-500">
                                    Harap laporkan kepada OpenDesa untuk dibantu lebih lanjut, sertakan juga isi berkas logs.
                                    Sementara Anda bisa masuk kembali.
                                </p>
                            @else
                                <span class="pk-icon pk-icon-success tw-mx-auto tw-h-14 tw-w-14 tw-text-2xl"><i class="fa fa-check"></i></span>
                                <h2 class="tw-m-0 tw-text-lg tw-font-semibold tw-text-slate-800">Tidak ada masalah terdeteksi</h2>
                                <p class="tw-mx-auto tw-m-0 tw-max-w-lg tw-text-sm tw-text-slate-500">Database desa Anda dalam kondisi baik.</p>
                            @endif
                            <a href="{{ ci_route('siteman') }}" class="pk-btn pk-btn-primary"><i class="fa fa-sign-in"></i> Masuk ke Admin</a>
                        </div>
                    </div>
                @else

                    {{-- Navigasi cepat --}}
                    <nav class="tw-flex tw-flex-wrap tw-gap-2" aria-label="Daftar masalah">
                        @foreach ($navigasi as $kodeNav => $judulNav)
                            <a href="#masalah-{{ $kodeNav }}" class="pk-chip">
                                <span class="tw-h-1.5 tw-w-1.5 tw-rounded-full tw-bg-rose-500"></span>{{ $judulNav }}
                            </a>
                        @endforeach
                    </nav>

                    @include('periksa.masalah.keuangan_template_tidak_sama')

                    @if (in_array('data_jabatan_tidak_ada', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'data_jabatan_tidak_ada', 'judul' => $judulMasalah['data_jabatan_tidak_ada'], 'jumlah' => count($data_jabatan ?? []), 'ikon' => 'fa-id-badge', 'jenis' => 'otomatis'])
                            <p>Jabatan kepala desa atau sekretaris desa tidak tersedia. Klik <b>Perbaiki Data</b> untuk mengembalikan data jabatan yang diperlukan.</p>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'data_jabatan_tidak_ada')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('autoincrement', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'autoincrement', 'judul' => $judulMasalah['autoincrement'], 'ikon' => 'fa-sort-numeric-asc', 'jenis' => 'otomatis'])
                            <p>Ada tabel yang kehilangan <code>AUTO_INCREMENT</code>, biasanya karena proses backup/restore yang tidak sempurna. Klik <b>Perbaiki Data</b> untuk mengembalikannya pada semua tabel yang memerlukan.</p>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'autoincrement')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('collation', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'collation', 'judul' => $judulMasalah['collation'], 'jumlah' => count($collation_table ?? []), 'ikon' => 'fa-font', 'jenis' => 'otomatis'])
                            <p>Collation tabel berikut bukan <code>{{ $collation }}</code>. Klik <b>Perbaiki Data</b> untuk menyeragamkan collation semua tabel menjadi <code>{{ $collation }}</code>.</p>
                            @if (! empty($collation_table))
                                <div class="pk-table-wrap">
                                    <table class="pk-table">
                                        <thead><tr><th>Tabel</th><th>Collation</th></tr></thead>
                                        <tbody>
                                            @foreach ($collation_table as $value)
                                                <tr><td>{{ $value->TABLE_NAME }}</td><td><code>{{ $value->TABLE_COLLATION }}</code></td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'collation')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('penduduk_tanpa_keluarga', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'penduduk_tanpa_keluarga', 'judul' => $judulMasalah['penduduk_tanpa_keluarga'], 'jumlah' => count($penduduk_tanpa_keluarga), 'ikon' => 'fa-users', 'jenis' => 'otomatis'])
                            <p>Klik <b>Perbaiki Data</b> untuk membuatkan keluarga dengan nomor KK sementara bagi penduduk berikut. Nomor KK sementara yang dibuat dapat dilihat di berkas logs.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th></tr></thead>
                                    <tbody>
                                        @foreach ($penduduk_tanpa_keluarga as $penduduk)
                                            <tr><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['nama'] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'penduduk_tanpa_keluarga')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('log_penduduk_tidak_sinkron', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'log_penduduk_tidak_sinkron', 'judul' => $judulMasalah['log_penduduk_tidak_sinkron'], 'jumlah' => count($log_penduduk_tidak_sinkron), 'ikon' => 'fa-random', 'jenis' => 'otomatis'])
                            <p>Peristiwa terakhir pada log penduduk tidak sesuai dengan status dasar penduduk. Klik <b>Perbaiki Data</b> untuk menghapus log penduduk yang terlanjur masuk sampai log terakhir sesuai dengan status dasarnya.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th><th>Peristiwa Log Terakhir</th><th>Status Dasar</th></tr></thead>
                                    <tbody>
                                        @foreach ($log_penduduk_tidak_sinkron as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ \App\Models\LogPenduduk::kodePeristiwaAll($penduduk['kode_peristiwa']) }}</td>
                                                <td><span class="pk-badge pk-badge-slate">{{ \App\Enums\StatusDasarEnum::all()[$penduduk['status_dasar']] ?? '-' }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'log_penduduk_tidak_sinkron')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('log_penduduk_null', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'log_penduduk_null', 'judul' => $judulMasalah['log_penduduk_null'], 'jumlah' => count($log_penduduk_null), 'ikon' => 'fa-file-o', 'jenis' => 'otomatis'])
                            <p>Klik <b>Perbaiki Data</b> untuk mengisi kode peristiwa yang kosong dengan nilai bawaan <b>5 (baru pindah masuk)</b>.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th><th>Kode Peristiwa</th></tr></thead>
                                    <tbody>
                                        @foreach ($log_penduduk_null as $penduduk)
                                            <tr><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['nama'] }}</td><td><span class="pk-badge pk-badge-slate">null</span></td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'log_penduduk_null')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('log_penduduk_asing', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'log_penduduk_asing', 'judul' => $judulMasalah['log_penduduk_asing'], 'jumlah' => count($log_penduduk_asing), 'ikon' => 'fa-question-circle', 'jenis' => 'otomatis'])
                            <p>Klik <b>Perbaiki Data</b> untuk menghapus log penduduk dengan kode peristiwa yang tidak terdaftar.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th><th>Kode Peristiwa</th></tr></thead>
                                    <tbody>
                                        @foreach ($log_penduduk_asing as $penduduk)
                                            <tr><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['nama'] }}</td><td><code>{{ $penduduk['kode_peristiwa'] }}</code></td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'log_penduduk_asing')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('penduduk_tanpa_log_penduduk', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'penduduk_tanpa_log_penduduk', 'judul' => $judulMasalah['penduduk_tanpa_log_penduduk'], 'jumlah' => count($penduduk_tanpa_log_penduduk), 'ikon' => 'fa-user-times', 'jenis' => 'otomatis'])
                            <p>Penduduk berikut berstatus hidup tetapi tidak memiliki riwayat peristiwa di log penduduk (biasanya karena data diubah langsung lewat database), sehingga tidak pernah terhitung di Buku Rekapitulasi Penduduk. Klik <b>Perbaiki Data</b> untuk menambahkan log <b>baru pindah masuk</b>.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th><th>Alamat</th></tr></thead>
                                    <tbody>
                                        @foreach ($penduduk_tanpa_log_penduduk as $penduduk)
                                            <tr><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['nama'] }}</td><td>{{ $penduduk['alamat_sekarang'] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'penduduk_tanpa_log_penduduk')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('penduduk_tanpa_log_masuk', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'penduduk_tanpa_log_masuk', 'judul' => $judulMasalah['penduduk_tanpa_log_masuk'], 'jumlah' => count($penduduk_tanpa_log_masuk), 'ikon' => 'fa-sign-out', 'jenis' => 'otomatis'])
                            <p>Penduduk berikut punya peristiwa keluar (mati/pindah/hilang) tetapi tidak punya peristiwa masuk (lahir/pindah masuk), sehingga mengurangi saldo Buku Rekapitulasi Penduduk tanpa pernah ditambahkan. Klik <b>Perbaiki Data</b> untuk menambahkan log <b>baru pindah masuk</b> sebelum log keluarnya.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>NIK</th><th>Nama</th><th>Alamat</th></tr></thead>
                                    <tbody>
                                        @foreach ($penduduk_tanpa_log_masuk as $penduduk)
                                            <tr><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['nama'] }}</td><td>{{ $penduduk['alamat_sekarang'] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'penduduk_tanpa_log_masuk')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('log_keluarga_bermasalah', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'log_keluarga_bermasalah', 'judul' => $judulMasalah['log_keluarga_bermasalah'], 'jumlah' => count($log_keluarga_bermasalah), 'ikon' => 'fa-home', 'jenis' => 'otomatis'])
                            <p>Klik <b>Perbaiki Data</b> untuk membuat log keluarga dengan peristiwa <b>1 (keluarga baru)</b> secara otomatis.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>No KK</th><th>Alamat</th></tr></thead>
                                    <tbody>
                                        @foreach ($log_keluarga_bermasalah as $keluarga)
                                            <tr><td>{{ $keluarga['no_kk'] }}</td><td>{{ $keluarga['alamat'] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'log_keluarga_bermasalah')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('log_keluarga_ganda', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'log_keluarga_ganda', 'judul' => $judulMasalah['log_keluarga_ganda'], 'jumlah' => count($log_keluarga_ganda), 'ikon' => 'fa-clone', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Keluarga berikut memiliki lebih dari satu log pada tanggal peristiwa yang sama. Buka log tiap keluarga lalu hapus log yang tidak diperlukan.</p>
                            <div id="info-log-keluarga-ganda"></div>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>No KK</th><th>Alamat</th><th class="tw-w-px">Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach ($log_keluarga_ganda as $keluarga)
                                            <tr data-log-keluarga-ganda="{{ $keluarga['id'] }}">
                                                <td>{{ $keluarga['no_kk'] }}</td>
                                                <td>{{ $keluarga['alamat'] }}</td>
                                                <td>
                                                    <button type="button" class="pk-btn pk-btn-ghost pk-btn-sm"
                                                        data-title="Catatan Peristiwa Keluarga {{ $keluarga['no_kk'] }} / {{ $keluarga['alamat'] }}"
                                                        data-url="periksaLogKeluarga"
                                                        data-ref="{{ json_encode(['keluarga' => $keluarga]) }}"
                                                        data-toggle="modal" data-target="#modal-kosong"><i class="fa fa-eye"></i> Lihat log</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('klasifikasi_surat_ganda', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'klasifikasi_surat_ganda', 'judul' => $judulMasalah['klasifikasi_surat_ganda'], 'jumlah' => count($klasifikasi_surat_ganda), 'ikon' => 'fa-envelope-o', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Hapus kode klasifikasi surat yang tidak diperlukan sehingga setiap kode hanya muncul sekali.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>Id</th><th>Kode</th><th>Nama</th><th>Uraian</th><th class="tw-w-px">Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach ($klasifikasi_surat_ganda as $klasifikasi)
                                            <tr>
                                                <td>{{ $klasifikasi['id'] }}</td>
                                                <td><code>{{ $klasifikasi['kode'] }}</code></td>
                                                <td>{{ $klasifikasi['nama'] }}</td>
                                                <td>{{ $klasifikasi['uraian'] }}</td>
                                                <td>
                                                    @include('periksa.partials.tombol', ['url' => ci_route('periksaKlasifikasiSurat.hapus'), 'method' => 'DELETE', 'params' => ['id' => $klasifikasi['id']], 'label' => 'Hapus', 'ikon' => 'fa-trash', 'kecil' => true, 'tetap' => true, 'pesan' => 'Klasifikasi surat ' . $klasifikasi['kode'] . ' (' . $klasifikasi['nama'] . ') akan dihapus.'])
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('kepala_rtm_ganda', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'kepala_rtm_ganda', 'judul' => $judulMasalah['kepala_rtm_ganda'], 'jumlah' => count($kepala_rtm_ganda), 'ikon' => 'fa-home', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Data kepala rumah tangga (RTM) berikut ganda atau tidak valid dan <b>perlu dihapus</b> agar tidak mengganggu keakuratan data.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>Nomor Rumah Tangga</th><th>Kepala Rumah Tangga</th><th>Tanggal Terdaftar</th><th class="tw-w-px">Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach ($kepala_rtm_ganda as $rtm)
                                            <tr>
                                                <td>{{ $rtm['no_kk'] }}</td>
                                                <td>{{ $rtm['nama_penduduk'] }}</td>
                                                <td>{{ $rtm['tgl_daftar'] }}</td>
                                                <td>
                                                    @include('periksa.partials.tombol', ['url' => ci_route('periksaKepalaRtm.hapus'), 'method' => 'DELETE', 'params' => ['id' => $rtm['id']], 'label' => 'Hapus', 'ikon' => 'fa-trash', 'kecil' => true, 'tetap' => true, 'pesan' => 'Rumah tangga ' . $rtm['no_kk'] . ' akan dihapus.'])
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('tgllahir_null_kosong', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'tgllahir_null_kosong', 'judul' => $judulMasalah['tgllahir_null_kosong'], 'jumlah' => count($tgllahir_null_kosong), 'ikon' => 'fa-birthday-cake', 'warna' => 'warning', 'jenis' => 'manual'])
                            <form id="form-tanggallahir" class="tw-space-y-4" data-form-perbaiki action="{{ ci_route('periksa.tanggallahir') }}" method="post">
                                <p>Isi tanggal lahir penduduk berikut lalu simpan.</p>
                                <div class="pk-table-wrap">
                                    <table class="pk-table">
                                        <thead><tr><th>Id</th><th>NIK</th><th>Nama</th><th>Tanggal Lahir</th></tr></thead>
                                        <tbody>
                                            @foreach ($tgllahir_null_kosong as $tgllahir)
                                                <tr>
                                                    <td>{{ $tgllahir['id'] }}</td>
                                                    <td>{{ $tgllahir['nik'] }}</td>
                                                    <td>{{ $tgllahir['nama'] }}</td>
                                                    <td>
                                                        <input type="hidden" name="id[]" value="{{ $tgllahir['id'] }}">
                                                        <input type="date" class="form-control" name="tanggallahir[]" value="{{ $tgllahir['tanggallahir'] }}" required>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tw-flex tw-justify-end">
                                    <button type="submit" class="pk-btn pk-btn-danger"><i class="fa fa-save"></i> Simpan Tanggal Lahir</button>
                                </div>
                            </form>
                        @endcomponent
                    @endif

                    @if (in_array('menu_tanpa_parent', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'menu_tanpa_parent', 'judul' => $judulMasalah['menu_tanpa_parent'], 'jumlah' => count($menu_tanpa_parent), 'ikon' => 'fa-sitemap', 'warna' => 'warning', 'jenis' => 'manual'])
                            <form id="form-menu-tanpa-parent" class="tw-space-y-4" data-form-perbaiki action="{{ ci_route('periksa.menu_tanpa_parent') }}" method="post">
                                <p>Pilih menu utama untuk setiap submenu berikut.</p>
                                <div class="pk-table-wrap">
                                    <table class="pk-table">
                                        <thead><tr><th>Id</th><th>Nama Submenu</th><th>Menu Utama</th></tr></thead>
                                        <tbody>
                                            @php $menuUtama = App\Models\Menu::where('parrent', 0)->get(); @endphp
                                            @foreach ($menu_tanpa_parent as $submenu)
                                                <tr>
                                                    <td>{{ $submenu['id'] }}</td>
                                                    <td>{{ $submenu['nama'] }}</td>
                                                    <td class="tw-min-w-[12rem]">
                                                        <input type="hidden" name="id[]" value="{{ $submenu['id'] }}">
                                                        <select class="form-control" name="parrent[]">
                                                            <option value="">Pilih Menu Utama</option>
                                                            @foreach ($menuUtama as $menu)
                                                                <option value="{{ $menu->id }}">{{ $menu->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tw-flex tw-justify-end">
                                    <button type="submit" class="pk-btn pk-btn-danger"><i class="fa fa-save"></i> Simpan Perubahan</button>
                                </div>
                            </form>
                        @endcomponent
                    @endif

                    @if (in_array('data_null', $masalah))
                        @php
                            $fields = [
                                'nama', 'nik', 'sex', 'kk_level', 'tempatlahir', 'tanggallahir',
                                'agama_id', 'pendidikan_kk_id', 'pekerjaan_id', 'golongan_darah_id',
                                'status_kawin', 'warganegara_id', 'nama_ayah', 'nama_ibu',
                                'dokumen_pasport', 'dokumen_kitas',
                            ];
                            $pilihanReferensi = [
                                'sex'               => ['Jenis Kelamin', \App\Enums\JenisKelaminEnum::all()],
                                'kk_level'          => ['Hubungan Dalam Keluarga', \App\Enums\SHDKEnum::all()],
                                'agama_id'          => ['Agama', \App\Enums\AgamaEnum::all()],
                                'pendidikan_kk_id'  => ['Pendidikan Dalam KK', \App\Enums\PendidikanKKEnum::all()],
                                'pekerjaan_id'      => ['Pekerjaan', \App\Enums\PekerjaanEnum::all()],
                                'golongan_darah_id' => ['Golongan Darah', \App\Enums\GolonganDarahEnum::all()],
                                'status_kawin'      => ['Status Perkawinan', \App\Enums\StatusKawinEnum::all()],
                                'warganegara_id'    => ['Status Warga Negara', \App\Enums\WargaNegaraEnum::all()],
                            ];
                            $isianTeks = [
                                'nama'            => ['Nama Lengkap (tanpa gelar)', 100, ''],
                                'nik'             => ['Nomor NIK', 16, ''],
                                'tempatlahir'     => ['Tempat Lahir', 100, ''],
                                'nama_ayah'       => ['Nama Ayah', 100, ''],
                                'nama_ibu'        => ['Nama Ibu', 100, ''],
                                'dokumen_pasport' => ['Nomor Paspor', 45, '-'],
                                'dokumen_kitas'   => ['Nomor KITAS/KITAP', 45, '-'],
                            ];
                        @endphp
                        @component('periksa.partials.kartu', ['kode' => 'data_null', 'judul' => $judulMasalah['data_null'], 'jumlah' => count($data_null), 'ikon' => 'fa-pencil-square-o', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Lengkapi isian wajib yang masih kosong untuk setiap penduduk berikut, lalu klik <b>Simpan</b> pada masing-masing penduduk.</p>
                            <div class="tw-max-h-[36rem] tw-space-y-3 tw-overflow-auto tw-pr-1">
                                @foreach ($data_null as $data)
                                    @php
                                        $null_fields = array_values(array_filter($fields, static fn ($f) => empty($data[$f])));
                                    @endphp
                                    @if (count($null_fields))
                                        <form class="pk-subpanel" data-form-perbaiki action="{{ ci_route('periksa.datanull') }}" method="post">
                                            <input type="hidden" name="id" value="{{ $data['id'] }}">
                                            <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2">
                                                <div class="tw-text-sm">
                                                    <span class="tw-font-semibold tw-text-slate-800">{{ $data['nama'] ?: '(tanpa nama)' }}</span>
                                                    <span class="tw-text-slate-500">&middot; NIK {{ $data['nik'] ?: '-' }} &middot; Id {{ $data['id'] }}</span>
                                                </div>
                                                <span class="pk-badge pk-badge-warning">{{ count($null_fields) }} isian kosong</span>
                                            </div>
                                            <div class="pk-form-grid">
                                                @foreach ($null_fields as $nf)
                                                    <div>
                                                        @if (isset($pilihanReferensi[$nf]))
                                                            <label class="pk-label">{{ $pilihanReferensi[$nf][0] }}</label>
                                                            <select class="form-control" name="{{ $nf }}">
                                                                <option value="">Pilih {{ $pilihanReferensi[$nf][0] }}</option>
                                                                @foreach ($pilihanReferensi[$nf][1] as $nilaiPilihan => $teksPilihan)
                                                                    <option value="{{ $nilaiPilihan }}">{{ $teksPilihan }}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($nf === 'tanggallahir')
                                                            <label class="pk-label">Tanggal Lahir</label>
                                                            <input class="form-control" name="tanggallahir" type="date">
                                                        @elseif (isset($isianTeks[$nf]))
                                                            <label class="pk-label">{{ $isianTeks[$nf][0] }}</label>
                                                            <input class="form-control" name="{{ $nf }}" type="text" maxlength="{{ $isianTeks[$nf][1] }}" placeholder="{{ $isianTeks[$nf][0] }}" value="{{ $isianTeks[$nf][2] }}">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="tw-flex tw-justify-end">
                                                <button type="submit" class="pk-btn pk-btn-danger pk-btn-sm"><i class="fa fa-save"></i> Simpan</button>
                                            </div>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('data_duplikatartikel', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'data_duplikatartikel', 'judul' => $judulMasalah['data_duplikatartikel'], 'jumlah' => count($data_duplikatartikel), 'ikon' => 'fa-newspaper-o', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Artikel berikut memiliki slug yang sama. Ganti slug tiap artikel agar unik.</p>
                            <div class="tw-space-y-3">
                                @foreach ($data_duplikatartikel as $data)
                                    <form class="pk-subpanel" data-form-perbaiki action="{{ ci_route('periksa.dataduplikatartikel') }}" method="post">
                                        <input type="hidden" name="id" value="{{ $data['id'] }}">
                                        <div class="tw-text-sm">
                                            <span class="tw-font-semibold tw-text-slate-800">Id {{ $data['id'] }}</span>
                                            <span class="tw-text-slate-500">&middot; <code>{{ $data['slug'] }}</code> &middot; {{ $data['tgl_upload'] }}</span>
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-gap-2 sm:tw-flex-row">
                                            <input name="slug" class="form-control tw-flex-1" maxlength="200" type="text" placeholder="Slug baru" required>
                                            <button type="submit" class="pk-btn pk-btn-danger"><i class="fa fa-save"></i> Simpan</button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('artikel_kategori_orphan', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'artikel_kategori_orphan', 'judul' => $judulMasalah['artikel_kategori_orphan'], 'jumlah' => count($artikel_kategori_orphan), 'ikon' => 'fa-tags', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p><code>id_kategori</code> pada artikel berikut tidak ada di tabel kategori. Pilih kategori pengganti (bawaan: dikosongkan). Setelah disimpan, relasi <code>artikel &rarr; kategori</code> akan ditambahkan kembali.</p>
                            <div class="pk-table-wrap" id="tbl-artikel-kategori-orphan">
                                <table class="pk-table">
                                    <thead><tr><th>Id</th><th>Judul</th><th>Kategori Tidak Valid</th><th>Kategori Pengganti</th><th>Tanggal Upload</th></tr></thead>
                                    <tbody>
                                        @foreach ($artikel_kategori_orphan as $item)
                                            <tr>
                                                <td>{{ $item['id'] }}</td>
                                                <td>{{ $item['judul'] }}</td>
                                                <td><span class="pk-badge pk-badge-danger">{{ $item['id_kategori'] }}</span></td>
                                                <td class="tw-min-w-[12rem]">
                                                    <select name="artikel_kategori[{{ $item['id'] }}]" class="form-control">
                                                        <option value="">&mdash; Kosongkan &mdash;</option>
                                                        @foreach ($daftar_kategori_aktif ?? [] as $k)
                                                            <option value="{{ $k['id'] }}">{{ $k['kategori'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ $item['tgl_upload'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => route('periksa.perbaiki.artikel_kategori_orphan'), 'sertakan' => '#tbl-artikel-kategori-orphan', 'pesan' => 'Artikel akan diperbarui sesuai pilihan kategori pengganti, lalu relasi artikel ke kategori ditambahkan kembali. ' . $bawaanBackup])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('relasi_artikel_kategori_tidak_ada', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'relasi_artikel_kategori_tidak_ada', 'judul' => $judulMasalah['relasi_artikel_kategori_tidak_ada'], 'ikon' => 'fa-link', 'jenis' => 'otomatis'])
                            <p>Foreign key <code>artikel.id_kategori &rarr; kategori.id</code> tidak ditemukan di database.</p>
                            @if (in_array('artikel_kategori_orphan', $masalah))
                                <div class="pk-alert pk-alert-warning"><i class="fa fa-exclamation-triangle tw-mt-1"></i><p>Selesaikan dulu perbaikan artikel dengan kategori tidak valid sebelum menambahkan relasi.</p></div>
                            @else
                                <p>Data artikel sudah bersih. Klik <b>Tambahkan Relasi</b> untuk menambahkan kembali foreign key tersebut.</p>
                                @slot('aksi')
                                    @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'relasi_artikel_kategori_tidak_ada'), 'label' => 'Tambahkan Relasi', 'ikon' => 'fa-link', 'warna' => 'warning'])
                                @endslot
                            @endif
                        @endcomponent
                    @endif

                    @if (in_array('artikel_user_orphan', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'artikel_user_orphan', 'judul' => $judulMasalah['artikel_user_orphan'], 'jumlah' => count($artikel_user_orphan), 'ikon' => 'fa-user-secret', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p><code>id_user</code> pada artikel berikut tidak ada di tabel pengguna. Pilih penulis pengganti (bawaan: dikosongkan). Setelah disimpan, relasi <code>artikel &rarr; user</code> akan ditambahkan kembali.</p>
                            <div class="pk-table-wrap" id="tbl-artikel-user-orphan">
                                <table class="pk-table">
                                    <thead><tr><th>Id</th><th>Judul</th><th>Penulis Tidak Valid</th><th>Penulis Pengganti</th><th>Tanggal Upload</th></tr></thead>
                                    <tbody>
                                        @foreach ($artikel_user_orphan as $item)
                                            <tr>
                                                <td>{{ $item['id'] }}</td>
                                                <td>{{ $item['judul'] }}</td>
                                                <td><span class="pk-badge pk-badge-danger">{{ $item['id_user'] }}</span></td>
                                                <td class="tw-min-w-[12rem]">
                                                    <select name="artikel_user[{{ $item['id'] }}]" class="form-control">
                                                        <option value="">&mdash; Kosongkan &mdash;</option>
                                                        @foreach ($daftar_user_aktif ?? [] as $u)
                                                            <option value="{{ $u['id'] }}">{{ $u['nama'] }}{{ $u['username'] ? ' (' . $u['username'] . ')' : '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>{{ $item['tgl_upload'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => route('periksa.perbaiki.artikel_user_orphan'), 'sertakan' => '#tbl-artikel-user-orphan', 'pesan' => 'Artikel akan diperbarui sesuai pilihan penulis pengganti, lalu relasi artikel ke pengguna ditambahkan kembali. ' . $bawaanBackup])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('relasi_artikel_user_tidak_ada', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'relasi_artikel_user_tidak_ada', 'judul' => $judulMasalah['relasi_artikel_user_tidak_ada'], 'ikon' => 'fa-link', 'jenis' => 'otomatis'])
                            <p>Foreign key <code>artikel.id_user &rarr; user.id</code> tidak ditemukan di database.</p>
                            @if (in_array('artikel_user_orphan', $masalah))
                                <div class="pk-alert pk-alert-warning"><i class="fa fa-exclamation-triangle tw-mt-1"></i><p>Selesaikan dulu perbaikan artikel dengan penulis tidak valid sebelum menambahkan relasi.</p></div>
                            @else
                                <p>Data artikel sudah bersih. Klik <b>Tambahkan Relasi</b> untuk menambahkan kembali foreign key tersebut.</p>
                                @slot('aksi')
                                    @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'relasi_artikel_user_tidak_ada'), 'label' => 'Tambahkan Relasi', 'ikon' => 'fa-link', 'warna' => 'warning'])
                                @endslot
                            @endif
                        @endcomponent
                    @endif

                    @if (in_array('data_cluster', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'data_cluster', 'judul' => $judulMasalah['data_cluster'], 'jumlah' => count($data_cluster), 'ikon' => 'fa-map-marker', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Ada nama dusun yang sama tetapi berbeda penulisan (huruf besar/kecil atau spasi). Pilih penulisan yang benar; wilayah dan penduduk akan digabungkan ke dusun tersebut.</p>
                            <div class="tw-space-y-3">
                                @foreach ($data_cluster as $data)
                                    <form class="pk-subpanel" data-form-perbaiki action="{{ ci_route('periksa.datacluster') }}" method="post">
                                        <label class="pk-label">Penulisan nama dusun yang benar</label>
                                        <div class="tw-flex tw-flex-col tw-gap-2 sm:tw-flex-row">
                                            <select name="dusun" class="form-control tw-flex-1" required>
                                                <option value="">Pilih Dusun</option>
                                                @foreach ($data as $dusun)
                                                    <option value="{{ $dusun }}">{{ $dusun }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="pk-btn pk-btn-danger"><i class="fa fa-save"></i> Simpan</button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('suplemen_terdata_kosong', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'suplemen_terdata_kosong', 'judul' => $judulMasalah['suplemen_terdata_kosong'], 'jumlah' => collect($suplemen_terdata_kosong)->flatten(1)->count(), 'ikon' => 'fa-list-alt', 'warna' => 'warning', 'jenis' => 'manual'])
                            <form id="form-suplemen-terdata" class="tw-space-y-4" data-form-perbaiki action="{{ ci_route('periksa.suplemen_terdata') }}" method="post">
                                <p>Pilih penduduk/keluarga yang sesuai untuk setiap terdata suplemen yang kosong.</p>
                                @foreach ($suplemen_terdata_kosong as $terdataSuplemen)
                                    <div class="tw-space-y-2">
                                        <p class="tw-text-sm">
                                            Suplemen <b>{{ $terdataSuplemen[0]['suplemen']['nama'] ?? '' }}</b>
                                            <span class="pk-badge pk-badge-slate">Sasaran {{ $terdataSuplemen[0]['sasaran'] == App\Models\SuplemenTerdata::PENDUDUK ? 'Penduduk' : 'Keluarga' }}</span>
                                        </p>
                                        <div class="pk-table-wrap tw-overflow-visible">
                                            <table class="pk-table">
                                                <thead><tr><th>No</th><th>Id Terdata</th><th>Keterangan</th><th>Sasaran Terdata</th></tr></thead>
                                                <tbody>
                                                    @foreach ($terdataSuplemen as $terdata)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $terdata['id_terdata'] }}</td>
                                                            <td>{{ $terdata['keterangan'] }}</td>
                                                            <td class="tw-min-w-[14rem]">
                                                                <select class="form-control select-terdata" style="width:100%;"
                                                                    name="suplemen_terdata[{{ $terdata['sasaran'] }}][{{ $terdata['id'] }}]"
                                                                    data-suplemen="{{ $terdata['id'] }}" data-sasaran="{{ $terdata['sasaran'] }}">
                                                                    <option value="">-- Cari {{ $terdata['suplemen']['nama'] }} --</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="tw-flex tw-justify-end">
                                    <button type="submit" class="pk-btn pk-btn-danger"><i class="fa fa-save"></i> Simpan Terdata</button>
                                </div>
                            </form>
                        @endcomponent
                    @endif

                    @if (in_array('kepala_keluarga_ganda', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'kepala_keluarga_ganda', 'judul' => $judulMasalah['kepala_keluarga_ganda'], 'jumlah' => count($kepala_keluarga_ganda), 'ikon' => 'fa-user-plus', 'warna' => 'warning', 'jenis' => 'manual'])
                            <p>Satu keluarga memiliki lebih dari satu kepala keluarga. Ubah hubungan dalam keluarga (SHDK) penduduk berikut.</p>
                            <div id="info-kepala-keluarga-ganda"></div>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>Nama</th><th>NIK</th><th>No KK</th><th class="tw-w-px">Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach ($kepala_keluarga_ganda as $penduduk)
                                            <tr data-kepala-keluarga-ganda="{{ $penduduk['id'] }}">
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['keluarga']['no_kk'] }}</td>
                                                <td>
                                                    <button type="button" class="pk-btn pk-btn-ghost pk-btn-sm"
                                                        data-title="Ubah SHDK {{ $penduduk['nik'] }} / {{ $penduduk['nama'] }}"
                                                        data-url="periksaKepalaKeluargaGanda"
                                                        data-ref="{{ json_encode(['id' => $penduduk['id']]) }}"
                                                        data-toggle="modal" data-target="#modal-kosong"><i class="fa fa-pencil"></i> Ubah SHDK</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endcomponent
                    @endif

                    @if (in_array('keluarga_kepala_ganda', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'keluarga_kepala_ganda', 'judul' => $judulMasalah['keluarga_kepala_ganda'], 'jumlah' => count($keluarga_kepala_ganda), 'ikon' => 'fa-object-ungroup', 'jenis' => 'otomatis'])
                            <p>
                                <b>Perbaiki Data</b> mengosongkan NIK kepala pada KK yang tidak sesuai dengan data kepala keluarganya.
                                Gunakan <b>Lepas Kaitan</b> pada baris tertentu bila kepala keluarga sudah tidak aktif (mis. meninggal)
                                dan anggota keluarganya sudah membuat KK baru.
                            </p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>Nama</th><th>NIK</th><th>No KK</th><th>Status Kepala Keluarga</th><th class="tw-w-px">Aksi</th></tr></thead>
                                    <tbody>
                                        @foreach ($keluarga_kepala_ganda as $item)
                                            <tr>
                                                <td>{{ $item['kepala_keluarga']['nama'] }}</td>
                                                <td>{{ $item['kepala_keluarga']['nik'] }}</td>
                                                <td>{{ $item['no_kk'] }}</td>
                                                <td>
                                                    @if ($item['kepala_keluarga']['status_dasar'] != 1)
                                                        <span class="pk-badge pk-badge-danger">Tidak aktif</span>
                                                    @else
                                                        <span class="pk-badge pk-badge-success">Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item['kepala_keluarga']['status_dasar'] != 1)
                                                        @include('periksa.partials.tombol', ['url' => ci_route('periksa.lepas_kaitan_kk_lama', $item['id']), 'label' => 'Lepas Kaitan', 'ikon' => 'fa-unlink', 'warna' => 'warning', 'kecil' => true, 'pesan' => 'NIK kepala keluarga pada KK ' . $item['no_kk'] . ' akan dikosongkan. Lanjutkan?'])
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'keluarga_kepala_ganda')])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('nik_kepala_bukan_kepala_keluarga', $masalah))
                        @component('periksa.partials.kartu', ['kode' => 'nik_kepala_bukan_kepala_keluarga', 'judul' => $judulMasalah['nik_kepala_bukan_kepala_keluarga'], 'jumlah' => count($nik_kepala_bukan_kepala_keluarga), 'ikon' => 'fa-user-circle', 'jenis' => 'otomatis'])
                            <p>Penduduk berikut tercatat sebagai NIK kepala pada keluarganya, tetapi hubungan dalam keluarganya bukan kepala keluarga. Klik <b>Perbaiki Data</b> untuk mengubah SHDK-nya menjadi kepala keluarga.</p>
                            <div class="pk-table-wrap">
                                <table class="pk-table">
                                    <thead><tr><th>Nama</th><th>NIK</th><th>No KK</th></tr></thead>
                                    <tbody>
                                        @foreach ($nik_kepala_bukan_kepala_keluarga as $penduduk)
                                            <tr><td>{{ $penduduk['nama'] }}</td><td>{{ $penduduk['nik'] }}</td><td>{{ $penduduk['keluarga']['no_kk'] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => ci_route('periksa.perbaikiSebagian', 'nik_kepala_bukan_kepala_keluarga')])
                            @endslot
                        @endcomponent
                    @endif

                    @if ($viewErrorsToShow)
                        @component('periksa.partials.kartu', ['kode' => 'view', 'judul' => $judulMasalah['view'], 'jumlah' => count($viewErrorsToShow), 'ikon' => 'fa-database', 'jenis' => 'otomatis'])
                            <p>View berikut diperlukan untuk fitur tertentu dan akan dibuat ulang.</p>
                            <div id="form-perbaiki-view" class="tw-flex tw-flex-wrap tw-gap-2">
                                @foreach ($viewErrorsToShow as $key => $viewName)
                                    <input type="hidden" name="pilihan[]" value="{{ $key }}">
                                    <span class="pk-badge pk-badge-danger"><i class="fa fa-table"></i> {{ $viewName }}</span>
                                @endforeach
                            </div>
                            @slot('aksi')
                                @include('periksa.partials.tombol', ['url' => route('periksa.perbaiki.pilihan'), 'sertakan' => '#form-perbaiki-view', 'label' => 'Perbaiki View'])
                            @endslot
                        @endcomponent
                    @endif

                    @if (in_array('modul_asing', $masalah))
                        @include('periksa.modul_asing')
                    @endif
                @endif
            @endif
        </main>

        <footer class="tw-border-t tw-bg-white">
            <div class="tw-mx-auto tw-flex tw-max-w-6xl tw-flex-col tw-gap-1 tw-px-4 tw-py-4 tw-text-xs tw-text-slate-500 sm:tw-flex-row sm:tw-justify-between sm:tw-px-6">
                <span>Hak cipta &copy; 2016-{{ date('Y') }} <a href="https://opendesa.id" class="tw-text-indigo-600">OpenDesa</a>. Seluruh hak cipta dilindungi.</span>
                <span><b>{{ config_item('nama_aplikasi') }}</b> v{{ AmbilVersi() }}</span>
            </div>
        </footer>

        @include('admin.layouts.components.modal_kosong')
    </div>

    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript">
        (function($) {
            const csrf = {
                name: '{{ $token_name }}',
                value: '{{ $token_value }}'
            };

            const swalTombol = {
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'pk-btn pk-btn-danger tw-mx-1',
                    cancelButton: 'pk-btn pk-btn-ghost tw-mx-1',
                },
            };

            function escapeHtml(teks) {
                return $('<div>').text(teks || '').html();
            }

            function tampilkanMemproses() {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu, data sedang diperbaiki.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });
            }

            function pesanGagal(xhr) {
                const res = xhr.responseJSON || {};
                if (res.message) {
                    return res.message;
                }

                return xhr.status ? 'Server merespons dengan status ' + xhr.status + '.' : 'Tidak dapat terhubung ke server.';
            }

            // Token terkini dari cookie: csrf_regenerate merotasi token setiap request non-GET.
            function tokenTerkini() {
                const cookie = document.cookie.match(new RegExp('(?:^|; )' + csrf.name + '=([^;]*)'));

                return cookie ? decodeURIComponent(cookie[1]) : csrf.value;
            }

            // Kirim permintaan perbaikan lalu tampilkan hasilnya. Respons diharapkan berupa
            // JSON {status: 1|0, message?: string}. Method selain GET/POST dikirim sebagai
            // POST + _method (method spoofing) dan selalu membawa token CSRF.
            function kirim(opsi) {
                tampilkanMemproses();

                const metode = String(opsi.method || 'POST').toUpperCase();
                const data = $.extend({}, opsi.data || {});
                if (metode !== 'GET') {
                    data[csrf.name] = tokenTerkini();
                    if (metode !== 'POST') {
                        data._method = metode;
                    }
                }

                return $.ajax({
                        url: opsi.url,
                        method: metode === 'GET' ? 'GET' : 'POST',
                        data: data,
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    })
                    .done(function(res) {
                        const berhasil = res && Number(res.status) === 1;

                        Swal.fire($.extend({}, swalTombol, {
                            icon: berhasil ? 'success' : 'warning',
                            title: berhasil ? 'Berhasil' : 'Belum tuntas',
                            text: (res && res.message) || (berhasil ? 'Data berhasil diperbaiki.' : 'Data gagal diperbaiki.'),
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'pk-btn pk-btn-primary'
                            },
                        })).then(function() {
                            if (berhasil && opsi.tetap && opsi.tombol) {
                                opsi.tombol.replaceWith('<span class="pk-btn pk-btn-done pk-btn-sm"><i class="fa fa-check"></i> Selesai</span>');
                                return;
                            }
                            location.reload();
                        });
                    })
                    .fail(function(xhr) {
                        Swal.fire($.extend({}, swalTombol, {
                            icon: 'error',
                            title: 'Gagal',
                            text: pesanGagal(xhr),
                            confirmButtonText: 'Tutup',
                            customClass: {
                                confirmButton: 'pk-btn pk-btn-ghost'
                            },
                        }));
                    });
            }

            // Tombol perbaikan: <button data-perbaiki="url" data-method data-pesan data-params data-sertakan data-tetap>
            $(document).on('click', '[data-perbaiki]', function() {
                const $tombol = $(this);
                let data = $tombol.data('params') || {};

                if ($tombol.data('sertakan')) {
                    $($tombol.data('sertakan')).find(':input[name]').serializeArray().forEach(function(isian) {
                        if (isian.name.slice(-2) === '[]') {
                            (data[isian.name] = data[isian.name] || []).push(isian.value);
                        } else {
                            data[isian.name] = isian.value;
                        }
                    });
                }

                Swal.fire($.extend({}, swalTombol, {
                    icon: 'warning',
                    title: $tombol.data('judul') || 'Lanjutkan perbaikan?',
                    html: '<p class="tw-m-0 tw-text-sm tw-text-slate-600">' + escapeHtml($tombol.data('pesan')) + '</p>',
                    showCancelButton: true,
                    reverseButtons: true,
                    confirmButtonText: '<i class="fa fa-check"></i> Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    focusCancel: true,
                })).then(function(hasil) {
                    if (!hasil.isConfirmed) {
                        return;
                    }

                    kirim({
                        url: $tombol.data('perbaiki'),
                        method: String($tombol.data('method') || 'POST').toUpperCase(),
                        data: data,
                        tetap: !!$tombol.data('tetap'),
                        tombol: $tombol,
                    });
                });
            });

            // Formulir perbaikan manual: <form data-form-perbaiki action="url">
            $(document).on('submit', 'form[data-form-perbaiki]', function(e) {
                e.preventDefault();

                const data = {};
                $(this).serializeArray().forEach(function(isian) {
                    if (isian.name.slice(-2) === '[]') {
                        (data[isian.name] = data[isian.name] || []).push(isian.value);
                    } else {
                        data[isian.name] = isian.value;
                    }
                });

                kirim({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: data,
                });
            });

            // Modal isi dinamis (log keluarga, ubah SHDK)
            $('#modal-kosong').on('show.bs.modal', function(e) {
                const $btn = $(e.relatedTarget);
                const $modal = $(this);

                $modal.find('.modal-title').text($btn.data('title'));
                $modal.find('.modal-body').html('<p class="tw-py-6 tw-text-center tw-text-slate-500"><i class="fa fa-spinner fa-spin"></i> Memuat...</p>');
                $modal.find('.modal-footer').html('<button type="button" class="pk-btn pk-btn-ghost" data-dismiss="modal">Tutup</button>');

                $.get($btn.data('url'), $btn.data('ref'), function(html) {
                    $modal.find('.modal-body').html(html);
                }, 'html');
            });

            $('.select-terdata').select2({
                ajax: {
                    url: "{{ ci_route('internal_api.apipenduduksuplemen') }}",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                            suplemen: $(this).data('suplemen'),
                            sasaran: $(this).data('sasaran'),
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                allowClear: true,
                placeholder: '-- Cari --',
                escapeMarkup: function(markup) {
                    return markup;
                },
            });
        })(jQuery);
    </script>
</body>

</html>
