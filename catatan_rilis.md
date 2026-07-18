Rilis versi 2607.0.1 ini berisi perbaikan TTE berhasil diproses tetapi status TTE tidak terdeteksi dan perbaikan lainnya yang diminta oleh komunitas SID.


### BUG

1. [#11443](https://github.com/OpenSID/OpenSID/issues/11443) Perbaikan menambahkan pemeriksaan identitas('email_desa') sebelum mengirim verifikasi.
2. [#11426](https://github.com/OpenSID/OpenSID/issues/11426) Perbaikan TTE berhasil diproses tetapi status TTE tidak terdeteksi dan placeholder qr_code tidak tergantikan pada surat.
3. [#11438](https://github.com/OpenSID/OpenSID/issues/11438) Perbaikan video youtube anjungan gagal diputar karena src iframe ganda.
4. [#11408](https://github.com/OpenSID/OpenSID/issues/11408) Perbaikan gambar latar website dan halaman login admin hilang/tidak terbaca, dan tidak bisa unggah file.
5. [#11414](https://github.com/OpenSID/OpenSID/issues/11414) Perbaikan daftar anggota pada Data Lembaga tidak tampil di halaman website.
6. [#11356](https://github.com/OpenSID/OpenSID/issues/11356) Perbaikan pemanggilan nama kabupaten dan provinsi di rincian pelanggan pada baris desa menggunakan fungsi proper.


### TEKNIS

1. [#11441](https://github.com/OpenSID/OpenSID/issues/11441) Penambahan deteksi spam pada menu artikel.

### KEAMANAN

1. [#6671](https://github.com/OpenSID/premium/issues/6671) Perbaikan keamanan untuk mengatasi celah Privilege Escalation (eskalasi hak akses) serta optimalisasi Mass Assignment pada model User.
2. [#6662](https://github.com/OpenSID/premium/issues/6662) Perbaikan keamanan cegah stored XSS via forgery token pelanggan (unauthenticated).
3. [#6680](https://github.com/OpenSID/premium/issues/6680) Perbaikan kerentanan enumerasi pengguna (user enumeration) pada endpoint /siteman/otp.
4. [#6675](https://github.com/OpenSID/premium/issues/6675) Perbaikan 2FA Toggle Without Password Verification — Pengguna::update_keamanan().
5. [#6672](https://github.com/OpenSID/premium/issues/6672) Perbaikan keamanan IDOR — Citizen Portal Surat Proses.
6. [#6673](https://github.com/OpenSID/premium/issues/6673) Perbaikan keamanan IDOR — Citizen Portal (fmandiri) Surat Cetak, Unauthorized Letter Download.
7. [#6674](https://github.com/OpenSID/premium/issues/6674) Perbaikan keamanan IDOR — Citizen Portal (fmandiri) Pesan Baca, Read Any Citizen's Messages.

