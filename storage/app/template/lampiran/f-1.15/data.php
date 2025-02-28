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

    define('MAX_ANGGOTA_F115', 10);
    define('MAX_ANGGOTA_F101', 10);

    $this->load->model('keluarga_model');
    $anggota      = $this->keluarga_model->list_anggota($individu['id_kk']);
    $anggota_ikut = $this->keluarga_model->list_anggota($individu['id_kk'], ['dengan_kk' => false], true);

    switch (strtolower($input['alasan_permohonan'])) {
        case 'karena membentuk rumah tangga baru':
            $input['alasan_permohonan'] = 1;
            break;

        case 'karena kartu keluarga hilang/rusak':
            $input['alasan_permohonan'] = 2;
            break;

        case 'lainnya':
            $input['alasan_permohonan'] = 3;
            break;

        default:
            $input['alasan_permohonan'] = null;
            break;
    }

    // include data F101
    include STORAGEPATH . 'app/template/lampiran/f-1.01/data.php';
