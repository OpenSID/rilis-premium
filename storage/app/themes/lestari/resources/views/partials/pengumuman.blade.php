{{-- Popup pengumuman (Admin Web > Pengumuman). Hanya tampil di beranda halaman pertama tanpa pencarian. --}}
@php
    $pengumumanPopup = ['kunci' => '', 'data' => []];

    if (function_exists('pengumuman') && in_array(request()->path(), ['/', ''], true) && empty($cari) && (int) request()->input('page', 1) <= 1) {
        $pengumumanPopup = pengumuman();
    }

    $pengumumanJumlah = count($pengumumanPopup['data']);
    $pengumumanModern = theme_config('tampilan_beranda', 'modern') !== 'klasik';
@endphp

@if ($pengumumanJumlah > 0)
    <style>
        #pengumuman-popup {
            --pengumuman-aksen: var(--color1, #005dfa);
            --pengumuman-aksen-gelap: var(--color3, #003793);
            --pengumuman-kartu: #ffffff;
            --pengumuman-teks: var(--primer, #313131);
            --pengumuman-judul: var(--primer, #313131);
            --pengumuman-garis: var(--grey1, #dbdbdb);
            --pengumuman-radius: 6px;
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            font-family: inherit;
            line-height: 1.5;
        }

        #pengumuman-popup[hidden],
        #pengumuman-popup [hidden] {
            display: none !important;
        }

        #pengumuman-popup *,
        #pengumuman-popup *::before,
        #pengumuman-popup *::after {
            box-sizing: border-box;
        }

        #pengumuman-popup .pengumuman-latar {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .65);
        }

        #pengumuman-popup .pengumuman-kotak {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 640px;
            max-height: calc(100vh - 32px);
            background: var(--pengumuman-kartu);
            color: var(--pengumuman-teks);
            border-top: 4px solid var(--pengumuman-aksen);
            border-radius: var(--pengumuman-radius);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .35);
            overflow: hidden;
            animation: pengumuman-muncul .2s ease-out;
        }

        @keyframes pengumuman-muncul {
            from {
                opacity: 0;
                transform: scale(.96);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Isi slide yang bisa digulir; tombol tutup dan navigasi tetap di tempatnya. */
        #pengumuman-popup .pengumuman-gulir {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
        }

        #pengumuman-popup .pengumuman-tutup {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 2;
            width: 34px;
            height: 34px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, .55);
            color: #fff;
            font-size: 22px;
            line-height: 34px;
            text-align: center;
            cursor: pointer;
        }

        #pengumuman-popup .pengumuman-tutup:hover,
        #pengumuman-popup .pengumuman-tutup:focus-visible {
            background: rgba(0, 0, 0, .8);
            outline: none;
        }

        #pengumuman-popup .pengumuman-gambar {
            display: block;
            width: 100%;
            height: auto;
            max-height: 60vh;
            object-fit: contain;
            background: rgba(0, 0, 0, .05);
        }

        #pengumuman-popup .pengumuman-isi {
            padding: 16px 20px 20px;
        }

        #pengumuman-popup .pengumuman-label {
            display: none;
        }

        #pengumuman-popup .pengumuman-judul {
            margin: 0 0 8px;
            padding-right: 40px;
            color: var(--pengumuman-judul);
            font-size: 18px;
            font-weight: 700;
            line-height: 1.35;
        }

        #pengumuman-popup .pengumuman-slide.berisi-gambar .pengumuman-judul {
            padding-right: 0;
        }

        #pengumuman-popup .pengumuman-keterangan {
            margin: 0 0 12px;
            font-size: 15px;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        #pengumuman-popup .pengumuman-tombol {
            display: inline-block;
            padding: 8px 16px;
            border-radius: var(--pengumuman-radius);
            background: var(--pengumuman-aksen);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        #pengumuman-popup .pengumuman-tombol:hover,
        #pengumuman-popup .pengumuman-tombol:focus-visible {
            background: var(--pengumuman-aksen-gelap);
            color: #fff;
        }

        /* Navigasi antarpengumuman di kaki kartu: tidak menutupi isi dan terpisah dari tombol tutup. */
        #pengumuman-popup .pengumuman-navigasi {
            display: flex;
            flex: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-top: 1px solid var(--pengumuman-garis);
        }

        #pengumuman-popup .pengumuman-posisi {
            font-size: 14px;
            font-weight: 600;
        }

        #pengumuman-popup .pengumuman-geser {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: var(--pengumuman-aksen);
            color: #fff;
            cursor: pointer;
        }

        #pengumuman-popup .pengumuman-geser:hover,
        #pengumuman-popup .pengumuman-geser:focus-visible {
            background: var(--pengumuman-aksen-gelap);
            outline: none;
        }

        #pengumuman-popup .pengumuman-geser svg {
            width: 20px;
            height: 20px;
        }

        /* Tampilan Modern: mengikuti kartu, warna, dan huruf tampilan Modern. */
        #pengumuman-popup.pengumuman-popup--modern {
            --pengumuman-teks: #5c6577;
            --pengumuman-judul: #1b2333;
            --pengumuman-garis: #e1e6ef;
            --pengumuman-radius: 20px;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-latar {
            background: rgba(15, 23, 42, .6);
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-kotak {
            border-top: 0;
            box-shadow: 0 24px 60px rgba(0, 38, 107, .25);
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-tutup {
            top: 12px;
            right: 12px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-isi {
            padding: 24px 28px 28px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 10px;
            padding: 4px 12px;
            border-radius: 999px;
            background: color-mix(in srgb, var(--pengumuman-aksen) 10%, white);
            color: var(--pengumuman-aksen);
            font-size: 13px;
            font-weight: 700;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-label svg {
            width: 15px;
            height: 15px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-judul {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.01em;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-keterangan {
            font-size: 16px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-tombol {
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 15px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-navigasi {
            padding: 12px 20px;
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-geser {
            background: color-mix(in srgb, var(--pengumuman-aksen) 10%, white);
            color: var(--pengumuman-aksen);
        }

        #pengumuman-popup.pengumuman-popup--modern .pengumuman-geser:hover,
        #pengumuman-popup.pengumuman-popup--modern .pengumuman-geser:focus-visible {
            background: var(--pengumuman-aksen);
            color: #fff;
        }

        @media (max-width: 767px) {
            #pengumuman-popup.pengumuman-popup--modern .pengumuman-isi {
                padding: 20px;
            }

            #pengumuman-popup.pengumuman-popup--modern .pengumuman-judul {
                font-size: 19px;
            }
        }

        body.pengumuman-terbuka {
            overflow: hidden;
        }

        @media (prefers-reduced-motion: reduce) {
            #pengumuman-popup .pengumuman-kotak {
                animation: none;
            }
        }
    </style>

    <div id="pengumuman-popup" class="{{ $pengumumanModern ? 'pengumuman-popup--modern' : '' }}" data-kunci="{{ $pengumumanPopup['kunci'] }}" role="dialog" aria-modal="true" aria-labelledby="pengumuman-judul-0" hidden>
        <div class="pengumuman-latar" data-tutup></div>
        <div class="pengumuman-kotak">
            <button type="button" class="pengumuman-tutup" data-tutup aria-label="Tutup pengumuman" title="Tutup">&times;</button>
            <div class="pengumuman-gulir">
                @foreach ($pengumumanPopup['data'] as $item)
                    <div class="pengumuman-slide {{ $item['gambar'] ? 'berisi-gambar' : '' }}" data-slide @if (!$loop->first) hidden @endif>
                        @if ($item['gambar'])
                            @if ($item['tautan'])
                                <a href="{{ $item['tautan'] }}" target="_blank" rel="noopener noreferrer">
                                    <img class="pengumuman-gambar" src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}">
                                </a>
                            @else
                                <img class="pengumuman-gambar" src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}">
                            @endif
                        @endif
                        <div class="pengumuman-isi">
                            <p class="pengumuman-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11l18-8v18l-18-8zM7 12.5V18a2 2 0 0 0 4 0v-3.5"></path></svg>
                                Pengumuman
                            </p>
                            <h2 class="pengumuman-judul" id="pengumuman-judul-{{ $loop->index }}">{{ $item['judul'] }}</h2>
                            @if ($item['keterangan'])
                                <p class="pengumuman-keterangan">{{ $item['keterangan'] }}</p>
                            @endif
                            @if ($item['tautan'] && $item['judul_tautan'])
                                <a class="pengumuman-tombol" href="{{ $item['tautan'] }}" target="_blank" rel="noopener noreferrer">{{ $item['judul_tautan'] }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($pengumumanJumlah > 1)
                <div class="pengumuman-navigasi">
                    <button type="button" class="pengumuman-geser" data-sebelumnya aria-label="Pengumuman sebelumnya" title="Sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <span class="pengumuman-posisi" aria-live="polite"><span data-posisi>1</span> / {{ $pengumumanJumlah }}</span>
                    <button type="button" class="pengumuman-geser" data-berikutnya aria-label="Pengumuman berikutnya" title="Berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            var popup = document.getElementById('pengumuman-popup');
            if (!popup) {
                return;
            }

            // Tampil sekali per sesi browser; muncul lagi jika isi pengumuman berubah.
            var kunci = 'pengumuman-popup-' + popup.getAttribute('data-kunci');

            function sudahDitutup() {
                try {
                    return window.sessionStorage.getItem(kunci) === '1';
                } catch (e) {
                    return false;
                }
            }

            function tandaiDitutup() {
                try {
                    window.sessionStorage.setItem(kunci, '1');
                } catch (e) {}
            }

            if (sudahDitutup()) {
                popup.parentNode.removeChild(popup);
                return;
            }

            var slides = popup.querySelectorAll('[data-slide]');
            var posisi = popup.querySelector('[data-posisi]');
            var gulir = popup.querySelector('.pengumuman-gulir');
            var fokusSebelumnya = null;
            var aktif = 0;

            function tampilkan(index) {
                aktif = (index + slides.length) % slides.length;
                for (var i = 0; i < slides.length; i++) {
                    slides[i].hidden = i !== aktif;
                }
                popup.setAttribute('aria-labelledby', 'pengumuman-judul-' + aktif);
                if (posisi) {
                    posisi.textContent = aktif + 1;
                }
                gulir.scrollTop = 0;
            }

            // Fokus keyboard tetap di dalam popup selama terbuka.
            function elemenFokus() {
                return Array.prototype.filter.call(
                    popup.querySelectorAll('a[href], button:not([disabled])'),
                    function(el) {
                        return el.offsetParent !== null;
                    }
                );
            }

            function tekanTombol(event) {
                if (event.key === 'Escape' || event.key === 'Esc') {
                    tutup();
                } else if (slides.length > 1 && event.key === 'ArrowRight') {
                    tampilkan(aktif + 1);
                } else if (slides.length > 1 && event.key === 'ArrowLeft') {
                    tampilkan(aktif - 1);
                } else if (event.key === 'Tab') {
                    var daftar = elemenFokus();
                    if (!daftar.length) {
                        return;
                    }
                    var pertama = daftar[0];
                    var terakhir = daftar[daftar.length - 1];
                    if (event.shiftKey && (document.activeElement === pertama || !popup.contains(document.activeElement))) {
                        event.preventDefault();
                        terakhir.focus();
                    } else if (!event.shiftKey && (document.activeElement === terakhir || !popup.contains(document.activeElement))) {
                        event.preventDefault();
                        pertama.focus();
                    }
                }
            }

            function tutup() {
                tandaiDitutup();
                popup.hidden = true;
                document.body.classList.remove('pengumuman-terbuka');
                document.removeEventListener('keydown', tekanTombol);
                if (fokusSebelumnya && typeof fokusSebelumnya.focus === 'function' && document.contains(fokusSebelumnya)) {
                    fokusSebelumnya.focus();
                }
            }

            popup.addEventListener('click', function(event) {
                var target = event.target;
                if (target.closest('[data-tutup]')) {
                    tutup();
                } else if (target.closest('[data-sebelumnya]')) {
                    tampilkan(aktif - 1);
                } else if (target.closest('[data-berikutnya]')) {
                    tampilkan(aktif + 1);
                } else if (target.closest('a[href]')) {
                    tandaiDitutup();
                }
            });

            function buka() {
                fokusSebelumnya = document.activeElement;
                document.addEventListener('keydown', tekanTombol);
                tampilkan(0);
                popup.hidden = false;
                document.body.classList.add('pengumuman-terbuka');
                popup.querySelector('.pengumuman-tutup').focus();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', buka);
            } else {
                buka();
            }
        })();
    </script>
@endif
