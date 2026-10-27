Rilis versi 2610.0.2 ini berisi [untuk diisi] dan perbaikan lainnya yang diminta oleh komunitas SID.

### FITUR


1. [#12019](https://github.com/OpenSID/OpenSID/issues/12019) Penambahan fitur akses menu persetujuan catatan kehadiran untuk administrator dan atasan.
2. [#12026](https://github.com/OpenSID/OpenSID/issues/12026) Penambahan fitur pilihan pelaksana kegiatan pada cetak rekapitulasi kehadiran, dan tampilan nama dan jabatan tidak sejajar.
3. [#5852](https://github.com/OpenSID/OpenSID/issues/5852) Penambahan fitur ubah urutan posisi indikator dan jawaban pada menu indikator dan pertanyaan analisis.
4. [#11102](https://github.com/OpenSID/OpenSID/issues/11102) Penambahan fitur pembeda warna program bantuan aktif (hijau) dan tidak aktif (merah) pada rincian penduduk, keluarga, dan rumah tangga.
5. [#11060](https://github.com/OpenSID/OpenSID/issues/11060) Penambahan fitur statistik kepemilikan akta kelahiran.
6. [#10151](https://github.com/OpenSID/OpenSID/issues/10151) Penambahan modul Pengumuman (Admin Web) untuk menampilkan popup pengumuman di halaman depan web.
7. [#12068](https://github.com/OpenSID/OpenSID/issues/12068) Penambahan fitur tombol lihat dokumen pada halaman dokumen Lembaga/Kelompok.
8. [#12032](https://github.com/OpenSID/OpenSID/issues/12032) Penambahan fitur filter dusun Laporan Bulanan pada halaman Statistik. 
9. [#8532](https://github.com/OpenSID/OpenSID/issues/8532) Penambahan fitur menampilkan data tamu di halaman utama website desa (untuk tema bawaan opensid premium).
10. [#5236](https://github.com/OpenSID/OpenSID/issues/5236) Penambahan fitur galeri video YouTube di album galeri.
11. [#11378](https://github.com/OpenSID/OpenSID/issues/11378) Penambahan absensi dinas luar, izin di jam kerja, dan foto kamera pada kehadiran perangkat.
12. [#11324](https://github.com/OpenSID/OpenSID/issues/11324) Penambahan fitur layer data lapak dengan popup foto produk pada peta admin dan website.
13. [#12084](https://github.com/OpenSID/OpenSID/issues/12084) Penambahan fitur impor data keuangan dari berkas .csv dan .pdf Laporan Realisasi APBDes.
14. [#12054](https://github.com/OpenSID/OpenSID/issues/12054) Penambahan fitur halaman statistik perkembangan penduduk di web.
15. [#9595](https://github.com/OpenSID/OpenSID/issues/9595) Penambahan fitur laporan bulanan pada halaman web.
16. [#12058](https://github.com/OpenSID/OpenSID/issues/12058) Penambahan fitur dokumentasi foto penyelesaian pengaduan masyarakat.



### BUG


1. [#12069](https://github.com/OpenSID/OpenSID/issues/12069) Perbaikan partial widget yang berawalan underscore tidak tampil di halaman depan.
2. [#12070](https://github.com/OpenSID/OpenSID/issues/12070) Perbaikan acak data via web menggantung di "Memulai proses…" tanpa pesan error.
3. [#12071](https://github.com/OpenSID/OpenSID/issues/12071) Perbaikan error 500 firstWhere() on null di halaman Pengguna dan Sinkronisasi pada install baru.
4. [#12086](https://github.com/OpenSID/OpenSID/issues/12086) Perbaikan error halaman periksa ketika klik perbaiki per item dan perbaiki semua halaman hanya me refresh tanpa ada hasil.
5. [#12080](https://github.com/OpenSID/OpenSID/issues/12080) Perbaikan pengguna dengan hak baca masih dapat mengubah pengaturan tahun IDM dan menghapus backup inkremental.
6. [#12087](https://github.com/OpenSID/OpenSID/issues/12087) Perbaikan gagal sinkronisasi data penduduk dari opensid ke opendk.
7. [#12089](https://github.com/OpenSID/OpenSID/issues/12089) Perbaikan warga di Layanan Mandiri dapat menghapus dan mengubah dokumen warga lain atau dokumen desa.




### TEKNIS

1. [#7302](https://github.com/OpenSID/premium/issues/7302) Pembaruan captcha bawaan OpenSID (App\Libraries\Captcha, dipakai di login siteman, lupa sandi, pengaduan, dan komentar artikel saat Google reCAPTCHA tidak aktif) agar tidak mudah ditembus bot/OCR.
2. [#12063](https://github.com/OpenSID/OpenSID/issues/12063) Menonaktifkan override spam artikel pada mode demo.
