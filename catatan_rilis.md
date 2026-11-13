Rilis versi 2611.0.1 ini berisi perbaikan gambar pengumuman tidak tampil di admin dan popup serta perbaikan lainnya yang diminta oleh komunitas SID.



### BUG


1. [#12115](https://github.com/OpenSID/OpenSID/issues/12115) Perbaikan gambar pengumuman tidak tampil di admin dan popup (URL diawali assets/).
2. [#7590](https://github.com/OpenSID/premium/issues/7590) Lebarkan kolom sentitems di tabel pengaduan.
3. [#12116](https://github.com/OpenSID/OpenSID/issues/12116) Perbaikan error tidak ada data yang di tampilkan pada kode isian surat.
4. [#12100](https://github.com/OpenSID/OpenSID/issues/12100) Perbaikan KK akhir bulan pada laporan bulanan tidak sama dengan KK awal bulan berikutnya.
5. [#12094](https://github.com/OpenSID/OpenSID/issues/12094) Perbaikan gunakan kode klasifikasi arsip pemerintah daerah pada klasifikasi surat.
6. [#12122](https://github.com/OpenSID/OpenSID/issues/12122) Perbaikan data statistik perdusun yang diakses dari peta tidak sesuai.
7. [#12121](https://github.com/OpenSID/OpenSID/issues/12121) Perbaikan tampilan popup data statistik dari peta terpotong.
8. [#12124](https://github.com/OpenSID/OpenSID/issues/12124) Perbaikan grafik keuangan tidak menampilkan bidang yang nilainya tersimpan di level 3 tanpa rincian.
9. [#12125](https://github.com/OpenSID/OpenSID/issues/12125) Perbaikan peringatan dataTables 'Incorrect column count' di halaman Pendapat saat data kosong.
10. [#12075](https://github.com/OpenSID/OpenSID/issues/12075) Perbaikan migrasi database gagal di Migrasi_2026030171 karena kolom keterangan tidak ada pada tabel bulanan_anak.
11. [#7578](https://github.com/OpenSID/premium/issues/7578) Perbaikan tambah anggota kelompok/lembaga selalu gagal disimpan.
12. [#7579](https://github.com/OpenSID/premium/issues/7579) Perbaikan hapus anggota kelompok/lembaga dan dokumen tanpa berkas memicu error unlink direktori upload.
13. [#7580](https://github.com/OpenSID/premium/issues/7580) Perbaikan kembalikan foto bawaan tetap mengubah foto penduduk berstatus mati/pindah/hilang.
14. [#7581](https://github.com/OpenSID/premium/issues/7581) Perbaikan route program bantuan penduduk mengarah ke method yang sudah dihapus (HTTP 500).
15. [#12135](https://github.com/OpenSID/OpenSID/issues/12135) Perbaikan rentang bulan default Perkembangan Penduduk sesuai tahun yang dipilih.
16. [#12136](https://github.com/OpenSID/OpenSID/issues/12136) Perbaikan kematian bulan berjalan yang ditambahkan lewat menu Penduduk Meninggal tidak terhitung di laporan bulanan dan perkembangan penduduk.
17. [#12130](https://github.com/OpenSID/OpenSID/issues/12130) Perbaikan validasi template surat tidak lagi menolak kode isian qr_bsre saat TTE aktif.
18. [#12137](https://github.com/OpenSID/OpenSID/issues/12137) Perbaikan tombol proses atau aksi tidak berfungsi.
19. [#12140](https://github.com/OpenSID/OpenSID/issues/12140) Perbaikan sebutan desa dan kepala desa pada cetak rekapitulasi bulanan kehadiran.
20. [#7642](https://github.com/OpenSID/premium/issues/7642) Perbaikan public/.htaccess v2611.0.0: URL folder pendamping tanpa garis miring (/pbb, /pbb3, /sp-api) diteruskan ke router dan 404.






### KEAMANAN


1. [#7480](https://github.com/OpenSID/premium/issues/7480) Perbaikan IDOR lintas tipe pada kelompok dan lembaga (ubah data, dokumen, dan anggota).
2. [#7339](https://github.com/OpenSID/premium/issues/7339) Hapus login otomatis dan perbaiki nama kolom verifikasi pada pendaftaran Layanan Mandiri.
3. [#7342](https://github.com/OpenSID/premium/issues/7342) Perbaikan path traversal pada hapus/ganti foto produk Lapak.
4. [#7343](https://github.com/OpenSID/premium/issues/7343) Perbaikan permohonan surat mandiri memakai pemohon dari sesi, cek pemilik, status, dan jenis surat.
5. [#7344](https://github.com/OpenSID/premium/issues/7344) Perbaikan kartu peserta bantuan milik sendiri, selain itu 404.
6. [#7345](https://github.com/OpenSID/premium/issues/7345) Periksa nilai balik validated() pada reset PIN dan tautan reset Layanan Mandiri.
7. [#7483](https://github.com/OpenSID/premium/issues/7483) Perbaikan kembalikan foto bawaan penduduk dapat dijalankan pengguna dengan hak baca saja.
8. [#7534](https://github.com/OpenSID/premium/issues/7534) Perbaikan dan pemulihan otomatis pada berkas folder public bawaan rilis.
9. [#7627](https://github.com/OpenSID/premium/issues/7627) Tambah pemeriksa isi public/ yang melaporkan berkas dan folder tak dikenal (perintah `php artisan opensid:bersihkan-public`).
10. [#7628](https://github.com/OpenSID/premium/issues/7628)  Inventaris dan pembatasan parameter query tak dikenal.
11. [#7338](https://github.com/OpenSID/premium/issues/7338) Wajibkan autentikasi atau tautan bertanda tangan pada unduh surat Layanan Mandiri.
12. [#7340](https://github.com/OpenSID/premium/issues/7340) Login e-KTP tanpa PIN hanya dari anjungan terdaftar dan rate limit berlaku.




### TEKNIS


1. [#7591](https://github.com/OpenSID/premium/issues/7591) Update sail ke PHP 8.5 dan tambahkan playwright di container.
2. [#7622](https://github.com/OpenSID/premium/issues/7622) Kunci skalar di desa/config/config.php dibaca sebagai variabel environment (contoh app_url menjadi APP_URL), sehingga app_url, prodeskel_otomasi_callback_secret, dan kunci lain dapat diatur tanpa .env. Penyalinan manual server_layanan, token_layanan, demo_mode, user_admin, serta kunci layanan Google/Mapbox ke config Laravel diganti alur env tersebut.
3. [#7461](https://github.com/OpenSID/premium/issues/7461) TinyMCE tidak lagi dipasang lewat composer, plugin kustom image dan media bersumber dari storage.
4. [#12127](https://github.com/OpenSID/OpenSID/issues/12127) Penambahan  migration tabel jobs, failed_jobs, dan job_batches untuk queue database.
5. [#7663](https://github.com/OpenSID/premium/issues/7663) Optimasi unit test menghindari muat ulang schema.sql pada test migrasi yang menjalankan DDL.



### TEKNIS KONVERSI LARAVEL


1. [#7476](https://github.com/OpenSID/premium/issues/7476) Perbaikan fondasi cetak penandatangan dan respons cetak/unduh Laravel.
2. [#7479](https://github.com/OpenSID/premium/issues/7479) Kebijakan dan pola route GET /clear pada panduan refaktor.
3. [#7477](https://github.com/OpenSID/premium/issues/7477) Fondasi UploadService dan rule UploadFile pengganti trait upload.
4. [#7600](https://github.com/OpenSID/premium/issues/7600) Kebijakan dan pola token CSRF untuk pemanggilan lintas tier pada panduan refaktor.
5. [#7600](https://github.com/OpenSID/premium/issues/7600) Kebijakan dan pola token CSRF untuk pemanggilan lintas tier pada panduan refaktor.
6. [#7517](https://github.com/OpenSID/premium/issues/7517) Refaktor Laporan APBDes (laporan-apbdes) dan Laporan Penduduk (laporan-penduduk) ke Laravel native.





