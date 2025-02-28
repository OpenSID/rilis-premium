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

use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_2024100171 extends MY_Model
{
    use Migrator;

    public function up()
    {
        $hasil = true;

        // Migrasi berdasarkan config_id
        $config_id = Config::appKey()->pluck('id')->toArray();

        foreach ($config_id as $id) {
            $hasil = $this->migrasi_2024090671($hasil, $id);
            $hasil = $this->migrasi_2024093051($hasil, $id);
            $hasil = $this->migrasi_2024093052($hasil, $id);
        }

        $hasil = $this->migrasi_2024090552($hasil);
        $hasil = $this->migrasi_2024090551($hasil);
        $hasil = $this->migrasi_2024090951($hasil);
        $hasil = $this->migrasi_2024091251($hasil);
        $hasil = $this->migrasi_2024092051($hasil);

        return $this->migrasi_2024092151($hasil);
    }

    protected function migrasi_2024090552($hasil)
    {
        if (! Schema::hasColumn('log_notifikasi_admin', 'token')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
                $table->longText('token')->nullable()->after('isi');
            });
        }

        if (! Schema::hasColumn('log_notifikasi_admin', 'device')) {
            Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
                $table->longText('device')->after('token');
            });
        }

        return $hasil;
    }

    protected function migrasi_2024090951($hasil)
    {
        // pakai get, bisa jadi di database gabungan
        $penduduk_luar = SettingAplikasi::dontCache()->withoutGlobalScope(App\Scopes\ConfigIdScope::class)->where('key', '=', 'form_penduduk_luar')->get();
        if ($penduduk_luar) {
            foreach ($penduduk_luar as $key => $penduduk) {
                if ($penduduk) {
                    $penduduk->value = json_encode(updateIndex(json_decode($penduduk->value, true)), JSON_THROW_ON_ERROR);
                    $penduduk->save();
                }
            }
        }

        return $hasil;
    }

    protected function migrasi_2024091251($hasil)
    {
        Schema::table('log_notifikasi_admin', static function (Blueprint $table) {
            $table->longText('device')->nullable()->change();
        });

        return $hasil;
    }

    protected function migrasi_2024090551($hasil)
    {
        DB::table('setting_aplikasi')
            ->whereIn('key', ['sebutan_dusun', 'sebutan_singkatan_kadus'])
            ->where('kategori', '!=', 'Wilayah Administratif')
            ->update(['kategori' => 'Wilayah Administratif']);

        $this->changeSettingKey('sebutan_singkatan_kadus', [
            'judul'      => 'Sebutan Singkatan Kepala Dusun',
            'key'        => 'sebutan_singkatan_kepala_dusun',
            'value'      => 'Kadus',
            'keterangan' => 'Sebutan singkatan Kepala Dusun',
            'jenis'      => 'input-text',
            'option'     => null,
            'attribute'  => [
                'class'       => 'required',
                'placeholder' => 'Kadus',
            ],
            'kategori' => 'Wilayah Administratif',
        ]);

        return $hasil;
    }

    public function migrasi_2024092051($hasil)
    {
        DB::table('widget')
            ->where('form_admin', 'web/tab/1000')
            ->update(['form_admin' => 'web/agenda']);

        return $hasil;
    }

    public function migrasi_2024092151($hasil)
    {
        $hasil = $hasil && checkAndFixTable('log_notifikasi_admin');

        return $hasil && checkAndFixTable('log_notifikasi_mandiri');
    }

    public function migrasi_2024093051($hasil, $config_id)
    {
        FormatSurat::whereNull('template')->whereNull('template_desa')->delete();
        $suratList = FormatSurat::where('jenis', 3)->get();

        foreach ($suratList as $surat) {
            if (str_starts_with($surat->url_surat, 'sistem-')) {
                continue;
            }

            $url_surat = 'sistem-' . $surat->url_surat;

            if (null !== $surat->template_desa) {
                $defaultSurat = collect(getSuratBawaanTinyMCE($url_surat))->first();
                $belumAda     = FormatSurat::where('url_surat', $url_surat)->doesntExist();

                if ($defaultSurat && $belumAda) {
                    FormatSurat::insert([
                        ...$defaultSurat,
                        'config_id'    => $config_id,
                        'url_surat'    => $url_surat,
                        'kunci'        => 1,
                        'syarat_surat' => json_encode($defaultSurat['syarat_surat']),
                        'form_isian'   => json_encode($defaultSurat['form_isian']),
                    ]);
                }

                FormatSurat::where('id', $surat->id)->update(['jenis' => 4]);
            } else {
                // kalau null berarti masih asli, jika sudah ada $url_surat maka hapus saja
                if (FormatSurat::where('url_surat', $url_surat)->exists()) {
                    FormatSurat::where('id', $surat->id)->delete();
                } else {
                    FormatSurat::where('id', $surat->id)->update(['url_surat' => $url_surat]);
                }
            }
        }

        return $hasil;
    }

    public function migrasi_2024093052($hasil, $config_id)
    {
        $suratList = DB::table('surat_dinas')->where('config_id', $config_id)->where('jenis', 3)->get();

        foreach ($suratList as $surat) {
            if (str_starts_with($surat->url_surat, 'sistem-')) {
                continue;
            }

            $url_surat = 'sistem-' . $surat->url_surat;

            if (null !== $surat->template_desa) {
                $defaultSurat = collect(getSuratBawaanDinasTinyMCE($url_surat))->first();

                if ($defaultSurat) {
                    DB::table('surat_dinas')->insert([
                        ...$defaultSurat,
                        'config_id'  => $config_id,
                        'url_surat'  => $url_surat,
                        'kunci'      => 1,
                        'form_isian' => json_encode($defaultSurat['form_isian']),
                    ]);
                }

                DB::table('surat_dinas')->where('config_id', $config_id)->where('id', $surat->id)->update(['jenis' => 4]);
            } else {
                DB::table('surat_dinas')->where('config_id', $config_id)->where('id', $surat->id)->update(['url_surat' => $url_surat]);
            }
        }

        return $hasil;
    }

    protected function migrasi_2024090671($hasil, $config_id)
    {
        $this->createSetting([
            'judul'      => 'Rentang Waktu Masuk',
            'key'        => 'rentang_waktu_masuk',
            'value'      => '10',
            'keterangan' => 'Rentang waktu kehadiran ketika masuk. (satuan: menit)',
            'jenis'      => 'input-number',
            'option'     => null,
            'attribute'  => [
                'class'       => 'required',
                'min'         => 0,
                'max'         => 3600,
                'step'        => 1,
                'placeholder' => '10',
            ],
            'kategori' => 'Kehadiran',
        ]);

        $this->changeSettingKey('rentang_waktu_kehadiran', [
            'judul'      => 'Rentang Waktu Keluar',
            'key'        => 'rentang_waktu_keluar',
            'value'      => '10',
            'keterangan' => 'Rentang waktu kehadiran ketika keluar. (satuan: menit)',
            'jenis'      => 'input-number',
            'option'     => null,
            'attribute'  => [
                'class'       => 'required',
                'min'         => 0,
                'max'         => 3600,
                'step'        => 1,
                'placeholder' => '10',
            ],
            'kategori' => 'Kehadiran',
        ]);

        return $hasil;
    }
}
