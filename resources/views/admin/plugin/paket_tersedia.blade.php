<div class="tab-pane active">
    @if (!$klien_terpasang && empty($token_layanan))
    <div class="alert alert-info">
        <h4><i class="fa fa-key"></i> Aktifkan Layanan Desa</h4>
        <p>Masukkan Token Layanan dari <strong>layanan.opendesa.id</strong> untuk mengaktifkan pengelolaan langganan (hosting, pembaruan, dll.).</p>
        {!! form_open(ci_route('plugin.simpan_token'), 'class="form-inline"') !!}
            <div class="input-group" style="width:100%">
                <input type="text" name="token_layanan" class="form-control"
                    placeholder="Token Layanan dari layanan.opendesa.id" required>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Simpan &amp; Aktifkan
                    </button>
                </span>
            </div>
        </form>
    </div>
    @elseif (!$klien_terpasang && !empty($token_layanan))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <strong><i class="fa fa-exclamation-triangle"></i> Modul klien langganan belum terpasang.</strong>
        Token Layanan tersimpan tetapi Layanan tidak dapat dihubungi saat token disimpan. Pastikan server memiliki akses internet lalu klik tombol di bawah untuk mencoba ulang.
        {!! form_open(ci_route('plugin.bootstrap_ulang'), 'style="display:inline;margin-left:8px"') !!}
            <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Coba Pasang Sekarang</button>
        </form>
    </div>
    @endif
    <style>
        /* Kartu modul punya tinggi berbeda-beda (deskripsi panjangnya bervariasi).
           Grid Bootstrap berbasis float membuat baris berikutnya rata ke kartu
           TERTINGGI di baris sebelumnya, menyisakan celah kosong di bawah kartu
           yang lebih pendek. Jadikan #mainform flex agar tiap kartu di baris yang
           sama otomatis sama tinggi. */
        #list-paket #mainform {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            /* .panel height:100% menelan margin-bottom bawaan Bootstrap,
               jadi jarak antar-baris diatur di sini. */
            row-gap: 20px;
        }
        #list-paket #mainform > [class*="col-"] > .panel {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        #list-paket #mainform > [class*="col-"] > .panel > .panel-body {
            flex: 1 1 auto;
        }
    </style>
    <div class="search">
        <div class="box box-info">
            <div class="box-header">
                <div class="row">
                    <div class="col-md-4">
                        <select name="tipe" id="tipe" class="control-form select2">
                            <option value="">-Pilih tipe -</option>
                            <option value="gratis">Gratis</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row" id="list-paket">
                {!! form_open(ci_route('plugin.pasang'), 'id="mainform" name="mainform"') !!}
                </form>
            </div>
            <ul class="pagination pagination-sm" id="pagination-container">

            </ul>
        </div>
    </div>
</div>

<!-- Modal Persetujuan Instalasi Paket Premium -->
<div class="modal fade" id="modalPersetujuanPaket" tabindex="-1" role="dialog" aria-labelledby="modalPersetujuanLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalPersetujuanLabel">
                    <i class="fa fa-exclamation-triangle"></i> &nbsp;Perhatian: Paket Premium
                </h4>
            </div>
            <div class="modal-body" id="modalPersetujuanBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-social btn-success btn-sm" id="btnSetujuPasang">
                    <i class="fa fa-check"></i> Setuju & Lanjutkan Instalasi
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            let pendingInstallValue = null;

            // Susun isi modal instalasi sesuai jenis paket. Strukturnya sama untuk
            // semua jenis (alert + nama paket + penjelasan + daftar poin):
            // - klien Layanan (Pelanggan): pengelola layanan non-aplikasi (hosting,
            //   pasang/pembaruan, verifikasi langganan & lisensi modul);
            // - modul berlisensi (mis. Anjungan): butuh "Lisensi <fitur>"
            //   (sekali bayar, berlaku selamanya);
            // - modul lain: butuh langganan Premium aktif untuk menerima update.
            function kontenModalPaket(nama, info) {
                const esc = (s) => $('<div>').text(s).html();
                const susun = (alert, penjelasan, poin) => `<div class="alert ${alert.kelas}">
                        <i class="fa fa-info-circle"></i> ${alert.isi}
                    </div>
                    <h5>Paket Premium: <strong>${esc(nama)}</strong></h5>
                    <p>${penjelasan}</p>
                    <ul>${poin.map((p) => `<li>${p}</li>`).join('')}</ul>`;

                if (info.isClient) {
                    return susun({
                        kelas: 'alert-info',
                        isi: '<strong>Penting:</strong> Modul ini gratis dan menjadi prasyarat sebelum memasang modul berlisensi.',
                    }, 'Modul ini adalah <strong>klien Layanan</strong> desa Anda. Memasangnya mengaktifkan pengelolaan layanan non-aplikasi:', [
                        'Langganan <strong>hosting</strong> desa',
                        'Layanan <strong>pemasangan &amp; pembaruan</strong> aplikasi',
                        'Verifikasi <strong>langganan/lisensi</strong> &amp; pengelolaan lisensi modul berbayar (mis. Anjungan)',
                    ]);
                }
                if (info.requiresEntitlement) {
                    const kunci = info.entitlement || nama;
                    const lisensi = esc('Lisensi ' + kunci.charAt(0).toUpperCase() + kunci.slice(1));
                    return susun({
                        kelas: 'alert-warning',
                        isi: `<strong>Penting:</strong> Modul ini memerlukan <strong>${lisensi}</strong> — sekali bayar, berlaku selamanya.`,
                    }, `Modul ini memerlukan <strong>${lisensi}</strong>. Aktivasi lisensi dilakukan melalui modul <strong>Layanan (Pelanggan)</strong>. Berikut yang perlu Anda ketahui:`, [
                        '<strong>Sekali bayar:</strong> Lisensi berlaku selamanya, tanpa perpanjangan maupun langganan berkala',
                        '<strong>Lisensi tersendiri:</strong> Dikelola lewat modul <strong>Layanan (Pelanggan)</strong>',
                    ]);
                }
                return susun({
                    kelas: 'alert-warning',
                    isi: '<strong>Penting:</strong> Pastikan Anda siap melanjutkan langganan Premium untuk terus mendapatkan manfaat penuh dari modul ini.',
                }, 'Modul ini memerlukan <strong>Langganan Premium yang Aktif</strong>. Berikut yang perlu Anda ketahui:', [
                    '<strong>Dengan Premium Aktif:</strong> Akses penuh ke modul dengan update versi terbaru',
                    '<strong>Premium Berakhir:</strong> Modul tidak akan menerima update versi terbaru',
                ]);
            }

            function compareVersions(version1, version2) {
                const splitVersion1 = version1.split('.');
                const splitVersion2 = version2.split('.');

                const maxLength = Math.max(splitVersion1.length, splitVersion2.length);

                for (let i = 0; i < maxLength; i++) {
                    const num1 = parseInt(splitVersion1[i]) || 0;
                    const num2 = parseInt(splitVersion2[i]) || 0;

                    if (num1 < num2) {
                        return -1;
                    } else if (num1 > num2) {
                        return 1;
                    }
                }

                return 0; // Versions are equal
            }

            function displayPagination(response) {
                // Populate the pagination container with links
                var paginationContainer = $('#pagination-container');
                paginationContainer.empty();
                const currentPage = response.meta.current_page
                const perPage = response.meta.per_page
                const totalPages = Math.ceil(response.meta.total / perPage)
                for (var i = 1; i <= totalPages; i++) {
                    // Create a link for each page
                    var pageLink = $('<li>', {
                        text: i,
                        html: `<a href="#">${i}</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = $(this).text();
                            loadModule(page);
                        }
                    });

                    // Add an active class to the current page
                    if (i == currentPage) {
                        pageLink.addClass('active');
                    }

                    // Append the link to the container
                    paginationContainer.append(pageLink);
                }

                // Add "Previous" button
                if (currentPage > 1) {
                    var prevButton = $('<li>', {
                        text: i,
                        html: `<a href="#">Sebelumnya</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = currentPage - 1;
                            loadModule(page);
                        }
                    });

                    prevButton.insertBefore(paginationContainer.find('li:first-child'));
                }

                // Add "Next" button
                if (currentPage < totalPages) {
                    var nextButton = $('<li>', {
                        text: i,
                        html: `<a href="#">Selanjutnya</a>`,
                        click: function() {
                            // Fetch data for the clicked page
                            var page = currentPage + 1;
                            loadModule(page);
                        }
                    });
                    paginationContainer.append(nextButton);
                }
            }

            function loadModule(page, tipe) {
                let paketTerpasang = {!! $paket_terpasang ?? '{}' !!}
                let klienTerpasang = {!! ($klien_terpasang ?? true) ? 'true' : 'false' !!}
                let kelolaEksternal = {!! ($kelola_eksternal ?? false) ? 'true' : 'false' !!}
                let cardView = [],
                    disabledPaket, buttonInstall, versionCheck, templateTmp
                let paketInfo = {}
                let urlModule = '{{ $url_marketplace }}'
                const templateCard = `@include('admin.plugin.item')`
                $('div#list-paket').find('form').empty()
                if (tipe === undefined) {
                    tipe = $('#tipe').val()
                }
                $.ajax({
                    url: urlModule,
                    data: {
                        page: page,
                        tipe: tipe
                    },
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer {{ $token_layanan }}',
                        'Accept': 'application/json'
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: response.responseJSON.message
                        })
                    },
                    success: function(response) {
                        const data = response.data
                        for (let i in data) {
                            templateTmp = templateCard
                            disabledPaket = ''
                            const installValue = `${data[i].name}___${data[i].url}___${data[i].version}`
                            paketInfo[data[i].name] = {
                                requiresEntitlement: !!data[i].requires_entitlement,
                                entitlement: data[i].entitlement || '',
                                isClient: !!data[i].is_client,
                            }
                            buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary btn-pasang-paket">Pasang</button>`
                            if (paketTerpasang[data[i].name] !== undefined) {
                                if (kelolaEksternal) {
                                    // SiapPakai (premium#6881): kode Modules/ disinkronkan SENTRAL
                                    // dari Layanan oleh proses ops di luar Premium (dasbor-siappakai
                                    // ModuleService::install()) — tak ada aksi upgrade per-desa yang
                                    // berarti, klik hanya akan migrasi ulang kode yang sama tanpa
                                    // benar-benar memperbarui apa pun.
                                    buttonInstall = `<span class="label label-success" title="Versi diperbarui otomatis oleh SiapPakai"><i class="fa fa-check"></i> Terpasang</span>`
                                } else {
                                    versionCheck = compareVersions(data[i].version, paketTerpasang[data[i].name].version)
                                    if (versionCheck > 0) {
                                        buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary btn-pasang-paket">Tingkatkan Versi</button>`
                                    } else {
                                        disabledPaket = 'disabled'
                                        buttonInstall = `<button type="button" ${disabledPaket} name="pasang" value="${installValue}" class="btn btn-primary">Pasang</button>`
                                    }
                                }
                            }

                            // Prasyarat: modul berbayar terkunci sampai klien langganan
                            // (Layanan) terpasang. Klien sendiri tak berbayar → tak terkunci.
                            if (!klienTerpasang && data[i].requires_entitlement && paketTerpasang[data[i].name] === undefined) {
                                buttonInstall = `<button type="button" disabled class="btn btn-default btn-terkunci" title="Perlu Layanan aktif — pasang paket klien langganan lebih dulu"><i class="fa fa-lock"></i> Perlu Layanan</button>`
                            }

                            templateTmp = templateTmp.replace('__name__', data[i].name)
                            templateTmp = templateTmp.replace('__version__', data[i].version)
                            templateTmp = templateTmp.replace('__description__', data[i].description)
                            templateTmp = templateTmp.replace('__button__', buttonInstall)
                            templateTmp = templateTmp.replace('__thumbnail__', data[i].thumbnail)
                            templateTmp = templateTmp.replace('__price__', data[i].price)
                            templateTmp = templateTmp.replace('__totalInstall__', data[i].totalInstall)
                            cardView.push(templateTmp)
                        }
                        $('div#list-paket').find('form').append(cardView.join(''))
                        
                        // Event listener untuk tombol pasang paket
                        $('div#list-paket').find('.btn-pasang-paket').click(function(e) {
                            e.preventDefault();
                            const paketName = $(this).val().split('___')[0];
                            pendingInstallValue = $(this).val();

                            // Isi modal sesuai jenis paket (klien Layanan / berlisensi / gratis)
                            $('#modalPersetujuanBody').html(kontenModalPaket(paketName, paketInfo[paketName] || {}));
                            $('#modalPersetujuanPaket').modal('show');
                        });

                        displayPagination(response)
                    }
                })
            }

            // Handle tombol setuju di modal
            $('#btnSetujuPasang').click(function() {
                if (pendingInstallValue) {
                    $('#modalPersetujuanPaket').modal('hide');
                    
                    // Submit form dengan nilai paket
                    Swal.fire({
                        title: 'Sedang Memproses',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    
                    // Create hidden input dan submit
                    const input = $('<input>').attr('type', 'hidden').attr('name', 'pasang').val(pendingInstallValue);
                    $('#mainform').append(input);
                    $('#mainform').submit();
                }
            });

            $('#tipe').on('change', function() {
                loadModule(1, $(this).val())
            })

            $('#tipe').trigger('change')
        })
    </script>
@endpush
