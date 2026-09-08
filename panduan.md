# Panduan Penggunaan Patch "Optimasi Tema"

Patch: **`fitur(asset): layani asset tema langsung sebagai file statis + versi tema`**
Commit: `c5d86f26c3` · Branch: `patch-optimasi-tema` · Basis: `rilis-dev`

---

## 1. Ringkasan

Patch ini mengubah cara URL aset tema dibuat oleh helper `theme_asset()`.

| | Sebelum | Sesudah |
|---|---|---|
| URL aset | `…/theme_asset/<slug>?file=css/app.css&v=<VERSION>` | `…/storage/app/themes/<tema>/assets/css/app.css?v=<VERSION>&tv=<versi-tema>` |
| Cara dilayani | Route PHP `theme_asset/{theme}` → `AssetController@serveTheme` (bootstrap Laravel penuh tiap request) | File statis langsung oleh web server |
| Cache-busting | `v` = versi aplikasi | `v` = versi aplikasi **+** `tv` = versi tema aktif (berubah otomatis saat tema di-update) |

**Tujuan:** menghilangkan overhead PHP untuk setiap file CSS/JS/font/gambar tema, sehingga
halaman publik desa lebih ringan dan hemat resource server.

File yang diubah: `app/Helpers/theme_helper.php` (satu file).

Perubahan detail:

- `theme_asset()` menghasilkan URL langsung ke `base_url($theme->asset_path . '/' . <path> . '?' . <query>)`.
- Ditambah fungsi `asset_uri_split_query()` — memisahkan query string yang menempel pada
  URI aset (mis. tema Wira menulis `theme_asset('css/app.css?v1')`) sebelum path
  di-`rawurlencode` per segmen, supaya `?` tidak berubah jadi `%3F`.
- `theme_version()` memakai memo statis per-request (mengurangi query berulang).
- Route lama `theme_asset/{theme}` **tetap ada** sebagai fallback untuk URL lama yang
  terlanjur ter-cache, tetapi **bukan** fallback otomatis untuk URL statis baru.

---

## 2. Prasyarat

- OpenSID pada basis `rilis-dev` (atau rilis yang setara dengan commit basis di atas).
- Akses untuk mengubah konfigurasi web server (`.htaccess` untuk Apache/OpenLiteSpeed,
  atau blok `location` untuk Nginx).
- Document root OpenSID = **root folder aplikasi** (bukan `public/`), sesuai bawaan OpenSID.

---

## 3. Cara Menerapkan Patch

### Opsi A — lewat Git (disarankan)

```bash
# dari root folder OpenSID
git fetch origin
git cherry-pick c5d86f26c3
# atau, bila memakai berkas patch di dalam arsip ini:
git am 0001-optimasi-tema.patch
```

### Opsi B — terapkan berkas patch tanpa commit

```bash
git apply --stat 0001-optimasi-tema.patch   # lihat ringkasan
git apply --check 0001-optimasi-tema.patch  # uji tanpa menulis
git apply 0001-optimasi-tema.patch          # terapkan
```

### Opsi C — salin manual

Timpa `app/Helpers/theme_helper.php` dengan versi dari arsip ini
(`app/Helpers/theme_helper.php`), lalu jalankan langkah verifikasi di bawah.

---

## 4. WAJIB — Perbarui Konfigurasi Web Server

Aset tema kini diakses pada path `/storage/app/themes/<tema>/assets/...`.
Konfigurasi bawaan OpenSID **memblokir seluruh `/storage`** (kecuali thumbnail tema).
Tanpa penyesuaian ini, semua CSS/JS tema akan 403 dan tampilan publik rusak.

### Apache / OpenLiteSpeed (`.htaccess` di root)

Pada `.htaccess` root, cari blok **"BLOCK STORAGE"** (berasal dari `htaccess.apache.txt`):

```apache
# 2. Block Storage (Kecuali file gambar thumbnail tema)
RewriteCond %{REQUEST_URI} !storage/app/themes/[^/]+/assets/thumbnail/[^/]+\.(jpe?g|png|gif|webp|svg)$ [NC]
RewriteRule ^storage(/|$) - [F,L]
```

Ganti `RewriteCond` tersebut agar **seluruh folder `assets` tema** diizinkan
(bukan hanya `thumbnail`):

```apache
# 2. Block Storage (Kecuali seluruh aset tema)
RewriteCond %{REQUEST_URI} !^/?storage/app/themes/[^/]+/assets/ [NC]
RewriteRule ^storage(/|$) - [F,L]
```

> Folder `storage/app/themes/*/assets/` sudah memiliki `.htaccess` sendiri
> (dari premium#7008) yang menolak eksekusi skrip, jadi hanya file statis
> (css, js, font, gambar) yang benar-benar terlayani.

Setelah itu **muat ulang** konfigurasi (Apache: `apachectl -k graceful`; OLS: restart lsws).

### Nginx

Nginx mengabaikan `.htaccess`. Pada `server { … }` OpenSID, pastikan ada:

```nginx
# izinkan aset tema dilayani sebagai file statis
location ^~ /storage/app/themes/ {
    location ~* \.(css|js|mjs|map|woff2?|ttf|eot|otf|png|jpe?g|gif|webp|svg|ico)$ {
        expires 1y;
        add_header Cache-Control "public";
        try_files $uri =404;
    }
    # selain file statis di atas: tolak
    return 403;
}

# blok umum yang menolak /storage tetap dipertahankan di bawah blok ini
location ~ ^/storage/ { deny all; }
```

Uji & muat ulang: `nginx -t && nginx -s reload`.

---

## 5. Bersihkan Cache

```bash
php artisan optimize:clear
php artisan view:clear
```

Lalu hard-refresh browser (Ctrl+F5). Jika memakai CDN/reverse-proxy cache
(Cloudflare dll.), purge cache untuk path `/storage/app/themes/*`.

---

## 6. Verifikasi

1. Buka halaman depan website desa, buka **DevTools → Network**.
2. Pastikan request CSS/JS tema mengarah ke
   `…/storage/app/themes/<tema>/assets/…?v=<VERSION>&tv=<versi-tema>`
   dengan **status `200`** (atau `304` pada refresh berikutnya).
3. Tidak boleh ada request ke `…/theme_asset/…` untuk halaman baru.
4. `curl -I "https://<situs>/storage/app/themes/<tema>/assets/css/app.css"`
   → `200 OK`, `Content-Type: text/css`.
5. `curl -I "https://<situs>/storage/app/themes/<tema>/assets/x.php"`
   → `403` (proteksi skrip tetap aktif).
6. Update salah satu tema, pastikan nilai `tv=` pada URL ikut berubah (cache-busting).

---

## 7. Rollback

Patch hanya menyentuh satu file, jadi rollback aman:

```bash
git revert c5d86f26c3       # bila sudah di-commit
# atau
git apply -R 0001-optimasi-tema.patch
```

Konfigurasi web server boleh dikembalikan seperti semula, tetapi **tidak wajib** —
mengizinkan `/storage/app/themes/*/assets/` tidak berbahaya. Route fallback
`theme_asset/{theme}` tetap berfungsi untuk versi lama.

---

## 8. Troubleshooting

| Gejala | Penyebab | Solusi |
|---|---|---|
| CSS/JS tema 403, tampilan berantakan | `.htaccess` / Nginx belum diperbarui (langkah 4) | Terapkan aturan pada langkah 4, reload web server |
| Aset 404 padahal file ada | Query string menempel (`app.css?v1`) tidak terpisah, atau path salah huruf besar/kecil | Pastikan patch `asset_uri_split_query()` terpasang; cek nama file di `storage/app/themes/<tema>/assets/` |
| Perubahan CSS tidak muncul | Cache browser/CDN | Ganti/naikkan `versi` tema, `optimize:clear`, purge CDN |
| Masih lewat `/theme_asset/...` | View cache lama | `php artisan view:clear` lalu hard-refresh |
| Aset tema desa (`desa/themes/...`) | Path-nya `desa/themes/...`, bukan `storage/...` — sudah diizinkan aturan folder static OpenSID | Tidak perlu tindakan tambahan |

---

## 9. Isi Arsip

```
panduan.md                      berkas ini
0001-optimasi-tema.patch        berkas patch (git am / git apply)
app/Helpers/theme_helper.php    versi final file yang diubah (untuk salin manual)
```
