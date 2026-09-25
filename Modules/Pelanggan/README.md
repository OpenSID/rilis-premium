# Modul Pelanggan

Modul **klien Layanan (langganan) OpenDesa** untuk OpenSID — klien langganan,
verifier token berlangganan (RS256 / tolak `alg=none` / integritas respons),
pinned public key, semantik tingkat (tier) & entitlement, halaman perbarui token,
serta pendaftaran kerjasama.

Dikemas sebagai **add-on OpenSID** (kontrak `module.json` + `Providers/PelangganServiceProvider`).
Berbeda dengan add-on berbayar (mis. Anjungan), modul ini `requires_entitlement:false`
& `removable:false` — ia lapisan langganan inti; Premium mem-*bundle* saat rilis, Umum
mengunduhnya saat pertama diminta. Struktur mengikuti template modul OpenSID terbaru
(`opensid-modules/*`).

## Struktur

```
Config/            konfigurasi modul + config/layanan.php (jwt_public_key, jwt_algo, tolak_alg_none) + webhook-client.php (spatie, port premium#7060)
Database/Migrations/ migrasi webhook_calls (port premium#7060, dijalankan core via Migrasi_module)
Http/Controllers/  PelangganController, PendaftaranKerjasamaController, TokenController
Jobs/              ProcessPelangganWebhookJob (port premium#7060, run-now refreshLangganan)
Models/            WebhookCall (port premium#7060, extends spatie + ConfigId)
Providers/         PelangganServiceProvider (registrasi ke seam netral core + webhook)
Routes/            web.php (pelanggan, peringatan, pendaftaran_kerjasama, token; route webhook Laravel didefinisikan langsung di Provider)
Services/          PelangganService, CekService, TokenDecoder + Exceptions + Webhook/PelangganSignatureValidator
Views/             tampilan pelanggan, perpanjang_layanan, pendaftarankerjasama, token
module.json        metadata add-on (nama, versi, blok require)
```

## Seam core yang didaftarkan (`PelangganServiceProvider::boot`)

Core OSS **tak punya pengetahuan compile-time** soal modul ini; ia hanya memanggil seam
netral (null-object default = perilaku "tanpa lapisan berbayar"). Modul mendaftar ke:

| Seam core | Diisi dengan |
|---|---|
| `RequestGuard` | `CekService::validasi()` / `validasiVersi()` |
| `EntitlementGate('premium')` | `CekService::validasiAkses()` |
| `AdminNoticeProvider` | banner `PelangganService::statusLangganan()` / `statusPercobaan()` |
| `PerbaruiLangganan` | `PelangganService::perbaruiLangganan()` |
| `SumberTemaPremium` | `PelangganService::apiPelangganPemesanan()` |
| `FeatureStatusSource` | keaktifan fitur (mis. `anjungan`) dari data langganan |
| `LayananAktif` | tingkat layanan aktif ← `PelangganService::getLayananAktifTier()` |

## Status: ekstraksi (Fase D)

Repo ini dibuat sebagai bagian pemisahan klien Layanan dari core OpenSID menjadi
add-on terenkripsi. Lihat issue perencanaan: `OpenSID/premium#6682`.

**Belum termasuk pada commit awal ini** (ditangguhkan ke Fase D lanjutan / E / F):
- Refactor testability: `PelangganService` → `LayananClient` (I/O Guzzle injectable) +
  `StatusLangganan` DTO + fungsi murni notif/tier; `CekService` constructor-inject CI & key.
- Pipeline build **IonCube** (artefak terenkripsi per versi PHP) + pinned public key di dalam artefak.
- Jalur pasang standar (`Plugin`/`Install_modul` → `Migrator`) agar Premium & Umum memasang seragam (Fase E).
