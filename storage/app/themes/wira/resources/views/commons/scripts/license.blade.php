<script>
/**
 * Theme Activation Script
 * Logic:
 * 1. Check local 'pemesanan-tema' cookie for valid product key.
 * 2. If invalid/missing, fallback to remote verification server API.
 * 3. Redirect to /aktivasi-tema only if both checks fail.
 */
(function () {
    // --- Configuration ---
    const VERIFICATION_SERVER_URL = "https://aktivasi.digidesa.id"; // Change to your actual server URL
    const PRODUCT_KEY = "1b7b39be-a750-48ad-9090-629b1c6fd6e9";
    const REDIRECT_PATH = "/aktivasi-tema";

    // Defines the license key globally for other scripts (like AI Chat)
    window.DESA_THEME_LICENSE_KEY = PRODUCT_KEY;

    // --- Helpers ---
    function cookiesEnabled() {
        document.cookie = "testcookie=1";
        const enabled = document.cookie.indexOf("testcookie=") !== -1;
        document.cookie = "testcookie=1; Max-Age=0";
        return enabled;
    }

    // Skip redirection on local/dev environments
    const hostname = window.location.hostname;
    const IS_LOCALHOST = hostname === 'localhost' ||
        hostname === '127.0.0.1' ||
        hostname.endsWith('.test') ||
        hostname.endsWith('.local');

    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
    }

    function redirectToActivation() {
        const path = window.location.pathname;
        const currentUrl = window.location.href;

        if (path.includes(REDIRECT_PATH) || currentUrl.includes(REDIRECT_PATH)) {
            return;
        }

        const baseUrl = typeof SITE_URL !== "undefined" ? SITE_URL : "/";
        const targetUrl = baseUrl.replace(/\/$/, '') + REDIRECT_PATH;

        window.location.href = targetUrl;
    }

    // --- Core Logic ---
    async function checkActivation() {
        if (IS_LOCALHOST) {
            return;
        }

        // 1. Initial Cookie Check (Browser Settings)
        if (!cookiesEnabled()) {
            const enable = confirm("Cookies dinonaktifkan di browser Anda.\nAktifkan cookies untuk melanjutkan.\n\nApakah Anda ingin mengaktifkan cookies sekarang?");
            if (enable) {
                alert("Silakan aktifkan cookies di pengaturan browser Anda, lalu muat ulang halaman ini.");
                location.reload();
            } else {
                document.body.innerHTML = "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'><h2>⚠️ Cookies tidak aktif</h2><p>Aktifkan cookies untuk melanjutkan menggunakan tema ini.</p></div>";
            }
            return;
        }

        // 2. Local Cookie Activation Check
        const cookieValue = getCookie('pemesanan-tema');

        let tema = null;
        let isLocallyActivated = false;

        if (cookieValue) {
            try {
                tema = JSON.parse(cookieValue);

                // Check if the product key exists in either format:
                // 1. Array: ["1b7b39be-a750-48ad-9090-629b1c6fd6e9"]
                // 2. Object: {"1b7b39be-a750-48ad-9090-629b1c6fd6e9": true}
                if (Array.isArray(tema)) {
                    isLocallyActivated = tema.includes(PRODUCT_KEY);
                } else if (tema && typeof tema === 'object') {
                    isLocallyActivated = tema[PRODUCT_KEY] === true;
                }
            } catch (e) {
                console.error('[License] Invalid cookie format:', e);
            }
        }

        // If locally activated, exit early - no need for remote check
        if (isLocallyActivated) {
            return;
        }

        // 3. Fallback: Remote API Activation Check

        try {
            const domain = window.location.hostname;
            let encodedDomain;
            try {
                encodedDomain = btoa(domain);
            } catch (b64Error) {
                console.error("[License] Failed to encode domain with btoa:", b64Error);
                throw b64Error;
            }

            const fetchUrl = `${VERIFICATION_SERVER_URL}/api/verify-theme-domain?domain=${encodedDomain}`;

            // AbortController safety check
            let signal = null;
            let timeoutId = null;
            if (typeof AbortController !== 'undefined') {
                const controller = new AbortController();
                signal = controller.signal;
                timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
            } else {
                console.warn("[License] AbortController not supported in this browser.");
            }

            try {
                const response = await fetch(fetchUrl, { signal });
                if (timeoutId) clearTimeout(timeoutId);

                if (response.ok) {
                    const data = await response.json();

                    if (data.status === 'verified') {
                        // Set a local cookie to avoid re-fetching on next page load
                        const cookieData = JSON.stringify([PRODUCT_KEY]);
                        document.cookie = `pemesanan-tema=${cookieData}; path=/; max-age=86400`; // 24 hours
                        return; // Success, exit script
                    }
                } else {
                    console.error("[License] Server returned non-OK status. Code:", response.status);
                    try {
                        const errorData = await response.text();
                        console.error("[License] Error body:", errorData);
                    } catch (e) { }
                }
            } catch (fetchError) {
                if (timeoutId) clearTimeout(timeoutId);
                const isTimeout = fetchError.name === 'AbortError' || fetchError.message.includes('timeout');
                console.error("[License] Fetch failed:", isTimeout ? 'Timeout' : fetchError.message);
                throw fetchError;
            }
        } catch (error) {
            console.error("[License] Verification server error:", error);
        }

        // 4. Final Fallback: Redirect

        // Final check: if we are already on the activation page, DO NOT REDIRECT
        const path = window.location.pathname;
        if (path.includes(REDIRECT_PATH)) {
            return;
        }

        // Delay redirect slightly to allow page to finish loading
        setTimeout(() => {
            redirectToActivation();
        }, 1000);
    }

    // Execute the check
    checkActivation();
})();
</script>
