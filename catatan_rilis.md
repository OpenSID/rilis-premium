Rilis versi 2610.0.0 ini berisi Penambahan fitur penyesuaian kepala keluarga meninggal/pindah maka data tersebut dengan sendirinya terhapus secara otomatis di DTSEN dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR


1. [#11913](https://github.com/OpenSID/OpenSID/issues/11913) Penambahan fitur tambahan ucwords pemisah romawi.
2. [#11990](https://github.com/OpenSID/OpenSID/issues/11990) Penambahan fitur penyesuaian yang tanda tangan untuk rekapitulasi kehadiran.
3. [#11601](https://github.com/OpenSID/OpenSID/issues/11601) Penambahan fitur surat keterangan kelahiran tidak dibatasi SHDK.
4. [#11938](https://github.com/OpenSID/OpenSID/issues/11938) Penambahan fitur penyesuaian kepala keluarga meninggal/pindah maka data tersebut dengan sendirinya terhapus secara otomatis di DTSEN.
5. [#7191](https://github.com/OpenSID/premium/issues/7191) Penambahan kategori Statistik DTSEN (Desil Kemensos & Desil Hasil Analisis) pada Statistik Kependudukan dan halaman statistik publik untuk semua tema.
6. [#3](https://github.com/OpenSID/modul-dtsen/issues/3) Penambahan indikator kesehatan DTSEN 428.a S/D 428.j.
7. [#5](https://github.com/OpenSID/modul-dtsen/issues/5) Penambahan fitur pendataan dengan menampilkan data bantuan bantuan yang di terima pada laporan DTSEN.
8. [#4](https://github.com/OpenSID/modul-dtsen/issues/4) Penambahan fitur pengaturan poin desil untuk super admin.
9. [#15](https://github.com/OpenSID/modul-dtsen/issues/15) Penambahan fitur halaman statiktik pada menu DTSEN.
10. [#14](https://github.com/OpenSID/modul-dtsen/issues/14) Penambahan API statistik desil untuk kebutuhan tema.


### BUG

1. [#7088](https://github.com/OpenSID/premium/issues/7088) Perbaikan tombol unduh pada tema tetap aktif meskipun tema telah terdownload dan terinstall.
2. [#7155](https://github.com/OpenSID/premium/issues/7155) Perbaikan tampilan modal pasang modul pada halaman Paket Tambahan.
3. [#7089](https://github.com/OpenSID/premium/issues/7089) Perbaikan riwayat pemesanan pada paket tambahan selalu kosong tidak ada data, padahal response dari API ada datanya.
4. [#11384](https://github.com/OpenSID/OpenSID/issues/11384) Perbaikan posisi TTD cetak Bumindes Penduduk (pamong_ketahui vs pamong_ttd).
5. [#12003](https://github.com/OpenSID/OpenSID/issues/12003) Perbaikan warna area pada peta dihalaman website tidak sama dengan halaman identitas desa.
6. [#12001](https://github.com/OpenSID/OpenSID/issues/12001) Perbaikan penjumlahan di surat keterangan harga tanah tidak terjumlah.
7. [#12002](https://github.com/OpenSID/OpenSID/issues/12002) Perbaikan hasil tinjau pdf dan cetak surat, Lebar baris dan isian dibeberapa tabel tidak rapi.
8. [#11995](https://github.com/OpenSID/OpenSID/issues/11995) Perbaikan status kehadiran tidak sesuai dan tidak terdata di rekapitulasi bulanan.
9. [#11949](https://github.com/OpenSID/OpenSID/issues/11949) Perbaikan tidak muncul tombol untuk passphrase tte di akun kades.
10. [#12006](https://github.com/OpenSID/OpenSID/issues/12006) Perbaikan badge belum terverifikasi generik tanpa hardcode nama modul.



### TEKNIS


1. [#7043](https://github.com/OpenSID/premium/issues/7043) Penerapan workflow rilis — build-release.yml duplikasi & gagal bundling Pelanggan.
2. [#7045](https://github.com/OpenSID/premium/issues/7045) Pipeline rilis otomatis Umum 2701–2709 — finalisasi release-umum.yml + pipeline/ (dari Premium pra-refaktor).
3. [#7031](https://github.com/OpenSID/premium/issues/7031) Konversi gerbang kompatibilitas core ke blok require gaya composer.json.
4. [#7047](https://github.com/OpenSID/premium/issues/7047) Membuang field priority dari skema module.json.
5. [#7121](https://github.com/OpenSID/premium/issues/7121) Opsi opensid:bersih-folder untuk mengeluarkan daftar path file kandidat (bukan cuma ringkasan).
6. [#11996](https://github.com/OpenSID/OpenSID/issues/11996) Perbaikan error Larastan dan testing DtsenStatistikDesilTest.
7. [#11997](https://github.com/OpenSID/OpenSID/issues/11997) Bersihkan file dan folder dari rilis produksi.
8. [#11999](https://github.com/OpenSID/OpenSID/issues/11999) Perbaikan testing di lingkungan wsl dan codespace.
9. [#12000](https://github.com/OpenSID/OpenSID/issues/12000) Saat mode demo agar bisa ganti identitas desa.
10. [#7173](https://github.com/OpenSID/premium/issues/7173) Menghapus Duplikat foreign key konflik pada tweb_penduduk_mandiri.id_pend (ON DELETE CASCADE vs SET NULL).
11. [#7159](https://github.com/OpenSID/premium/issues/7159) Satukan mekanisme migrasi custom OpenSID dengan artisan migrate standar Laravel.
12. [#7172](https://github.com/OpenSID/premium/issues/7172) Bedakan pesan "modul tidak ditemukan" vs tidak memiliki migrasi di opensid:module.
13. [#7166](https://github.com/OpenSID/premium/issues/7166) Backup/restore Database Gabungan (multi-desa) bisa dijalankan via CLI (`php artisan opensid:multidb-backup` / `opensid:multidb-restore`).
14. [#7167](https://github.com/OpenSID/premium/issues/7167) Backup folder desa bisa dijalankan via CLI (`php artisan opensid:desa-backup`).
15. [#7196](https://github.com/OpenSID/premium/issues/7196) Update tema Esensi, Wira, Palanta, Seruit-lite, Lestari.