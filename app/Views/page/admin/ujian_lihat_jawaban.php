<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-search"></i> <?=$title;?></div>
						<div class="card-body">
							<a href="<?=base_url('admin/ujian/lihat_jawaban_mendatar/'.$id_ujian.'/'.$id_peserta);?>" class="btn btn-primary mb-2">Lihat Versi Horizontal</a>

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
							foreach ($detil_jawaban as $dj) {
								$id_tab = "tab_".strtolower($dj['jenis'])."_".strtolower($dj['bagian']);
								$id_tab_content = "tab_content_".strtolower($dj['jenis'])."_".strtolower($dj['bagian']);
								$aktif = ($no_tab == 1) ? 'active' : '';

								$li_tab_tab .= '<li class="nav-item"><a class="nav-link '.$aktif.'" id="'.$id_tab.'" data-toggle="tab" href="#'.$id_tab_content.'" role="tab" aria-controls="home" aria-selected="true">'.$dj['jenis'].' '.$dj['bagian'].'</a></li>';

								$generate_content = '<table class="table table-sm table-hover">
													<thead><tr>
													<th>No</th>
													<th>Jawaban</th>
													<th>Kunci</th>
													<th>Nilai</th>
													</tr></thead><tbody>';

								$detil_to_array = json_decode($dj['detil'], true);

								$jumlah_nilai = 0;
								foreach ($detil_to_array as $dtk => $dtv) {
									$jumlah_nilai += $dtv['status'];
									$background_jika_benar = ($dtv['status'] == 1) ? 'class="bg-secondary"' : '';

									$generate_content .= '<tr '.$background_jika_benar.'>
														<td>'.$dtk.'</td>
														<td>'.json_encode($dtv['jawaban']).'</td>
														<td>'.json_encode($dtv['kunci']).'</td>
														<td>'.$dtv['status'].'</td>
														</tr>';
								}

								$generate_content .= '<tr><th colspan="3">Jumlah Benar</th><th>'.$jumlah_nilai.'</th></tr>';
								$generate_content .= '</tbody></table>';

								$li_tab_content .= '<div class="tab-pane fade show '.$aktif.'" id="'.$id_tab_content.'" role="tabpanel" aria-labelledby="home-tab">'.$generate_content.'</div>';

								$no_tab ++;
							}
							?>


							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<?=$li_tab_tab;?>
							</ul>
							<div class="tab-content" id="myTabContent">
  								<?=$li_tab_content;?>
  							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
