Rilis versi 2609.0.0 ini berisi penambahan integrasi data desil Kemensos SIKNg ke laporan DTSEN dan perbaikan lainnya yang diminta oleh komunitas SID.


### FITUR

1. [#11532](https://github.com/OpenSID/OpenSID/issues/11532) Penambahan integrasi data desil Kemensos SIKNg ke laporan DTSEN.
2. [#11305](https://github.com/OpenSID/OpenSID/issues/11305) Penambahan fitur data DTSEN otomatis sinkron dengan data keluarga.
3. [#11528](https://github.com/OpenSID/OpenSID/issues/11528) Penambahan fitur  DTSEN filter wilayah.
4. [#11354](https://github.com/OpenSID/OpenSID/issues/11354) Penambahan fitur penerapan pembatasan akses wilayah pada modul Wilayah Administrasi.
5. [#11541](https://github.com/OpenSID/OpenSID/issues/11541) Penambahan fitur validasi template surat kustom sebelum dipakai mencetak.


### BUG

1. [#11787](https://github.com/OpenSID/OpenSID/issues/11787) Perbaikan perintah opensid:module tetap meminta input modul meski tidak ada modul terdeteksi.
2. [#11788](https://github.com/OpenSID/OpenSID/issues/11788) Perbaikan Kolom qr_code_tte tidak ditemukan pada tabel tweb_surat_format setelah restore database lama.
3. [#11533](https://github.com/OpenSID/OpenSID/issues/11533) Perbaikan error keterangan Demografi di DTKS.
4. [#11786](https://github.com/OpenSID/OpenSID/issues/11786) Perbaikan urutan kartu peserta pada menu Bantuan.
5. [#6831](https://github.com/OpenSID/premium/issues/6831) Perbaikan notifikasi permohonan surat baru tidak pernah sampai ke mobile.
6. [#11807](https://github.com/OpenSID/OpenSID/issues/11807) Perbaikan kasus null pada getDokumen ketika dokumen tidak ditemukan.
7. [#11808](https://github.com/OpenSID/OpenSID/issues/11808) Perbaikan beberapa pemanggilan first()->toArray() tanpa null-check pada input pengguna (Notif, fmandiri/Surat, Permohonan_surat_admin).
8. [#11809](https://github.com/OpenSID/OpenSID/issues/11809) Perbaikan User::superAdmin() scope mengembalikan tipe tidak konsisten.
9. [#11896](https://github.com/OpenSID/OpenSID/issues/11896) Perbaikan erorr kode isian tanggal, hari, bulan, dan tahun tidak tampil sesuai.
10. [#11893](https://github.com/OpenSID/OpenSID/issues/11893) Perbaikan data umur penduduk tetap bertambah walaupun sudah berstatus meninggal.
11. [#11837](https://github.com/OpenSID/OpenSID/issues/11837) Perbaikan halaman blank dan gagal unduh file excel pada fitur cetak dan ekspor rekap catatan harian kerja di RekapCatatanController.
12. [#11891](https://github.com/OpenSID/OpenSID/issues/11891) Perbaikan Uncaught TypeError di jquery.validate.min.js (deledonjo) saat klik kontrol layer peta pada halaman Lokasi Pelapak.
13. [#11892](https://github.com/OpenSID/OpenSID/issues/11892) Perbaikan error data pada pemantauan dan rekapitulasi anak 0-2 tahun tidak sesuai.
14. [#11897](https://github.com/OpenSID/OpenSID/issues/11897) Perbaikan error saat perpanjang layanan.
15. [#6831](https://github.com/OpenSID/premium/issues/6831) Perbaikan notifikasi permohonan surat baru tidak pernah sampai ke mobile.


### Optimasi

1. [#11811](https://github.com/OpenSID/OpenSID/issues/11811) Optimasi fitur deteksi spam menggunakan algoritma machine learning (Logistic Regression).


### Teknis 

1. [#6849](https://github.com/OpenSID/premium/issues/6849) Penambahan SSO akses panel admin OpenSID dari OpenKab.
2. [#6889](https://github.com/OpenSID/premium/issues/6899) Standardisasi komponen input pengaturan aplikasi menggunakan modular blade components.

### Keamanan

1. [#6976](https://github.com/OpenSID/premium/issues/6976) Perbaikan keamanan untuk mencegah PHP Object Injection (POI) pada decryption cookie RFM (Rich Filemanager).
2. [#6985](https://github.com/OpenSID/premium/issues/6985) Perbaikan RCE via decrypt(serialize=true) pada gallery.
3. [#7001](https://github.com/OpenSID/premium/issues/7001) Perbaikan keamanan internal_api/peta.
4. [#7020](https://github.com/OpenSID/premium/issues/7020) Perbaikan data sensitif author terekspos di endpoint publik GET /internal_api/arsip — PII + OTP/telegram bocor tanpa autentikasi.
5. [#7021](https://github.com/OpenSID/premium/issues/7021) Perbaikan Data sensitif terekspos via var setting/config di HTML — NIP Camat/Kades, nomor operator, id config, mapbox_key, google_api_key, ip kehadiran dkk tanpa autentikasi.
6. [#7002](https://github.com/OpenSID/premium/issues/7002) Perbaikan internal_api/verifikasi-surat tanpa filter wajib — bocorkan seluruh arsip surat desa + PDF (NIK, alamat, keperluan surat) tanpa auth.