
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
					<tr><td colspan="2">Sdr./i <?=$peserta['nama'];?></td></tr>
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
			Laporan Assessment Individu<br>
			PT. CIPTA KRIDATAMA
		</p>

		<?php 
		$foto = '';
		if (is_file('./public/foto_peserta/'.$peserta['foto'])) {
			$foto = '<img src="'.base_url('public/foto_peserta/'.$peserta['foto']).'" style="width: 100%">';
		}
		?>

		<table class="table table-no-bordered">
			<tr>
				<!--<td width="15%" rowspan="6"><?=$foto;?></td>
				<td width="5%">&nbsp;</td>-->
				<td width="15%">Nama</td>
				<td width="1%">:</td>
				<td width="30%"><?=$peserta['nama'];?></td>
				<td width="34%">&nbsp;</td>
			</tr>
			<tr>
				<!--<td>&nbsp;</td>
				<td>Tanggal Lahir</td>
				<td>:</td>
				<td><?=tjs($peserta['tgl_lahir']);?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<!--<td>&nbsp;</td>-->
				<td>Usia</td>
				<td>:</td>
				<td><?=hitung_umur($peserta['tgl_lahir']);?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<!--<td>&nbsp;</td>-->
				<td>SN</td>
				<td>:</td>
				<td><?=$peserta['nomor'];?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<!--<td>&nbsp;</td>-->
				<td>Posisi</td>
				<td>:</td>
				<td><?=$peserta['posisi_saat_ini'];?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<!--<td>&nbsp;</td>-->
				<td>Tanggal Tes</td>
				<td>:</td>
				<td><?=tjs($detil_tes['last_activity']);?></td>
				<td>&nbsp;</td>
			</tr>
		</table>

		<br>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="5%" class="text-center">No</th>
					<th width="25%" class="text-center">Nama Kompetensi</th>
					<!--<th width="10%" class="text-center">Level</th>-->
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
				$jml_nilai_konversi_keputusan = 0;
				$kekurangan = [];
				$kelebihan = [];

				// echo json_encode($syarat_kompetensi['critical']);
				// exit;

				foreach ($syarat_kompetensi['critical'] as $cv) {
					$nilai_konversi = $nilai_kompetensi[$cv]['nilai_pembulatan'];
					$nilai_konversi_keputusan = $nilai_kompetensi[$cv]['nilai_konversi_keputusan'];
					$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;

					$nama_aspek = $kompetensi_assesment[$cv]['name'];
					$deskripsi_aspek = $kompetensi_assesment[$cv]['deskripsi_aspek'];
					$nilai_to_deskripsi = $kompetensi_assesment[$cv]['nilai_to_deskripsi'][$nilai_konversi];
					
					$satu_x = "";
					$dua_x = "";
					$tiga_x = "";
					$empat_x = "";
					$lima_x = "";
					$background = "<td>-</td>";

					if ($nilai_konversi == 1) {
						$satu_x = "X";
						$background = '<td style="background: #fe0000" class="text-center">KS</td>';
					} else if ($nilai_konversi == 2) {
						$dua_x = "X";
						$background = '<td style="background: #ffff00" class="text-center">K</td>';
					} else if ($nilai_konversi == 3) {
						$tiga_x = "X";
						$background = '<td style="background: #a6a6a6" class="text-center">S</td>';
					} else if ($nilai_konversi == 4) {
						$empat_x = "X";
						$background = '<td style="background: #92d14f" class="text-center">B</td>';
					} else if ($nilai_konversi == 5) {
						$lima_x = "X";
						$background = '<td style="background: #00af50" class="text-center">BS</td>';
					}  

					// cek kelebihan atau kekurangan
					if ($nilai_konversi > 3) {
						$kelebihan[$cv] = $nilai_konversi;
					}

					if ($nilai_konversi < 3) {
						$kekurangan[$cv] = $nilai_konversi;
					}

					echo '<tr class="critical">
							<td class="text-center">'.$no.'</td>
							<td>'.$nama_aspek.'</td>
							<!--'.$background.'-->
							<td class="text-center">'.$satu_x.'</td>
							<td class="text-center">'.$dua_x.'</td>
							<td class="text-center">'.$tiga_x.'</td>
							<td class="text-center">'.$empat_x.'</td>
							<td class="text-center">'.$lima_x.'</td>
							<td>'.$nilai_to_deskripsi.'</td>
							</tr>';
						
					$no++;
				}

				foreach ($syarat_kompetensi['umum'] as $cv) {
					$nilai_konversi = $nilai_kompetensi[$cv]['nilai_pembulatan'];
					$nilai_konversi_keputusan = $nilai_kompetensi[$cv]['nilai_konversi_keputusan'];
					$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;

					$nama_aspek = $kompetensi_assesment[$cv]['name'];
					$deskripsi_aspek = $kompetensi_assesment[$cv]['deskripsi_aspek'];
					
					$nilai_to_deskripsi = 'Tidak mendapatkan nilai. Nilai: '.$nilai_konversi; //$nilai_konversi;
					if (!empty($kompetensi_assesment[$cv]['nilai_to_deskripsi'][$nilai_konversi])) {
						$nilai_to_deskripsi = $kompetensi_assesment[$cv]['nilai_to_deskripsi'][$nilai_konversi];
					}

					$satu_x = "";
					$dua_x = "";
					$tiga_x = "";
					$empat_x = "";
					$lima_x = "";
					$background = "<td>-</td>";

					if ($nilai_konversi == 1) {
						$satu_x = "X";
						$background = '<td style="background: #fe0000" class="text-center">KS</td>';
					} else if ($nilai_konversi == 2) {
						$dua_x = "X";
						$background = '<td style="background: #ffff00" class="text-center">K</td>';
					} else if ($nilai_konversi == 3) {
						$tiga_x = "X";
						$background = '<td style="background: #a6a6a6" class="text-center">S</td>';
					} else if ($nilai_konversi == 4) {
						$empat_x = "X";
						$background = '<td style="background: #92d14f" class="text-center">B</td>';
					} else if ($nilai_konversi == 5) {
						$lima_x = "X";
						$background = '<td style="background: #00af50" class="text-center">BS</td>';
					} 

					// cek kelebihan atau kekurangan
					if ($nilai_konversi > 3) {
						$kelebihan[$cv] = $nilai_konversi;
					}

					if ($nilai_konversi < 3) {
						$kekurangan[$cv] = $nilai_konversi;
					}
					
					echo '<tr class="">
							<td class="text-center">'.$no.'</td>
							<td>'.$nama_aspek.'</td>
							<!--'.$background.'-->
							<td class="text-center">'.$satu_x.'</td>
							<td class="text-center">'.$dua_x.'</td>
							<td class="text-center">'.$tiga_x.'</td>
							<td class="text-center">'.$empat_x.'</td>
							<td class="text-center">'.$lima_x.'</td>
							<td>'.$nilai_to_deskripsi.'</td>
							</tr>';
						
					$no++;
				}
				?>
			</tbody>
		</table>
		<p>
			<table class="table-bordered">
				<tr><td colspan="2">Keterangan: </td></tr>
				<tr><td width="10%">SB</td><td>Sangat Baik</td></tr>
				<tr><td>B</td><td>Baik</td></tr>
				<tr><td>C</td><td>Cukup</td></tr>
				<tr><td>K</td><td>Kurang</td></tr>
				<tr><td>KS</td><td>Kurang Sekali</td></tr>
			</table>
		</p>
		<p>
			<table class="table-bordered">
				<thead>
					<tr>
						<th colspan="3">Kekuatan</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (!empty($kelebihan)) {
						$no = 1;
						foreach ($kelebihan as $lebih_k => $lebih_v) {
							echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$kompetensi_assesment[$lebih_k]['name'].'</td>
									<td>'.$kompetensi_assesment[$lebih_k]['nilai_to_deskripsi'][$lebih_v].'</td>
								</tr>';

							$no++;
						}
					} else {
						echo '<tr><td colspan="3">Tidak Ada Kompetensi yang Berada Di Atas Skor 3</td></tr>';
					}
					?>
				</tbody>
			</table>
		</p>
		<p>
			<table class="table-bordered">
				<thead>
					<tr>
						<th colspan="2">Area Pengembangan</th>
						<th>Saran Pengembangan</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if (!empty($kekurangan)) {
						$no = 1;
						foreach ($kekurangan as $kurang_k => $kurang_v) {
							echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$kompetensi_assesment[$kurang_k]['name'].'</td>
									<td>'.$kompetensi_assesment[$kurang_k]['metode_pengembangan'].'</td>
								</tr>';

							$no++;
						}
					} else {
						echo '<tr><td colspan="3">Tidak Ada Kompetensi yang Berada Di Bawah Skor 3</td></tr>';
					}
					?>
				</tbody>
			</table>
		</p>
		<p>
			<table class="table-bordered">
				<!--<tr><td colspan="2">KEPUTUSAN (nilai konversi keputusan : <?=$jml_nilai_konversi_keputusan;?>)</td></tr>-->
				<?php 
				if (!empty($get_hasil_rekomendasi)) {
					if ($get_hasil_rekomendasi['notes'] == 'Ready') {
				?>
				<tr><td width="15%" class="text-center" style="background: green">V</td><td width="85%">Ready</td></tr>
				<tr><td></td><td>Need Development</td></tr>
				<tr><td></td><td>Not Ready</td></tr>
			    <?php } else if ($get_hasil_rekomendasi['notes'] == 'Need Development') { ?>
				<tr><td width="15%"></td><td width="85%">Ready</td></tr>
				<tr><td class="text-center" style="background: orange">V</td><td>Need Development</td></tr>
				<tr><td></td><td>Not Ready</td></tr>
			    <?php } else if ($get_hasil_rekomendasi['notes'] == 'Not Ready') { ?>
				<tr><td width="15%"></td><td width="85%">Ready</td></tr>
				<tr><td></td><td>Need Development</td></tr>
				<tr><td class="text-center" style="background: red">V</td><td>Not Ready</td></tr>
			    <?php } else { ?>
				<tr><td colspan="2">-</td></tr>
				<?php 
						} 
					} else { 
				?>
				<tr><td colspan="2">-</td></tr>
				<?php } ?>
			</table>
		</p>
		<p>&nbsp;</p>
		<table class="table-bordered" style="margin-top: 20px">
			<tr><td colspan="6">PROFIL INDIVIDU</td></tr>
			<!--<tr>
				<td width="20%">Definisi</td>
				<td width="5%">Low</td>
				<td width="5%">Moderate</td>
				<td width="5%">High</td>
				<td width="5%">Score</td>
				<td width="60%">Keterangan</td>
			</tr>-->
		<?php 
		if (!empty($nilai_e)) {
			foreach ($nilai_e as $vck => $vcv) {
				echo '<tr>
						<td width="20%"><b>'.$vcv['detil_aspek']['nama'].'</b><br>'.$vcv['detil_aspek']['deskripsi_aspek'].'</td>
						';

				$level_color = 'red';
				if ($vcv['nilai'] > 0 && $vcv['nilai'] < 6) {
					$level_color = 'red';
				} else if ($vcv['nilai'] >= 6 && $vcv['nilai'] < 8) {
					$level_color = 'green';
				} else if ($vcv['nilai'] >= 8 && $vcv['nilai'] <= 10) {
					$level_color = 'blue';
				} 

				if ($vcv['nilai'] > 0) {
					echo '<td colspan="3" width="13%"><div style="background: '.$level_color.'; width: '.($vcv['nilai']*10).'%; height: 50px">&nbsp;</div></td>';
				} else {
					echo '<td colspan="3"  width="13%">&nbsp;</td>';
				}

				$deskripsi_pendek = "-";
				$deskripsi_panjang = "-";

				if ($vcv['nilai_konversi'] > 0) {
					$deskripsi_pendek = $vcv['detil_aspek']['nilai_to_deskripsi'][$vcv['nilai_konversi']]['desc'];
					$deskripsi_panjang = $vcv['detil_aspek']['nilai_to_deskripsi'][$vcv['nilai_konversi']]['description'];
				}

				echo '	<td  width="7%">'.($vcv['nilai']*10).' %</td>
						<td  width="60%">
							<b>'.$deskripsi_pendek.'</b><br>
							<span style="font-size: 8px">'.$deskripsi_panjang.'</span>
						</td>
					</tr>';
			}
		}
		?>
		</table>
	</body>
</html>