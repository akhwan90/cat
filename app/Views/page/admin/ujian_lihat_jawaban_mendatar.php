<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-search"></i> <?=$title;?></div>
						<div class="card-body">
							<table class="table table-sm table-bordered">
								<tr><td width="30%">Nama Peserta</td><td width="70%"><?=$detil_peserta_ujian['nm_peserta'];?></td></tr>
								<tr><td>Nama Ujian</td><td><?=$detil_peserta_ujian['nm_ujian'];?></td></tr>
								<tr><td>Mulai Ujian</td><td><?=tjs($detil_peserta_ujian['mulai']);?></td></tr>
								<tr><td>Selesai Ujian</td><td><?=tjs($detil_peserta_ujian['selesai']);?></td></tr>
								<tr><td>Jenis Tes</td><td><?=$jenis_tes;?></td></tr>
							</table>

							<?php 
							$no_tab = 1;
							$li_tab_tab = '';
							$li_tab_content = '';
							$arr_hasil_jawaban_per_nomor = [];
							
							foreach ($detil_jawaban as $dj) {
								$id_tab = "tab_".strtolower($dj['jenis'])."_".strtolower($dj['bagian']);
								$id_tab_content = "tab_content_".strtolower($dj['jenis'])."_".strtolower($dj['bagian']);
								$aktif = ($no_tab == 1) ? 'active' : '';

								$li_tab_tab .= '<li class="nav-item"><a class="nav-link '.$aktif.'" id="'.$id_tab.'" data-toggle="tab" href="#'.$id_tab_content.'" role="tab" aria-controls="home" aria-selected="true">'.$dj['jenis'].' '.$dj['bagian'].'</a></li>';

								$detil_to_array = json_decode($dj['detil'], true);
								$generate_content = '<div class="table-responsive" style="min-height: 100px;"><table class="table table-bordered table-sm">';

								$search_arr = ['["', '"]', '[]'];
								$ganti_arr = ['', '', ''];

								$nomor = '';
								$kunci = '';
								$jawaban = '';
								$hasil = '';

								$no = 1;

								foreach ($detil_to_array as $dtk => $dtv) {
									$nomor .= '<td>'.$no.'</td>';
									$arr_hasil_jawaban_per_nomor[$dj['jenis']][$dj['bagian']][$no] = $dtv['status'];

									if ($dj['jenis'] == "A" && $dj['bagian'] == 2) {
										$kunci .= '<td>'.implode("", $dtv['kunci']).'</td>';
										$jawaban .= '<td>'.implode("", $dtv['jawaban']).'</td>';
										$hasil .= '<td>'.$dtv['status'].'</td>';

									} else if ($dj['jenis'] == "E" && $dj['bagian'] == 1) {
										$kunci .= '<td>'.$dtv['kunci'].'</td>';
										$jawaban .= '<td>'.$dtv['jawaban'].' </td>';
										$hasil .= '<td>'.$dtv['status'].'</td>';
									} else if ($dj['jenis'] == "B" && $dj['bagian'] == 2) {
										$kunci .= '<td>'.implode("<br>", $dtv['kunci']).'</td>';
										$jawaban .= '<td>'.implode("", $dtv['jawaban']).'</td>';
										$hasil .= '<td>'.$dtv['status'].'</td>';
									} else {
										$kunci .= '<td>'.str_replace($search_arr, $ganti_arr, json_encode($dtv['kunci'])).'</td>';
										$jawaban .= '<td>'.str_replace($search_arr, $ganti_arr, json_encode($dtv['jawaban'])).' </td>';
										$hasil .= '<td>'.$dtv['status'].'</td>';
									}
									$no++;
								}

								$generate_content .= '<tr><td>Nomor</td>'.$nomor.'</tr>';
								$generate_content .= '<tr><td>Kunci</td>'.$kunci.'</tr>';
								$generate_content .= '<tr><td>Jawaban</td>'.$jawaban.'</tr>';
								$generate_content .= '<tr><td>Hasil</td>'.$hasil.'</tr>';

								$generate_content .= '</table><br/><br/></div>';


								// $jumlah_nilai = 0;
								// foreach ($detil_to_array as $dtk => $dtv) {
								// 	$jumlah_nilai += $dtv['status'];
								// 	$background_jika_benar = ($dtv['status'] == 1) ? 'class="bg-secondary"' : '';

								// 	$generate_content .= '<tr '.$background_jika_benar.'>
								// 						<td>'.$dtk.'</td>
								// 						<td>'.json_encode($dtv['jawaban']).'</td>
								// 						<td>'.json_encode($dtv['kunci']).'</td>
								// 						<td>'.$dtv['status'].'</td>
								// 						</tr>';
								// }

								// $generate_content .= '<tr><th colspan="3">Jumlah Benar</th><th>'.$jumlah_nilai.'</th></tr>';
								// $generate_content .= '</tbody></table>';

								$li_tab_content .= '<div class="tab-pane fade show '.$aktif.'" id="'.$id_tab_content.'" role="tabpanel" aria-labelledby="home-tab">'.$generate_content.'</div>';

								$no_tab ++;
								// echo json_encode($arr_hasil_jawaban_per_nomor);
							}
							?>


							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<?=$li_tab_tab;?>
							</ul>
							<div class="tab-content" id="myTabContent">
  								<?=$li_tab_content;?>
  							</div>

  							<table class="table table-bordered table-sm mt-2 table-hover table-stripped">
  								<thead>
  									<tr>
  										<th width="50%">Aspek</th>
  										<th width="50%">Nilai</th>
  									</tr>
  								</thead>
  								<tbody>
  									<?php 
  									foreach ($aspek_a as $ak => $av) {
  										echo '<tr><td>'.$av['nama'].'</td><td>'.$m_nilai_a[$ak].'</td></tr>';
  									}
  									?>
  								</tbody>
  							</table>

  							<table class="table table-bordered table-sm mt-2 table-hover table-stripped">
  								<thead>
  									<tr>
  										<th width="50%">Aspek</th>
  										<th width="50%">Nilai</th>
  									</tr>
  								</thead>
  								<tbody>
  									<?php 
  									foreach ($aspek_b as $bk => $bv) {
  										foreach ($bv as $bdk => $bdv) {
												$nilai_per_aspek = [];
												$total_nilai = 0;
												foreach ($bdv['nomor_soal'] as $ns) {
													$nilai = $arr_hasil_jawaban_per_nomor['B'][$bk][$ns];
													$total_nilai += $nilai;
													$nilai_per_aspek[] = $ns.":".$nilai;
												}
												$nilai_per_aspek = implode(", ", $nilai_per_aspek);
	  										echo '<tr><td>'.$bdv['nama'].' ('.$nilai_per_aspek.')<br>Total: '.$total_nilai.'</td><td>'.$m_nilai_b[$bdk].'</td></tr>';
	  									}
  									}
  									?>
  								</tbody>
  							</table>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
