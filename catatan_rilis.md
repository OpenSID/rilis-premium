Rilis versi 2610.0.1 ini berisi perbaikan filter desil tidak menampilkan data dan perbaikan lainnya yang diminta oleh komunitas SID.



### BUG

1. [#12018](https://github.com/OpenSID/OpenSID/issues/12018) Perbaikan tampilan sub menu tema di menu admin web tidak rapi.
2. [#12013](https://github.com/OpenSID/OpenSID/issues/12013) Perbaikan tampilan mobile sub menu paket tambahan tidak rapi.
3. [7228](https://github.com/OpenSID/premium/issues/7228) Perbaikan API pembangunan menampilkan data pribadi sensitif.
4. [12028](https://github.com/OpenSID/OpenSID/issues/12028) Perbaikan modal ubah kategori lokasi (sub point) tidak menampilkan header dan tombol close.
5. [12025](https://github.com/OpenSID/OpenSID/issues/12025) Perbaikan file mp4 tidak bisa diunggah lewat file manager.
6. [12023](https://github.com/OpenSID/OpenSID/issues/12023) Perbaikan error 500 saat mengakses qrcode surat.
7. [12022](https://github.com/OpenSID/OpenSID/issues/12022) Perbaikan tampilan log keluarga ganda di halaman periksa mengecil.
8. [#12012](https://github.com/OpenSID/OpenSID/issues/12012) Perbaikan isian alamat asal pada f103 di lampiran surat keterangan pindah tidak muncul.
9. [12031](https://github.com/OpenSID/OpenSID/issues/12031) Perbaikan data staff tidak bisa di pilih saat tambah pengguna ketika sudah ada data pengguna dengan data staff tersebut yang di hapus.
10. [12030](https://github.com/OpenSID/OpenSID/issues/12030) Perbaikan URL gambar artikel tidak di-encode, nama file berisi % menghasilkan 400 Bad Request.
11. [12045](https://github.com/OpenSID/OpenSID/issues/12045) Perbaikan saat ingin membuat surat dengan melampirkan foto terjadi error.
12. [12049](https://github.com/OpenSID/OpenSID/issues/12049) Perbaikan error datatables pada halaman Pendapat.
13. [12047](https://github.com/OpenSID/OpenSID/issues/12047) Perbaikan error console validator settings di /pengguna.
14. [12048](https://github.com/OpenSID/OpenSID/issues/12048) Perbaikan gagal captcha salah ketika input pengaduan.
15. [7250](https://github.com/OpenSID/premium/issues/7250) Perbaikan tema pro kembali ke tema bawaan setiap update SiapPakai (fallback destruktif mengunci DB ke esensi).
16. [5](https://github.com/OpenSID/modul-anjungan/issues/5) Perbaikan saat akses sub menu gawai_layanan muncul popup ajax eror.
17. [#29](https://github.com/OpenSID/modul-dtsen/issues/29) Perbaikan filter desil tidak menampilkan data.
18. [#28](https://github.com/OpenSID/modul-dtsen/issues/28) Perbaikan update setting_aplikasi DTSEN gagal Duplicate entry '1-dtsen_skor_LANTAI_1' untuk key 'key_config'.
19. [7274](https://github.com/OpenSID/premium/issues/7274) Perbaikan error call to undefined function mark_pantau_down() saat server Pantau tidak bisa dihubungi di route laravel native.
20. [7280](https://github.com/OpenSID/premium/issues/7280) Perbaikan error 500 undefined array key "masa" pada header admin saat layanan hosting expired.


### TEKNIS

1. [#12024](https://github.com/OpenSID/OpenSID/issues/12024) Penambahan mekanisme override manual ketika konten terdeteksi spam, form tidak langsung menolak, melainkan menampilkan kembali isian beserta tombol "Ini bukan spam, tetap simpan".
2. [#12034](https://github.com/OpenSID/OpenSID/issues/12034) Penonaktifan 2FA untuk akun superadmin saat mode demo.
3. [#12056](https://github.com/OpenSID/OpenSID/issues/12056) Penonaktifan activity log saat hit artikel naik.
4. [7198](https://github.com/OpenSID/premium/issues/7198) Perbaikan duplikat foreign key pada artikel.config_id (artikel_config_fk vs artikel_config_2026_fk).
5. [7045](https://github.com/OpenSID/premium/issues/7045) Salin workflow rilis Umum dari rilis-dev.


### KEAMANAN

1. [#7243](https://github.com/OpenSID/premium/issues/7243) Perbaikan endpoint publik internal_api/peta membocorkan data pribadi penduduk.
2. [#7103](https://github.com/OpenSID/premium/issues/7103) Penambahan mekanisme rotasi APP_KEY untuk mengurangi risiko kebocoran kunci enkripsi lewat UI superadmin.
3. [#7275](https://github.com/OpenSID/premium/issues/7275) Perbaikan kerentanan dari laporan Dependabot npm — braces ≤3.0.3 danpostcss-selector-parser <7.1.6 via @shufo/prettier-plugin-blade (dev-only).

