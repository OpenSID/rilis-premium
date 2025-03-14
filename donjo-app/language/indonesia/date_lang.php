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

$lang['date_year']    = 'Tahun';
$lang['date_years']   = 'Tahun';
$lang['date_month']   = 'Bulan';
$lang['date_months']  = 'Bulan';
$lang['date_week']    = 'Minggu';
$lang['date_weeks']   = 'Minggu';
$lang['date_day']     = 'Hari';
$lang['date_days']    = 'Hari';
$lang['date_hour']    = 'Jam';
$lang['date_hours']   = 'Jam';
$lang['date_minute']  = 'Menit';
$lang['date_minutes'] = 'Menit';
$lang['date_second']  = 'Detik';
$lang['date_seconds'] = 'Detik';

$lang['UM12']   = '(UTC -12:00) Pulau Baker/Howland';
$lang['UM11']   = '(UTC -11:00) Niue';
$lang['UM10']   = '(UTC -10:00) Standar Waktu Hawaii-Aleutian, Pulau Cook, Tahiti';
$lang['UM95']   = '(UTC -9:30) Pulau Marquesas';
$lang['UM9']    = '(UTC -9:00) Standar Waktu Alaska, Pulau Gambier';
$lang['UM8']    = '(UTC -8:00) Standar Waktu Pasifik, Pulau Clipperton';
$lang['UM7']    = '(UTC -7:00) Standar Waktu Mountain';
$lang['UM6']    = '(UTC -6:00) Standar Waktu Pusat';
$lang['UM5']    = '(UTC -5:00) Standar Waktu Timur, Standar Waktu Caribbean Barat';
$lang['UM45']   = '(UTC -4:30) Standar Waktu Venezuela';
$lang['UM4']    = '(UTC -4:00) Standar Waktu Atlantic, Standar Waktu Caribbean Timur';
$lang['UM35']   = '(UTC -3:30) Standar Waktu Newfoundland';
$lang['UM3']    = '(UTC -3:00) Argentina, Brazil, French Guiana, Uruguay';
$lang['UM2']    = '(UTC -2:00) South Georgia/South Sandwich Islands';
$lang['UM1']    = '(UTC -1:00) Azores, Cape Verde Islands';
$lang['UTC']    = '(UTC) Waktu Rata-rata Greenwich, Waktu Eropa Barat';
$lang['UP1']    = '(UTC +1:00) Waktu Eropa Tengah, Waktu Afrika Barat';
$lang['UP2']    = '(UTC +2:00) Waktu Afrika Tengah, Waktu Eropa Timur, Waktu Kaliningrad';
$lang['UP3']    = '(UTC +3:00) Waktu Moskow, Waktu Afrika Timur, Standar Waktu Arab';
$lang['UP35']   = '(UTC +3:30) Standar Waktu Iran';
$lang['UP4']    = '(UTC +4:00) Standar Waktu Azerbaijan, Waktu Samara';
$lang['UP45']   = '(UTC +4:30) Afghanistan';
$lang['UP5']    = '(UTC +5:00) Standar Waktu Pakistan, Waktu Yekaterinburg';
$lang['UP55']   = '(UTC +5:30) Standar Waktu India, Waktu Sri Lanka';
$lang['UP575']  = '(UTC +5:45) Waktu Nepal';
$lang['UP6']    = '(UTC +6:00) Standar Waktu Bangladesh, Waktu Bhutan, Waktu Omsk';
$lang['UP65']   = '(UTC +6:30) Pulau Cocos, Myanmar';
$lang['UP7']    = '(UTC +7:00) Waktu Krasnoyarsk, Cambodia, Laos, Thailand, Vietnam';
$lang['UP8']    = '(UTC +8:00) Standar Waktu Australia Barat, Waktu Beijing, Waktu Irkutsk';
$lang['UP875']  = '(UTC +8:45) Standar Waktu Australia Tengah Barat';
$lang['UP9']    = '(UTC +9:00) Standar Waktu Jepang, Standar Waktu Korea, Waktu Yakutsk';
$lang['UP95']   = '(UTC +9:30) Standar Waktu Australia Tengah';
$lang['UP10']   = '(UTC +10:00) Standar Waktu Australia Timur, Waktu Vladivostok';
$lang['UP105']  = '(UTC +10:30) Pulau Lord Howe';
$lang['UP11']   = '(UTC +11:00) Waktu Srednekolymsk, Pulau Solomon, Vanuatu';
$lang['UP115']  = '(UTC +11:30) Pulau Norfolk';
$lang['UP12']   = '(UTC +12:00) Fiji, Pulau Gilbert, Waktu Kamchatka, Standar Waktu Selandia Baru';
$lang['UP1275'] = '(UTC +12:45) Standar Waktu Pulau Chatham';
$lang['UP13']   = '(UTC +13:00) Zona Waktu Samoa, Waktu Pulau Phoenix, Tonga';
$lang['UP14']   = '(UTC +14:00) Pulau Line';
