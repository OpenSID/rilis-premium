@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-purple">
            <h4 class="box-title">Verifikasi</h4>
        </div>
        <div class="box-body">
            @if ($notif_verifikasi = session('notif_verifikasi'))
                <div class="callout callout-{{ $notif_verifikasi['status'] == 1 ? 'success' : 'danger' }}">
                    <p>{{ $notif_verifikasi['pesan'] }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px 20px;">
                    @if ($verifikasi = session('sudah-verifikasi-semua'))
                        <input type="text" class="form-control input-md bilangan pin" value="Semua Akun Anda Sudah di Verifikasi" disabled style="background-color: #5cb85c; color: #ffffff; height:50px; padding:20px 20px; margin-top:50px; margin-bottom:50px">
                    @else
                        <!-- start nav-tab -->
                        <div class="nav-tabs-custom">
                            @if ($tab_aktif = session('tab-aktif'))
                                <ul class="nav nav-tabs">
                                    <li id="link-telegram" class="{{ $tab_aktif['status'] == 0 ? 'active' : '' }}"><a href="#telegram" data-toggle="tab">Telegram</a></li>
                                    <li id="link-email" class="{{ $tab_aktif['status'] == 1 ? 'active' : '' }}"><a href="#email" data-toggle="tab">Email</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="{{ $tab_aktif['status'] == 0 ? 'active' : '' }} tab-pane" id="telegram">
                                        <p style="margin-top: 10px; margin-bottom: 10px;">
                                            Ini adalah langkah untuk melakukan verifikasi telegram, silakan lakukan mulai dari Tahap 1 sampai 4.
                                        </p>

                                        <div id="smartwizard">
                                            <ul class="nav">
                                                <li>
                                                    <a class="nav-link" href="#langkah-1">
                                                        Langkah 1<br />
                                                        <small>Link Verifikasi</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-2">
                                                        Langkah 2<br />
                                                        <small>Masukan User ID</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-3">
                                                        Langkah 3<br />
                                                        <small>OTP Verifikasi</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-4">
                                                        Langkah 4<br />
                                                        <small>Status Verifikasi {{ $tgl_verifikasi_telegram != null ? '(Sudah)' : '' }}</small>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <div id="langkah-1" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 1 Link Verifikasi</h2>
                                                    <p>1. Silakan Klik link ini <a href="https://t.me/opensid_notifikasi_bot" target="_blank">Verifikasi Telegram {{ config_item('nama_aplikasi') }}</a> </p>
                                                    <p>2. Klik start untuk mendapatkan kode User ID Anda. </p>
                                                    <p>3. Masukkan kode User ID di <strong>Langkah 2</strong> dengan cara klik tombol "Selanjutnya".</p>
                                                </div>
                                                <div id="langkah-2" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 2 Masukan User ID</h2>
                                                    <p>1. Langkah ini digunakan untuk masukan user ID Anda yang telah didapatkan pada langkah 1.</p>
                                                    <p>2. Setelah mengirim User ID pada tombol "Kirim", maka Anda akan mendapatkan pesan kode berupa OTP Verifikasi</p>
                                                    <p>3. Silakan melanjutkan ke Langkah 3 jika sudah mendapatkan OTP Verifikasi dengan cara klik tombol "Selanjutnya".</p>

                                                    <form action="{{ $form_kirim_userid }}" method="POST">
                                                        <div class=" form-group col-md-5">
                                                            <label for="telegram_userID">Silakan Masukan User ID</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}" name="telegram_userID" placeholder="User ID Telegram" maxlength="16">
                                                                <span class="input-group-addon"><i class="fa fa-eye-slash" id="telegram_userID" onclick="show(this);" aria-hidden="true"></i></span>
                                                            </div>
                                                            <button type="submit" class="btn bg-green pull-right" style="margin-top: 10px;">Kirim</button>
                                                        </div>
                                                    </form>

                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                </div>
                                                <div id="langkah-3" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 3 OTP Verifikasi</h2>
                                                    <p>1. Langkah ini digunakan untuk masukan OTP Verifikasi yang telah didapatkan pada langkah 2.</p>
                                                    <p>2. Setelah mengirim OTP Verifikasi pada tombol "Kirim", maka akun telegram sudah terverifikasi</p>
                                                    <p>3. OTP Verifikasi ini berbatas waktu dengan lama 1 menit.</p>
                                                    <p>4. Silakan melanjutkan ke Langkah 4 untuk melihat status sudah terverifikasi atau belum dengan cara klik tombol "Selanjutnya".</p>
                                                    <p>5. Jika Status telegram belum terverifikasi maka silakan ulang dari awal.</p>

                                                    <form action="{{ $form_kirim_otp }}" method="POST" id="validasi">
                                                        <div class="form-group col-md-5">
                                                            <label for="token_telegram">Silakan Masukan OTP Verifikasi</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}" name="token_telegram" placeholder="OTP Verifikasi">
                                                                <span class="input-group-addon"><i class="fa fa-eye-slash" id="token_telegram" onclick="show(this);" aria-hidden="true"></i></span>
                                                            </div>
                                                            <button type="submit" class="btn bg-green pull-right" style="margin-top: 10px;">Kirim</button>
                                                        </div>
                                                    </form>

                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                </div>
                                                <div id="langkah-4" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 4 Status Verifikasi</h2>
                                                    <input type="text" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}" name="user_id" value="Akun Telegram Anda {{ $tgl_verifikasi_telegram != null ? 'Sudah' : 'Belum' }} di verifikasi"
                                                        disabled style="{{ $tgl_verifikasi_telegram != null ? 'background-color: #5cb85c; color: #ffffff;' : 'color: #000000;' }} "
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="{{ $tab_aktif['status'] == 1 ? 'active' : '' }} tab-pane" id="email">
                                        <p style="margin-top: 10px; margin-bottom: 10px;">
                                            Ini adalah langkah untuk melakukan verifikasi email, silakan lakukan mulai dari Tahap 1 sampai 4.
                                        </p>

                                        <div id="smartwizard-email">
                                            <ul class="nav">
                                                <li>
                                                    <a class="nav-link" href="#langkah-1">
                                                        Langkah 1<br />
                                                        <small>Persiapkan Email</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-2">
                                                        Langkah 2<br />
                                                        <small>Masukan Alamat Email</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-3">
                                                        Langkah 3<br />
                                                        <small>OTP Verifikasi</small>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="nav-link" href="#langkah-4">
                                                        Langkah 4<br />
                                                        <small>Status Verifikasi {{ $tgl_verifikasi_email != null ? '(Sudah)' : '' }}</small>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content">
                                                <div id="langkah-1" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 1 Persiapkan Email</h2>
                                                    <p>1. Silakan Login ke Alamat Email yang akan diverifikasi</p>
                                                    <p>2. Masukkan Alamat Email di <strong>Langkah 2</strong> dengan cara klik tombol "Selanjutnya".</p>
                                                </div>
                                                <div id="langkah-2" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 2 Masukan Alamat Email</h2>
                                                    <p>1. Langkah ini digunakan untuk masukan alamat email Anda</p>
                                                    <p>2. Setelah mengirim alamat email pada tombol "Kirim", maka Anda akan mendapatkan pesan kode berupa OTP Verifikasi</p>
                                                    <p>3. Silakan melanjutkan ke Langkah 3 jika sudah mendapatkan OTP Verifikasi dengan cara klik tombol "Selanjutnya".</p>

                                                    <form action="{{ $form_kirim_email }}" method="POST" id="validasi">
                                                        <div class="form-group col-md-5">
                                                            <label for="alamat_email">Silakan Masukan Alamat Email</label>
                                                            <input type="text" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvtext' : '' }}" name="alamat_email" placeholder="Alamat Email">
                                                            <button type="submit" class="btn bg-green pull-right" style="margin-top: 10px;">Kirim</button>
                                                        </div>
                                                    </form>

                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                </div>
                                                <div id="langkah-3" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 3 OTP Verifikasi</h2>
                                                    <p>1. Langkah ini digunakan untuk masukan OTP Verifikasi yang telah didapatkan pada langkah 2.</p>
                                                    <p>2. Setelah mengirim OTP Verifikasi pada tombol "Kirim", maka alamat email sudah terverifikasi</p>
                                                    <p>3. OTP Verifikasi ini berbatas waktu dengan lama 1 menit.</p>
                                                    <p>4. Silakan melanjutkan ke Langkah 4 untuk melihat status sudah terverifikasi atau belum dengan cara klik tombol "Selanjutnya".</p>
                                                    <p>5. Jika Status email belum terverifikasi maka silakan ulang dari awal.</p>

                                                    <form action="{{ $form_kirim_otp_email }}" method="POST" id="validasi">
                                                        <div class="form-group col-md-5">
                                                            <label for="token_email">Silakan Masukan OTP Verifikasi</label>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}" name="token_email" placeholder="OTP Verifikasi">
                                                                <span class="input-group-addon"><i class="fa fa-eye-slash" id="token_email" onclick="show(this);" aria-hidden="true"></i></span>
                                                            </div>
                                                            <button type="submit" class="btn bg-green pull-right" style="margin-top: 10px;">Kirim</button>
                                                        </div>
                                                    </form>

                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                    <p class="text-right">.</p>
                                                </div>
                                                <div id="langkah-4" class="tab-pane" role="tabpanel">
                                                    <h2 class="StepTitle">Langkah 4 Status Verifikasi</h2>
                                                    <input type="text" class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}" name="user_email" value="Alamat Email Anda {{ $tgl_verifikasi_email != null ? 'Sudah' : 'Belum' }} di verifikasi"
                                                        disabled style="{{ $tgl_verifikasi_email != null ? 'background-color: #5cb85c; color: #ffffff;' : 'color: #000000;' }} "
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                            @endif
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function show(elem) {
            if ($(elem).hasClass('fa-eye')) {
                $(".pin").attr('type', 'password');
                $(".fa-eye").addClass('fa-eye-slash');
                $(".fa-eye").removeClass('fa-eye');
            } else {
                $(".pin").attr('type', 'text');
                $(".fa-eye-slash").addClass('fa-eye');
                $(".fa-eye-slash").removeClass('fa-eye-slash');
            }
        }

        $(document).ready(function() {
            // Link tab Telegram
            $('#link-telegram').click(function(e) {
                e.preventDefault();
                window.location = '{{ site_url('layanan-mandiri/verifikasi/telegram') }}';
            });

            // Link tab Email
            $('#link-email').click(function(e) {
                e.preventDefault();
                window.location = '{{ site_url('layanan-mandiri/verifikasi/email') }}';
            });


            // Smartwizard Telegram
            $('#smartwizard').smartWizard({
                selected: 0, // Initial selected step, 0 = first step
                theme: 'arrows', // 1. default 2. arrows 3. dots 4. progress  theme for the wizard, related css need to include for other than default theme
                justified: true, // Nav menu justification. true/false
                darkMode: false, // Enable/disable Dark Mode if the theme supports. true/false
                autoAdjustHeight: true, // Automatically adjust content height
                cycleSteps: false, // Allows to cycle the navigation of steps
                backButtonSupport: true, // Enable the back button support
                enableURLhash: true, // Enable selection of the step based on url hash
                transition: {
                    animation: 'fade', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
                    speed: '400', // Transion animation speed
                    easing: '' // Transition animation easing. Not supported without a jQuery easing plugin
                },
                toolbarSettings: {
                    toolbarPosition: 'bottom', // none, top, bottom, both
                    toolbarButtonPosition: 'right', // left, right, center
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    toolbarExtraButtons: [] // Extra buttons to show on toolbar, array of jQuery input/buttons elements
                },
                anchorSettings: {
                    anchorClickable: true, // Enable/Disable anchor navigation
                    enableAllAnchors: false, // Activates all anchors clickable all times
                    markDoneStep: true, // Add done state on navigation
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                },
                keyboardSettings: {
                    keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    keyLeft: [37], // Left key code
                    keyRight: [39] // Right key code
                },
                lang: { // Language variables for button
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                },
                disabledSteps: [], // Array Steps disabled
                errorSteps: [], // Highlight step with errors
                hiddenSteps: [] // Hidden steps
            });

            $('#smartwizard').smartWizard("stepState", [2, 3], "disable");

            @if (session('sudah-diverifikasi'))
                $('#smartwizard').smartWizard("stepState", [0, 1, 2], "disable");
                $('#smartwizard').smartWizard("stepState", [3], "enable");
                $('#smartwizard').smartWizard("goToStep", 3);
            @endif

            @if (session('kirim-otp-telegram'))
                $('#smartwizard').smartWizard("stepState", [0, 1], "disable");
                $('#smartwizard').smartWizard("stepState", [2], "enable");
                $('#smartwizard').smartWizard("goToStep", 2);
            @endif

            // Smartwizard Email
            $('#smartwizard-email').smartWizard({
                selected: 0, // Initial selected step, 0 = first step
                theme: 'arrows', // 1. default 2. arrows 3. dots 4. progress  theme for the wizard, related css need to include for other than default theme
                justified: true, // Nav menu justification. true/false
                darkMode: false, // Enable/disable Dark Mode if the theme supports. true/false
                autoAdjustHeight: true, // Automatically adjust content height
                cycleSteps: false, // Allows to cycle the navigation of steps
                backButtonSupport: true, // Enable the back button support
                enableURLhash: true, // Enable selection of the step based on url hash
                transition: {
                    animation: 'fade', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
                    speed: '400', // Transion animation speed
                    easing: '' // Transition animation easing. Not supported without a jQuery easing plugin
                },
                toolbarSettings: {
                    toolbarPosition: 'bottom', // none, top, bottom, both
                    toolbarButtonPosition: 'right', // left, right, center
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    toolbarExtraButtons: [] // Extra buttons to show on toolbar, array of jQuery input/buttons elements
                },
                anchorSettings: {
                    anchorClickable: true, // Enable/Disable anchor navigation
                    enableAllAnchors: false, // Activates all anchors clickable all times
                    markDoneStep: true, // Add done state on navigation
                    markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                    removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
                    enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
                },
                keyboardSettings: {
                    keyNavigation: true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
                    keyLeft: [37], // Left key code
                    keyRight: [39] // Right key code
                },
                lang: { // Language variables for button
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                },
                disabledSteps: [], // Array Steps disabled
                errorSteps: [], // Highlight step with errors
                hiddenSteps: [] // Hidden steps
            });

            $('#smartwizard-email').smartWizard("stepState", [2, 3], "disable");
            $('#smartwizard-email').smartWizard("goToStep", 0);

            @if (session('sudah-diverifikasi-email'))
                $('#smartwizard-email').smartWizard("stepState", [0, 1, 2], "disable");
                $('#smartwizard-email').smartWizard("stepState", [3], "enable");
                $('#smartwizard-email').smartWizard("goToStep", 3);
            @endif

            @if (session('kirim-otp-email'))
                $('#smartwizard-email').smartWizard("stepState", [0, 1], "disable");
                $('#smartwizard-email').smartWizard("stepState", [2], "enable");
                $('#smartwizard-email').smartWizard("goToStep", 2);
            @endif
        });
    </script>
@endpush
