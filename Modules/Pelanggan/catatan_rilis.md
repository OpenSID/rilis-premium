Rilis versi 2610.0.0-rc.1 ini berisi ekstraksi klien Layanan (langganan) dari core OpenSID menjadi add-on mandiri dan perbaikan lainnya yang diminta oleh komunitas SID.

### Fitur

1. [#6991](https://github.com/OpenSID/premium/issues/6991) Pendaftaran penyedia seam tema bawaan per wilayah dan pembacaan klaim `tema_pro` dari token untuk gerbang entitlement SiapPakai.
2. Pintasan pendaftaran mandiri pada halaman peringatan aktivasi, terhubung ke alur pemesanan layanan.

### Bug

1. [#6988](https://github.com/OpenSID/premium/issues/6988) Perbaikan error saat perpanjang layanan.
2. [#6744](https://github.com/OpenSID/premium/issues/6744) `CekService` menormalkan prefiks `www.` di kedua sisi pembandingan host dan menolak klaim `domain`/`domain_alternatif` tanpa skema `https://`.
3. Alur Ganti Token disatukan (ikon gear), menutup celah macet dan XSS.

### Teknis

1. [#6682](https://github.com/OpenSID/premium/issues/6682) Ekstraksi klien Layanan (`Modules/Pelanggan` + bekas berkas core `app/Services/Layanan/*`, `config/layanan.php`, `donjo-app/controllers/Token.php`) ke repo add-on mandiri; core menjadi nol-verifier / nol `jwt_public_key`, modul mendaftar ke seam netral core.
2. [#6768](https://github.com/OpenSID/premium/issues/6768) Migrasi `config_item()` CI3 ke `config()` Laravel pada `PelangganService`, `CekService`, dan `LayananClient`.
3. [#7029](https://github.com/OpenSID/premium/issues/7029) Manifes `module.json` beralih ke blok `require` gaya composer (`opensid-core >= 2609.0.0`).
4. [#1328](https://github.com/OpenSID/Layanan_OpenDESA/issues/1328) Pipeline GitHub Release dan harness CI modul (`ci.yml`).
