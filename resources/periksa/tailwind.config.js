// Konfigurasi Tailwind khusus halaman Periksa Database.
//
// Hasil build (public/assets/css/periksa.min.css) ikut di-commit supaya halaman tetap
// tampil tanpa koneksi internet. Jalankan ulang `npm run build:periksa` setiap kali
// mengubah kelas pada resources/views/periksa/**.
//
// Prefix `tw-` dan preflight dimatikan karena halaman ini masih memuat Bootstrap 3
// (modal, DataTables, Select2) yang memiliki nama kelas serupa (.table, .hidden, .collapse).
module.exports = {
    content: ['./resources/views/periksa/**/*.blade.php'],
    // Varian warna komponen dirangkai di Blade (mis. pk-btn-{{ $warna }}) sehingga tidak
    // terbaca oleh pemindai Tailwind; pertahankan semua kelas komponen pk-*.
    safelist: [{ pattern: /^pk-/ }],
    prefix: 'tw-',
    corePlugins: {
        preflight: false,
    },
    theme: {
        extend: {
            fontFamily: {
                sans: ['ui-sans-serif', 'system-ui', '-apple-system', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', 'sans-serif'],
                mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Consolas', '"Liberation Mono"', 'monospace'],
            },
        },
    },
};
