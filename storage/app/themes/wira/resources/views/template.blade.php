@php
    $themeVersion = 'v2606.0.0';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'Website Resmi ' . ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']))
    </title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @include('theme::commons.meta')
    @include('theme::commons.source_css')
    @include('theme::commons.source_js')
    {{--
    <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="{{ theme_asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .theme-container {
            max-width: 1440px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }
    </style>
    @stack('styles')

</head>
@php
    $post = $single_artikel;
@endphp

<body class="w-full bg-white">
    <div class="theme-container px-4 md:px-6 lg:px-8 mb-16">
        @include('theme::commons.loading_screen')
        {{-- @include('theme::partials.header') --}}
        @if(theme_config('hero_klasik') == '1')
            @include('theme::partials.hero_klasik')
        @else
            @include('theme::partials.hero')
        @endif

        @yield('layout')
        @if (request()->path() === '/' || request()->path() === '')
            <div class="px-2 md:px-6 lg:px-4">

                <div class="flex flex-col gap-0 mt-0">

                    @include('theme::partials.articles')
                    @include('theme::partials.statistics')
                    @if(theme_config('village_officials') == '1')
                        @include('theme::partials.officials')
                    @endif

                </div>
            </div>

        @endif

    </div>

    @include('theme::partials.footer')
    @if(theme_config('ai_assistant') == '1')
        @include('theme::commons.ai_chat')
    @endif
    @stack('scripts')
    <script type="text/javascript">
        function formatRupiah(angka, prefix = 'Rp ') {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '') + ',00';
        }
    </script>
    <script>
        lucide.createIcons()
        function cookiesEnabled() {
            document.cookie = "testcookie=1";
            const enabled = document.cookie.indexOf("testcookie=") !== -1;
            document.cookie = "testcookie=1; Max-Age=0";
            return enabled;
        }

        function getCookie(name) {
            const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            return match ? decodeURIComponent(match[2]) : null;
        }

        (function () {
            if (!cookiesEnabled()) {
                const enable = confirm("Cookies dinonaktifkan di browser Anda.\nAktifkan cookies untuk melanjutkan.\n\nApakah Anda ingin mengaktifkan cookies sekarang?");

                if (enable) {
                    alert("Silakan aktifkan cookies di pengaturan browser Anda, lalu muat ulang halaman ini.");
                    location.reload();
                } else {
                    alert("Anda tidak dapat melanjutkan menggunakan tema tanpa mengaktifkan cookies.");
                    document.body.innerHTML = "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'><h2>⚠️ Cookies tidak aktif</h2><p>Aktifkan cookies untuk melanjutkan menggunakan tema ini.</p></div>";
                    return;
                }
            }

            const cookieValue = getCookie('langganan-premium');

            if (cookieValue !== 'true') {
                const baseUrl = typeof SITE_URL !== "undefined" ? SITE_URL : "/";
                window.location.href = baseUrl + "aktivasi-tema";
            }
        })();
    </script>

    <!-- Theme Tracker -->
    @include('theme::commons.scripts.theme_tracker')

</body>

</html>
