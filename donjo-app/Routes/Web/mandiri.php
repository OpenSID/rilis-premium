<?php

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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

Route::group('layanan-mandiri', ['namespace' => 'fmandiri'], static function (): void {
    Route::get('/', static function (): void {
        redirect(route('anjungan.index'));
    });
    Route::get('/masuk', 'auth/AuthenticatedSessionController@create')->name('layanan-mandiri.masuk.index');
    Route::post('/cek', 'auth/AuthenticatedSessionController@store')->name('layanan-mandiri.masuk.cek');
    // Route::get('/lupa-pin', 'Masuk@lupa_pin')->name('layanan-mandiri.masuk.lupa_pin');
    // Route::post('/cek-pin', 'Masuk@cek_pin')->name('layanan-mandiri.masuk.cek_pin');

    Route::get('/lupa-pin', 'auth/PasswordResetLinkController@create')->name('layanan-mandiri.masuk.lupa_pin');
    Route::post('/cek-pin', 'auth/PasswordResetLinkController@store')->name('layanan-mandiri.masuk.cek_pin');

    Route::get('/masuk-ektp', 'auth/AuthenticatedSessionController@createEktp')->name('layanan-mandiri.masuk_ektp.index');
    Route::post('/cek-ektp', 'auth/AuthenticatedSessionController@store')->name('layanan-mandiri.masuk_ektp.cek_ektp');

    Route::get('reset-password/{token?}', 'auth/NewPasswordController@create')->name('password.reset');
    Route::post('reset-password', 'auth/NewPasswordController@store')->name('password.store');

    Route::get('/beranda', 'Beranda@index')->name('layanan-mandiri.beranda.index');
    Route::get('/profil', 'Beranda@profil')->name('layanan-mandiri.beranda.profil');
    Route::get('/cetak-biodata', 'Beranda@cetak_biodata')->name('layanan-mandiri.beranda.cetak_biodata');
    Route::get('/cetak-kk', 'Beranda@cetak_kk')->name('layanan-mandiri.beranda.cetak_kk');
    Route::get('/ganti-pin', 'Beranda@ganti_pin')->name('layanan-mandiri.beranda.ganti_pin');
    Route::post('/proses-ganti-pin', 'Beranda@proses_ganti_pin')->name('layanan-mandiri.beranda.proses_ganti_pin');
    Route::get('/keluar', 'auth/AuthenticatedSessionController@destroy')->name('layanan-mandiri.beranda.keluar');
    Route::get('/pendapat/{pilihan?}', 'Beranda@pendapat')->name('layanan-mandiri.beranda.pendapat');

    Route::get('/pesan-masuk/{id?}', 'Pesan@index')->name('layanan-mandiri.pesan.masuk')->param('id', 2);
    Route::get('/pesan-keluar/{id?}', 'Pesan@index')->name('layanan-mandiri.pesan.keluar')->param('id', 1);

    Route::group('pesan', static function (): void {
        Route::get('/datatables/{kat?}', 'Pesan@datatables')->name('layanan-mandiri.pesan.datatables')->param('id', 1);
        Route::post('/kirim/{kat?}', 'Pesan@kirim')->name('layanan-mandiri.pesan.kirim');
        Route::get('/baca/{kat}/{id?}', 'Pesan@baca')->name('layanan-mandiri.pesan.baca');
        Route::get('/tulis/{id?}', 'Pesan@tulis')->name('layanan-mandiri.pesan.tulis')->param('id', 1);
        Route::post('/balas/{id?}', 'Pesan@tulis')->name('layanan-mandiri.pesan.balas')->param('id', 2);
    });

    Route::post('proses-daftar', 'auth/RegisteredUserController@store')->name('layanan-mandiri.daftar.proses_daftar');
    Route::group('daftar', static function (): void {
        Route::get('/', 'auth/RegisteredUserController@create')->name('layanan-mandiri.daftar.index');

        Route::group('verifikasi', static function (): void {
            Route::group('telegram', static function (): void {
                Route::get('/', 'auth/VerificationNotificationController@telegram')->name('layanan-mandiri.daftar.verifikasi.telegram');
                Route::post('/kirim', 'auth/VerificationNotificationController@sendNotificationTelegram')->name('layanan-mandiri.daftar.verifikasi.telegram.kirim');
                Route::get('/verify/{hash}', 'auth/VerificationNotificationController@verifyTelegram')->name('layanan-mandiri.daftar.verifikasi.telegram.verify');
            });
            Route::group('email', static function (): void {
                Route::get('/', 'auth/VerificationNotificationController@email')->name('layanan-mandiri.daftar.verifikasi.email');
                Route::post('/kirim', 'auth/VerificationNotificationController@sendNotificationEmail')->name('layanan-mandiri.daftar.verifikasi.email.kirim');
                Route::get('/verify/{hash}', 'auth/VerificationNotificationController@verifyEmail')->name('layanan-mandiri.daftar.verifikasi.email.verify');
            });
        });
    });

    Route::get('/permohonan-surat', 'Surat@index')->name('layanan-mandiri.surat.index');
    Route::get('/arsip-surat', 'Surat@arsip')->name('layanan-mandiri.surat.index-arsip');

    Route::group('surat', static function (): void {
        Route::get('/buat/{id?}', 'Surat@buat')->name('layanan-mandiri.surat.buat');
        Route::get('/cek_syarat', 'Surat@cek_syarat')->name('layanan-mandiri.surat.cek_syarat');
        Route::post('/form/{id?}', 'Surat@form')->name('layanan-mandiri.surat.form');
        Route::post('/kirim/{id?}', 'Surat@kirim')->name('layanan-mandiri.surat.kirim');
        Route::get('/proses/{id?}', 'Surat@proses')->name('layanan-mandiri.surat.proses');
        Route::get('/cetak_no_antrian/{no_antrian}', 'Surat@cetak_no_antrian')->name('layanan-mandiri.surat.cetak_no_antrian');
        Route::get('/{id}', 'Surat@cetak')->name('layanan-mandiri.surat.cetak');
    });

    Route::group('bantuan', static function (): void {
        Route::get('/', 'Bantuan@index')->name('layanan-mandiri.bantuan.index');
        Route::get('/datatables', 'Bantuan@datatables')->name('layanan-mandiri.bantuan.datatables');
        Route::get('/kartu_peserta/{aksi?}/{id_peserta?}', 'Bantuan@kartu_peserta')->name('layanan-mandiri.bantuan.kartu_peserta');
    });

    Route::group('dokumen', static function (): void {
        Route::get('/', 'Dokumen@index')->name('layanan-mandiri.dokumen.index');
        Route::get('/datatables', 'Dokumen@datatables')->name('layanan-mandiri.dokumen.datatables');
        Route::get('/form/{id?}', 'Dokumen@form')->name('layanan-mandiri.dokumen.form');
        Route::post('/tambah', 'Dokumen@tambah')->name('layanan-mandiri.dokumen.tambah');
        Route::post('/ubah/{id?}', 'Dokumen@ubah')->name('layanan-mandiri.dokumen.ubah');
        Route::get('/hapus/{id?}', 'Dokumen@hapus')->name('layanan-mandiri.dokumen.hapus');
        Route::get('/unduh/{id?}', 'Dokumen@unduh')->name('layanan-mandiri.dokumen.unduh');
    });
    Route::group('kehadiran', static function (): void {
        Route::get('/', 'Kehadiran_perangkat@index')->name('layanan-mandiri.kehadiran_perangkat.index');
        Route::get('/datatables', 'Kehadiran_perangkat@datatables')->name('layanan-mandiri.kehadiran_perangkat.datatables');
        Route::match(['GET', 'POST'], '/lapor/{id}', 'Kehadiran_perangkat@lapor')->name('layanan-mandiri.kehadiran_perangkat.lapor');
    });

    Route::group('produk', static function (): void {
        Route::get('/', 'Produk@index')->name('layanan-mandiri.produk.index');
        Route::get('/datatables/{kat?}', 'Produk@datatables')->name('layanan-mandiri.produk.datatables');
        Route::get('/form/{id?}', 'Produk@form')->name('layanan-mandiri.produk.form');
        Route::post('/store', 'Produk@store')->name('layanan-mandiri.produk.store');
        Route::post('/update/{id}', 'Produk@update')->name('layanan-mandiri.produk.update');
        Route::get('/pengaturan', 'Produk@pengaturan')->name('layanan-mandiri.produk.pengaturan');
        Route::post('/pengaturan-update', 'Produk@pengaturanUpdate')->name('layanan-mandiri.produk.pengaturan-update');
    });

    Route::group('lapak', static function (): void {
        Route::get('/{p?}', 'Lapak@index')->name('layanan-mandiri.lapak.index');
    });

    Route::group('verifikasi', static function (): void {
        Route::get('/', 'Verifikasi@index')->name('layanan-mandiri.verifikasi.index');
        Route::group('telegram', static function (): void {
            Route::get('/', 'Verifikasi@telegram')->name('layanan-mandiri.verifikasi.telegram');
            Route::post('/kirim-userid', 'Verifikasi@kirim_otp_telegram')->name('layanan-mandiri.verifikasi.kirim_otp_telegram');
            Route::post('/kirim-otp', 'Verifikasi@verifikasi_telegram')->name('layanan-mandiri.verifikasi.verifikasi_telegram');
        });
        Route::group('email', static function (): void {
            Route::get('/', 'Verifikasi@email')->name('layanan-mandiri.verifikasi.email');
            Route::post('/kirim-email', 'Verifikasi@kirim_otp_email')->name('layanan-mandiri.verifikasi.kirim_otp_email');
            Route::post('/kirim-otp', 'Verifikasi@verifikasi_email')->name('layanan-mandiri.verifikasi.verifikasi_email');
        });
    });
});
// harus define ulang karena di code ada yang memanggil fmandiri langsung bukan layanan-mandiri
Route::group('fmandiri', ['namespace' => 'fmandiri'], static function (): void {
    Route::group('surat', static function (): void {
        Route::post('/kirim', 'Surat@kirim')->name('fmandiri.surat.kirim');
        Route::get('/proses/{id?}', 'Surat@proses')->name('fmandiri.surat.proses');
        Route::get('/cetak_no_antrian/{no_antrian}', 'Surat@cetak_no_antrian')->name('fmandiri.surat.cetak_no_antrian');
        Route::get('/cetak/{id}', 'Surat@cetak')->name('fmandiri.surat.cetak');
    });
});
