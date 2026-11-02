<script>
// Skrip dimuat bersama header (beranda & halaman lain), jadi dijalankan setelah seluruh halaman siap
// dan mencari elemen di seluruh dokumen (isi halaman bisa berada di pembungkus .lv2 lain).
(function (mulai) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mulai);
    else mulai();
})(function () {
    'use strict';

    var root = document.getElementById('lv-beranda');
    if (!root) return;

    var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
    var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
    var esc = function (s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    };
    var angka = new Intl.NumberFormat('id-ID');
    // Teks untuk pencocokan: huruf kecil tanpa tanda diakritik (dipakai pencarian menyeluruh & daftar Pembangunan)
    var normalCari = function (s) { return String(s == null ? '' : s).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, ''); };
    // Foto pengganti dari pengaturan tema "Foto Tidak Tersedia"
    var fotoKosong = root.getAttribute('data-lv-foto-kosong') || '';
    var fotoAtauKosong = function (url) {
        if (!url) return fotoKosong;
        // URL storage-desa membawa gambar cadangan di parameter "default": yang dicek hanya berkas yang diminta ("path")
        var berkas = url;
        try { var u = new URL(url, location.href); berkas = u.searchParams.get('path') || u.pathname; } catch (e) {}
        return /404-image-not-found/.test(berkas) ? fotoKosong : lokal(url, true);
    };
    var rupiah = function (n) { return 'Rp ' + angka.format(Math.round(Number(n) || 0)); };
    var hemat = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    // Selalu minta ke alamat yang sedang dibuka: route() di server memakai APP_URL yang bisa
    // berbeda domain (www/non-www, http/https, tunnel), dan akan diblokir CSP connect-src 'self'.
    // Asal APP_URL server, dibaca dari URL API yang dibuat route() di server
    var asalServer = (function () {
        var e = document.querySelector('[data-lv-lazy][data-lv-url]');
        try { return e ? new URL(e.getAttribute('data-lv-url'), location.href).origin : null; } catch (x) { return null; }
    })();
    // ketat = true untuk gambar: hanya URL dari APP_URL yang dialihkan, gambar dari layanan luar dibiarkan
    var lokal = function (url, ketat) {
        try {
            var u = new URL(url, location.href);
            if (u.origin === location.origin || (ketat && u.origin !== asalServer)) return u.href;
            return location.origin + u.pathname + u.search;
        } catch (e) {
            return url;
        }
    };

    /* Jam di top bar */
    var jam = $('[data-lv-clock]');
    if (jam) {
        setInterval(function () {
            var d = new Date();
            jam.textContent = String(d.getHours()).padStart(2, '0') + '.' + String(d.getMinutes()).padStart(2, '0');
        }, 15000);
    }

    /* Panel pencarian */
    var tombolCari = $('[data-lv-toggle="lv-search"]');
    var panelCari = document.getElementById('lv-search');
    if (tombolCari && panelCari) {
        var setCari = function (buka, kembalikanFokus) {
            panelCari.hidden = !buka;
            tombolCari.setAttribute('aria-expanded', String(buka));
            if (buka) $('input', panelCari).focus();
            else if (kembalikanFokus) tombolCari.focus();
        };
        tombolCari.addEventListener('click', function () { setCari(panelCari.hidden); });
        $('[data-lv-search-tutup]', panelCari).addEventListener('click', function () { setCari(false, true); });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !panelCari.hidden) setCari(false, true);
        });
        document.addEventListener('click', function (e) {
            if (!panelCari.hidden && !panelCari.contains(e.target) && !tombolCari.contains(e.target)) setCari(false);
        });
        // Beranda: hero sudah punya kotak cari, jadi tombol cari header disembunyikan selama kotak itu terlihat
        var cariHero = $('.lv-hero-search');
        if (cariHero && 'IntersectionObserver' in window) {
            new IntersectionObserver(function (entri) {
                var terlihat = entri[0].isIntersecting;
                tombolCari.classList.toggle('is-sembunyi', terlihat);
                tombolCari.tabIndex = terlihat ? -1 : 0;
                tombolCari.setAttribute('aria-hidden', String(terlihat));
                if (terlihat && !panelCari.hidden) setCari(false);
            }, { rootMargin: '-140px 0px 0px 0px' }).observe(cariHero);
        }
    }

    /* Pencarian menyeluruh (panel cari header & kotak cari hero): halaman dari menu + data API publik.
       Hasil dikelompokkan dengan urutan tetap; Enter tanpa pilihan tetap membuka hasil pencarian artikel. */
    var dataCari = null;
    try { dataCari = JSON.parse(($('[data-lv-cari-sumber]') || {}).textContent || 'null'); } catch (e) {}
    // Penanda dipasang pada teks asli lalu tiap potongan di-escape, agar kata kunci tidak cocok di dalam entitas HTML (&amp;, &lt;)
    var tandaiCari = function (teks, kata) {
        var pola = kata.split(/\s+/).filter(Boolean).map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }).join('|');
        if (!pola) return esc(teks);
        return String(teks == null ? '' : teks).split(new RegExp('(' + pola + ')', 'gi')).map(function (bagian, i) {
            return i % 2 ? '<mark>' + esc(bagian) + '</mark>' : esc(bagian);
        }).join('');
    };
    // Tiap sumber: jumlah hasil tampil (n), parameter tambahan, & cara membaca satu item API menjadi {judul, meta, url, baru (tab baru)}.
    // API diminta dua kali lipat lalu yang judulnya cocok didahulukan (pencarian API juga mencocokkan isi/keterangan).
    var POLA_CARI = {
        // fields: tanpa isi artikel (4 artikel dengan isi bisa ~300 KB)
        artikel: { n: 4, param: '&fields[artikel]=id,judul,slug,tgl_upload,id_kategori', item: function (a) {
            return { judul: a.judul, meta: [a.category && a.category.kategori, a.tgl_upload_local].filter(Boolean).join(' · '), url: lokal(a.url_slug) };
        } },
        lapak: { n: 3, item: function (a, s) {
            var diskon = Number(a.harga_diskon) > 0 && Number(a.harga_diskon) < Number(a.harga);
            return { judul: a.nama, meta: rupiah(diskon ? a.harga_diskon : a.harga) + (a.satuan ? ' / ' + a.satuan : ''), url: s.semua + encodeURIComponent(a.nama) };
        } },
        pembangunan: { n: 3, item: function (a, s) {
            return { judul: a.judul, meta: ['Tahun ' + a.tahun_anggaran, a.lokasi].filter(Boolean).join(' · '), url: s.semua.replace(/\?cari=$/, '') + '/' + a.slug };
        } },
        produk_hukum: { n: 3, item: function (a, s) {
            return { judul: a.nama, meta: [a.kategori, a.tahun].filter(Boolean).join(' · '), url: a.satuan ? lokal(a.satuan, true) : s.semua + encodeURIComponent(a.nama), baru: !!a.satuan };
        } },
        informasi_publik: { n: 3, item: function (a, s) {
            return { judul: a.nama, meta: [a.kategori, a.tahun].filter(Boolean).join(' · '), url: a.satuan ? lokal(a.satuan, true) : s.semua + encodeURIComponent(a.nama), baru: !!a.satuan };
        } }
    };

    // Halaman dari menu: semua kata harus ada di judul/induk; judul yang diawali kata kunci didahulukan
    var cariHalaman = function (kata) {
        var kataN = normalCari(kata).split(/\s+/).filter(Boolean);
        return dataCari.halaman.filter(function (h) {
            var teks = normalCari(h.judul + ' ' + h.induk);
            return kataN.every(function (k) { return teks.indexOf(k) !== -1; });
        }).sort(function (a, b) {
            return (normalCari(b.judul).indexOf(kataN[0]) === 0) - (normalCari(a.judul).indexOf(kataN[0]) === 0);
        }).slice(0, 5).map(function (h) {
            return { judul: h.judul, meta: h.induk, url: lokal(h.url) };
        });
    };

    // Data API per kata kunci, dipakai bersama saran (panel/hero) dan halaman hasil pencarian agar isinya selalu sama
    var cacheCari = {};
    var ambilHasilCari = function (kata, signal) {
        if (cacheCari[kata]) return Promise.resolve(cacheCari[kata]);
        var hasil = {};
        var kataN = normalCari(kata);
        return Promise.all(dataCari.sumber.map(function (s) {
            var pola = POLA_CARI[s.kunci];
            var url = s.api + '?filter[search]=' + encodeURIComponent(kata) + '&page[size]=' + (pola.n * 2) + (pola.param || '');
            return fetch(lokal(url), { headers: { Accept: 'application/json' }, signal: signal })
                .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
                .then(function (json) {
                    var item = ((json && json.data) || []).map(function (d) { return pola.item(d.attributes, s); });
                    // Judul yang memuat kata kunci didahulukan (urutan asli dipertahankan di dalam tiap golongan)
                    var urut = item.map(function (h, i) { return { h: h, i: i, cocok: normalCari(h.judul).indexOf(kataN) !== -1 ? 0 : 1 }; })
                        .sort(function (a, b) { return a.cocok - b.cocok || a.i - b.i; });
                    hasil[s.kunci] = urut.map(function (x) { return x.h; });
                    // Tidak ada judul yang cocok (hanya cocok di isi): cukup 2 hasil agar tidak mendesak kelompok lain
                    hasil[s.kunci].batas = urut.some(function (x) { return x.cocok === 0; }) ? pola.n : Math.min(2, pola.n);
                })
                .catch(function (e) { if (e.name === 'AbortError') throw e; hasil[s.kunci] = []; });
        })).then(function () {
            // Dokumen yang sama bisa terdaftar di Produk Hukum & Informasi Publik: tampilkan sekali saja
            var terlihat = {};
            dataCari.sumber.forEach(function (s) {
                var daftar = hasil[s.kunci] || [];
                hasil[s.kunci] = daftar.filter(function (h) {
                    var kunci = normalCari(h.judul);
                    if ((s.kunci === 'produk_hukum' || s.kunci === 'informasi_publik') && terlihat[kunci]) return false;
                    terlihat[kunci] = true;
                    return true;
                }).slice(0, daftar.batas || POLA_CARI[s.kunci].n);
            });
            cacheCari[kata] = hasil;
            return hasil;
        });
    };
    var semuaSumber = function (s, kata) { return s.semua.slice(-1) === '=' ? s.semua + encodeURIComponent(kata) : s.semua; };

    var pasangCari = function (form) {
        var input = $('input[name="cari"]', form);
        var wadah = form.parentNode.querySelector('[data-lv-cari-hasil]');
        if (!input || !wadah) return;
        var diPanel = !!form.closest('#lv-search');
        var pengendali = null, tunda = null, aktif = -1;
        wadah.id = input.id + '-hasil';
        wadah.setAttribute('role', 'listbox');
        wadah.setAttribute('aria-label', 'Hasil pencarian');
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-controls', wadah.id);
        input.setAttribute('aria-expanded', 'false');

        var opsi = function () { return $$('[role="option"]', wadah); };
        var pilih = function (i) {
            var semua = opsi();
            semua.forEach(function (o, j) { o.setAttribute('aria-selected', String(j === i)); });
            aktif = semua.length ? (i + semua.length) % semua.length : -1;
            if (aktif < 0) { input.removeAttribute('aria-activedescendant'); return; }
            semua[aktif].setAttribute('aria-selected', 'true');
            input.setAttribute('aria-activedescendant', semua[aktif].id);
            semua[aktif].scrollIntoView({ block: 'nearest' });
        };
        var tutup = function () {
            wadah.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
            aktif = -1;
        };
        var nomor = 0;
        var baris = function (kunci, h, kata) {
            return '<a role="option" id="' + input.id + '-o' + (nomor++) + '" aria-selected="false" class="lv-cari-item" href="' + esc(h.url) + '"'
                + (h.baru ? ' target="_blank" rel="noopener"' : '') + '>'
                + '<span class="lv-cari-item__ikon">' + (dataCari.ikon[kunci] || '') + '</span>'
                + '<span class="lv-cari-item__teks"><span class="lv-cari-item__judul">' + tandaiCari(h.judul, kata) + '</span>'
                + (h.meta ? '<span class="lv-cari-item__meta">' + esc(h.meta) + '</span>' : '') + '</span>'
                + (h.baru ? '<span class="lv-cari-item__luar" aria-label="buka di tab baru">' + dataCari.ikon.luar + '</span>' : '')
                + '</a>';
        };
        var kelompok = function (kunci, judul, hasil, kata, semua) {
            if (!hasil.length) return '';
            return '<div role="group" aria-label="' + esc(judul) + '" class="lv-cari-grup"><p class="lv-cari-grup__judul" aria-hidden="true">' + esc(judul)
                + (semua ? '<a href="' + esc(semua) + '" tabindex="-1">Lihat semua</a>' : '') + '</p>'
                + hasil.map(function (h) { return baris(kunci, h, kata); }).join('') + '</div>';
        };
        var gambar = function (kata, hasilApi, selesai) {
            nomor = 0;
            var halaman = cariHalaman(kata);
            var html = kelompok('halaman', 'Halaman & Layanan', halaman, kata, '');
            var jumlah = halaman.length;
            dataCari.sumber.forEach(function (s) {
                var r = hasilApi[s.kunci];
                if (!r) return;
                jumlah += r.length;
                html += kelompok(s.kunci, s.judul, r, kata, semuaSumber(s, kata));
            });
            if (!selesai) {
                html += '<p class="lv-cari-status">' + (dataCari.ikon.cari || '') + ' Mencari di ' + dataCari.sumber.map(function (s) { return s.judul; }).join(', ') + '…</p>';
            } else if (!jumlah) {
                html += '<div class="lv-cari-kosong"><b>Tidak ada hasil untuk "' + esc(kata) + '"</b>'
                    + '<span>Periksa ejaan atau gunakan kata yang lebih umum, misalnya "jalan", "surat", atau "statistik".</span></div>';
            }
            if (kata.length >= 3) {
                html += '<a role="option" id="' + input.id + '-o' + (nomor++) + '" aria-selected="false" class="lv-cari-item lv-cari-item--semua" href="' + esc(lokal(dataCari.beranda) + '?cari=' + encodeURIComponent(kata)) + '">'
                    + '<span class="lv-cari-item__ikon">' + dataCari.ikon.cari + '</span><span class="lv-cari-item__teks"><span class="lv-cari-item__judul">Lihat semua hasil untuk "' + esc(kata) + '"</span></span></a>';
            }
            html += '<p class="lv-sr" aria-live="polite">' + (selesai ? jumlah + ' hasil ditemukan' : '') + '</p>';
            wadah.innerHTML = html;
            wadah.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            aktif = -1;
            input.removeAttribute('aria-activedescendant');
        };
        var jalankan = function () {
            var kata = input.value.trim().slice(0, 50);
            if (pengendali) pengendali.abort();
            clearTimeout(tunda);
            if (kata.length < 2) { tutup(); return; }
            if (kata.length < 3) { gambar(kata, {}, true); return; } // data API baru dicari mulai 3 huruf
            if (cacheCari[kata]) { gambar(kata, cacheCari[kata], true); return; }
            gambar(kata, {}, false);
            tunda = setTimeout(function () {
                pengendali = ('AbortController' in window) ? new AbortController() : null;
                ambilHasilCari(kata, pengendali ? pengendali.signal : undefined).then(function (hasil) {
                    if (input.value.trim().slice(0, 50) === kata) gambar(kata, hasil, true);
                }).catch(function () {});
            }, 250);
        };

        input.addEventListener('input', jalankan);
        input.addEventListener('focus', function () { if (wadah.hidden && input.value.trim().length >= 2) jalankan(); });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                if (wadah.hidden) return;
                e.preventDefault();
                pilih(aktif < 0 && e.key === 'ArrowUp' ? -1 : aktif + (e.key === 'ArrowDown' ? 1 : -1));
            } else if (e.key === 'Enter' && aktif >= 0 && !wadah.hidden) {
                e.preventDefault();
                opsi()[aktif].click();
            } else if (e.key === 'Escape' && !diPanel && !wadah.hidden) {
                tutup();
            }
        });
        if (!diPanel) {
            document.addEventListener('click', function (e) { if (!form.parentNode.contains(e.target)) tutup(); });
            // HP: kotak cari hero membuka panel cari layar penuh (keyboard layar tidak menutupi hasil)
            input.addEventListener('focus', function () {
                if (!panelCari || !window.matchMedia('(max-width: 760px)').matches) return;
                input.blur();
                $('input', panelCari).value = input.value;
                setCari(true);
            });
        }
    };
    if (dataCari) $$('form[data-lv-cari]').forEach(pasangCari);

    // Halaman hasil pencarian (/?cari=): artikel dari server, sumber lain (halaman, lapak, pembangunan, dokumen)
    // diambil dengan mesin yang sama dengan saran, agar yang terlihat di saran tidak hilang setelah menekan Cari
    var cariLain = $('[data-lv-cari-halaman]');
    if (dataCari && cariLain) {
        var kataLain = cariLain.getAttribute('data-lv-cari-halaman').trim().slice(0, 50);
        var isiLain = $('[data-lv-cari-lain-isi]', cariLain);
        var kartuLain = function (kunci, judul, hasil, semua) {
            if (!hasil.length) return '';
            return '<section class="lv-cari-lain__grup" aria-label="' + esc(judul) + '"><h3 class="lv-cari-grup__judul">' + esc(judul)
                + (semua ? '<a href="' + esc(semua) + '">Lihat semua</a>' : '') + '</h3>'
                + hasil.map(function (h) {
                    return '<a class="lv-cari-item" href="' + esc(h.url) + '"' + (h.baru ? ' target="_blank" rel="noopener"' : '') + '>'
                        + '<span class="lv-cari-item__ikon">' + (dataCari.ikon[kunci] || '') + '</span>'
                        + '<span class="lv-cari-item__teks"><span class="lv-cari-item__judul">' + tandaiCari(h.judul, kataLain) + '</span>'
                        + (h.meta ? '<span class="lv-cari-item__meta">' + esc(h.meta) + '</span>' : '') + '</span>'
                        + (h.baru ? '<span class="lv-cari-item__luar" aria-label="buka di tab baru">' + dataCari.ikon.luar + '</span>' : '')
                        + '</a>';
                }).join('') + '</section>';
        };
        if (kataLain.length >= 2) {
            (kataLain.length >= 3 ? ambilHasilCari(kataLain) : Promise.resolve({})).then(function (hasil) {
                var html = kartuLain('halaman', 'Halaman & Layanan', cariHalaman(kataLain), '');
                dataCari.sumber.forEach(function (s) {
                    if (s.kunci !== 'artikel' && hasil[s.kunci]) html += kartuLain(s.kunci, s.judul, hasil[s.kunci], semuaSumber(s, kataLain));
                });
                if (!html) return;
                isiLain.innerHTML = html;
                cariLain.hidden = false;
                $$('[data-lv-cari-artikel-judul]').forEach(function (j) { j.hidden = false; });
            }).catch(function () {});
        }
    }

    /* Menu seluler */
    var drawer = document.getElementById('lv-drawer');
    var setDrawer = function (buka) {
        drawer.classList.toggle('is-open', buka);
        document.body.style.overflow = buka ? 'hidden' : '';
        if (buka) { var tutup = drawer.querySelector('button'); tutup && tutup.focus(); }
    };
    $$('[data-lv-drawer]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (el === drawer && e.target !== drawer) return;
            setDrawer(el.getAttribute('data-lv-drawer') === 'open');
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer && drawer.classList.contains('is-open')) setDrawer(false);
    });
    // Menu utama disebar selebar header; bila tidak muat satu baris (jumlah menu berbeda tiap desa),
    // header memakai tombol menu seperti di HP agar tidak ada baris kedua yang berisi satu-dua menu saja
    var header = $('.lv-header');
    var navMenu = $('.lv-nav > ul');
    if (header && navMenu) {
        var aturMenu = function () {
            header.classList.remove('lv-nav-ringkas');
            var item = navMenu.children;
            if (!item.length || !navMenu.offsetParent) return;
            var satuBaris = item[item.length - 1].offsetTop === item[0].offsetTop;
            header.classList.toggle('lv-nav-ringkas', !satuBaris);
        };
        aturMenu();
        var tundaMenu;
        window.addEventListener('resize', function () {
            clearTimeout(tundaMenu);
            tundaMenu = setTimeout(aturMenu, 100);
        });
        // Ukuran huruf web (Plus Jakarta Sans) bisa selesai dimuat setelah pengukuran pertama
        document.fonts && document.fonts.ready.then(aturMenu);
    }

    // Submenu di menu HP: buka/tutup (akordeon)
    $$('.lv-drawer__toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sub = document.getElementById(btn.getAttribute('aria-controls'));
            var buka = btn.getAttribute('aria-expanded') !== 'true';
            btn.setAttribute('aria-expanded', String(buka));
            if (sub) sub.hidden = !buka;
        });
    });

    /* Slider hero */
    var slider = $('[data-lv-slider]');
    if (slider) {
        var slides = $$('.lv-hero__slide', slider);
        var dots = $$('[data-lv-goto]', slider);
        var nomor = $('[data-lv-current]', slider);
        var caption = $('[data-lv-caption]', slider);
        var aktif = 0, timer = null;
        var tampil = function (i) {
            aktif = (i + slides.length) % slides.length;
            slides.forEach(function (s, k) {
                s.classList.toggle('is-active', k === aktif);
                s.setAttribute('aria-hidden', String(k !== aktif));
            });
            dots.forEach(function (d, k) { d.classList.toggle('is-active', k === aktif); });
            if (nomor) nomor.textContent = String(aktif + 1).padStart(2, '0');
            var judul = slides[aktif].getAttribute('data-judul');
            var url = slides[aktif].getAttribute('data-url');
            if (caption) {
                caption.hidden = !judul;
                caption.textContent = judul ? judul + (url ? ' →' : '') : '';
                caption.href = url || '#';
            }
        };
        var jalan = function () {
            if (hemat || slides.length < 2) return;
            clearInterval(timer);
            timer = setInterval(function () { tampil(aktif + 1); }, 6000);
        };
        $('[data-lv-next]', slider).addEventListener('click', function () { tampil(aktif + 1); jalan(); });
        $('[data-lv-prev]', slider).addEventListener('click', function () { tampil(aktif - 1); jalan(); });
        dots.forEach(function (d) {
            d.addEventListener('click', function () { tampil(Number(d.getAttribute('data-lv-goto'))); jalan(); });
        });
        slider.addEventListener('mouseenter', function () { clearInterval(timer); });
        slider.addEventListener('mouseleave', jalan);
        jalan();
    }

    /* Tab (arsip, agenda, peta) */
    $$('[data-lv-tabs]').forEach(function (grup) {
        var tabs = $$('[role="tab"]', grup);
        tabs.forEach(function (tab, i) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) {
                    var pilih = t === tab;
                    t.setAttribute('aria-selected', String(pilih));
                    t.tabIndex = pilih ? 0 : -1;
                    var panel = document.getElementById(t.getAttribute('aria-controls'));
                    if (panel) panel.hidden = !pilih;
                });
                var peta = window[tab.getAttribute('data-lv-map')];
                if (peta && peta.invalidateSize) setTimeout(function () {
                    peta.invalidateSize();
                    if (peta.lvFokus) peta.lvFokus();
                }, 50);
            });
            tab.addEventListener('keydown', function (e) {
                var arah = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
                if (!arah) return;
                // Lewati tab yang disembunyikan pemuat (mis. tab Insight tanpa data)
                var j = i;
                do { j = (j + arah + tabs.length) % tabs.length; } while (tabs[j].hidden && j !== i);
                var next = tabs[j];
                next.focus();
                next.click();
            });
        });
    });

    /* Statistik penduduk yang bisa diganti: grafik batang horizontal */
    var stat = $('[data-lv-stat]');
    if (stat) {
        var statMeta = JSON.parse(($('[data-lv-stat-meta]', stat) || {}).textContent || '{}');
        var statTabs = $$('[data-lv-stat-key]', stat);
        var statGrup = $$('[data-lv-stat-grup]', stat);
        var statTahun = $('[data-lv-stat-tahun]', stat);
        var chart = $('[data-lv-chart]', stat);
        var chartRows = $('[data-lv-chart-rows]', stat);
        var chartGrid = $('[data-lv-chart-grid]', stat);
        var chartAxis = $('[data-lv-chart-axis]', stat);
        var chartTip = $('[data-lv-chart-tip]', stat);
        var statSub = $('[data-lv-stat-sub]', stat);
        var tabelBtn = $('[data-lv-stat-tabel]', stat);
        var tabelWrap = $('[data-lv-stat-tabelwrap]', stat);
        var statCache = {};
        var statAktif = null;
        // Urutan baris bermakna (kelompok umur), jangan diurutkan ulang
        var statUrutAsli = ['13', '15'];
        var STAT_MAKS = 10;

        var el = function (tag, cls, teks) {
            var n = document.createElement(tag);
            if (cls) n.className = cls;
            if (teks != null) n.textContent = teks;
            return n;
        };
        var persenTeks = function (p) { return p.toLocaleString('id-ID', { maximumFractionDigits: 1 }) + '%'; };

        // Batas sumbu yang "bulat" (1, 2, 2.5, 5 x 10^n) agar tick mudah dibaca
        var skalaSumbu = function (maks) {
            if (maks <= 0) return { maks: 1, langkah: 1 };
            var kasar = maks / 4;
            var pangkat = Math.pow(10, Math.floor(Math.log10(kasar)));
            var langkah = [1, 2, 2.5, 5, 10].map(function (m) { return m * pangkat; })
                .filter(function (l) { return l >= kasar; })[0];
            langkah = Math.max(1, langkah);
            return { maks: Math.ceil(maks / langkah) * langkah, langkah: langkah };
        };

        // Baris ringkasan dari API dikenali dari id-nya:
        // 666 = jumlah terisi / penerima, 777 = belum mengisi / bukan penerima, 888 = total
        var ID_RINGKAS = { '666': 'terisi', '777': 'kosong', '888': 'total' };

        var olahStat = function (key, data) {
            var ringkas = {};
            var semua = [];
            data.forEach(function (d) {
                var a = d.attributes || {};
                var jenis = ID_RINGKAS[String(d.id)];
                if (jenis) {
                    ringkas[jenis] = { nama: String(a.nama || ''), jumlah: Number(a.jumlah) || 0 };
                    return;
                }
                if (!(Number(a.jumlah) > 0)) return;
                semua.push({ nama: String(a.nama), jumlah: Number(a.jumlah) });
            });
            var total = ringkas.total ? ringkas.total.jumlah : semua.reduce(function (n, b) { return n + b.jumlah; }, 0);

            var grafik = semua.slice();
            if (statUrutAsli.indexOf(key) === -1) {
                grafik.sort(function (x, y) { return y.jumlah - x.jumlah; });
                semua = grafik.slice();
                if (grafik.length > STAT_MAKS) {
                    var sisa = grafik.splice(STAT_MAKS - 1);
                    grafik.push({
                        nama: 'Lainnya (' + sisa.length + ' kategori)',
                        jumlah: sisa.reduce(function (n, b) { return n + b.jumlah; }, 0),
                        lainnya: true
                    });
                }
            }
            return { total: total, grafik: grafik, semua: semua, ringkas: ringkas };
        };

        var sembunyikanTip = function () { chartTip.hidden = true; };
        var tampilkanTip = function (baris, d, total) {
            chartTip.textContent = '';
            var nilai = el('strong', null, angka.format(d.jumlah) + ' ' + statMeta[statAktif].satuan);
            var info = el('span', null, d.nama + ' · ' + persenTeks(total ? d.jumlah / total * 100 : 0));
            chartTip.appendChild(nilai);
            chartTip.appendChild(info);
            chartTip.hidden = false;
            // Letakkan setelah label nilai di ujung batang agar angkanya tidak tertutup
            var bar = baris.querySelector('.lv-chart__bar');
            var kotak = chart.getBoundingClientRect();
            var b = bar.getBoundingClientRect();
            var ujung = baris.querySelector('.lv-chart__val').getBoundingClientRect().right;
            var kiri = Math.min(ujung - kotak.left + 12, kotak.width - chartTip.offsetWidth);
            var muat = kiri >= ujung - kotak.left + 12;
            chartTip.style.left = Math.max(0, kiri) + 'px';
            // Tidak muat di kanan (batang terpanjang): naikkan ke atas batang
            chartTip.style.top = (muat
                ? b.top - kotak.top + b.height / 2 - chartTip.offsetHeight / 2
                : b.top - kotak.top - chartTip.offsetHeight - 6) + 'px';
        };

        var gambarGrafik = function (hasil) {
            var skala = skalaSumbu(Math.max.apply(null, hasil.grafik.map(function (b) { return b.jumlah; })));
            chartGrid.textContent = '';
            chartAxis.textContent = '';
            for (var t = 0; t <= skala.maks; t += skala.langkah) {
                var posisi = (t / skala.maks * 100) + '%';
                var garis = el('span');
                garis.style.left = posisi;
                chartGrid.appendChild(garis);
                var tick = el('span', null, angka.format(t));
                tick.style.left = posisi;
                chartAxis.appendChild(tick);
            }

            chartRows.textContent = '';
            hasil.grafik.forEach(function (d) {
                var persen = hasil.total ? d.jumlah / hasil.total * 100 : 0;
                var baris = el('li', 'lv-chart__row' + (d.lainnya ? ' lv-chart__row--lain' : ''));
                baris.tabIndex = 0;
                baris.setAttribute('aria-label', d.nama + ': ' + angka.format(d.jumlah) + ' ' + statMeta[statAktif].satuan + ', ' + persenTeks(persen));
                var plot = el('span', 'lv-chart__plot');
                var bar = el('span', 'lv-chart__bar');
                var lebar = d.jumlah / skala.maks * 100;
                bar.style.width = lebar + '%';
                var nilai = el('span', 'lv-chart__val', angka.format(d.jumlah));
                nilai.style.left = lebar + '%';
                plot.appendChild(bar);
                plot.appendChild(nilai);
                baris.appendChild(el('span', 'lv-chart__name', d.nama));
                baris.appendChild(plot);
                var tampil = function () { tampilkanTip(baris, d, hasil.total); };
                baris.addEventListener('pointerenter', tampil);
                baris.addEventListener('focus', tampil);
                baris.addEventListener('pointerleave', sembunyikanTip);
                baris.addEventListener('blur', sembunyikanTip);
                chartRows.appendChild(baris);
            });
        };

        var isiTabel = function (key, hasil) {
            var tbody = $('[data-lv-stat-tbody]', stat);
            tbody.textContent = '';
            hasil.semua.forEach(function (d) {
                var tr = el('tr');
                tr.appendChild(el('th', null, d.nama)).setAttribute('scope', 'row');
                tr.appendChild(el('td', null, angka.format(d.jumlah)));
                tr.appendChild(el('td', null, persenTeks(hasil.total ? d.jumlah / hasil.total * 100 : 0)));
                tbody.appendChild(tr);
            });
            ['terisi', 'kosong', 'total'].forEach(function (jenis) {
                var r = hasil.ringkas[jenis];
                if (!r || (jenis !== 'total' && !r.jumlah)) return;
                var tf = el('tr', 'lv-stat__total');
                tf.appendChild(el('th', null, r.nama.charAt(0) + r.nama.slice(1).toLowerCase())).setAttribute('scope', 'row');
                tf.appendChild(el('td', null, angka.format(r.jumlah)));
                tf.appendChild(el('td', null, persenTeks(hasil.total ? r.jumlah / hasil.total * 100 : 0)));
                tbody.appendChild(tf);
            });
            $('[data-lv-stat-caption]', stat).textContent = statMeta[key].label;
        };

        var tampilStat = function (key, hasil) {
            if (key !== statAktif) return;
            stat.classList.remove('is-loading');
            sembunyikanTip();
            if (!hasil.grafik.length) {
                statSub.textContent = 'Belum ada data.';
                chartRows.textContent = chartGrid.textContent = chartAxis.textContent = '';
                $('[data-lv-stat-tbody]', stat).textContent = '';
                return;
            }
            var meta = statMeta[key];
            var r = hasil.ringkas;
            var thn = statTahun ? statTahun.value : '';
            var periode = thn ? (meta.bantuan ? ' · program aktif tahun ' + thn : ' · kondisi akhir ' + thn) : '';
            if (meta.bantuan && r.terisi) {
                statSub.textContent = angka.format(r.terisi.jumlah) + ' ' + meta.satuan + ' penerima dari ' +
                    angka.format(hasil.total) + ' ' + meta.satuan + ' (' + persenTeks(hasil.total ? r.terisi.jumlah / hasil.total * 100 : 0) + ')' + periode;
            } else {
                statSub.textContent = 'Jumlah ' + meta.satuan + ' per kategori · total ' + angka.format(hasil.total) + ' ' + meta.satuan +
                    (r.kosong && r.kosong.jumlah ? ' · ' + angka.format(r.kosong.jumlah) + ' belum mengisi' : '') + periode;
            }
            gambarGrafik(hasil);
            isiTabel(key, hasil);
        };

        var pilihStat = function (key) {
            if (!key || !statMeta[key]) return;
            statAktif = key;
            var grup = statMeta[key].grup;
            statGrup.forEach(function (g) {
                var pilih = g.getAttribute('data-lv-stat-grup') === grup;
                g.setAttribute('aria-selected', String(pilih));
                g.tabIndex = pilih ? 0 : -1;
            });
            statTabs.forEach(function (t) {
                var pilih = t.getAttribute('data-lv-stat-key') === key;
                t.hidden = t.getAttribute('data-grup') !== grup;
                t.setAttribute('aria-selected', String(pilih));
                t.tabIndex = pilih ? 0 : -1;
            });
            $('[data-lv-stat-judul]', stat).textContent = statMeta[key].label;
            $('[data-lv-stat-link]', stat).href = statMeta[key].url;

            var thn = statTahun ? statTahun.value : '';
            var kunci = key + '|' + thn;
            if (statCache[kunci]) return tampilStat(key, statCache[kunci]);
            // Grafik sebelumnya tetap tampil (redup) selama data baru dimuat
            stat.classList.add('is-loading');
            if (!chartRows.children.length) statSub.textContent = 'Memuat data…';
            fetch(lokal(stat.getAttribute('data-lv-url') + '/' + encodeURIComponent(key) + (thn ? '?tahun=' + encodeURIComponent(thn) : '')), { headers: { Accept: 'application/json' } })
                .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
                .then(function (j) {
                    statCache[kunci] = olahStat(key, j.data || []);
                    // Abaikan respons lama bila tahun sudah diganti lagi
                    if ((statTahun ? statTahun.value : '') === thn) tampilStat(key, statCache[kunci]);
                })
                .catch(function () {
                    if (key !== statAktif) return;
                    stat.classList.remove('is-loading');
                    statSub.textContent = 'Data gagal dimuat.';
                });
        };

        statTabs.forEach(function (tab, i) {
            tab.addEventListener('click', function () { pilihStat(tab.getAttribute('data-lv-stat-key')); });
            tab.addEventListener('keydown', function (e) {
                var arah = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
                if (!arah) return;
                var terlihat = statTabs.filter(function (t) { return !t.hidden; });
                var posisi = terlihat.indexOf(tab);
                var next = terlihat[(posisi + arah + terlihat.length) % terlihat.length];
                next.focus();
                next.click();
            });
        });
        // Pindah kelompok: buka statistik terakhir yang dilihat di kelompok itu, atau yang pertama
        var terakhirDiGrup = {};
        statGrup.forEach(function (g, i) {
            g.addEventListener('click', function () {
                var grup = g.getAttribute('data-lv-stat-grup');
                if (statAktif) terakhirDiGrup[statMeta[statAktif].grup] = statAktif;
                var pertama = statTabs.filter(function (t) { return t.getAttribute('data-grup') === grup; })[0];
                pilihStat(terakhirDiGrup[grup] || (pertama && pertama.getAttribute('data-lv-stat-key')));
            });
            g.addEventListener('keydown', function (e) {
                var arah = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
                if (!arah) return;
                var next = statGrup[(i + arah + statGrup.length) % statGrup.length];
                next.focus();
                next.click();
            });
        });
        if (statTahun) statTahun.addEventListener('change', function () { pilihStat(statAktif); });
        tabelBtn.addEventListener('click', function () {
            var tabel = tabelBtn.getAttribute('aria-pressed') !== 'true';
            tabelBtn.setAttribute('aria-pressed', String(tabel));
            tabelBtn.textContent = tabel ? 'Tampilkan grafik' : 'Tampilkan tabel';
            tabelWrap.hidden = !tabel;
            chart.hidden = tabel;
            sembunyikanTip();
        });

        pilihStat(stat.getAttribute('data-lv-awal'));
    }

    /* APB Desa: ganti tahun anggaran (semua tahun sudah dirender di server) */
    var apb = $('[data-lv-apb]');
    var apbPilih = apb && $('[data-lv-apb-pilih]', apb);
    if (apbPilih) {
        apbPilih.addEventListener('change', function () {
            $$('[data-lv-apb-tahun]', apb).forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-lv-apb-tahun') !== apbPilih.value;
            });
        });
    }

    /* Artikel: salin tautan & cetak */
    $$('[data-lv-salin]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var info = $('[data-lv-salin-info]');
            var tautan = btn.getAttribute('data-lv-salin');
            var selesai = function (pesan) { if (info) { info.textContent = pesan; setTimeout(function () { info.textContent = ''; }, 2500); } };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(tautan).then(function () { selesai('Tautan disalin'); }, function () { selesai('Gagal menyalin'); });
            } else {
                var t = document.createElement('textarea');
                t.value = tautan; t.style.position = 'fixed'; t.style.opacity = '0';
                document.body.appendChild(t); t.select();
                try { document.execCommand('copy'); selesai('Tautan disalin'); } catch (e) { selesai('Gagal menyalin'); }
                document.body.removeChild(t);
            }
        });
    });
    $$('[data-lv-cetak]').forEach(function (btn) { btn.addEventListener('click', function () { window.print(); }); });

    /* Hitung mundur acara */
    var cd = $('[data-lv-countdown]');
    if (cd) {
        var target = new Date(cd.getAttribute('data-lv-countdown')).getTime();
        var isi = function (u, v) { var el = $('[data-u="' + u + '"]', cd); if (el) el.textContent = String(v).padStart(2, '0'); };
        var detak = function () {
            var sisa = Math.max(0, Math.floor((target - Date.now()) / 1000));
            isi('d', Math.floor(sisa / 86400));
            isi('h', Math.floor(sisa % 86400 / 3600));
            isi('m', Math.floor(sisa % 3600 / 60));
            isi('s', sisa % 60);
        };
        detak();
        setInterval(detak, 1000);
    }

    /* Geser daftar aparatur */
    $$('[data-lv-scroll]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var el = document.getElementById(btn.getAttribute('data-lv-scroll'));
            el.scrollBy({ left: Number(btn.getAttribute('data-arah')) * 480, behavior: hemat ? 'auto' : 'smooth' });
        });
    });

    /* Sambutan: lipat teks yang lebih tinggi dari batas, buka/tutup dengan tombol */
    $$('[data-lv-lipat]').forEach(function (teks) {
        var tombol = $('[aria-controls="' + teks.id + '"]');
        if (!tombol) return;
        teks.classList.add('is-lipat');
        if (teks.scrollHeight <= teks.clientHeight + 24) {
            teks.classList.remove('is-lipat');
            return;
        }
        tombol.hidden = false;
        tombol.addEventListener('click', function () {
            var buka = teks.classList.toggle('is-lipat') === false;
            tombol.setAttribute('aria-expanded', buka ? 'true' : 'false');
            tombol.textContent = buka ? 'Tutup' : 'Baca selengkapnya';
            if (!buka) teks.scrollIntoView({ block: 'nearest', behavior: hemat ? 'auto' : 'smooth' });
        });
    });

    /* Video: muat iframe YouTube hanya saat diputar */
    var video = $('[data-lv-video]');
    var putar = function (id) {
        video.setAttribute('data-lv-video', id);
        video.innerHTML = '<iframe src="https://www.youtube-nocookie.com/embed/' + esc(id) + '?autoplay=1&rel=0" title="Video" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>';
    };
    if (video) {
        var tombol = $('[data-lv-play]', video);
        tombol && tombol.addEventListener('click', function () { putar(video.getAttribute('data-lv-video')); });
        $$('[data-lv-pick]').forEach(function (b) {
            b.addEventListener('click', function () {
                putar(b.getAttribute('data-lv-pick'));
                video.scrollIntoView({ behavior: hemat ? 'auto' : 'smooth', block: 'center' });
            });
        });
    }

    /* Grafik insight: garis SVG ringan tanpa pustaka tambahan.
       wadah.lvGambar diisi pemuat "insight" agar tombol rentang (1 thn, 3 thn, Semua) bisa menggambar ulang */
    var gambarGarisInsight = function (wadah, label, seri) {
        var NS = 'http://www.w3.org/2000/svg';
        var el = function (tag, attr) {
            var e = document.createElementNS(NS, tag);
            Object.keys(attr || {}).forEach(function (k) { e.setAttribute(k, attr[k]); });
            return e;
        };
        var n = Number(wadah.getAttribute('data-n')) || label.length;
        var mulai = Math.max(0, label.length - n);
        var lab = label.slice(mulai);
        var sr = seri.map(function (x) { return Object.assign({}, x, { data: x.data.slice(mulai) }); });
        var semua = [].concat.apply([], sr.map(function (x) { return x.data; }));
        // Batas sumbu dibulatkan ke kelipatan 4 agar label sumbu Y berupa bilangan bulat
        var maks = Math.ceil(Math.max(1, Math.max.apply(null, semua)) / 4) * 4;
        var min = -Math.ceil(-Math.min(0, Math.min.apply(null, semua)) / 4) * 4;
        var W = 640, H = 260, L = 34, R = 10, T = 14, B = 34;
        var x = function (i) { return L + (lab.length < 2 ? (W - L - R) / 2 : i * (W - L - R) / (lab.length - 1)); };
        var y = function (v) { return T + (maks - v) * (H - T - B) / (maks - min || 1); };
        var svg = el('svg', { viewBox: '0 0 ' + W + ' ' + H, preserveAspectRatio: 'none' });
        for (var t = 0; t <= 4; t++) {
            var v = min + (maks - min) * t / 4;
            var yy = y(v);
            svg.appendChild(el('line', { x1: L, x2: W - R, y1: yy, y2: yy, class: 'lv-grid-line' }));
            var tk = el('text', { x: L - 6, y: yy + 4, 'text-anchor': 'end' });
            tk.textContent = Math.round(v * 10) / 10;
            svg.appendChild(tk);
        }
        var langkah = Math.max(1, Math.ceil(lab.length / 7));
        lab.forEach(function (l, i) {
            var akhir = lab.length - 1;
            if (i !== akhir && (i % langkah || akhir - i < langkah * 0.75)) return;
            var tx = el('text', { x: x(i), y: H - 10, 'text-anchor': i === 0 ? 'start' : i === lab.length - 1 ? 'end' : 'middle' });
            tx.textContent = l;
            svg.appendChild(tx);
        });
        sr.forEach(function (s) {
            var titik = s.data.map(function (v, i) { return x(i) + ',' + y(v); }).join(' ');
            if (s.isi) {
                svg.appendChild(el('polygon', { points: x(0) + ',' + y(0) + ' ' + titik + ' ' + x(s.data.length - 1) + ',' + y(0), style: 'fill:' + s.warna + ';opacity:.12' }));
            }
            svg.appendChild(el('polyline', { points: titik, fill: 'none', style: 'stroke:' + s.warna + ';stroke-width:2.5;stroke-linejoin:round;stroke-linecap:round' + (s.putus ? ';stroke-dasharray:6 5' : '') }));
            s.data.forEach(function (v, i) {
                var c = el('circle', { cx: x(i), cy: y(v), r: lab.length > 24 ? 2 : 3.5, style: 'fill:#fff;stroke:' + s.warna + ';stroke-width:2' });
                var judul = el('title');
                judul.textContent = s.nama + ' · ' + lab[i] + ': ' + v;
                c.appendChild(judul);
                svg.appendChild(c);
            });
        });
        wadah.innerHTML = '';
        wadah.appendChild(svg);
        if (sr.length > 1) {
            var lg = document.createElement('div');
            lg.className = 'lv-ichart__legend';
            lg.innerHTML = sr.map(function (s) { return '<span><i style="background:' + s.warna + '"></i>' + esc(s.nama) + '</span>'; }).join('');
            wadah.appendChild(lg);
        }
    };
    $$('[data-lv-range]').forEach(function (grup) {
        var w = document.getElementById(grup.getAttribute('data-lv-range'));
        $$('button', grup).forEach(function (b) {
            b.addEventListener('click', function () {
                $$('button', grup).forEach(function (x) { x.setAttribute('aria-pressed', String(x === b)); });
                w.setAttribute('data-n', b.getAttribute('data-n'));
                if (w.lvGambar) w.lvGambar();
            });
        });
    });

    /* Pembangunan: data dari api.pembangunan, dipakai bagian beranda, halaman daftar, dan halaman detail */
    var STATUS_PEMB = { selesai: 'Selesai', berjalan: 'Sedang berjalan', belum: 'Belum dimulai' };
    // Data kependudukan tersimpan huruf kapital ("ZAKI ILHAM", "ABD.RAHMAN"): tampilkan "Zaki Ilham", "Abd.Rahman".
    // Huruf setelah tanda hubung tidak dibesarkan agar kata ulang tetap lazim ("Sikabu-kabu").
    var hurufAwal = function (s) {
        return String(s || '').toLowerCase().replace(/(^|[\s.'])(\S)/g, function (m, pisah, huruf) { return pisah + huruf.toUpperCase(); });
    };
    var dataPembangunan = function (a, dasar) {
        var w = a.wilayah || null;
        var rtrw = w ? [['RT', w.rt], ['RW', w.rw]].filter(function (x) { return ['', '-', '0'].indexOf(String(x[1] == null ? '' : x[1]).trim()) === -1; })
            .map(function (x) { return x[0] + ' ' + x[1]; }).join(' / ') : '';
        // API mengisi "lokasi" dengan lokasi_lengkap core ("RT - / RW - - DUSUN") bila lokasi tidak diisi: susun ulang dari wilayah
        var lokasi = w && w.dusun && (!a.lokasi || a.lokasi.indexOf(w.dusun) !== -1) ? [hurufAwal(w.dusun), rtrw].filter(Boolean).join(', ') : (a.lokasi || '');
        var anggaran = Number(a.anggaran) || 0;
        var realisasiRp = Number(a.realisasi_anggaran) || 0;
        var realisasi = Math.min(100, anggaran ? Math.round(realisasiRp / anggaran * 100) : 0);
        var dok = (a.pembangunan_dokumentasi || []).map(function (d) {
            return { persen: parseInt(String(d.persentase).replace(/\D/g, ''), 10) || 0, keterangan: d.keterangan, tanggal: d.created_at, foto: fotoAtauKosong(d.gambar) };
        }).sort(function (x, y) { return x.persen - y.persen; });
        var fisik = dok.length ? Math.min(100, Math.max.apply(null, dok.map(function (d) { return d.persen; }))) : null;
        // Status dari yang terbesar: progres fisik (dokumentasi) atau realisasi anggaran
        var kemajuan = Math.max(fisik || 0, realisasi);
        return {
            a: a, judul: a.judul, url: dasar + '/' + a.slug, foto: fotoAtauKosong(a.foto), tahun: String(a.tahun_anggaran || ''),
            lokasi: lokasi || 'Lokasi belum diisi', dana: (Array.isArray(a.sumber_dana) ? a.sumber_dana : [a.sumber_dana]).filter(Boolean),
            anggaran: anggaran, realisasiRp: realisasiRp, realisasi: realisasi, fisik: fisik, dokumentasi: dok,
            status: kemajuan >= 100 ? 'selesai' : (kemajuan > 0 ? 'berjalan' : 'belum')
        };
    };
    var kartuPembangunan = function (p) {
        return '<a class="lv-build" href="' + esc(p.url) + '" data-tahun="' + esc(p.tahun) + '" data-anggaran="' + p.anggaran + '" data-realisasi="' + p.realisasiRp + '"'
            + ' data-status="' + p.status + '" data-cari="' + esc([p.judul, p.lokasi].concat(p.dana).join(' ')) + '">'
            + '<div class="lv-build__img"><img src="' + esc(p.foto) + '" alt="" loading="lazy"><span class="lv-build__year">' + esc(p.tahun) + '</span>'
            + '<span class="lv-pemb-status lv-pemb-status--' + p.status + '">' + STATUS_PEMB[p.status] + '</span></div>'
            + '<div class="lv-build__body"><span class="lv-build__title">' + esc(p.judul) + '</span>'
            + '<span class="lv-meta">' + esc([p.lokasi, p.dana[0]].filter(Boolean).join(' · ')) + '</span>'
            + '<div style="display:flex;justify-content:space-between;font-size:14px;padding-top:4px"><b>' + rupiah(p.anggaran) + '</b><b style="color:var(--c1-700)">Realisasi ' + p.realisasi + '%</b></div>'
            + '<div class="lv-bar lv-bar--sm"><span style="width:' + p.realisasi + '%"></span></div></div></a>';
    };

    // Halaman daftar: kartu sudah ada di grid; saring tahun & kata kunci, ringkasan, tampilkan bertahap
    var pasangDaftarPembangunan = function (pemb, daftar) {
        var kartuPemb = $$('[data-lv-pemb-grid] > .lv-build', pemb);
        var inputPemb = $('#lv-pemb-cari', pemb);
        var tombolLagi = $('[data-lv-pemb-lagi]', pemb);
        var kosongPemb = $('[data-lv-pemb-kosong]', pemb);
        var grupTahun = $('[data-lv-pemb-tahun]', pemb);
        var LANGKAH = 9;
        var saringPemb = { tahun: '', kata: '', batas: LANGKAH };
        var isi = function (attr, nilai) { var e = $('[' + attr + ']', pemb); if (e) e.textContent = nilai; };

        // Pilihan tahun dari data (tampil bila lebih dari satu tahun)
        var daftarTahun = daftar.map(function (p) { return p.tahun; }).filter(function (t, i, s) { return t && s.indexOf(t) === i; }).sort().reverse();
        if (daftarTahun.length > 1) {
            grupTahun.insertAdjacentHTML('beforeend', daftarTahun.map(function (t) { return '<button type="button" aria-pressed="false" data-tahun="' + esc(t) + '">' + esc(t) + '</button>'; }).join(''));
            grupTahun.hidden = false;
        }

        var terapkan = function () {
            var kata = normalCari(saringPemb.kata);
            var cocok = kartuPemb.filter(function (k) {
                return (!saringPemb.tahun || k.getAttribute('data-tahun') === saringPemb.tahun)
                    && (!kata || normalCari(k.getAttribute('data-cari')).indexOf(kata) !== -1);
            });
            kartuPemb.forEach(function (k) { k.hidden = true; });
            cocok.forEach(function (k, i) { k.hidden = i >= saringPemb.batas; });
            var jumlah = function (fn) { return cocok.reduce(function (t, k) { return t + fn(k); }, 0); };
            isi('data-lv-pemb-jumlah', angka.format(cocok.length));
            isi('data-lv-pemb-anggaran', rupiah(jumlah(function (k) { return Number(k.getAttribute('data-anggaran')) || 0; })));
            isi('data-lv-pemb-realisasi', rupiah(jumlah(function (k) { return Number(k.getAttribute('data-realisasi')) || 0; })));
            isi('data-lv-pemb-selesai', angka.format(jumlah(function (k) { return k.getAttribute('data-status') === 'selesai' ? 1 : 0; })));
            var tampil = Math.min(cocok.length, saringPemb.batas);
            isi('data-lv-pemb-info', cocok.length ? 'Menampilkan ' + angka.format(tampil) + ' dari ' + angka.format(cocok.length) + ' kegiatan' + (saringPemb.tahun ? ' tahun ' + saringPemb.tahun : '') : '');
            tombolLagi.hidden = cocok.length <= saringPemb.batas;
            tombolLagi.textContent = 'Tampilkan lebih banyak (' + angka.format(cocok.length - tampil) + ' lagi)';
            kosongPemb.hidden = !!cocok.length;
            if (!cocok.length) {
                $('[data-lv-pemb-kosong-pesan]', pemb).textContent = 'Tidak ada kegiatan'
                    + (saringPemb.kata ? ' yang cocok dengan "' + saringPemb.kata + '"' : '')
                    + (saringPemb.tahun ? ' pada tahun ' + saringPemb.tahun : '') + '. Coba kata lain atau pilih Semua tahun.';
            }
        };
        var pilihTahun = function (thn) {
            saringPemb.tahun = thn;
            saringPemb.batas = LANGKAH;
            $$('[data-tahun]', grupTahun).forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-tahun') === thn)); });
            terapkan();
        };
        grupTahun.addEventListener('click', function (e) {
            var b = e.target.closest('[data-tahun]');
            if (b) pilihTahun(b.getAttribute('data-tahun'));
        });
        $('[data-lv-pemb-cari]', pemb).addEventListener('submit', function (e) {
            e.preventDefault();
            // anti-csrf.js core mengubah tombol submit jadi "Mohon tunggu..." dan baru memulihkannya saat halaman
            // dimuat ulang atau $.ajax selesai; pencarian ini menyaring di halaman tanpa muat ulang, jadi jangan diteruskan
            e.stopPropagation();
            saringPemb.kata = inputPemb.value.trim();
            saringPemb.batas = LANGKAH;
            terapkan();
        });
        // Menyaring sambil mengetik (data sudah dimuat, tidak membebani server)
        inputPemb.addEventListener('input', function () { saringPemb.kata = inputPemb.value.trim(); saringPemb.batas = LANGKAH; terapkan(); });
        tombolLagi.addEventListener('click', function () {
            var sebelum = saringPemb.batas;
            saringPemb.batas += LANGKAH;
            terapkan();
            // Fokus ke kartu pertama yang baru muncul (pengguna keyboard tidak kembali ke atas)
            var baru = kartuPemb.filter(function (k) { return !k.hidden; })[sebelum];
            if (baru) baru.focus();
        });
        $('[data-lv-pemb-reset]', pemb).addEventListener('click', function () { inputPemb.value = ''; saringPemb.kata = ''; pilihTahun(''); });
        // ?cari= dari pencarian menyeluruh ("Lihat semua")
        var kataUrl = (new URLSearchParams(location.search).get('cari') || '').trim().slice(0, 50);
        if (kataUrl) { inputPemb.value = kataUrl; saringPemb.kata = kataUrl; }
        terapkan();
    };

    /* Halaman tidak ditemukan: tombol kembali hanya bila pengunjung datang dari halaman lain di situs ini */
    var tombolKembali = $('[data-lv-kembali]');
    if (tombolKembali && document.referrer.indexOf(location.origin) === 0 && history.length > 1) {
        tombolKembali.hidden = false;
        tombolKembali.addEventListener('click', function () { history.back(); });
    }

    /* Data dari API: dimuat setelah halaman tampil, kartu/bagian tetap tersembunyi bila gagal atau kosong */
    var ambil = function (url) {
        return fetch(lokal(url), { headers: { Accept: 'application/json' } }).then(function (r) {
            if (!r.ok) throw new Error(r.status);
            return r.json();
        });
    };
    var munculkan = function (el) {
        el.hidden = false;
        var bagian = el.closest('[data-lv-section-lazy]');
        if (bagian) bagian.hidden = false;
    };
    var field = function (el, nama, nilai) { var f = $('[data-lv-field="' + nama + '"]', el); if (f) f.textContent = nilai; };
    var atribut = function (json) { return json && json.data && json.data[0] ? json.data[0].attributes : null; };

    // Ikon garis untuk kartu yang dibuat skrip (path sama dengan $svg di kerangka)
    var ikon = function (d) { return '<svg class="lv-ico" viewBox="0 0 24 24" aria-hidden="true"><path d="' + d + '"/></svg>'; };
    var IKON_WA = 'M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12zM9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2z';
    var IKON_PETA = 'M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21zM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z';
    var IKON_ORANG = 'M20 21v-1a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v1M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z';

    // Kartu produk lapak: bagian Lapak di beranda & halaman lapak
    var kartuProduk = function (item) {
        var a = item.attributes;
        var foto = fotoAtauKosong(a.foto && a.foto[0]);
        var harga = Number(a.harga), hargaDiskon = Number(a.harga_diskon);
        var diskon = hargaDiskon > 0 && hargaDiskon < harga;
        var persen = diskon ? Math.round((1 - hargaDiskon / harga) * 100) : 0;
        var kategori = a.kategori ? (a.kategori.nama || hurufAwal(String(a.kategori.slug || '').replace(/-/g, ' '))) : '';
        var pelapak = a.pelapak || {};
        var penjual = pelapak.penduduk ? hurufAwal(pelapak.penduduk.nama) : '';
        var telp = String(pelapak.telepon || '').replace(/\D/g, '').replace(/^0/, '62');
        // pesan_wa dari core sudah berupa tautan WhatsApp lengkap (nomor + pesan dari pengaturan "pesan singkat WA");
        // tautan dibuat sendiri hanya bila core belum mengirimnya
        var wa = /^https?:\/\//.test(a.pesan_wa || '') ? a.pesan_wa
            : telp.length >= 10 ? 'https://wa.me/' + telp + '?text=' + encodeURIComponent('Halo, saya tertarik dengan ' + a.nama) : '';
        // Lokasi penjual dibuka di Google Maps (seperti "Petunjuk Arah" di Pembangunan); hanya bila koordinat terisi
        var lat = Number(pelapak.lat), lng = Number(pelapak.lng);
        var lokasi = pelapak.lat != null && pelapak.lng != null && isFinite(lat) && isFinite(lng) && (lat || lng)
            && Math.abs(lat) <= 90 && Math.abs(lng) <= 180 ? 'https://www.google.com/maps?q=' + lat + ',' + lng : '';
        return '<article class="lv-product">'
            + '<div class="lv-product__img">' + (foto ? '<img src="' + esc(foto) + '" alt="" loading="lazy">' : '')
            + (persen > 0 ? '<span class="lv-product__diskon">−' + persen + '%</span>' : '') + '</div>'
            + '<div class="lv-product__body">'
            + (kategori ? '<span class="lv-product__kategori">' + esc(kategori) + '</span>' : '')
            + '<h3 class="lv-product__nama">' + esc(a.nama) + '</h3>'
            + '<p class="lv-product__harga"><span class="lv-price">' + rupiah(diskon ? hargaDiskon : harga) + '</span>'
            + (a.satuan ? '<small> / ' + esc(a.satuan) + '</small>' : '')
            + (diskon ? ' <s><span class="lv-sr">Harga semula </span>' + rupiah(harga) + '</s>' : '') + '</p>'
            + (penjual ? '<p class="lv-product__penjual">' + ikon(IKON_ORANG) + '<span>' + esc(penjual) + '</span></p>' : '')
            + (wa || lokasi ? '<div class="lv-product__aksi">'
                + (wa ? '<a class="lv-btn lv-btn--wa" href="' + esc(wa) + '" target="_blank" rel="noopener noreferrer">' + ikon(IKON_WA) + '<span>Hubungi<span class="lv-product__lengkap"> Penjual</span></span></a>' : '')
                + (lokasi ? '<a class="lv-icon-btn" href="' + esc(lokasi) + '" target="_blank" rel="noopener noreferrer" title="Lokasi di Google Maps" aria-label="Lokasi ' + esc(penjual || a.nama) + ' di Google Maps">' + ikon(IKON_PETA) + '</a>' : '')
                + '</div>' : '')
            + '</div></article>';
    };

    // Navigasi halaman untuk daftar dari API; susunan sama dengan commons/pagination_default (daftar artikel)
    var navigasiHalaman = function (sekarang, total, label) {
        var tombol = function (hal, isi, judul, aktif) {
            return aktif
                ? '<li class="page-item active" aria-current="page"><span class="page-link">' + hal + '</span></li>'
                : '<li class="page-item"><a class="page-link" href="#" data-lv-hal="' + hal + '"' + (judul ? ' aria-label="' + judul + '"' : '') + '>' + isi + '</a></li>';
        };
        var html = tombol(1, '<i class="fa fa-fast-backward"></i>', 'Halaman pertama');
        if (sekarang > 1) html += tombol(sekarang - 1, '<i class="fa fa-backward"></i>', 'Halaman sebelumnya');
        for (var i = Math.max(sekarang - 2, 1); i <= Math.min(sekarang + 2, total); i++) html += tombol(i, i, '', i === sekarang);
        if (sekarang < total) html += tombol(sekarang + 1, '<i class="fa fa-forward"></i>', 'Halaman berikutnya');
        html += tombol(total, '<i class="fa fa-fast-forward"></i>', 'Halaman terakhir');
        return '<nav class="pagination_area" aria-label="' + label + '"><div>Halaman ' + sekarang + ' dari ' + total + '</div><ul class="pagination">' + html + '</ul></nav>';
    };

    var pemuat = {
        idm: function (el) {
            var coba = function (url, cadangan) {
                return ambil(url).then(function (json) {
                    var a = atribut(json);
                    if (!a || !a.SUMMARIES) throw new Error('kosong');
                    return { a: a, url: url };
                }).catch(function () { if (cadangan) return coba(cadangan, null); throw new Error('gagal'); });
            };
            // IDM diambil dari server Kemendesa; hasil disimpan 12 jam agar tidak memanggil ulang di tiap kunjungan.
            var kunci = 'lv-idm:' + el.getAttribute('data-lv-url');
            var simpan = function (nilai) {
                try { localStorage.setItem(kunci, JSON.stringify({ t: Date.now(), v: nilai })); } catch (e) {}
            };
            var tersimpan = null;
            try { tersimpan = JSON.parse(localStorage.getItem(kunci) || 'null'); } catch (e) {}
            var segar = tersimpan && Date.now() - tersimpan.t < 12 * 3600 * 1000;
            var sumber = segar
                ? (tersimpan.v ? Promise.resolve(tersimpan.v) : Promise.reject(new Error('gagal')))
                : coba(el.getAttribute('data-lv-url'), el.getAttribute('data-lv-url-alt')).then(function (hasil) {
                    var ringkas = { a: { SUMMARIES: hasil.a.SUMMARIES }, url: hasil.url };
                    simpan(ringkas);
                    return ringkas;
                }, function (err) { simpan(null); throw err; });
            sumber.then(function (hasil) {
                var skor = parseFloat(hasil.a.SUMMARIES.SKOR_SAAT_INI) || 0;
                field(el, 'skor', skor.toFixed(2).replace('.', ','));
                field(el, 'status', hasil.a.SUMMARIES.STATUS || '–');
                field(el, 'tahun', hasil.url.split('/').pop());
                $('[data-lv-ring]', el).style.setProperty('--p', Math.min(100, skor * 100) + '%');
                munculkan(el);
            }).catch(function () {});
        },
        sdgs: function (el) {
            ambil(el.getAttribute('data-lv-url')).then(function (json) {
                var a = atribut(json);
                if (!a || !a.data || !a.data.length) return;
                field(el, 'skor', String(a.average).replace('.', ','));
                $('[data-lv-field="bar"]', el).innerHTML = a.data.map(function (g) {
                    var s = Number(g.score) || 0;
                    var warna = s >= 60 ? 'var(--c1)' : s >= 30 ? 'var(--c1-100)' : 'var(--c2)';
                    return '<span title="' + esc(g.goals + '. ' + g.title + ': ' + s) + '" style="background:' + warna + '"></span>';
                }).join('');
                munculkan(el);
            }).catch(function () {});
        },
        stunting: function (el) {
            ambil(el.getAttribute('data-lv-url')).then(function (json) {
                var a = atribut(json);
                if (!a || !a.widgets) return;
                var total = function (judul) {
                    var w = a.widgets.filter(function (x) { return x.title === judul; })[0];
                    return w ? angka.format(w.total) : '0';
                };
                field(el, 'periksa', total('Anak Periksa'));
                field(el, 'stunting', total('Anak 0-23 Bulan Stunting'));
                field(el, 'posyandu', angka.format((a.scorecard && a.scorecard.posyandu || []).length));
                munculkan(el);
            }).catch(function () {});
        },
        // Insight desa: pemilih (DPT), aset (inventaris), dokumen publik, dan artikel (arsip) dari API inti
        insight: function (el) {
            var isi = function (k, v) { var e = $('[data-lv-in="' + k + '"]', el); if (e) e.innerHTML = v; };
            var url = function (k) { return el.getAttribute('data-lv-url-' + k); };
            var tautan = function (k) { return el.getAttribute('data-lv-tautan-' + k); };
            var pct = function (a, b) { return (b > 0 ? Math.round(a / b * 1000) / 10 : 0).toString().replace('.', ',') + '%'; };
            var tgl = function (t) {
                // tgl_upload API: "dd-mm-YYYY HH:ii:ss"
                var m = /^(\d{2})-(\d{2})-(\d{4})/.exec(String(t || ''));
                return m ? new Date(+m[3], +m[2] - 1, +m[1]) : null;
            };
            var fmtTgl = function (d) { return d ? d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '–'; };
            var jumlahMeta = function (j) { return j && j.meta && j.meta.pagination ? Number(j.meta.pagination.total) || 0 : null; };
            // Sumber yang menunya tidak aktif tidak punya URL; kegagalan satu sumber tidak menggagalkan yang lain
            var opsional = function (u) { return u ? ambil(u).catch(function () { return null; }) : Promise.resolve(null); };
            var sembunyikanTab = function (k) {
                var tab = document.getElementById('lv-in-tab-' + k), panel = document.getElementById('lv-in-' + k);
                if (tab) tab.hidden = true;
                if (panel) panel.hidden = true;
            };
            var kpi = function (label, nilai, ket) {
                return '<div class="lv-kpi"><span class="lv-meta">' + esc(label) + '</span><b>' + nilai + '</b><small>' + esc(ket) + '</small></div>';
            };
            var pasangKpi = function (k, kartu) {
                isi(k, kartu.join(''));
                var e = $('[data-lv-in="' + k + '"]', el);
                if (e) e.setAttribute('data-jumlah', kartu.length);
            };
            var urlArtikel = url('artikel') + '?fields[artikel]=id,tgl_upload&page[size]=';

            Promise.all([
                opsional(url('dpt')),
                opsional(url('aset')),
                opsional(url('hukum') && url('hukum') + '?page[size]=1'),
                opsional(url('publik') && url('publik') + '?page[size]=1'),
                opsional(urlArtikel + '1&sort=-tgl_upload'),
                opsional(urlArtikel + '1&sort=tgl_upload')
            ]).then(function (hasil) {
                // Pemilih: API memecah per dusun, RW, dan jenis kelamin (sex 1 = laki-laki, 2 = perempuan); digabung per dusun
                var perDusun = {};
                ((hasil[0] && hasil[0].data) || []).forEach(function (d) {
                    var a = d.attributes || {}, nama = String(a.dusun || '-').trim();
                    perDusun[nama] = perDusun[nama] || { nama: nama, l: 0, p: 0 };
                    perDusun[nama][Number(a.sex) === 2 ? 'p' : 'l'] += Number(a.total) || 0;
                });
                var dusun = Object.keys(perDusun).map(function (k) { var d = perDusun[k]; d.n = d.l + d.p; return d; })
                    .filter(function (d) { return d.n > 0; })
                    .sort(function (a, b) { return b.n - a.n; });
                var pemilih = dusun.reduce(function (t, d) { return t + d.n; }, 0);
                var pemilihL = dusun.reduce(function (t, d) { return t + d.l; }, 0);

                // Aset: satu baris per jenis, dengan rincian asal perolehan
                var jenisAset = ((hasil[1] && hasil[1].data && hasil[1].data[0] && hasil[1].data[0].attributes) || []);
                jenisAset = (Array.isArray(jenisAset) ? jenisAset : []).filter(function (a) { return Number(a.total) > 0; })
                    .sort(function (a, b) { return b.total - a.total; });
                var aset = jenisAset.reduce(function (t, a) { return t + Number(a.total); }, 0);
                // Kolom API sesuai tabel halaman inventaris: pribadi = dibeli sendiri; pemerintah/provinsi/kabupaten = bantuan
                var asal = [['Dibeli sendiri', 'pribadi', 'pembelian sendiri'], ['Bantuan pemerintah', 'pemerintah', 'bantuan pemerintah'], ['Bantuan provinsi', 'provinsi', 'bantuan provinsi'], ['Bantuan kabupaten', 'kabupaten', 'bantuan kabupaten'], ['Sumbangan', 'sumbangan', 'sumbangan']]
                    .map(function (s) { return { nama: s[0], frasa: s[2], n: jenisAset.reduce(function (t, a) { return t + (Number(a[s[1]]) || 0); }, 0) }; })
                    .filter(function (s) { return s.n > 0; })
                    .sort(function (a, b) { return b.n - a.n; });

                var hukum = jumlahMeta(hasil[2]), publik = jumlahMeta(hasil[3]), artikel = jumlahMeta(hasil[4]);
                var akhir = hasil[4] && hasil[4].data && hasil[4].data[0] ? tgl(hasil[4].data[0].attributes.tgl_upload) : null;
                var awal = hasil[5] && hasil[5].data && hasil[5].data[0] ? tgl(hasil[5].data[0].attributes.tgl_upload) : null;

                // Ringkasan: hanya angka yang ada datanya
                var kartu = [];
                if (pemilih) kartu.push(kpi('Pemilih terdaftar', angka.format(pemilih), 'di ' + dusun.length + ' dusun'));
                if (aset) kartu.push(kpi('Aset tercatat', angka.format(aset), jenisAset.length + ' jenis aset'));
                if (hukum || publik) {
                    kartu.push(kpi('Dokumen publik', angka.format((hukum || 0) + (publik || 0)),
                        [hukum ? angka.format(hukum) + ' produk hukum' : null, publik ? angka.format(publik) + ' informasi publik' : null].filter(Boolean).join(' · ')));
                }
                if (artikel !== null) kartu.push(kpi('Artikel tayang', angka.format(artikel), 'di website'));
                if (!kartu.length) return;
                pasangKpi('kpi', kartu);

                var hariSejak = akhir ? Math.floor((Date.now() - akhir.getTime()) / 86400000) : null;
                isi('catatan', [
                    dusun.length > 1 ? 'Pemilih terbanyak ada di ' + dusun[0].nama + ' (' + angka.format(dusun[0].n) + ' orang, ' + pct(dusun[0].n, pemilih) + ' dari seluruh pemilih).' : null,
                    pemilih ? 'Pemilih perempuan ' + pct(pemilih - pemilihL, pemilih) + ', laki-laki ' + pct(pemilihL, pemilih) + '.' : null,
                    jenisAset.length ? 'Aset terbanyak berupa ' + jenisAset[0].jenis.toLowerCase() + ' (' + angka.format(jenisAset[0].total) + ' dari ' + angka.format(aset) + ' aset).' : null,
                    asal.length ? 'Asal aset terbesar: ' + asal[0].frasa + ' (' + pct(asal[0].n, aset) + ' dari aset tercatat).' : null,
                    hukum || publik ? 'Warga dapat mengunduh ' + [hukum ? angka.format(hukum) + ' produk hukum' : null, publik ? angka.format(publik) + ' dokumen informasi publik' : null].filter(Boolean).join(' dan ') + ' secara terbuka.' : null,
                    hariSejak === null ? null : hariSejak <= 30 ? 'Website aktif: artikel terakhir terbit ' + (hariSejak === 0 ? 'hari ini' : hariSejak + ' hari lalu') + '.'
                        : 'Artikel terakhir terbit ' + angka.format(hariSejak) + ' hari lalu; kabar desa perlu diperbarui.'
                ].filter(Boolean).map(function (c) { return '<li>' + esc(c) + '</li>'; }).join(''));

                var baris = function (judul, nilai, href) {
                    return '<a class="lv-list-row" href="' + esc(href) + '"><span>' + esc(judul) + '</span><b>' + nilai + ' →</b></a>';
                };
                isi('jelajah', [
                    pemilih ? baris('Data pemilih (DPT)', angka.format(pemilih), tautan('dpt')) : '',
                    aset ? baris('Inventaris aset', angka.format(aset), tautan('aset')) : '',
                    hukum ? baris('Produk hukum', angka.format(hukum), tautan('hukum')) : '',
                    publik ? baris('Informasi publik', angka.format(publik), tautan('publik')) : '',
                    artikel ? baris('Arsip artikel', angka.format(artikel), tautan('arsip')) : ''
                ].join(''));

                // Pemilih per dusun: batang bertumpuk laki-laki/perempuan, panjang relatif terhadap dusun terbesar
                if (pemilih) {
                    pasangKpi('kpi_dpt', [
                        kpi('Total pemilih', angka.format(pemilih), 'terdaftar di DPT'),
                        kpi('Laki-laki', angka.format(pemilihL), pct(pemilihL, pemilih)),
                        kpi('Perempuan', angka.format(pemilih - pemilihL), pct(pemilih - pemilihL, pemilih)),
                        kpi('Dusun', angka.format(dusun.length), 'wilayah pemilihan')
                    ]);
                    var maksD = dusun[0].n;
                    isi('dusun', dusun.map(function (d) {
                        return '<div class="lv-in-rentang__baris" title="Laki-laki ' + angka.format(d.l) + ', perempuan ' + angka.format(d.p) + '">'
                            + '<span>' + esc(d.nama) + '</span>'
                            + '<div class="lv-bar lv-bar--sm lv-bar--dua" role="img" aria-label="' + esc(d.nama + ': laki-laki ' + angka.format(d.l) + ', perempuan ' + angka.format(d.p)) + '">'
                            + '<span style="width:' + (d.l / maksD * 100) + '%"></span><span style="width:' + (d.p / maksD * 100) + '%"></span></div>'
                            + '<b>' + angka.format(d.n) + '</b></div>';
                    }).join(''));
                } else {
                    sembunyikanTab('wilayah');
                }

                // Aset per jenis (tautan ke halaman inventaris jenisnya) & asal perolehan
                if (aset) {
                    var maksA = jenisAset[0].total;
                    isi('aset_jenis', jenisAset.map(function (a) {
                        return '<div class="lv-in-rentang__baris">'
                            + '<a href="' + esc(a.url) + '">' + esc(a.jenis) + '</a>'
                            + '<div class="lv-bar lv-bar--sm"><span style="width:' + (a.total / maksA * 100) + '%"></span></div>'
                            + '<b>' + angka.format(a.total) + '</b></div>';
                    }).join(''));
                    isi('aset_asal', asal.length ? asal.map(function (s) {
                        return '<div class="lv-list-row"><span>' + esc(s.nama) + '</span><b>' + angka.format(s.n) + ' <small class="lv-meta">(' + pct(s.n, aset) + ')</small></b></div>';
                    }).join('') : '<p class="lv-meta">Asal perolehan belum diisi.</p>');
                } else {
                    sembunyikanTab('aset');
                }

                // Artikel: jumlah dari meta, terbaru & pertama dari urutan tgl_upload
                if (artikel === null) sembunyikanTab('artikel');
                isi('artikel_total', artikel === null ? '–' : angka.format(artikel));
                isi('artikel_awal', esc(fmtTgl(awal)));
                isi('artikel_akhir', esc(fmtTgl(akhir)));
                munculkan(el);

                // Tren artikel per bulan: semua halaman arsip (hanya id & tanggal), dimuat saat tab Artikel pertama kali dibuka
                var tabArtikel = document.getElementById('lv-in-tab-artikel');
                var sudah = false;
                var muatTren = function () {
                    if (sudah || artikel === null) return;
                    sudah = true;
                    var halaman = Math.ceil(artikel / 30), minta = [];
                    for (var h = 1; h <= halaman; h++) minta.push(ambil(urlArtikel + '30&page[number]=' + h));
                    Promise.all(minta).then(function (semua) {
                        var per = {};
                        semua.forEach(function (j) {
                            ((j && j.data) || []).forEach(function (d) {
                                var t = tgl(d.attributes.tgl_upload);
                                if (!t) return;
                                var k = t.getFullYear() + '-' + String(t.getMonth() + 1).padStart(2, '0');
                                per[k] = (per[k] || 0) + 1;
                            });
                        });
                        var kunci = Object.keys(per).sort();
                        if (!kunci.length) return;
                        // Isi bulan tanpa artikel dengan nol agar garis tren tidak melompati bulan kosong
                        var label = [], data = [];
                        var b = new Date(+kunci[0].slice(0, 4), +kunci[0].slice(5) - 1, 1);
                        var akhirB = new Date(+kunci[kunci.length - 1].slice(0, 4), +kunci[kunci.length - 1].slice(5) - 1, 1);
                        for (; b <= akhirB; b.setMonth(b.getMonth() + 1)) {
                            var kk = b.getFullYear() + '-' + String(b.getMonth() + 1).padStart(2, '0');
                            label.push(b.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }));
                            data.push(per[kk] || 0);
                        }
                        var w = document.getElementById('lv-chart-artikel');
                        w.lvGambar = function () { gambarGarisInsight(w, label, [{ nama: 'Artikel', warna: 'var(--c1)', data: data, isi: true }]); };
                        w.lvGambar();
                        var teraktif = label.map(function (l, i) { return { l: l, n: data[i] }; }).sort(function (a, c) { return c.n - a.n; }).slice(0, 5);
                        isi('artikel_puncak', teraktif[0] ? esc(teraktif[0].l) : '–');
                        isi('teraktif', teraktif.map(function (t) { return '<div class="lv-list-row"><span>' + esc(t.l) + '</span><b>' + angka.format(t.n) + ' artikel</b></div>'; }).join(''));
                    }).catch(function () {
                        sudah = false;
                        $('#lv-chart-artikel').innerHTML = '<p class="lv-meta">Tren artikel belum dapat dimuat.</p>';
                    });
                };
                if (tabArtikel) tabArtikel.addEventListener('click', muatTren);
            }).catch(function () {});
        },
        pembangunan: function (el) {
            ambil(el.getAttribute('data-lv-url')).then(function (json) {
                var data = (json && json.data) || [];
                if (!data.length) return;
                var dasar = el.getAttribute('data-lv-base');
                el.innerHTML = data.map(function (item) { return kartuPembangunan(dataPembangunan(item.attributes, dasar)); }).join('');
                munculkan(el);
            }).catch(function () {});
        },
        // Halaman daftar pembangunan: semua halaman API dimuat (maks. 30 per halaman), lalu disaring di browser
        pembangunan_daftar: function (el) {
            var bagian = el.closest('[data-lv-pemb]');
            var dasar = el.getAttribute('data-lv-base');
            var url = function (hal) { return el.getAttribute('data-lv-url') + '?page[size]=30&page[number]=' + hal; };
            var tampilkan = function (jenis) {
                el.hidden = jenis !== 'ada';
                $$('[data-lv-pemb-isi]', bagian).forEach(function (b) { b.hidden = jenis !== 'ada'; });
                $$('[data-lv-pemb-keadaan]', bagian).forEach(function (b) { b.hidden = b.getAttribute('data-lv-pemb-keadaan') !== jenis; });
            };
            ambil(url(1)).then(function (json) {
                var total = (json && json.meta && json.meta.pagination && json.meta.pagination.total_pages) || 1;
                var sisa = [];
                for (var h = 2; h <= total; h++) sisa.push(ambil(url(h)));
                return Promise.all(sisa).then(function (lain) {
                    return [json].concat(lain).reduce(function (semua, j) { return semua.concat((j && j.data) || []); }, []);
                });
            }).then(function (data) {
                el.removeAttribute('aria-busy');
                if (!data.length) { tampilkan('belum'); return; }
                var daftar = data.map(function (d) { return dataPembangunan(d.attributes, dasar); });
                el.innerHTML = daftar.map(kartuPembangunan).join('');
                tampilkan('ada');
                pasangDaftarPembangunan(bagian, daftar);
            }).catch(function () {
                el.removeAttribute('aria-busy');
                tampilkan('gagal');
            });
        },
        // Halaman detail pembangunan: data dari api.pembangunan (filter[slug])
        pembangunan_detail: function (el) {
            var bagian = el.closest('[data-lv-pemb-detail]');
            var dasar = el.getAttribute('data-lv-base');
            var tidakAda = function () { bagian.hidden = true; var td = $('[data-lv-pemb-tidak-ada]'); if (td) td.hidden = false; };
            var ikonPemb = {};
            $$('template[data-lv-ikon]', bagian).forEach(function (t) { ikonPemb[t.getAttribute('data-lv-ikon')] = t.innerHTML; });
            ambil(el.getAttribute('data-lv-url') + '?filter[slug]=' + encodeURIComponent(el.getAttribute('data-lv-slug'))).then(function (json) {
                var d = json && json.data && json.data[0];
                if (!d) { tidakAda(); return; }
                var p = dataPembangunan(d.attributes, dasar);
                var a = p.a;
                var teks = function (t) { return t != null && String(t).trim() !== '' && String(t).trim() !== '-' ? String(t).trim() : ''; };
                var tanggal = function (t) { try { return new Date(t).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }); } catch (e) { return ''; } };
                var satuan = { 1: 'hari', 2: 'minggu', 3: 'bulan', 4: 'tahun' };
                var biaya = [['Pemerintah (Dana Desa)', a.sumber_biaya_pemerintah], ['Provinsi', a.sumber_biaya_provinsi], ['Kabupaten/Kota', a.sumber_biaya_kab_kota], ['Swadaya masyarakat', a.sumber_biaya_swadaya]]
                    .filter(function (b) { return Number(b[1]) > 0; });
                var totalBiaya = Math.max(1, biaya.reduce(function (t, b) { return t + Number(b[1]); }, 0));
                var dataKegiatan = [
                    ['Tahun anggaran', p.tahun], ['Lokasi', p.lokasi], ['Volume', teks(a.volume)],
                    ['Waktu pelaksanaan', Number(a.waktu) > 0 ? a.waktu + ' ' + (satuan[a.satuan_waktu] || '') : ''],
                    ['Pelaksana', teks(a.pelaksana_kegiatan)], ['Sifat proyek', a.sifat_proyek ? hurufAwal(a.sifat_proyek).replace(/\s.*/, '') : ''],
                    ['Sumber dana', p.dana.join(', ')]
                ].filter(function (x) { return x[1]; });
                var adaPeta = isFinite(parseFloat(a.lat)) && isFinite(parseFloat(a.lng));

                document.title = p.judul + ' · ' + document.title;
                $('[data-lv-pemb-crumb]', bagian).textContent = p.judul.length > 60 ? p.judul.slice(0, 60) + '…' : p.judul;
                $('[data-lv-pemb-kepala]', bagian).innerHTML = '<div class="lv-pemb-kepala__chip"><span class="lv-chip">Tahun ' + esc(p.tahun) + '</span>'
                    + '<span class="lv-pemb-status lv-pemb-status--' + p.status + '">' + STATUS_PEMB[p.status] + '</span></div>'
                    + '<h1 class="lv-artikel__judul">' + esc(p.judul) + '</h1>'
                    + '<p class="lv-artikel__meta"><span>' + ikonPemb.map + ' ' + esc(p.lokasi) + '</span>' + (teks(a.pelaksana_kegiatan) ? '<span>' + ikonPemb.users + ' ' + esc(a.pelaksana_kegiatan) + '</span>' : '') + '</p>';

                var html = '<div class="lv-pemb-detail__utama">'
                    + '<a class="lv-pemb-foto" href="' + esc(p.foto) + '" data-fancybox="pembangunan" data-caption="' + esc(p.judul) + '"><img src="' + esc(p.foto) + '" alt="Foto ' + esc(p.judul) + '"></a>'
                    + '<section class="lv-pemb-kartu" aria-labelledby="lv-pemb-progres"><h2 class="lv-h3" id="lv-pemb-progres">Progres pembangunan</h2>'
                    + (p.dokumentasi.length
                        ? '<ol class="lv-pemb-progres">' + p.dokumentasi.map(function (d) {
                            return '<li><span class="lv-pemb-progres__persen">' + d.persen + '%</span>'
                                + '<a href="' + esc(d.foto) + '" data-fancybox="progres" data-caption="' + esc(d.persen + '% · ' + (d.keterangan || '')) + '"><img src="' + esc(d.foto) + '" alt="Dokumentasi ' + d.persen + '%" loading="lazy"></a>'
                                + '<div><b>' + esc(teks(d.keterangan) || 'Progres ' + d.persen + '%') + '</b>' + (d.tanggal ? '<span class="lv-meta">' + esc(tanggal(d.tanggal)) + '</span>' : '') + '</div></li>';
                        }).join('') + '</ol>'
                        : '<p class="lv-empty">Belum ada dokumentasi progres untuk kegiatan ini.</p>')
                    + '</section>'
                    + (teks(a.keterangan) || teks(a.manfaat)
                        ? '<section class="lv-pemb-kartu" aria-labelledby="lv-pemb-tentang"><h2 class="lv-h3" id="lv-pemb-tentang">Tentang kegiatan</h2>'
                            + (teks(a.keterangan) ? '<p class="lv-prose">' + esc(teks(a.keterangan)) + '</p>' : '')
                            + (teks(a.manfaat) ? '<p class="lv-pemb-manfaat"><b>Manfaat:</b> ' + esc(teks(a.manfaat)) + '</p>' : '') + '</section>'
                        : '')
                    + '<section class="lv-pemb-kartu" aria-labelledby="lv-pemb-lokasi"><h2 class="lv-h3" id="lv-pemb-lokasi">Lokasi</h2>'
                    + (adaPeta
                        ? '<div id="lv-pemb-peta" class="lv-pemb-peta" role="img" aria-label="Peta lokasi ' + esc(p.judul) + '"></div>'
                            + '<a class="lv-more" href="https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(a.lat + ',' + a.lng) + '" target="_blank" rel="noopener noreferrer">Petunjuk arah →</a>'
                        : '<p class="lv-empty">Titik lokasi belum ditandai di peta. Lokasi: ' + esc(p.lokasi) + '.</p>')
                    + '</section></div>'
                    + '<aside class="lv-pemb-detail__samping">'
                    + '<section class="lv-pemb-kartu lv-pemb-anggaran" aria-label="Anggaran"><span class="lv-meta">Anggaran</span>'
                    + '<b class="lv-pemb-anggaran__nilai">' + rupiah(p.anggaran) + '</b>'
                    + '<div class="lv-pemb-meter"><div><span>Realisasi anggaran</span><b>' + p.realisasi + '%</b></div><div class="lv-bar"><span style="width:' + p.realisasi + '%"></span></div>'
                    + '<span class="lv-meta">' + rupiah(p.realisasiRp) + ' terpakai</span></div>'
                    + (p.fisik !== null ? '<div class="lv-pemb-meter"><div><span>Progres fisik</span><b>' + p.fisik + '%</b></div><div class="lv-bar lv-bar--fisik"><span style="width:' + p.fisik + '%"></span></div></div>' : '')
                    + (biaya.length ? '<h3 class="lv-pemb-sub">Sumber biaya</h3><ul class="lv-pemb-biaya">' + biaya.map(function (b) {
                        return '<li><span>' + esc(b[0]) + '</span><b>' + rupiah(b[1]) + '</b><i style="width:' + Math.round(Number(b[1]) / totalBiaya * 100) + '%"></i></li>';
                    }).join('') + '</ul>' : '')
                    + '</section>'
                    + '<section class="lv-pemb-kartu" aria-labelledby="lv-pemb-data"><h2 class="lv-h3" id="lv-pemb-data">Data kegiatan</h2><dl class="lv-pemb-data">'
                    + dataKegiatan.map(function (x) { return '<div><dt>' + esc(x[0]) + '</dt><dd>' + esc(x[1]) + '</dd></div>'; }).join('') + '</dl></section>'
                    + '</aside>';
                el.innerHTML = html;
                el.removeAttribute('aria-busy');
                // Tombol bagikan (server) dipindah ke kolom samping setelah isi tampil
                var bagikan = $('[data-lv-pemb-bagikan]', bagian);
                if (bagikan) { bagikan.hidden = false; $('.lv-pemb-detail__samping', el).appendChild(bagikan); }

                // Peta lokasi: dasar peta & ikon mengikuti pengaturan inti (peta.js)
                var petaEl = document.getElementById('lv-pemb-peta');
                if (adaPeta && petaEl && typeof L !== 'undefined') {
                    // Pengaturan peta dari atribut data-lv-peta-* (dikirim per key oleh view)
                    var cfgPeta = function (k) { return el.getAttribute('data-lv-peta-' + k) || ''; };
                    var posisi = [parseFloat(a.lat), parseFloat(a.lng)];
                    var peta = L.map(petaEl, { maxZoom: Number(cfgPeta('maks')) || 18, minZoom: Number(cfgPeta('min')) || 5, scrollWheelZoom: false }).setView(posisi, Number(cfgPeta('zoom')) || 15);
                    if (typeof getBaseLayers === 'function') getBaseLayers(peta, cfgPeta('mapbox'), cfgPeta('jenis'));
                    // Ikon pembangunan dari pengaturan inti; bila belum diisi, penanda lingkaran berwarna tema (tanpa berkas gambar)
                    var warna = getComputedStyle(petaEl).getPropertyValue('--c1').trim() || '#005dfa';
                    (cfgPeta('ikon')
                        ? L.marker(posisi, { icon: L.icon({ iconUrl: cfgPeta('ikon'), iconSize: [30, 40], iconAnchor: [15, 40] }) })
                        : L.circleMarker(posisi, { radius: 10, weight: 4, color: '#fff', fillColor: warna, fillOpacity: 1 })
                    ).addTo(peta);
                }
            }).catch(function () {
                el.removeAttribute('aria-busy');
                el.innerHTML = '<div class="lv-kosong" style="grid-column:1/-1"><div><h2 class="lv-h3">Data kegiatan belum dapat dimuat</h2><p>Periksa koneksi internet Anda, lalu muat ulang halaman.</p></div></div>';
            });
        },
        lapak: function (el) {
            ambil(el.getAttribute('data-lv-url')).then(function (json) {
                var data = (json && json.data) || [];
                if (!data.length) return;
                el.innerHTML = data.map(kartuProduk).join('');
                munculkan(el);
            }).catch(function () {});
        },
        // Halaman galeri: daftar album, atau foto dalam album (data-lv-album), dengan navigasi halaman
        galeri: function (el) {
            var bagian = el.closest('[data-lv-galeri]');
            var kosong = $('[data-lv-galeri-kosong]', bagian);
            var pager = $('[data-lv-galeri-pager]', bagian);
            var album = el.hasAttribute('data-lv-album');
            var ukuran = el.getAttribute('data-lv-ukuran') || 12;

            var kartu = function (item, i) {
                var a = item.attributes;
                var jumlah = (a.children || []).length;
                // --i: urutan muncul kartu (animasi bergiliran, dibatasi agar kartu terakhir tidak menunggu lama)
                var urutan = ' style="--i:' + Math.min(i, 10) + '"';
                // Isi album berupa video YouTube: diberi ikon putar dan dibuka sebagai video di fancybox
                var video = album && a.is_video && a.url_video;
                var isi = '<img src="' + esc(fotoAtauKosong(a.src_gambar)) + '" alt="" loading="lazy">'
                    + (video ? '<i class="lv-gallery__putar" aria-hidden="true"></i>' : '')
                    + '<span>' + esc(a.nama) + (!album && jumlah ? '<small>' + angka.format(jumlah) + ' foto</small>' : '') + '</span>';
                if (!album) return '<a href="' + esc(lokal(a.url_detail)) + '"' + urutan + '>' + isi + '</a>';
                if (video) return '<a href="' + esc(a.url_video) + '" data-fancybox="galeri" data-caption="' + esc(a.nama) + '"' + urutan + '>' + isi + '</a>';
                // Foto yang berkasnya tidak ada tidak bisa dibuka ukuran penuh
                return a.src_gambar
                    ? '<a href="' + esc(lokal(a.src_gambar, true)) + '" data-fancybox="galeri" data-caption="' + esc(a.nama) + '"' + urutan + '>' + isi + '</a>'
                    : '<div' + urutan + '>' + isi + '</div>';
            };
            // Foto tampil perlahan setelah selesai diunduh (gagal pun tetap ditampilkan agar kartu tidak kosong)
            var tampilkanFoto = function () {
                $$('img', el).forEach(function (img) {
                    var tampil = function () { img.classList.add('is-tampil'); };
                    if (img.complete) tampil();
                    else { img.addEventListener('load', tampil); img.addEventListener('error', tampil); }
                });
            };

            var muat = function (hal, gulir) {
                el.setAttribute('aria-busy', 'true');
                if (gulir) el.classList.add('is-memuat');
                ambil(el.getAttribute('data-lv-url') + '?sort=-tgl_upload&page[number]=' + hal + '&page[size]=' + ukuran).then(function (json) {
                    var data = (json && json.data) || [];
                    var halaman = (json && json.meta && json.meta.pagination) || {};
                    el.innerHTML = data.map(kartu).join('');
                    tampilkanFoto();
                    el.hidden = !data.length;
                    kosong.hidden = !!data.length;
                    pager.hidden = !(halaman.total_pages > 1);
                    pager.innerHTML = pager.hidden ? '' : navigasiHalaman(halaman.current_page, halaman.total_pages, 'Halaman galeri');
                    if (gulir) bagian.scrollIntoView({ behavior: hemat ? 'auto' : 'smooth' });
                }).catch(function () {
                    el.hidden = true;
                    pager.hidden = true;
                    kosong.textContent = 'Galeri belum dapat dimuat. Silakan muat ulang halaman.';
                    kosong.hidden = false;
                }).then(function () {
                    el.removeAttribute('aria-busy');
                    el.classList.remove('is-memuat');
                });
            };

            pager.addEventListener('click', function (e) {
                var a = e.target.closest('[data-lv-hal]');
                if (!a) return;
                e.preventDefault();
                muat(Number(a.getAttribute('data-lv-hal')), true);
            });
            muat(1, false);
        },
        // Halaman lapak: filter kategori & pencarian, navigasi halaman, dan pesan kosong sesuai penyebabnya
        lapak_daftar: function (el) {
            var bagian = el.closest('[data-lv-lapak]');
            var formCari = $('[data-lv-lapak-cari]', bagian);
            var inputCari = $('input', formCari);
            var grupKategori = $('[data-lv-lapak-kategori]', bagian);
            var info = $('[data-lv-lapak-info]', bagian);
            var kosong = $('[data-lv-lapak-kosong]', bagian);
            var pager = $('[data-lv-lapak-pager]', bagian);
            var ukuran = el.getAttribute('data-lv-ukuran') || 12;
            // ?cari= dari pencarian menyeluruh ("Lihat semua" / hasil produk) langsung menyaring daftar
            var kataAwal = (new URLSearchParams(location.search).get('cari') || '').trim().slice(0, 50);
            inputCari.value = kataAwal;
            var saring = { kategori: '', namaKategori: '', kata: kataAwal, hal: 1 };

            var tampilkanKosong = function (jenis) {
                kosong.hidden = !jenis;
                $$('[data-lv-kosong]', kosong).forEach(function (blok) { blok.hidden = blok.getAttribute('data-lv-kosong') !== jenis; });
                if (jenis === 'cari') {
                    $('[data-lv-kosong-pesan]', kosong).textContent = 'Tidak ada produk'
                        + (saring.kata ? ' yang cocok dengan "' + saring.kata + '"' : '')
                        + (saring.namaKategori ? ' di kategori ' + saring.namaKategori : '')
                        + '. Coba kata lain atau pilih kategori Semua.';
                }
            };

            // Nomor permintaan terakhir: jawaban API yang datang terlambat (mis. saat mengetik cepat) diabaikan
            var permintaan = 0;
            var muat = function (gulir) {
                var ke = ++permintaan;
                el.setAttribute('aria-busy', 'true');
                el.classList.add('is-memuat');
                var url = el.getAttribute('data-lv-url') + '?page[number]=' + saring.hal + '&page[size]=' + ukuran
                    + (saring.kategori ? '&filter[id_produk_kategori]=' + encodeURIComponent(saring.kategori) : '')
                    + (saring.kata ? '&filter[search]=' + encodeURIComponent(saring.kata) : '');
                ambil(url).then(function (json) {
                    if (ke !== permintaan) return;
                    var data = (json && json.data) || [];
                    var halaman = (json && json.meta && json.meta.pagination) || {};
                    var tersaring = !!(saring.kategori || saring.kata);
                    el.innerHTML = data.map(kartuProduk).join('');
                    el.hidden = !data.length;
                    // Lapak belum punya produk sama sekali: kotak cari & kategori tidak berguna, jadi disembunyikan
                    formCari.hidden = !data.length && !tersaring;
                    if (!data.length && !tersaring) grupKategori.hidden = true;
                    tampilkanKosong(data.length ? '' : (tersaring ? 'cari' : 'belum'));
                    var awal = (halaman.current_page - 1) * halaman.per_page;
                    info.textContent = !data.length ? ''
                        : halaman.total > data.length
                            ? 'Menampilkan ' + angka.format(awal + 1) + '–' + angka.format(awal + data.length) + ' dari ' + angka.format(halaman.total) + ' produk'
                            : 'Menampilkan ' + angka.format(data.length) + ' produk';
                    pager.hidden = !(halaman.total_pages > 1);
                    pager.innerHTML = pager.hidden ? '' : navigasiHalaman(halaman.current_page, halaman.total_pages, 'Halaman lapak');
                    if (gulir) bagian.scrollIntoView({ behavior: hemat ? 'auto' : 'smooth' });
                }).catch(function () {
                    if (ke !== permintaan) return;
                    el.hidden = true;
                    pager.hidden = true;
                    info.textContent = '';
                    tampilkanKosong('gagal');
                }).then(function () {
                    if (ke !== permintaan) return;
                    el.removeAttribute('aria-busy');
                    el.classList.remove('is-memuat');
                });
            };

            // Kata kunci ikut tercatat di alamat (?cari=) agar hasil bisa dibagikan & dibuka lagi
            var cari = function (kata, gulir) {
                kata = kata.trim().slice(0, 50);
                if (kata === saring.kata) return;
                saring.kata = kata;
                saring.hal = 1;
                var alamat = new URL(location.href);
                if (kata) alamat.searchParams.set('cari', kata);
                else alamat.searchParams.delete('cari');
                history.replaceState(null, '', alamat);
                muat(gulir);
            };

            // Kategori dari API; hanya ditampilkan bila ada lebih dari satu pilihan
            ambil(grupKategori.getAttribute('data-lv-url')).then(function (json) {
                var data = (json && json.data) || [];
                if (!data.length) return;
                grupKategori.insertAdjacentHTML('beforeend', data.map(function (k) {
                    return '<button type="button" aria-pressed="false" data-kategori="' + esc(k.id) + '">' + esc(k.attributes.kategori) + '</button>';
                }).join(''));
                if (!formCari.hidden) grupKategori.hidden = false;
            }).catch(function () {});

            grupKategori.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-kategori]');
                if (!btn) return;
                $$('[data-kategori]', grupKategori).forEach(function (b) { b.setAttribute('aria-pressed', String(b === btn)); });
                saring.kategori = btn.getAttribute('data-kategori');
                saring.namaKategori = saring.kategori ? btn.textContent : '';
                saring.hal = 1;
                muat(true);
            });
            // Menyaring saat mengetik (seperti halaman Pembangunan), dengan jeda agar API tidak dipanggil tiap tombol
            var tundaCari;
            inputCari.addEventListener('input', function () {
                clearTimeout(tundaCari);
                tundaCari = setTimeout(function () { cari(inputCari.value, false); }, 350);
            });
            formCari.addEventListener('submit', function (e) {
                e.preventDefault();
                // anti-csrf.js core mengubah tombol submit jadi "Mohon tunggu..." dan baru memulihkannya saat halaman
                // dimuat ulang atau $.ajax selesai; pencarian ini memakai fetch tanpa muat ulang, jadi jangan diteruskan
                e.stopPropagation();
                clearTimeout(tundaCari);
                cari(inputCari.value, true);
            });
            pager.addEventListener('click', function (e) {
                var a = e.target.closest('[data-lv-hal]');
                if (!a) return;
                e.preventDefault();
                saring.hal = Number(a.getAttribute('data-lv-hal'));
                muat(true);
            });
            $('[data-lv-lapak-reset]', kosong).addEventListener('click', function () {
                clearTimeout(tundaCari);
                inputCari.value = '';
                saring.kategori = '';
                saring.namaKategori = '';
                $$('[data-kategori]', grupKategori).forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-kategori') === '')); });
                if (saring.kata) cari('', true);
                else { saring.hal = 1; muat(true); }
            });
            $('[data-lv-lapak-ulang]', kosong).addEventListener('click', function () { muat(false); });
            muat(false);
        }
    };

    var muatSemua = function () {
        $$('[data-lv-lazy]:not([data-lv-segera])').forEach(function (el) {
            var jenis = el.getAttribute('data-lv-lazy');
            if (pemuat[jenis]) pemuat[jenis](el);
        });
    };
    // Bagian di atas pemicu lazy (mis. Insight) dimuat langsung agar tidak muncul mendadak saat pengunjung sedang membaca
    $$('[data-lv-lazy][data-lv-segera]').forEach(function (el) {
        var jenis = el.getAttribute('data-lv-lazy');
        if (pemuat[jenis]) pemuat[jenis](el);
    });
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entri) {
            entri.forEach(function (e) {
                if (!e.isIntersecting) return;
                io.unobserve(e.target);
                muatSemua();
            });
        }, { rootMargin: '600px' });
        var pemicu = $('#lv-kabar-judul');
        pemicu ? io.observe(pemicu) : muatSemua();
    } else {
        muatSemua();
    }
});
</script>
