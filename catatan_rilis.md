Rilis versi 2608.1.0 ini berisi penambahan integrasi data desil Kemensos SIKNg ke laporan DTSEN dan perbaikan lainnya yang diminta oleh komunitas SID.


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


### Optimasi

1. [#11811](https://github.com/OpenSID/OpenSID/issues/11811) Optimasi fitur deteksi spam menggunakan algoritma machine learning (Logistic Regression).


### Teknis 

1. [#6849](https://github.com/OpenSID/premium/issues/6849) Penambahan SSO akses panel admin OpenSID dari OpenKab.
