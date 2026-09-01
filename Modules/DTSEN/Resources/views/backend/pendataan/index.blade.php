@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        DTSEN
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">DTSEN</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-btn-button judul="Kelola Keluarga" icon="fa fa-reply" type="btn-default" modal="true" :url="'keluarga'" />
            @if (can('u'))
            <x-btn-button judul="Tambah Data Baru" icon="fa fa-plus" modal='true' modalTarget="modal-survey" type="btn-success" :url="'dtsen/pendataan#'" />
            <x-btn-button judul="Sinkronkan Data Keluarga" icon="fa fa-refresh" modal="true" modalTarget="modal-pengaturan-dtsen" type="btn-info" :url="'dtsen/pendataan#'" />
            @endif
            {{-- <x-btn-button 
                judul="Cetak Prelist Terpilih" 
                icon="fa fa-print" 
                type="bg-purple" 
                url="#" 
                formAction="true"
                :disabled="true"
                attribut='id="cetak_terpilih"'
            /> --}}
            <x-btn-button judul="Ekspor ke excel" icon="fa fa-file" type="bg-navy" :url="'dtsen/pendataan/ekspor?versi=' . \Modules\DTSEN\Enums\DtsenEnum::VERSION_CODE" />
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <div class="row mepet" style="margin-bottom:10px">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2" id="sex" name="sex">
                            <option value="">Pilih Jenis Kelamin</option>
                            @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('admin.layouts.components.wilayah')
                </div>
                <table class="table table-bordered table-striped table-hover nowrap" id="tabeldata">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th rowspan="2"><input type="checkbox" id="checkall" /></th>
                            <th rowspan="2">No</th>
                            <th rowspan="2" class="padat">Aksi</th>
                            <th colspan="3" class="padat" kolom="3,4,5">Status Data</th>
                            <th colspan="6" class="padat" kolom="5,6,7,8,9,10">Kepala Keluarga</th>
                            <th rowspan="2">Petugas</th>
                            <th rowspan="2">Terakhir diubah</th>
                            <th rowspan="2">Status Kelengkapan</th>
                        </tr>
                        <tr>
                            <th>Desil Kemensos</th>
                            <th>Desil Analisis</th>
                            <th>Pengisian</th>
                            <th>NIK</th>
                            <th nowrap>Nama</th>
                            <th>Jumlah Anggota</th>
                            <th kolom="5">{{ ucwords(setting('sebutan_dusun')) }}</th>
                            <th>RW</th>
                            <th>RT</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="modal-survey" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Data Baru</h4>
                </div>
                <form data-action="{{ ci_route('dtsen.pendataan.new') }}" id="form-new-dtsen" method="POST">
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <div class="box" style="border-top:none">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="id_keluarga">NIK / Nama Kepala Keluarga</label>
                                        <select class="form-control input-sm select2 required" id="id_keluarga" name="id_keluarga" style="width:100%;">
                                            <option value="">-- Silakan Cari NIK / Nama Kepala Kepala Keluarga--</option>
                                            @foreach ($keluarga as $data)
                                                <option value="{{ $data->id }}">NIK :{{ $data->kepalaKeluarga->nik . ' - ' . $data->kepalaKeluarga->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
                                </div>
                                <div>
                                    @include('dtsen::backend.pendataan.info_new_dtsen')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-pengaturan-dtsen"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalPengaturanLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modalPengaturanLabel"><i class="fa fa-cogs"></i> &nbsp; Pengaturan &amp; Sinkronisasi Data Keluarga DTSEN</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/sync-semua-keluarga'), 'id="form-pengaturan-dtsen" method="POST"') !!}
                <div class="modal-body">
                    <p class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Berikut adalah daftar keluarga <b>aktif/hidup</b> yang <b>belum terdaftar</b> di data DTSEN. Centang keluarga yang ingin dimasukkan, atau centang kotak di header tabel untuk memilih semua.
                    </p>
                    <p class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> <b>Maksimal 100 data</b> yang dapat dipilih untuk sinkronisasi sekaligus.
                    </p>
                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-bordered table-striped table-hover nowrap" id="tabel-sync-dtsen">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th class="padat text-center"><input type="checkbox" id="checkall-sync" disabled /></th>
                                    <th class="padat">No</th>
                                    <th>No. KK</th>
                                    <th>NIK Kepala Keluarga</th>
                                    <th>Nama Kepala Keluarga</th>
                                    <th>Wilayah (Dusun / RW / RT)</th>
                                    <th class="padat text-center">Jumlah Anggota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan di-load via DataTables server-side -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-sync-submit"><i class="fa fa-check"></i> Simpan &amp; Sinkronkan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus Standar -->
    @include('admin.layouts.components.konfirmasi_hapus')
    <div
        class="modal fade"
        id="modal-cetak-multi-dtsen"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/cetak2'), 'method="POST"') !!}
                <div class="modal-body">
                    <p class="alert alert-info">
                        Proses cetak dapat memakan waktu cukup lama dan memerlukan halaman ini untuk tetap terbuka
                    </p>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td>NIK</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Hanya cetak file yang sudah siap</button>
                    <button type="button" id="batal_cetak" class="btn btn-danger btn-sm" data-dismiss="modal">Tutup</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-ekspor"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Cetak</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/ekspor'), 'method="GET"') !!}
                <div class="modal-body">
                    <select name="versi" class="form-control">
                        @foreach (Modules\DTSEN\Enums\DtsenEnum::VERSION_LIST as $key => $value)
                            <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Ekspor</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div
        class="modal fade"
        id="modal-impor"
        style="overflow: scroll;"
        tabindex="-1"
        role="dialog"
        aria-labelledby="myModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Proses Impor</h4>
                </div>
                {!! form_open(ci_route('dtsen/pendataan/impor'), 'method="GET"') !!}
                <div class="modal-body">
                    <select name="versi" class="form-control">
                        @foreach (Modules\DTSEN\Enums\DtsenEnum::VERSION_LIST as $key => $value)
                            <option value="{{ $key }}" {{ $key == 1 ? 'disabled' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                    <div id="impor_info"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-sm btn-social btn-primary"><i class="fa fa-check"></i> Impor</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.layouts.components.ajax_dtsen')
    <script>
        $(document).ready(function() {
            let batal_cetak = false;
            const MAX_CHECKBOX_SELECTION = 100;

            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            // Select2 dengan fitur pencarian karena tidak ngeload /js/custom.select2.js
            $('.select2').select2({
                width: '100%',
                dropdownAutoWidth: true
            });

            // Initialize DataTables untuk modal sinkronisasi
            var TableSyncDtsen = $('#tabel-sync-dtsen').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('dtsen/pendataan/datatables-sync') }}",
                    method: 'POST',
                    data: function(req) {
                        req._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'ceklist', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'no_kk', name: 'keluarga.no_kk' },
                    { data: 'nik', name: 'kk.nik' },
                    { data: 'nama', name: 'kk.nama' },
                    { data: 'wilayah_text', orderable: false, searchable: false },
                    { data: 'anggota_count', orderable: false, searchable: false, className: 'text-center' },
                ],
                order: [[2, 'asc']], // Order by No. KK
                language: {
                    'url': "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
                    'emptyTable': 'Semua keluarga aktif/hidup di desa sudah terdaftar di data DTSEN.',
                    'zeroRecords': 'Tidak ada data keluarga yang ditemukan.'
                },
                drawCallback: function(settings) {
                    // Re-bind checkbox events after DataTables redraw
                    updateCheckAllState();
                    // Enable checkall checkbox if there's data
                    const tableInfo = TableSyncDtsen.page.info();
                    $('#checkall-sync').prop('disabled', tableInfo.recordsTotal === 0);
                },
                initComplete: function(settings, json) {
                    // Enable checkall checkbox based on initial data
                    const tableInfo = TableSyncDtsen.page.info();
                    $('#checkall-sync').prop('disabled', tableInfo.recordsTotal === 0);
                }
            });

            // Handle checkbox selection limit di modal sinkronisasi
            $(document).on('change', '.cb-sync', function() {
                const checkedCount = $('.cb-sync:checked').length;

                if (checkedCount > MAX_CHECKBOX_SELECTION) {
                    $(this).prop('checked', false);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Batas Pemilihan',
                        text: 'Maksimal 100 data yang dapat dipilih untuk sinkronisasi sekaligus.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Update checkall state
                updateCheckAllState();
            });

            // Handle checkall di modal sinkronisasi
            $('#checkall-sync').on('change', function() {
                const isChecked = $(this).prop('checked');
                const totalCheckboxes = $('.cb-sync').length;

                if (isChecked && totalCheckboxes > MAX_CHECKBOX_SELECTION) {
                    $(this).prop('checked', false);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Batas Pemilihan',
                        text: 'Maksimal 100 data yang dapat dipilih untuk sinkronisasi sekaligus.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                $('.cb-sync').prop('checked', isChecked);
            });

            // Update checkall state function
            function updateCheckAllState() {
                const checkedCount = $('.cb-sync:checked').length;
                const totalCheckboxes = $('.cb-sync').length;
                $('#checkall-sync').prop('checked', checkedCount === totalCheckboxes && totalCheckboxes > 0);
            }

            // Refresh DataTables when modal is opened
            $('#modal-pengaturan-dtsen').on('shown.bs.modal', function() {
                if (typeof TableSyncDtsen !== 'undefined') {
                    TableSyncDtsen.ajax.reload(null, false);
                }
            });

            // Clear checkboxes when modal is closed
            $('#modal-pengaturan-dtsen').on('hidden.bs.modal', function() {
                $('.cb-sync').prop('checked', false);
                $('#checkall-sync').prop('checked', false);
            });

            // Validasi sebelum submit form sinkronisasi
            $('#form-pengaturan-dtsen').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                const checkedCount = $('.cb-sync:checked').length;

                if (checkedCount === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Ada Pilihan',
                        text: 'Silakan pilih minimal 1 keluarga untuk disinkronkan.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                if (checkedCount > MAX_CHECKBOX_SELECTION) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Batas Pemilihan',
                        text: 'Maksimal 100 data yang dapat dipilih untuk sinkronisasi sekaligus.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Submit via AJAX to handle DataTables checkboxes properly
                const formData = $(this).serialize();
                const submitBtn = $('#btn-sync-submit');
                const originalText = submitBtn.html();

                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#modal-pengaturan-dtsen').modal('hide');
                            // Refresh main DataTables
                            TableData.draw();
                            // Refresh sync DataTables
                            if (typeof TableSyncDtsen !== 'undefined') {
                                TableSyncDtsen.ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Terjadi kesalahan saat menyinkronkan data.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: errorMsg,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('dtsen/pendataan/datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.sex = $('#sex').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                    }
                },
                columns: [
                    { data: 'ceklist', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'aksi', orderable: false, searchable: false },

                    { data: 'desil_kemensos', name: 'desil_kemensos' },
                    { data: 'kd_peringkat_kesejahteraan_keluarga', name: 'dtsen.kd_peringkat_kesejahteraan_keluarga' },
                    { data: 'kd_hasil_pendataan_keluarga', name: 'dtsen.kd_hasil_pendataan_keluarga' },

                    { data: 'nik_kk', name: 'kk.nik' },
                    { data: 'nama_kk', name: 'kk.nama' },

                    { data: 'jumlah_anggota', orderable: false, searchable: false },

                    { data: 'dusun', name: 'wil_kk.dusun' },
                    { data: 'rw', name: 'wil_kk.rw' },
                    { data: 'rt', name: 'wil_kk.rt' },

                    { data: 'petugas', name: 'dtsen.nama_ppl' },
                    { data: 'updated_at', name: 'dtsen.updated_at' },
                    { data: 'status_lengkap', orderable: false, searchable: false },
                ],
                order: [
                    [5, 'asc']
                ],
                language: {
                    'url': "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}"
                }
            });

            $('#sex, #dusun, #rw, #rt').on('change', function() {
                TableData.draw();
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
            $('#form-new-dtsen').one('submit', function(ev) {
                ev.preventDefault();
                let id_keluarga = $('#id_keluarga').val();
                $('#form-new-dtsen').attr('action', $('#form-new-dtsen').data('action') + '/' + id_keluarga);
                $(this).submit();
            });

            let dtsen_delete_id = null;
            $(document).on('click', '.btn-delete-row', function() {
                dtsen_delete_id = $(this).data('id');
                $('#confirm-delete').data('mode', 'dtsen-single-row');
            });

            $('#confirm-delete').on('hidden.bs.modal', function() {
                dtsen_delete_id = null;
                $(this).removeData('mode');
            });

            $('#confirm-delete').on('click', '#ok-delete', function(e) {
                if ($('#confirm-delete').data('mode') !== 'dtsen-single-row') {
                    return;
                }
                e.preventDefault();
                e.stopImmediatePropagation();

                if (dtsen_delete_id) {
                    $.ajax({
                        url: "{{ ci_route('dtsen/pendataan/delete') }}" + "/" + dtsen_delete_id,
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        }
                    })
                    .done(function(data) {
                        $('#confirm-delete').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        TableData.draw();
                    })
                    .fail(function(xhr) {
                        let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : (xhr.statusText + ": " + xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: msg,
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });

            $('#checkall-sync').on('change', function() {
                $('.cb-sync').prop('checked', $(this).is(':checked'));
            });

            $('#batal_cetak').on('click', function() {
                batal_cetak = true;
            });
            $(document).on('click', 'input[type=checkbox]', function() {
                let checked = [];
                $('input[type=checkbox]:checked').each(function(index, el) {
                    if (el.value != 'on') {
                        checked.push(el.value);

                        let nik = $(el).parentsUntil('tr').parent().find('td:eq(6)').text();
                        $('#modal-cetak-multi-dtsen tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                    }
                });

                $('#cetak_terpilih').prop('disabled', checked.length == 0);
                $('#cetak_terpilih').attr('disabled', checked.length == 0);
            });

            $('#cetak_terpilih').on('click', function(ev_cetak_terpilih) {
                let checked = [];
                $('#modal-cetak-multi-dtsen tbody').empty();

                // Collect selected checkboxes
                $('input[type=checkbox]:checked').each(function(index, el) {
                    if (el.value != 'on') {
                        checked.push(el.value);
                        let nik = $(el).parentsUntil('tr').parent().find('td:eq(3)').text();
                        $('#modal-cetak-multi-dtsen tbody').append('<tr><td>' + nik + '</td><td id="status_' + el.value + '">Menunggu</td></tr>')
                    }
                });

                // If no checkboxes are selected, exit early
                if (checked.length == 0) {
                    return;
                }

                $('#modal-cetak-multi-dtsen').modal();

                function ubah_status_file(list) {
                    list.forEach(function(element) {
                        if (element.status_file == 0) {
                            $('#status_' + element.id).text('Menunggu');
                        } else {
                            $('#status_' + element.id).html('<input type="hidden" name="id[]" value="' + element.id + '">Selesai');
                        }
                    });
                }

                let callback_fail = function(xhr) {
                    console.error("AJAX Failed", xhr);
                };

                let callback_success = function(data) {
                    if (data.message === 'Mengunduh 1 data') {
                        window.open(data.href, '_blank');
                        $('#modal-cetak-multi-dtsen').modal('hide');
                    } else if (data.message === 'Proses Data' && !batal_cetak) {
                        ubah_status_file(data.list);
                        // Continue processing if there's still work to be done
                        process_cetak_terpilih(checked);
                    } else if (!batal_cetak) {
                        ubah_status_file(data.list);
                    }
                };

                // This function ensures that `ajax_save_dtsen` is called recursively only when needed
                function process_cetak_terpilih(checked) {
                    ajax_save_dtsen("{{ ci_route('dtsen/pendataan/cetak2') }}", {
                        id: checked
                    }, callback_success, callback_fail);
                }

                // Start the first process
                batal_cetak = false;
                process_cetak_terpilih(checked);
            });

            $('#modal-impor').on('show.bs.modal', function() {
                $('#impor_info').empty();
                $('#impor_info').load("<?= ci_route('dtsen/pendataan/loadRecentImpor') ?>");
            });
        });
    </script>
@endpush
