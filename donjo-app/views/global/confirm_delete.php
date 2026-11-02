<?php if (can('h')): ?>
	<div class='modal fade' id='confirm-delete' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
				</div>
				<div class='modal-body btn-info'>
					<?= $pesan_hapus ?? 'Apakah Anda yakin ingin menghapus data ini?' ?>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
						<a class='btn-ok'>
							<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" id="ok-delete"><i class="<?= $icon_hapus ?? 'fa fa-trash-o' ?>"></i> <?= $tombol_hapus ?? 'Hapus' ?></button>
						</a>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Method request mengikuti atribut data-method pada tombol pemicu (tanpa data-method tetap GET).
		// Selain GET dikirim sebagai form POST + token CSRF; PUT/PATCH/DELETE lewat field _method.
		$(function() {
			var modal = $('#confirm-delete');

			modal.on('show.bs.modal', function(e) {
				var trigger = $(e.relatedTarget);

				modal.data('method', String(trigger.data('method') || 'GET').toUpperCase());
				modal.data('href', trigger.data('href'));
			});

			modal.on('click', '.btn-ok', function(e) {
				var method = modal.data('method');

				if (method === 'GET') {
					return;
				}

				e.preventDefault();

				var form = $('<form method="POST" style="display:none"></form>').attr('action', modal.data('href'));

				if (method !== 'POST') {
					form.append($('<input type="hidden" name="_method">').val(method));
				}

				form.append($('<input type="hidden">').attr('name', '<?= $this->security->get_csrf_token_name() ?>').val('<?= $this->security->get_csrf_hash() ?>'));
				$('body').append(form);
				form.submit();
			});
		});
	</script>
<?php endif; ?>
