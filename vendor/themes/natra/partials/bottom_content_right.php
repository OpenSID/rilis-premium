<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if (theme_config('jam', true)): ?>
<div id="jam"></div>
<?php endif ?>

<?php if (theme_config('pintasan_masuk', true)): ?>
<div class="single_bottom_rightbar">
	<h2><i class="fa fa-lock"></i>&ensp;MASUK</h2>
	<div class="tab-pane fade in active">
		<a href="<?= site_url('siteman') ?>" class="btn btn-primary btn-block" rel="noopener noreferrer" target="_blank">ADMIN</a>
		<?php if ((bool) $this->setting->layanan_mandiri) : ?>
		<a href="<?= site_url('layanan-mandiri') ?>" class="btn btn-success btn-block" rel="noopener noreferrer" target="_blank">LAYANAN MANDIRI</a>
		<?php endif ?>
	</div>
</div>
<?php endif ?>

<!-- Tampilkan Widget -->
<?php if ($w_cos): ?>
	<?php foreach ($w_cos as $widget): ?>
		<?php
			$judul_widget = [
				'judul_widget' => str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget['judul']))
			];
		?>
		<?php if ($widget["jenis_widget"] == 1): ?>
			<?php $this->load->view("{$folder_themes}/widgets/{$widget['isi']}", $judul_widget) ?>
		<?php elseif($widget['jenis_widget'] == 2) : ?>
			<div class="single_bottom_rightbar">
				<?php $this->load->view("../../{$widget['isi']}", $judul_widget) ?>
			</div>
		<?php else : ?>
			<div class="single_bottom_rightbar">
				<h2><i class="fa fa-folder"></i>&ensp;<?= $judul_widget['judul_widget'] ?></h2>
				<div class="box-body">
					<div class="embed-responsive embed-responsive-16by9">
						<?= htmlspecialchars_decode(html_entity_decode($widget['isi']), ENT_QUOTES) ?>
					</div>
				</div>
			</div>
		<?php endif ?>
	<?php endforeach ?>
<?php endif ?>