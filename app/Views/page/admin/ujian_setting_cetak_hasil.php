<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Hasil Ujian</title>

	<style type="text/css">
		body {
			font-family: Arial;
		}
		table, .table-bordered, .table-striped {
			border: solid 1px #000;
			border-collapse: collapse;
			width: 100%;
		}
		table tr td, table tr th, .table-bordered tr td, .table-bordered tr th {
			border: solid 1px #000;
			padding: 2px 5px;
		}
		.table-striped tbody tr:nth-of-type(odd) {
		    background-color: rgba(0, 0, 0, 0.15);
		}
		.text-center {
			text-align: center;
		}
		.text-left {
			text-align: left;
		}
		.table-striped tr th {
			padding: 10px;
		}
	</style>
</head>
<body>

	<h3>Detil Ujian</h3>
	<table class="table table-bordered table-sm">
		<tr><th width="30%" class="text-left">Nama Ujian</th><td width="70%"><?=$detil_ujian['nama_ujian'];?></td></tr>
		<tr><th class="text-left">Tgl Mulai Ujian</th><td><?=$detil_ujian['tgl_mulai'];?></td></tr>
		<tr><th class="text-left">Tgl Selesai Ujian</th><td><?=$detil_ujian['terlambat'];?></td></tr>
		<tr><th class="text-left">Jenis</th><td><?=$detil_ujian['jenis'];?></td></tr>
		<tr><th class="text-left">Waktu</th><td><?=$detil_ujian['waktu'];?></td></tr>
		<tr><th class="text-left">Jumlah Soal</th><td><?=$detil_ujian['jumlah_soal'];?></td></tr>
		<tr><th class="text-left">Nama Guru</th><td><?=$detil_ujian['nm_guru'];?></td></tr>
		<tr><th class="text-left">Mapel</th><td><?=$detil_ujian['nm_mapel'];?></td></tr>
	</table>

	<h3 class="mt-4">Daftar Peserta</h3>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="5%">No</th>
				<th width="20%">Nama</th>
				<th width="15%">Jam Mulai</th>
				<th width="15%">Jam Selesai</th>
				<th width="15%">Status</th>
				<th width="15%">Jumlah Benar</th>
				<th width="15%">Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$no = 1;
			if (!empty($detil_pesertas)) {
				foreach ($detil_pesertas as $detil_peserta) {
					// $status = ($soal['jawaban'] == $soal['kunci']) ? '<i class="fa fa-check text-success"></i> ' : '<i class="fa fa-minus-circle text-danger"></i> ';
					$status = '';
					if ($detil_peserta['status'] == 'N') {
						$status = 'Belum mengerjakan';
					} else if ($detil_peserta['status'] == 'D') {
						$status = 'Sedang dikerjakan';
					} else if ($detil_peserta['status'] == 'Y') {
						$status = 'Sudah selesai';
					} 

					echo '
					<tr>
					<td class="text-center">'.$no.'</td>
					<td>'.$detil_peserta['nama'].'</td>
					<td>'.$detil_peserta['tgl_mulai'].'</td>
					<td>'.$detil_peserta['tgl_selesai'].'</td>
					<td class="text-center">'.$status.'</td>
					<td class="text-center">'.$detil_peserta['jml_benar'].'</td>
					<td class="text-center">'.$detil_peserta['nilai'].'</td>
					</tr>';

					$no++;
				}
			}
			?>
		</tbody>
	</table>
	
</body>
</html>
