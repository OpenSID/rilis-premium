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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keuangan_ta_triwulan_rinci', static function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_id')->nullable()->index('keuangan_ta_triwulan_rinci_config_fk');
            $table->integer('id_keuangan_master')->index('id_keuangan_ta_triwulan_rinci_master_fk');
            $table->string('KdPosting', 100);
            $table->string('KURincianSD', 100);
            $table->string('Tahun', 100);
            $table->string('Sifat', 100);
            $table->string('SumberDana', 100);
            $table->string('Kd_Desa', 100);
            $table->string('Kd_Keg', 100);
            $table->string('Kd_Rincian', 100);
            $table->string('Anggaran', 100);
            $table->string('AnggaranPAK', 100);
            $table->string('Tw1Rinci', 100)->nullable();
            $table->string('Tw2Rinci', 100)->nullable();
            $table->string('Tw3Rinci', 100)->nullable();
            $table->string('Tw4Rinci', 100)->nullable();
            $table->string('KunciData', 100);
            $table->string('Jan', 100)->nullable();
            $table->string('Peb', 100)->nullable();
            $table->string('Mar', 100)->nullable();
            $table->string('Apr', 100)->nullable();
            $table->string('Mei', 100)->nullable();
            $table->string('Jun', 100)->nullable();
            $table->string('Jul', 100)->nullable();
            $table->string('Agt', 100)->nullable();
            $table->string('Sep', 100)->nullable();
            $table->string('Okt', 100)->nullable();
            $table->string('Nop', 100)->nullable();
            $table->string('Des', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keuangan_ta_triwulan_rinci');
    }
};
