Rilis versi 2608.0.1 ini berisi perbaikan celah keamanan dan perbaikan lainnya yang diminta oleh komunitas SID.


### BUG

1. [#11514](https://github.com/OpenSID/OpenSID/issues/11514) Perbaikan data peserta program bantuan tidak terupdate ketika data penduduk peserta dirubah nik.
2. [#11522](https://github.com/OpenSID/OpenSID/issues/11522) Perbaikan tampilan pengunjung pada menu Admin Web > Pengunjung.
3. [#11524](https://github.com/OpenSID/OpenSID/issues/11524) Perbaikan pengaturan high pada table di tamplate surat berbeda ketika ditinjau pdf.
4. [#11523](https://github.com/OpenSID/OpenSID/issues/11523) Perbaikan gagal memperbarui token setelah perpanjangan layanan pada beberapa desa.
5. [#11527](https://github.com/OpenSID/OpenSID/issues/11527) Perbaikan tidak dapat menambahkan syarat surat.
6. [#11530](https://github.com/OpenSID/OpenSID/issues/11530) Perbaikan sasaran terdata muncul huruf aneh berupa "e" pada edit anggota suplemen.
7. [#11529](https://github.com/OpenSID/OpenSID/issues/11529) Perbaikan tampilkan otomatis website desa saat ini jika inputan website pada identitas desa kosong.
8. [#11539](https://github.com/OpenSID/OpenSID/issues/11539) Perbaikan error 500 Call to undefined function ci_route() pada dropdown notifikasi di header saat mengakses controller Laravel native.
9. [#11534](https://github.com/OpenSID/OpenSID/issues/11534) Perbaikan data penduduk mati masih terbaca ketika menambah data peserta bantuan.


### KEAMANAN

1. [6793](https://github.com/OpenSID/premium/issues/6793) Perbaikan celah LFI pada asset dan file storage desa.
2. [6807](https://github.com/OpenSID/premium/issues/6807) Perbaikan RCE via Insecure Deserialization pada endpoint /index.php/tampil/.
3. [6808](https://github.com/OpenSID/premium/issues/6808) Penambahan artisan command app:key-rotate untuk rotasi APP_KEY aman (multitenant).
4. [#6810](https://github.com/OpenSID/premium/issues/6810) Perbaikan vulnerability pada paket npm xlsx (SheetJS).
5. [#6798](https://github.com/OpenSID/premium/issues/6798) Perbaikan celah keamanan  validasi tema tidak memindai isi file PHP.




### TEKNIS

1. [#11521](https://github.com/OpenSID/OpenSID/issues/11521) Perbaikan cache tema yang tidak terhapus saat mengganti tema.