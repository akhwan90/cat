
<html>
	<head>
		<title>Cetak Nilai</title>
		<style type="text/css">
			
			body {
				font-family: Arial;
				width: 8.5in;
			}

			.text-center {
				text-align: center;
			}
			.table-bordered {
				width: 100%;
				border: solid 1px #000;
				border-collapse: collapse;
			}

			.table-bordered tr td, .table-bordered tr th {
				border: solid 1px #000;
			}

			.table-bordered tr th {
				padding: 10px 0px;
			}
			.table-bordered tr td {
				padding: 5px;
				font-size: 10pt;
			}


			.table-no-bordered {
				width: 100%;
				border: none;
			}

			.table-no-bordered tr td, .table-no-bordered tr th {
				border: none;
			}

			.table-no-bordered tr th {
				padding: 10px 0px;
			}
			.table-no-bordered tr td {
				padding: 0px;
				font-size: 10pt;
			}

			.critical {
				background: #eee;
			}
		</style>
	</head>

	<body>

		<p style="text-align: center; font-weight: bold">
			Laporan Hasil Test<br>
			PT. CIPTA KRIDATAMA
		</p>

		<table class="table table-no-bordered">
			<tr>
				<td width="15%">Nomor</td>
				<td width="1%">:</td>
				<td width="20%"><?=$peserta['nomor'];?></td>
				<td width="28%">&nbsp;</td>
				<td width="15%">Test Level</td>
				<td width="1%">:</td>
				<td width="20%"><?=$sistem_seleksi[$peserta['jenis_tes']]['sub'][$peserta['jenis_staff']]['nama'];?></td>
			</tr>
			<tr>
				<td width="15%">Nama</td>
				<td width="1%">:</td>
				<td width="20%"><?=$peserta['nama'];?></td>
				<td width="28%">&nbsp;</td>
				<td width="15%">Nama Jabatan</td>
				<td width="1%">:</td>
				<td width="20%"><?=$sistem_seleksi[$peserta['jenis_tes']]['sub'][$peserta['jenis_staff']]['level'][$peserta['level_test']]['nama'];?></td>
			</tr>
			<tr>
				<td width="15%">TTL</td>
				<td width="1%">:</td>
				<td width="20%"><?=$peserta['tmp_lahir'].", ".tjs($peserta['tgl_lahir']);?></td>
				<td width="28%">&nbsp;</td>
				<td width="15%">Grade Level</td>
				<td width="1%">:</td>
				<td width="20%"><?=$peserta['nomor'];?></td>
			</tr>
			<tr>
				<td width="15%">Usia</td>
				<td width="1%">:</td>
				<td width="20%"><?=hitung_umur($peserta['tgl_lahir']);?></td>
				<td width="28%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="1%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
			</tr>
		</table>

		<br>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="5%" class="text-center">No</th>
					<th width="10%" class="text-center">Aspek Psikologi</th>
					<th width="25%" class="text-center">Definisi</th>
					<th width="5%" class="text-center">1</th>
					<th width="5%" class="text-center">2</th>
					<th width="5%" class="text-center">3</th>
					<th width="5%" class="text-center">4</th>
					<th width="5%" class="text-center">5</th>
					<th width="35%" class="text-center">Keterangan</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$no = 1;
				$jml_nilai = 0;
				$jml_nilai_konversi = 0;
				$jml_nilai_konversi_keputusan = 0;

				foreach ($jenis_peserta['critical'] as $cv) {

					$nilai_konversi = empty($list_hasil_a_b[$cv]['nilai_konversi']) ? 0 : $list_hasil_a_b[$cv]['nilai_konversi'];
					$nilai_konversi_keputusan = $konversi_keputusan_non_staff['critical'][$nilai_konversi];
					$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;
					
					$satu_x = "";
					$dua_x = "";
					$tiga_x = "";
					$empat_x = "";
					$lima_x = "";

					if ($nilai_konversi == 1) {
						$satu_x = "X";
					} else if ($nilai_konversi == 2) {
						$dua_x = "X";
					} else if ($nilai_konversi == 3) {
						$tiga_x = "X";
					} else if ($nilai_konversi == 4) {
						$empat_x = "X";
					} else if ($nilai_konversi == 5) {
						$lima_x = "X";
					}  

					$keterangan = $list_hasil_a_b[$cv]['detil_aspek']['nilai_to_deskripsi'][$nilai_konversi];
					echo '<tr class="critical">
							<td>'.$no.'</td>
							<td>'.$list_hasil_a_b[$cv]['detil_aspek']['nama_indo'].'</td>
							<td>'.$list_hasil_a_b[$cv]['detil_aspek']['nama_definisi'].'</td>
							<td class="text-center">'.$satu_x.'</td>
							<td class="text-center">'.$dua_x.'</td>
							<td class="text-center">'.$tiga_x.'</td>
							<td class="text-center">'.$empat_x.'</td>
							<td class="text-center">'.$lima_x.'</td>
							<td>'.$keterangan.'</td>
							</tr>';
						
					$no++;
				}

				foreach ($jenis_peserta['umum'] as $cv) {
					$nilai_konversi = $list_hasil_a_b[$cv]['nilai_konversi'];
					$nilai_konversi_keputusan = $konversi_keputusan_non_staff['umum'][$nilai_konversi];
					$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;
					
					$satu_x = "";
					$dua_x = "";
					$tiga_x = "";
					$empat_x = "";
					$lima_x = "";

					if ($nilai_konversi == 1) {
						$satu_x = "X";
					} else if ($nilai_konversi == 2) {
						$dua_x = "X";
					} else if ($nilai_konversi == 3) {
						$tiga_x = "X";
					} else if ($nilai_konversi == 4) {
						$empat_x = "X";
					} else if ($nilai_konversi == 5) {
						$lima_x = "X";
					}  

					$keterangan = $list_hasil_a_b[$cv]['detil_aspek']['nilai_to_deskripsi'][$nilai_konversi];
					echo '<tr>
							<td>'.$no.'</td>
							<td>'.$list_hasil_a_b[$cv]['detil_aspek']['nama_indo'].'</td>
							<td>'.$list_hasil_a_b[$cv]['detil_aspek']['nama_definisi'].'</td>
							<td class="text-center">'.$satu_x.'</td>
							<td class="text-center">'.$dua_x.'</td>
							<td class="text-center">'.$tiga_x.'</td>
							<td class="text-center">'.$empat_x.'</td>
							<td class="text-center">'.$lima_x.'</td>
							<td>'.$keterangan.'</td>
							</tr>';
						
					$no++;
				}
				?>
			</tbody>
		</table>

		<p>
			<?php 
			$keputusan = konversi_rekomendasi_non_staff($jml_nilai_konversi_keputusan);
			
			if ($keputusan) {
				echo '<div style="clear: both;"><div style="background: green; width: 30px; height: 21px; text-align: center; padding: 8px; font-weight: bold; color: #fff; display: inline; float: left; margin-right: 10px; border: solid 1px #000">V</div><div style="float: left; display: inline; padding-top: 10px">Direkomendasikan</div></div>';

				echo '<br><br><br><div style="clear: both;"><div style="background: none; width: 28px; height: 21px; text-align: center; padding: 8px; font-weight: bold; color: #fff; display: inline; float: left; margin-right: 10px; border: solid 1px #000">&nbsp;</div><div style="float: left; display: inline; padding-top: 10px">Tidak Direkomendasikan</div></div>';
			} else {

				echo '<br><br><div style="clear: both;"><div style="background: none; width: 30px; height: 21px; text-align: center; padding: 8px; font-weight: bold; color: #fff; display: inline; float: left; margin-right: 10px; border: solid 1px #000">&nbsp;</div><div style="float: left; display: inline; padding-top: 10px">Direkomendasikan</div></div>';

				echo '<div style="clear: both"><div style="background: red; width: 30px; height: 21px; text-align: center; padding: 8px; font-weight: bold; color: #fff; display: inline; float: left; margin-right: 10px">V</div><div style="float: left; display: inline; padding-top: 10px">Tidak Direkomendasikan</div></div>';
			}
			?>
		</p>
		<p>&nbsp;</p>
		<table class="table-bordered" style="margin-top: 20px">
		<?php 
		if (!empty($list_hasil_c)) {
			foreach ($list_hasil_c as $vck => $vcv) {
				echo '<tr>
						<td width="30%">'.$vcv['detil_aspek']['nama'].'</td>
						<td width="30%"><div style="width: '.(($vcv['nilai']/10)*100).'%; height: 35px; vertical-align: middle; background: green; color: #fff; font-weight: bold; text-align: right; padding-top: 20px; ">'.$vcv['nilai'].'&nbsp;&nbsp;</div></td>
						<td width="40%">'.$vcv['detil_aspek']['nilai_to_deskripsi'][$vcv['nilai_konversi']].'</td>
					</tr>';
			}
		}
		?>
		</table>
	</body>
</html>