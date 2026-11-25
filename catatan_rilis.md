Rilis versi 2611.1.0 ini berisi tentang melanjutkan  refaktor laravel 13 dan perbaikan lainnya yang diminta oleh komunitas SID.



### KEAMANAN


1. [#12173](https://github.com/OpenSID/OpenSID/issues/12173) Perbaikan eskalasi hak akses melalui pengelolaan pengguna dan grup pengguna.
2. [#7421](https://github.com/OpenSID/premium/issues/7421) Blokir query database dari dalam render view tema.



### BUG


1. [#12168](https://github.com/OpenSID/OpenSID/issues/12168) Perbaikan error tinjau surat saat gunakan kop berupa image.
2. [#12143](https://github.com/OpenSID/OpenSID/issues/12143) Perbaikan hapus data penduduk error 500 karena foreign key ke tweb_penduduk tidak CASCADE.
3. [#12179](https://github.com/OpenSID/OpenSID/issues/12179) Perbaikan aktivasi tema Pro/Premium tidak nempel — tampilan publik balik ke Esensi dan setting ikut revert.
4. [#12174](https://github.com/OpenSID/OpenSID/issues/12174) Perbaikan logo kembali ke default opensid ketika akses menu identitas desa/lokasi kantor desa.
5. [#12199](https://github.com/OpenSID/OpenSID/issues/12199) Perbaikan nformasi tema yang aktif tidak sesuai, pada pengaturan yang aktif adalah silir, tapi aktual website menggunakan batuah.
6. [#12180](https://github.com/OpenSID/OpenSID/issues/12180) Perbaikan pengguna aktif tidak muncul di filter aktif maupun tidak aktif (jumlah filter tidak cocok).
7. [#12207](https://github.com/OpenSID/OpenSID/issues/12207) Perbaikan pemilih tahun di Rincian Realisasi keuangan berpindah ke halaman Grafik karena parameter jenis laporan hilang.
8. [#12191](https://github.com/OpenSID/OpenSID/issues/12191) Perbaikan error kolom config.pamong_id dan crash data subjek desa pada analisis.
9. [#12214](https://github.com/OpenSID/OpenSID/issues/12214) Perbaikan marker kantor di peta selalu berlabel 'Kantor Desa'.
10. [#12178](https://github.com/OpenSID/OpenSID/issues/12178) Perbaikan Logo tidak tampil pada surat ketika logo desa masih default.
11. [#12198](https://github.com/OpenSID/OpenSID/issues/12198) Perbaikan gagal aktivasi tema padahal desa sudah memiliki langganan tema pro tersebut.
12. [#12201](https://github.com/OpenSID/OpenSID/issues/12201) Perbaikan nama pemohon di form cetak surat tidak berubah setelah biodata diubah lewat modal.
13. [#12193](https://github.com/OpenSID/OpenSID/issues/12193) Perbaikan unggah berkas lebih dari 2 MB di File Manager gagal.
14. [#12206](https://github.com/OpenSID/OpenSID/issues/12206) Perbaikan lupa PIN Layanan Mandiri via Telegram selalu gagal dengan pesan "Via wajib diisi."
15. [#7768](https://github.com/OpenSID/premium/issues/7768) Perbaiki aset bawaan assets/ dan salin simbol bawaan yang gagal sejak path pindah ke public/.
16. [#12216](https://github.com/OpenSID/OpenSID/issues/12216) Perbaikan data pemohon non-warga kosong di halaman Periksa Surat, padahal data sudah tersimpan.




### TEKNIS

1. [#7663](https://github.com/OpenSID/premium/issues/7663)  Optimasi unit test menghindari muat ulang schema.sql pada test migrasi yang menjalankan DDL.
2. [#7829](https://github.com/OpenSID/premium/issues/7829) Update tema Lestari pada opensid premium.
3. [#12163](https://github.com/OpenSID/OpenSID/issues/12163) Sinkronisasi tema bawaan cukup 3 perintah, tanpa baseline.
4. [#7781](https://github.com/OpenSID/premium/issues/7781) Perbaiki peringatan token pelanggan kosong atau tidak valid.




### TEKNIS REFAKTOR LARAVEL


1. [#7566](https://github.com/OpenSID/premium/issues/7566) Refaktor Notifikasi & Profil Pengguna (notif, notifikasi, pengguna (profil)) ke Laravel native.
2. [#7547](https://github.com/OpenSID/premium/issues/7547) Refaktor Daftar Persil (persil) ke Laravel native.
3. [#7557](https://github.com/OpenSID/premium/issues/7557) Refaktor Pengaturan Modul ke Laravel native.
4. [#7523](https://github.com/OpenSID/premium/issues/7523) Refaktor Buku Tanah Kas Desa, Tanah di Desa & Inventaris Kekayaan (buku-tanah-kas-desa, buku-tanah-di-desa, buku-inventaris-dan-kekayaan-desa).
5. [#7520](https://github.com/OpenSID/premium/issues/7520) Refaktor Produk Hukum (produk-hukum).
6. [#7567](https://github.com/OpenSID/premium/issues/7567) Refaktor Sinkronisasi OpenDK (sinkronisasi-opendk).
7. [#7525](https://github.com/OpenSID/premium/issues/7525) Refaktor Buku Administrasi Pembangunan (3 buku + Kader) (administrasi-pembangunan, bumindes-*)
8. [#7560](https://github.com/OpenSID/premium/issues/7560) Refaktor Grup Pengguna (pengaturan-grup).
9. [#7559](https://github.com/OpenSID/premium/issues/7559) Refaktor Pengguna.
10. [#7558](https://github.com/OpenSID/premium/issues/7558) Refaktor Pengaturan Aplikasi.
11. [#7536](https://github.com/OpenSID/premium/issues/7536) Refaktor Pengaturan Web (pengaturan-web).
12. [#7552](https://github.com/OpenSID/premium/issues/7552) Refaktor Daftar Kontak & Grup Kontak (daftar-kontak, grup-kontak).
13. [#7521](https://github.com/OpenSID/premium/issues/7521) Refaktor Informasi Publik (informasi-publik).
14. [#7522](https://github.com/OpenSID/premium/issues/7522) Refaktor Buku Administrasi Umum (Ekspedisi, Lembaran & Berita Desa, Arsip Desa) (administrasi-umum, buku-eskpedisi, buku-lembaran-dan-berita-desa, arsip-desa).
15. [#7528](https://github.com/OpenSID/premium/issues/7528) Refaktor Widget (widget).
16. [#7564](https://github.com/OpenSID/premium/issues/7564) Refaktor Paket Tambahan, Shortcut & OTP (paket-tambahan, shortcut).
17. [#7554](https://github.com/OpenSID/premium/issues/7554) Refaktor Pengaduan (pengaduan).
18. [#7531](https://github.com/OpenSID/premium/issues/7531) Refaktor Galeri (galeri).
19. [#7555](https://github.com/OpenSID/premium/issues/7555) Refaktor Beranda admin (beranda, tanpa menu).
20. [#7561](https://github.com/OpenSID/premium/issues/7561) Refaktor Database (database).
21. [#7553](https://github.com/OpenSID/premium/issues/7553) Refaktor Pembangunan (pembangunan).
22. [#7537](https://github.com/OpenSID/premium/issues/7537) Refaktor Sinergi Program (sinergi-program).
23. [#7518](https://github.com/OpenSID/premium/issues/7518) Refaktor Surat Masuk & Surat Keluar (surat-masuk, surat-keluar).
24. [#7524](https://github.com/OpenSID/premium/issues/7524) Refaktor Buku Administrasi Penduduk (5 buku) (administrasi-penduduk, buku-*).
25. [#7516](https://github.com/OpenSID/premium/issues/7516) Refaktor Laporan Kelompok Rentan (laporan-kelompok-rentan).
26. [#7519](https://github.com/OpenSID/premium/issues/7519) Refaktor Klasifikasi Surat (klasifikasi-surat).
27. [#7545](https://github.com/OpenSID/premium/issues/7545) Refaktor Keuangan Laporan (keuangan/laporan).
28. [#7548](https://github.com/OpenSID/premium/issues/7548) Refaktor C Desa (c-desa).
29. [#7551](https://github.com/OpenSID/premium/issues/7551) Refaktor Kirim Pesan (SMS) (kirim-pesan).
30. [#7549](https://github.com/OpenSID/premium/issues/7549) Refaktor Inventaris (6 jenis + mutasi) (inventaris, inventaris-*).
31. [#7546](https://github.com/OpenSID/premium/issues/7546) Refaktor Keuangan: Input Data Manual (input-data).
32. [#7533](https://github.com/OpenSID/premium/issues/7533) Refaktor Teks Berjalan (teks-berjalan).
33. [#7542](https://github.com/OpenSID/premium/issues/7542) Refaktor Vaksin Covid-19 (vaksin).
34. [#7517](https://github.com/OpenSID/premium/issues/7517) Refaktor Laporan APBDes (laporan-apbdes) dan Laporan Penduduk (laporan-penduduk).
35. [#7541](https://github.com/OpenSID/premium/issues/7541) Refaktor Covid-19 (Pendataan & Pemantauan) (pendataan, pemantauan).
36. [#7543](https://github.com/OpenSID/premium/issues/7543) Refaktor Stunting (stunting).
37. [#7544](https://github.com/OpenSID/premium/issues/7544) Refaktor DTKS (dtks).
38. [#7514](https://github.com/OpenSID/premium/issues/7514) Refaktor Statistik Kependudukan (statistik-kependudukan).
39. [#7563](https://github.com/OpenSID/premium/issues/7563) Refaktor QR Code & Optimasi Gambar (qrcode, optimasi-gambar).
40. [#7538](https://github.com/OpenSID/premium/issues/7538) Refaktor Tema (theme).
41. [#7535](https://github.com/OpenSID/premium/issues/7535) Refaktor Pengunjung (pengunjung).
42. [#7530](https://github.com/OpenSID/premium/issues/7530) Refaktor Komentar (komentar). 
43. [#7532](https://github.com/OpenSID/premium/issues/7532) Refaktor Media Sosial (media-sosial).
44. [#7529](https://github.com/OpenSID/premium/issues/7529) Refaktor Menu & Kategori (menu, kategori).
45. [#7414](https://github.com/OpenSID/premium/issues/7414) Analisis adopsi nwidart/laravel-modules untuk refaktor modul ke Laravel-native (modul CI3 tetap berjalan).
46. [#7515](https://github.com/OpenSID/premium/issues/7515) Refaktor Laporan Bulanan.
47. [#7550](https://github.com/OpenSID/premium/issues/7550) Refaktor Laporan Inventaris (laporan-inventaris) + hapus Api_inventaris.
48. [#7474](https://github.com/OpenSID/premium/issues/7474) Sederhanakan resolusi AdminModul dan ambil fallback controller dari prefix nama route.
49. [#7527](https://github.com/OpenSID/premium/issues/7527) Refaktor Artikel & Slider (artikel, slider).
50. [#7540](https://github.com/OpenSID/premium/issues/7540)  Refaktor Pengaturan Peta (lokasi, garis, area, tipe, simbol) (pengaturan-peta, plan, point, garis, line, area, polygon, simbol).
51. [#7539](https://github.com/OpenSID/premium/issues/7539) Refaktor Peta (gis).
52. [#7562](https://github.com/OpenSID/premium/issues/7562) Refaktor Info Sistem (info-sistem).
