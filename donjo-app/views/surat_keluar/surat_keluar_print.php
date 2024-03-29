<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Agenda Surat Keluar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="<?= asset('css/report.css') ?>" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
	</head>
	<body>
		<div id="container">
			<!-- Print Body -->
			<div id="body">
				<div class="header" align="center">
					<label align="left"><?= get_identitas()?></label>
					<h3>
						<span>AGENDA SURAT KELUAR</span>
						<?php if (! empty($_SESSION['filter'])): ?>
							TAHUN <?= $_SESSION['filter']; ?>
						<?php endif; ?>
					</h3>
					<br>
				</div>
				<table class="border thick">
					<thead>
						<tr class="border thick">
							<th>Nomor Urut</th>
							<th>Nomor Surat</th>
							<th>Tanggal Surat</th>
							<th>Ditujukan Kepada</th>
							<th>Isi Singkat</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($main as $data): ?>
						<tr>
							<td><?= $data['nomor_urut']?></td>
							<td><?= $data['nomor_surat']?></td>
							<td><?= tgl_indo($data['tanggal_surat'])?></td>
							<td><?= $data['tujuan']?></td>
							<td><?= $data['isi_singkat']?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<table>
					<col span="5" style="width: 8%">
					<col style="width: 28%">
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr class="text-center">
						<td colspan="1">&nbsp;</td>
						<td colspan="2">MENGETAHUI</td>
						<td colspan="2">&nbsp;</td>
						<td><?= strtoupper($desa['nama_desa'] . ', ' . tgl_indo(date('Y m d'))) ?></td>
					</tr>
					<tr class="text-center">
						<td colspan="1">&nbsp;</td>
						<td colspan="2"><?= strtoupper($pamong_ketahui['jabatan'] . ' ' . $desa['nama_desa']) ?></td>
						<td colspan="2">&nbsp;</td>
						<td><?= strtoupper($pamong_ttd['jabatan'] . ' ' . $desa['nama_desa']) ?></td>
					</tr>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr><td colspan="6">&nbsp;</td>
					<tr class="text-center">
						<td colspan="1">&nbsp;</td>
						<td colspan="2"><u><?= strtoupper($pamong_ketahui['nama']) ?></u></td>
						<td colspan="2">&nbsp;</td>
						<td><u><?= strtoupper($pamong_ttd['nama']) ?></u></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>
