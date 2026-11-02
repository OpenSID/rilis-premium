{{-- Tabel rincian statistik (dipakai tampilan Modern & Klasik); isinya diisi script di statistik/index --}}
<table class="table table-striped" id="table-statistik">
	<thead>
		<tr>
			<th rowspan="2">Kode</th>
			<th rowspan="2" style='text-align:left;'>Kelompok</th>
			<th colspan="2">Jumlah</th>
			@unless ($lvTanpaJk)
				<th colspan="2">Laki-laki</th>
				<th colspan="2">Perempuan</th>
			@endunless
		</tr>
		<tr>
			<th style='text-align:right'>n</th>
			<th style='text-align:right'>%</th>
			@unless ($lvTanpaJk)
				<th style='text-align:right'>n</th>
				<th style='text-align:right'>%</th>
				<th style='text-align:right'>n</th>
				<th style='text-align:right'>%</th>
			@endunless
		</tr>
	</thead>
	<tbody>
		<tr class="memuat-statistik">
			<td colspan="{{ $lvTanpaJk ? 4 : 8 }}" style="text-align:center;">Memuat data...</td>
		</tr>
	</tbody>
</table>
