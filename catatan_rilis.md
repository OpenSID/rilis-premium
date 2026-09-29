Rilis versi 2610.0.0 ini berisi [untuk diisi] dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR


1. [#11913](https://github.com/OpenSID/OpenSID/issues/11913) Penambahan fitur tambahan ucwords pemisah romawi.
2. [#11990](https://github.com/OpenSID/OpenSID/issues/11990) Penambahan fitur penyesuaian yang tanda tangan untuk rekapitulasi kehadiran.
3. [#7130](https://github.com/OpenSID/premium/issues/7130) Penyesuaian route, controller & menu publik untuk statistik desil DTSEN (khusus tema lestari).
4. [#11601](https://github.com/OpenSID/OpenSID/issues/11601) Penambahan fitur surat keterangan kelahiran tidak dibatasi SHDK.


### BUG

1. [#7088](https://github.com/OpenSID/premium/issues/7088) Perbaikan tombol unduh pada tema tetap aktif meskipun tema telah terdownload dan terinstall.
2. [#7155](https://github.com/OpenSID/premium/issues/7155) Perbaikan tampilan modal pasang modul pada halaman Paket Tambahan.
3. [#7089](https://github.com/OpenSID/premium/issues/7089) Perbaikan riwayat pemesanan pada paket tambahan selalu kosong tidak ada data, padahal response dari API ada datanya.


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
