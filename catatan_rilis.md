Rilis versi 2608.0.0 ini berisi penambahan fitur statistik perkembangan penduduk dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR

1. [#3983](https://github.com/OpenSID/OpenSID/issues/3983) Penambahan fitur statistik perkembangan penduduk.
2. [#11437](https://github.com/OpenSID/OpenSID/issues/11437) Penambahan fitur riwayat kata sandi & pencegahan penggunaan ulang kata sandi lama.
3. [#11293](https://github.com/OpenSID/OpenSID/issues/11293) Penambahan fitur pengaturan untuk sesuaikan batas maksimal impor data penduduk.
4. [#11268](https://github.com/OpenSID/OpenSID/issues/11268) Penambahan fitur tampilan laporan DTSEN.


### BUG


1. [#11480](https://github.com/OpenSID/OpenSID/issues/11480) Perbaikan favicon tidak muncul pada halaman admin.
2. [#11458](https://github.com/OpenSID/OpenSID/issues/11458) Perbaikan pengaturan tinggi baris pada tinjau pdf surat.
3. [#11465](https://github.com/OpenSID/OpenSID/issues/11465) Perbaikan eror penambahan rumah tangga.
4. [#11481](https://github.com/OpenSID/OpenSID/issues/11481) Perbaikan versi tema dan scan tema.
5. [#11482](https://github.com/OpenSID/OpenSID/issues/11482) Perbaikan tema wira.
6. [#6657](https://github.com/OpenSID/premium/issues/6657) Perbaikan Foto yang diubah di server (blur/watermark Acak, unggah/edit) tetap basi di browser tanpa hard refresh.
7. [#11477](https://github.com/OpenSID/OpenSID/issues/11477) Perbaikan judul halaman dan breadcrumb bertumpuk ketika teks terlalu panjang.
8. [#11471](https://github.com/OpenSID/OpenSID/issues/11471) Perbaikan eror saat menampilkan format pdf di buku rekapituasi jumlah penduduk.
9. [#6731](https://github.com/OpenSID/premium/issues/6731) Perbaikan Tema Pusako muncul versi sebelumnya.
10. [#6743](https://github.com/OpenSID/premium/issues/6743) Perbaikan akses via www.domain.com dianggap tidak terdaftar walau domain sudah didaftarkan.
11. [#11484](https://github.com/OpenSID/OpenSID/issues/11484) Perbaikan migrasi default tidak pernah dijalankan sama sekali.
12. [#11476](https://github.com/OpenSID/OpenSID/issues/11476) Perbaikan setelah menyimpan pada form tambah beberapa anggota lembaga, sistem kembali ke detail lembaga.
13. [#11464](https://github.com/OpenSID/OpenSID/issues/11464) Perbaikan gagal menampilkan peta mapbox di halaman public.
14. [#11461](https://github.com/OpenSID/OpenSID/issues/11461) Perbaikan Embed video tidak bisa tampil di halaman web.
15. [#11449](https://github.com/OpenSID/OpenSID/issues/11449) Sembunyikan file sistem bawaan sistem di folder desa saat melakukan scan folder desa.
16. [#6722](https://github.com/OpenSID/premium/issues/6722) Perbaikan Route CodeIgniter yang menggunakan ->param('id', value) untuk memberikan default value pada optional parameter.
17. [#11454](https://github.com/OpenSID/OpenSID/issues/11454) Perbaikan subjek pesan disable tapi required saat kirim pesan di layanan mandiri.
18. [#11479](https://github.com/OpenSID/OpenSID/issues/11479) Perbaikan notifikasi pengingat layanan akan berakhir mengalami salah perhitungana.
19. [#11450](https://github.com/OpenSID/OpenSID/issues/11450) Perbaikan perbaiki form input pemantauan ibu hamil.
20. [#11505](https://github.com/OpenSID/OpenSID/issues/11505) Perbaikan pada laporan perkembangan.
21. [#11507](https://github.com/OpenSID/OpenSID/issues/11507) Perbaikan table template input data keuangan.
22. [#11510](https://github.com/OpenSID/OpenSID/issues/11510) Perbaikan error surat ubahan desa.
23. [#11509](https://github.com/OpenSID/OpenSID/issues/11509) Perbaikan filter dusun DPT tidak berfungsi.
24. [#11508](https://github.com/OpenSID/OpenSID/issues/11508) Perbaikan filter dusun pada data Suplemen tidak menampilkan data.


### TEKNIS

1. [#11474](https://github.com/OpenSID/OpenSID/issues/11474) Migrasi Install_modul CI CLI ke artisan opensid:module (mode non-interaktif by name).
2. [#11485](https://github.com/OpenSID/OpenSID/issues/11485) Menghapus duplikasi helper.
3. [#11486](https://github.com/OpenSID/OpenSID/issues/11486) Perbaikan testing PelangganServiceTest.
4. [#11487](https://github.com/OpenSID/OpenSID/issues/11487) Perbaikan testing Perbaiki testing PelangganService, BaseAdminController, dan SyaratSurat.
5. [#11488](https://github.com/OpenSID/OpenSID/issues/11488) Merapikan konstruktor MY_Controller, Admin_Controller, dan Web_Controller.
6. [#11489](https://github.com/OpenSID/OpenSID/issues/11489) Hapus backfill otomatis artikel dan kategori wilayah pada migrasi.
7. [#6715](https://github.com/OpenSID/premium/issues/6715) Perbaikan gerbang CI (PHPStan + Unit) merah.
8. [#6745](https://github.com/OpenSID/premium/issues/6745) Memperbaiki banjir request (flood request) ke server layanan pada method PelangganService::perbaruiLangganan().


