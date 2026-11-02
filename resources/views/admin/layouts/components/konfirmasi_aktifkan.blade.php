{{-- Dimuat global di admin.layouts.index, dipicu oleh admin.layouts.components.tombol_aktifkan --}}
<div class="modal fade" id="confirm-aktifkan" tabindex="-1" role="dialog" aria-labelledby="judulKonfirmasiAktifkan"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="judulKonfirmasiAktifkan">
                    <i class="fa fa-exclamation-triangle"></i> Konfirmasi Perubahan Status
                </h4>
            </div>
            <div class="modal-body">
                <div class="callout callout-warning">
                    <h4><i class="fa fa-info-circle"></i> Perhatian!</h4>
                    <p id="pesan-aktifkan"></p>
                </div>

                <p><strong>Apakah Anda yakin ingin melanjutkan?</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal">
                    <i class="fa fa-sign-out"></i> Tutup
                </button>
                <a class="btn-ok">
                    <button type="button" class="btn btn-social btn-info btn-sm" id="ok-aktifkan">
                        <i class="fa fa-check"></i> Ya, Lanjutkan
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            const modal = $('#confirm-aktifkan');
            const pesanDefault = 'Status data ini akan diubah.';

            const notifikasi = (success, message) => {
                Swal.fire({
                    icon: success ? 'success' : 'error',
                    title: success ? 'Berhasil' : 'Error',
                    text: message,
                    timer: 2000,
                });
            };

            // Method request mengikuti atribut data-method pada tombol pemicu (default GET).
            // Selain GET dan POST dikirim sebagai POST + field _method (method spoofing).
            // data-ajax="true" untuk endpoint yang mengembalikan JSON (tabel dimuat ulang tanpa reload halaman).
            modal.on('show.bs.modal', function(e) {
                const trigger = $(e.relatedTarget);

                modal.data('method', String(trigger.data('method') || 'GET').toUpperCase());
                modal.data('ajax', trigger.data('ajax') === true);
                modal.data('href', trigger.data('href'));
                modal.find('.btn-ok').attr('href', trigger.data('href'));
                modal.find('#pesan-aktifkan').html(trigger.data('body') || pesanDefault);
                modal.find('#ok-aktifkan').prop('disabled', false);
            });

            modal.on('click', '.btn-ok', function(e) {
                if (modal.data('method') === 'GET' && !modal.data('ajax')) {
                    return;
                }

                e.preventDefault();

                if (modal.find('#ok-aktifkan').prop('disabled')) {
                    return;
                }

                modal.find('#ok-aktifkan').prop('disabled', true);

                if (!modal.data('ajax')) {
                    submitPostRequest(modal.data('href'), modal.data('method'));

                    return;
                }

                $.ajax({
                    url: modal.data('href'),
                    method: modal.data('method') === 'GET' ? 'GET' : 'POST',
                    data: ['GET', 'POST'].includes(modal.data('method')) ? {} : { _method: modal.data('method') },
                    dataType: 'json',
                    success: function(response) {
                        modal.modal('hide');
                        notifikasi(response.success, response.message);

                        if (response.success) {
                            $('#tabeldata').DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        modal.modal('hide');
                        notifikasi(false, xhr.responseJSON?.message || 'Gagal mengubah status data');
                    },
                });
            });
        })();
    </script>
@endpush
