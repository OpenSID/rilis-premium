@extends('admin.auth.index')

@php
    preg_match('/(\d+)/', $errors?->first('email'), $matches);
    $second = $matches[0] ?? 0;
    $isProduction = app()->isProduction();
@endphp

@section('content')
    <form id="validasi" class="login-form" action="{{ $form_action }}" method="post">
        <div class="form-group">
            <input
                name="username"
                type="text"
                autocomplete="off"
                placeholder="Nama pengguna"
                @disabled($second)
                class="form-username form-control required"
                maxlength="100"
            >
        </div>
        <div class="form-group">
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="off"
                placeholder="Kata sandi"
                @disabled($second)
                class="form-username form-control required"
                maxlength="100"
            >
        </div>

        @if ($isProduction && setting('google_recaptcha'))
            {!! app('captcha')->display() !!}
        @elseif ($isProduction)
            <div class="form-group">
                <a href="#" id="b-captcha" onclick="event.preventDefault(); document.getElementById('captcha').src = '{{ site_url('captcha') }}?' + Math.random();" style="color: #000000;">
                    <img id="captcha" src="{{ site_url('captcha') }}" alt="CAPTCHA Image" />
                </a>
            </div>
            <div class="form-group captcha">
                <input
                    name="captcha_code"
                    type="text"
                    class="form-control required"
                    maxlength="6"
                    placeholder="Masukkan kode di atas"
                    @disabled($second)
                    autocomplete="off"
                />
            </div>
        @endif

        <div class="form-group">
            <input @disabled($second) type="checkbox" id="checkbox" class="form-checkbox">
            <label for="checkbox" style="font-weight: unset">Tampilkan kata sandi</label>
            <a href="{{ site_url('siteman/lupa_sandi') }}" class="btn" role="button" aria-pressed="true">Lupa kata sandi?</a>
        </div>
        <div class="form-group">
            <button type="submit" class="btn" @disabled($second)>Masuk</button>
        </div>
    </form>
@endsection

@push('js')
    @if ($isProduction && setting('google_recaptcha'))
        {!! app('captcha')->renderJs('id', true, 'recaptchaCallback') !!}

        <script>
            var recaptchaCallback = function() {
                grecaptcha.render(document.querySelector('.g-recaptcha'), {
                    'sitekey': '{{ $list_setting->firstWhere('key', 'google_recaptcha_site_key')?->value }}',
                    'error-callback': function() {
                        $.ajax({
                            url: '{{ site_url('siteman/matikan-captcha') }}',
                            type: 'post',
                            success: function(response) {
                                // Redirect to the 'siteman' URL after disabling captcha
                                window.location.href = '{{ site_url('siteman') }}';
                            },
                            error: function(xhr, status, error) {
                                // Log the error for debugging
                                console.error('Error in captcha disabling request:', error);
                            }
                        });
                    }
                });
            }
        </script>
    @endif

    <script>
        function start_countdown() {
            let totalSeconds = {{ $second }};
            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = `Terlalu banyak upaya masuk. Silakan coba lagi dalam ${minutes} menit ${seconds} detik.`;
                    totalSeconds--;
                }
            }, 1000);
        }

        $(document).ready(function() {
            var pass = $("#password");
            $('#checkbox').click(function() {
                if (pass.attr('type') === "password") {
                    pass.attr('type', 'text');
                } else {
                    pass.attr('type', 'password')
                }
            });
            if ($('#countdown').length) {
                start_countdown();
            }
        });
    </script>
@endpush
