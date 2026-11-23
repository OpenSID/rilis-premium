<?php

declare(strict_types=1);

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

return [
    'created' => [
        'success' => 'Berhasil menambah data',
        'error'   => 'Gagal menambah data',
    ],
    'updated' => [
        'success' => 'Berhasil mengubah data',
        'error'   => 'Gagal mengubah data',
    ],
    'deleted' => [
        'success' => 'Berhasil menghapus data',
        'error'   => 'Gagal menghapus data',
    ],
    'status' => [
        'success' => 'Berhasil mengubah status data',
        'error'   => 'Gagal mengubah status data',
    ],

    'vaksin_covid' => [
        'file_impor_tidak_sesuai' => '-> File impor tidak sesuai',
        'impor_gagal'             => 'Terjadi kesalahan impor data Penerima Vaksin',
    ],

    'keuangan_manual' => [
        'template' => [
            'success' => 'Berhasil menyalin data',
            'error'   => 'Tidal berhasil menyalin data',
        ],
        'impor' => [
            'success' => 'Data berhasil diimpor',
        ],
        'format_berkas'      => 'File harus dalam format .zip, .csv atau .pdf',
        'tanpa_data'         => 'File tidak berisi data keuangan yang dapat diimpor',
        'pdf_bukan_laporan'  => 'Berkas PDF bukan Laporan Realisasi Pelaksanaan APBDes cetakan Siskeudes',
        'template_tidak_ada' => 'Template keuangan tahun :tahun tidak ditemukan, tambahkan template terlebih dahulu.',
        'zip_berbahaya'      => 'File tidak valid atau berbahaya ditemukan dalam arsip ZIP.',
    ],

    'keuangan_laporan' => [
        'belum_tersedia' => 'Data Laporan Keuangan Belum Tersedia',
    ],

    'surat_keluar' => [
        'nama_berkas_panjang' => 'Nama berkas yang coba Anda unggah terlalu panjang, batas maksimal yang diijinkan adalah :batas karakter',
        'gagal_hapus_berkas'  => 'Gagal menghapus berkas lama',
        'ekspedisi_berhasil'  => 'Berhasil Memasukkan ke Buku Ekspedisi',
        'ekspedisi_gagal'     => 'Gagal Memasukkan ke Buku Ekspedisi',
    ],

    'berkas' => [
        'tidak_valid'     => 'Nama berkas tidak valid',
        'tidak_ditemukan' => 'Berkas tidak ditemukan',
    ],

    'theme' => [
        'unduh_gagal'   => 'Gagal mengunduh tema',
        'hapus_aktif'   => 'Tema yang aktif tidak dapat dihapus',
        'hapus_sistem'  => 'Tema sistem tidak dapat dihapus',
        'config_sistem' => 'Tidak dapat menambahkan config pada tema sistem',
        'salin_config'  => [
            'success' => 'Berhasil Salin Config',
            'error'   => 'Gagal Salin Config',
        ],
        'pindai' => [
            'success' => 'Berhasil Memindai Tema',
        ],
    ],

    'media_sosial' => [
        'belum_punya_link' => 'data ini tidak bisa diaktifkan karena belum memiliki link',
    ],

    // Lainnya
    'impor' => [
        'success' => 'Berhasil mengimpor data',
        'error'   => 'Gagal mengimpor data',
    ],

    'mode_demo'              => 'Fitur ini tidak tersedia dalam mode demo.',
    'berkas_tidak_ditemukan' => 'Berkas tidak ditemukan',

    'ekspor_kosong' => 'Tidak ada data yang ditemukan untuk diekspor.',

    'berkas' => [
        'tidak_valid'     => 'Nama berkas tidak valid',
        'tidak_ditemukan' => 'Berkas tidak ditemukan',
    ],

    'ekspedisi' => [
        'dikeluarkan'         => 'Data berhasil dikeluarkan dari Buku Ekspedisi',
        'gagal_hapus_berkas'  => 'Gagal menghapus berkas lama',
        'gagal_simpan'        => 'Gagal memperbarui data di database',
        'nama_berkas_panjang' => 'Nama berkas yang coba Anda unggah terlalu panjang, batas maksimal yang diijinkan adalah :batas karakter',
    ],
];
