#!/usr/bin/env bash
#
# build-release.sh — bangun artefak rilis `modul-pelanggan` (ZIP).
#
# ⚠️  TODO IonCube: modul ini WAJIB dienkripsi IonCube sebelum distribusi PUBLIK
#     (penyembunyian pendekatan / concealment monetisasi OpenDesa — BUKAN batas
#     keamanan; penegakan tetap server-side Layanan). Lihat RELEASE.md +
#     inventaris-permukaan-premium-layanan.md §4.3 & rencana-dekopling §0.
#
#     Encoder IonCube TIDAK tersedia di lingkungan dev → skrip menghasilkan ZIP
#     POLOS + penanda `*.PLAIN-UNENCRYPTED`. Jalankan ulang di lingkungan rilis
#     ber-encoder (per versi PHP target) untuk artefak final.
#
# Bentuk artefak = git-archive ber-wrapper `OpenSID-modul-pelanggan-<sha>/...`,
# kompatibel dgn marketplace Layanan & `App\Services\Module\ModuleManager`
# (extractPackage men-*strip* wrapper → `Modules/Pelanggan`).
set -euo pipefail

NAME="Pelanggan"
OUT="${1:-./dist}"
SHA="$(git rev-parse --short HEAD)"
WRAP="OpenSID-modul-pelanggan-${SHA}"
STAGE="$(mktemp -d)"
trap 'rm -rf "$STAGE"' EXIT

mkdir -p "$OUT"
OUT="$(cd "$OUT" && pwd)"   # absolutkan agar aman lintas-cd

# 1) Ekspor pohon terlacak ke wrapper dir (hormati .gitattributes export-ignore).
git archive --format=tar --prefix="${WRAP}/" HEAD | tar -xf - -C "$STAGE"

# 2) Langkah enkripsi IonCube (TODO — placeholder, JANGAN dianggap final).
ENCRYPTED=0
if command -v ioncube_encoder >/dev/null 2>&1; then
    # TODO(IonCube): panggil encoder nyata di sini, per versi PHP target. Encode
    # seluruh *.php di "$STAGE/$WRAP" in-place. Pertimbangkan biarkan `Config/*.php`
    # clear bila perlu diedit admin. Set ENCRYPTED=1 setelah encode berhasil.
    echo "⚠️  [ioncube] encoder terdeteksi, tapi langkah encode BELUM diimplementasi (TODO)."
    echo "    Artefak tetap POLOS sampai cabang ini diisi — lihat RELEASE.md."
else
    echo "⚠️  [ioncube] encoder TIDAK ditemukan — artefak POLOS."
    echo "    JANGAN distribusi publik dalam bentuk polos. Lihat RELEASE.md."
fi

# 3) Kemas ZIP.
ZIP="$OUT/${NAME}.zip"
rm -f "$ZIP"
( cd "$STAGE" && zip -rq "$ZIP" "$WRAP" )

# 4) Penanda artefak belum-terenkripsi (dipangkas bila ENCRYPTED=1).
MARK="$OUT/${NAME}.PLAIN-UNENCRYPTED"
if [ "$ENCRYPTED" = "1" ]; then
    rm -f "$MARK"
else
    # GitHub Release API menolak aset 0 byte ("size must be >= 1") -- isi
    # penanda dengan teks, bukan berkas kosong.
    echo "ZIP ini BELUM dienkripsi IonCube -- jangan distribusi publik. Lihat RELEASE.md." > "$MARK"
fi

echo "artefak : $ZIP"
echo "sha     : $SHA"
echo "encrypted: $ENCRYPTED  (0 = POLOS, jangan distribusi publik)"
