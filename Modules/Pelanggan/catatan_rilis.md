Rilis versi 2611.0.1 ini berisi perbaikan webhook perbarui langganan dari Layanan OpenDESA yang gagal karena config webhook-client modul tertimpa config bawaan Spatie.


### Bug

1. [#19](https://github.com/OpenSID/modul-pelanggan/issues/19) Perbaikan webhook perbarui-langganan gagal karena config webhook-client modul tertimpa config bawaan Spatie, sehingga data langganan desa tidak otomatis terupdate setelah perpanjangan layanan.
2. [#19](https://github.com/OpenSID/modul-pelanggan/issues/19) Perbaikan jadwal pembersihan data webhook_calls yang selalu gagal karena perintah webhook-client:clean tidak tersedia.
