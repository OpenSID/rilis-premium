# Rilis `modul-pelanggan` — artefak & IonCube

`modul-pelanggan` adalah **Klien Layanan** (plumbing langganan): memuat verifier RS256
(`TokenDecoder`), pinned public key, `Config/layanan.php`, dan semantik tier/entitlement.
Karena itu artefak rilis **WAJIB dienkripsi IonCube sebelum distribusi PUBLIK** — bukan
sebagai batas keamanan (penegakan tetap **server-side** di Layanan) melainkan
**penyembunyian pendekatan (concealment)** mekanisme monetisasi OpenDesa, agar tak mudah
disalin/di-*leverage* rebrander. Rujukan: `inventaris-permukaan-premium-layanan.md` §4.3
& `rencana-dekopling-pelanggan-klien-layanan.md` §0.

## Status saat ini

- **Encoder IonCube: TIDAK tersedia di lingkungan dev** (dikonfirmasi: tak ada
  `ioncube_encoder`, tak ada IonCube Loader di PHP 8.2/8.4). Sampai encoder tersedia,
  artefak = **ZIP POLOS**, ditandai berkas `dist/Pelanggan.PLAIN-UNENCRYPTED`.
  **⚠️ JANGAN distribusi publik dalam bentuk polos.**
- **Jalur pasang SUDAH TERBUKTI** (tak bergantung enkripsi):
  `Install_modul::pasang` / `Plugin::pasang` → `App\Services\Module\ModuleManager::installFromSource`
  → unduh/ekstrak ZIP → `pastikanPrasyaratKlien` (lolos: `requires_entitlement:false`) →
  min_core (dev-bypass) → migrasi (modul ini tanpa migrasi). Diuji: `php index.php modul
  pasang Pelanggan` merekonstruksi `Modules/Pelanggan` **identik-sumber** di Premium
  (PHP 8.4) & Umum (PHP 8.2).

## Membangun artefak

```bash
./build-release.sh [outdir]   # default ./dist
```

Menghasilkan `dist/Pelanggan.zip` berbentuk `OpenSID-modul-pelanggan-<sha>/…`
(wrapper git-archive) — kompatibel marketplace Layanan & `ModuleManager::extractPackage`
(men-*strip* wrapper → `Modules/Pelanggan`).

## TODO IonCube (langkah rilis final)

1. Sediakan `ioncube_encoder` sesuai **versi PHP target** (encode per-versi PHP).
2. Isi cabang encode di `build-release.sh` (encode seluruh `*.php`; pertimbangkan biarkan
   `Config/*.php` clear bila perlu diedit admin) → set `ENCRYPTED=1`.
3. Pemuatan di desa butuh **IonCube Loader** (`zend_extension`) sesuai versi PHP — **hanya**
   untuk instalasi yang memasang modul ini, **bukan** core Umum (core tetap clear GPL).
4. Distribusi lewat Layanan (`/api/v1/modules`), tergerbang langganan aktif.

## Catatan

- Kompatibilitas core dinyatakan lewat blok `require` gaya `composer.json`
  (`"require": { "opensid-core": ">=2609.0.0" }`, premium#7029) — `min_core`/`max_core`
  lama tak dipakai lagi (masih di-fold oleh core bila ada). `version` dinaikkan ke
  `2609.0.0` (sejajar train core; disiplin SemVer monoton wajib tiap rilis).
- Artefak & `dist/` **tak** di-commit (lihat `.gitignore`); yang di-commit hanya skrip +
  dokumen ini.
