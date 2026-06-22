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

/**
 * Pemeriksaan IonCube Loader
 *
 * Harus diletakkan di baris paling awal, sebelum require file manapun.
 * File-file premium sudah dalam format encoded IonCube dan tidak dapat
 * dieksekusi tanpa IonCube Loader aktif di server.
 */
if (! extension_loaded('ionCube Loader') && ! file_exists(__DIR__ . '/phpunit.xml')) {
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');

    echo <<<'HTML'
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>OpenSID Premium — IonCube Loader Diperlukan</title>
            <style>
                html, body {
                    height: 100%;
                }
                body {
                    font-family: Arial, sans-serif;
                    background: #f5f5f5;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    margin: 0;
                }
                *, *:before, *:after {
                    box-sizing: border-box;
                }
                .card {
                    background: white;
                    border-radius: 8px;
                    padding: 40px;
                    max-width: 600px;
                    width: 90%;
                    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
                    border-top: 4px solid #e74c3c;
                }
                h1 { color: #e74c3c; font-size: 20px; margin-top: 0; }
                h2 { color: #333; font-size: 16px; margin-top: 24px; }
                p, li { color: #555; line-height: 1.7; font-size: 14px; }
                code {
                    background: #f0f0f0; padding: 2px 6px;
                    border-radius: 4px; font-family: monospace; font-size: 13px;
                }
                .info {
                    background: #eaf4fb; border-left: 4px solid #3498db;
                    padding: 12px 16px; border-radius: 0 4px 4px 0;
                    margin: 16px 0; font-size: 13px;
                }
                a { color: #3498db; }
            </style>
        </head>
        <body>
            <div class="card">
                <h1>⚠ IonCube Loader Tidak Ditemukan</h1>
                <p>OpenSID Premium memerlukan <b>IonCube Loader</b> yang aktif di server untuk dapat berjalan. Extension ini belum terdeteksi di server Anda.</p>
                <div class="info">
                    Hubungi administrator server atau tim teknis Anda untuk mengaktifkan IonCube Loader.
                </div>
                <h2>Cara Mengaktifkan IonCube Loader</h2>
                <b>Via cPanel (Shared Hosting):</b>
                <ol>
                    <li>Login ke cPanel hosting Anda</li>
                    <li>Cari menu <b>PHP Selector</b> atau <b>Select PHP Version</b></li>
                    <li>Aktifkan extension <code>ioncube_loader</code></li>
                    <li>Klik Save dan muat ulang halaman ini</li>
                </ol>
                <b>Via VPS / Server Mandiri:</b>
                <ol>
                    <li>Download IonCube Loader dari <a href="https://www.ioncube.com/loaders.php" target="_blank" rel="noopener noreferrer">ioncube.com/loaders.php</a></li>
                    <li>Tambahkan <code>zend_extension = /path/to/ioncube_loader.so</code> di baris pertama <code>php.ini</code></li>
                    <li>Restart web server</li>
                </ol>
                <p>Butuh bantuan? Hubungi tim OpenDesa di <a href="https://opendesa.id" target="_blank" rel="noopener noreferrer">opendesa.id</a>.</p>
            </div>
        </body>
        </html>
        HTML;

    exit;
}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
