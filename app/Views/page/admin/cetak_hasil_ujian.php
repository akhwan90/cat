
<html>
	<head>
		<title>Cetak Nilai</title>
		<style type="text/css">
			@font-face {
				font-family: 'Verdana';
				font-style: normal;
				font-weight: normal;
				src: url(<?=base_url('/public/aset/font/verdana.ttf');?>) format('truetype');
			}

			body {
				font-family: Verdana;
				/*width: 8.5in;*/
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
				font-family: Verdana;
				font-size: 9pt;
			}

			.table-bordered tr th {
				padding: 10px 0px;
			}
			.table-bordered tr td {
				padding: 5px;
			}


			.table-no-bordered {
				width: 100%;
				border: none;
			}

			.table-no-bordered tr td, .table-no-bordered tr th {
				border: none;
				font-family: Verdana;
				font-size: 9pt;
			}

			.table-no-bordered tr th {
				padding: 10px 0px;
			}
			.table-no-bordered tr td {
				padding: 0px;
			}

			.critical {
				background: #eee;
			}
			.row {
				display: table;
			    width: 100%; /*Optional*/
			    table-layout: fixed; /*Optional*/
			    border-spacing: 10px; /*Optional*/
			}
			.col {
				display: table-cell;
			}
		</style>
	</head>

	<body>
		<div style="height: 10.2in; background: url('<?=$bg_base64;?>'); background-repeat: no-repeat; background-size: 97% 97%; display: block; width: 100%; ">
			<!-- <div style="float:left; ">Yayay</div> -->
				<!-- <p>Sdr. <?=$peserta['nama'];?></p>
				<p>T T L <?=$peserta['tmp_lahir'].", ".tjs($peserta['tgl_lahir']);?></p>
				<p>Pendidikan <?=$peserta['pendidikan'];?></p>
				<p>Jenis Kelamin <?=$peserta['jenis_kelamin'];?></p>
				<p>Posisi <?=$peserta['posisi_saat_ini'];?></p> -->
				<!-- <br><br><br><br>
				<br><br><br><br>
				<br><br><br><br> -->
				<!-- <div style="border: solid 1px; width: 6cm">Sdr. <?=$peserta['nama'];?></div>
				-->
				<br><br><br><br>
				<br><br><br><br>
				<br><br><br><br>
				<br><br><br><br>
				<br><br><br><br>
				<br><br>
				<table class="table-bordered" style="width: 14cm; float: left; margin-left: 4cm">
					<tr><td colspan="2">Sdr. <?=$peserta['nama'];?></td></tr>
					<tr><td width="30%">T T L</td><td width="70%"><?=$peserta['tmp_lahir'].", ".tjs($peserta['tgl_lahir']);?></td></tr>
					<tr><td>Pendidikan</td><td><?=$peserta['pendidikan'];?></td></tr>
					<tr><td>Jenis Kelamin</td><td><?=$peserta['jenis_kelamin'];?></td></tr>
					<tr><td>Posisi</td><td><?=$peserta['posisi_saat_ini'];?></td></tr>
					<tr><td>Tgl Tes</td><td><?=tjs($detil_tes['last_activity']);?></td></tr>
				</table>
				<br><br>
		</div>

		<div style="float: left; display: inline"><img src="<?=base_url('public/aset/ck_white.png');?>" style="width: 75px"></div>
		
		<p style="text-align: center; font-weight: bold">
			Laporan Hasil Test<br>
			PT. CIPTA KRIDATAMA
		</p>

		<table class="table table-no-bordered">
			<tr>
				<td width="15%">Nama</td>
				<td width="1%">:</td>
				<td width="30%"><?=$peserta['nama'];?></td>
				<td width="18%">&nbsp;</td>
				<td width="15%">Test Level</td>
				<td width="1%">:</td>
				<td width="20%"><?=$sistem_seleksi[$peserta['jenis_tes']]['sub'][$peserta['jenis_staff']]['nama'];?></td>
			</tr>
			<tr>
				<td width="15%">Tanggal Lahir</td>
				<td width="1%">:</td>
				<td width="30%"><?=tjs($peserta['tgl_lahir']);?></td>
				<td width="18%">&nbsp;</td>
				<td width="15%">Nama Posisi</td>
				<td width="1%">:</td>
				<td width="20%"><?=$sistem_seleksi[$peserta['jenis_tes']]['sub'][$peserta['jenis_staff']]['level'][$peserta['level_test']]['nama'];?></td>
			</tr>
			<tr>
				<td width="15%">Usia</td>
				<td width="1%">:</td>
				<td width="30%"><?=hitung_umur($peserta['tgl_lahir']);?></td>
				<td width="18%">&nbsp;</td>
				<td width="15%">Tanggal Tes</td>
				<td width="1%">:</td>
				<td width="20%"><?=tjs($detil_tes['last_activity']);?></td>
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
					$nilai_konversi_keputusan = empty($konversi_keputusan_non_staff['critical'][$nilai_konversi]) ? 0 : $konversi_keputusan_non_staff['critical'][$nilai_konversi];
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

					$keterangan = 'Tidak mendapatkan nilai. Nilai: '.$nilai_konversi;
					if (!empty($list_hasil_a_b[$cv]['detil_aspek']['nilai_to_deskripsi'][$nilai_konversi])) {
						$keterangan = $list_hasil_a_b[$cv]['detil_aspek']['nilai_to_deskripsi'][$nilai_konversi];
					}				
				

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
			<table class="table-bordered">
				<tr><td colspan="2">Keterangan: </td></tr>
				<tr><td width="10%" class="text-center">5</td><td>Sangat Baik</td></tr>
				<tr><td class="text-center">4</td><td>Baik</td></tr>
				<tr><td class="text-center">3</td><td>Cukup</td></tr>
				<tr><td class="text-center">2</td><td>Kurang</td></tr>
				<tr><td class="text-center">1</td><td>Kurang Sekali</td></tr>
			</table>
		</p>

		<p>
			<table class="table-bordered">
				<tr><td colspan="2">KEPUTUSAN</td></tr>
				<?php 
				if (!empty($get_hasil_rekomendasi)) {
					if ($get_hasil_rekomendasi['notes'] == 'Direkomendasikan') {
				?>
				?>
				<tr><td width="15%" class="text-center" style="background: green">V</td><td width="85%">Direkomendasikan</td></tr>
				<tr><td></td><td>Tidak Direkomendasikan</td></tr>
			    <?php } else if ($get_hasil_rekomendasi['notes'] == 'Tidak Direkomendasikan') { ?>
				<tr><td width="15%"></td><td width="85%">Direkomendasikan</td></tr>
				<tr><td class="text-center" style="background: red">V</td><td>Tidak Direkomendasikan</td></tr>
			  <?php 
					} else {
				?>
				<tr><td colspan="2">-</td></tr>
				<?php 
					}
				} else {
				?>
				<tr><td colspan="2">-</td></tr>
				<?php
				}
				?>
			</table>
		</p>

		<p>&nbsp;</p>
		<table class="table-bordered" style="margin-top: 20px">
		<?php 
		if (!empty($list_hasil_c)) {
			foreach ($list_hasil_c as $vck => $vcv) {
				$level_color = 'red';
				if ($vcv['nilai'] >= 5) {
					$level_color = 'green';
				}
				echo '<tr>
						<td width="20%">'.$vcv['detil_aspek']['nama'].'</td>
						<td width="25%"><div style="width: '.(($vcv['nilai']/10)*100).'%; height: 35px; vertical-align: middle; background: '.$level_color.'; color: #fff; font-weight: bold; text-align: right; padding-top: 20px; ">'.$vcv['nilai'].'&nbsp;&nbsp;</div></td>
						<td width="55%">'.$vcv['detil_aspek']['nilai_to_deskripsi'][$vcv['nilai_konversi']].'</td>
					</tr>';
			}
		}
		?>
		</table>
	</body>
</html>