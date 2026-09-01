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

error_reporting(0);

define('RFM_BASE_PATH', dirname(__FILE__, 2));

require RFM_BASE_PATH . '/vendor/autoload.php';

Dotenv\Dotenv::createImmutable(RFM_BASE_PATH)->safeLoad();

$encryptedCookie = $_COOKIE['rfm_access'] ?? null;

if (! $encryptedCookie) {
    http_response_code(401);

    exit('Access denied: No cookie');
}

try {
    $appKey = $_ENV['APP_KEY'] ?? file_get_contents(RFM_BASE_PATH . '/desa/app_key') ?? null;

    if (str_starts_with($appKey, 'base64:')) {
        $appKey = base64_decode(substr($appKey, 7));
    }

    $encrypter  = new Illuminate\Encryption\Encrypter($appKey, $_ENV['APP_CIPHER'] ?? 'AES-256-CBC');
    $serialized = $encrypter->decrypt($encryptedCookie, false);

    $data = @unserialize($serialized, ['allowed_classes' => false]);

    if (! is_array($data) || ! isset($data['user_id'], $data['expires'])) {
        throw new Exception('Invalid structure');
    }

    if (time() > $data['expires']) {
        throw new Exception('Expired');
    }

    $GLOBALS['RFM_AUTH'] = [
        'user_id'                => $data['user_id'],
        'fm_key'                 => $data['fm_key'] ?? '',
        'hapus_gambar_rfm'       => $data['hapus_gambar_rfm'] ?? false,
        'ubah_tambah_gambar_rfm' => $data['ubah_tambah_gambar_rfm'] ?? false,
    ];
} catch (Exception $e) {
    http_response_code(401);

    exit('Access denied: ' . $e->getMessage());
}
