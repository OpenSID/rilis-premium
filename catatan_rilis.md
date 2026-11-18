Rilis versi 2611.0.2 ini berisi perbaikan migrasi kolom relasi dan perbaikan penjaga folder public yang keliru mengarantina alias SiapPakai dan perbaikan lainnya yang diminta oleh komunitas SID.



### BUG


1. [#12169](https://github.com/OpenSID/OpenSID/issues/12169) Perbaikan migrasi gagal memasang foreign key karena kolom relasi masih int, sedangkan kolom ID tabel rujukannya sudah bigint unsigned.
2. [#12172](https://github.com/OpenSID/OpenSID/issues/12172) Perbaikan query widget keuangan yang lambat.
3. [#7724](https://github.com/OpenSID/premium/issues/7724) Perbaikan penjaga public/ mengarantina symlink pendamping sp-api dan pbb sehingga /sp-api dan /pbb 404.



### TEKNIS

1. [#7726](https://github.com/OpenSID/premium/issues/7726) Perintah opensid:migrasi-database kini sama seperti jalur web: tidak berjalan bila database sudah selesai, tambah opsi --full.


### TEKNIS REFRAKTOR LARAVEL

1. [#7568](https://github.com/OpenSID/premium/issues/7568) Refaktor Pesan OpenDK (pesan) ke Laravel native.
2. [#7565](https://github.com/OpenSID/premium/issues/7565) Refaktor Periksa (pemeriksaan & perbaikan data) (periksa*) ke Laravel native.
