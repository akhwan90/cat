<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">
							<ul class="nav nav-tabs" id="myTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Per Aspek</a>
								</li>
								<!-- <li class="nav-item">
									<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cetak</a>
								</li> -->
							</ul>
							
							<div class="tab-content" id="myTabContent">
								<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
									<h3>Per Aspek</h3>
									<a href="<?=base_url('/peserta/ujian/cetak_1/'.$id_ujian);?>" class="btn btn-success btn-lg mb-3" target="_blank">Cetak</a>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th width="5%" class="text-center">No</th>
													<th width="65%" class="text-center">Kompetensi</th>
													<th width="15%" class="text-center">Nilai</th>
													<th width="15%" class="text-center">Nilai Konversi</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$no = 1;
												$jml_nilai = 0;
												$jml_nilai_konversi = 0;

												foreach ($data as $nilai_aspek_k => $nilai_aspek_v) {
													if (!empty($nilai_aspek_v)) {
														foreach ($nilai_aspek_v as $as => $pek) {

															$idx = $pek['id_aspek'];
															$nama_aspek = $idx;
															if ($nilai_aspek_k == "a") {
																$nama_aspek = $aspek_a[$idx]['nama_indo'];
															} else if ($nilai_aspek_k == "b") {
																if (!empty($aspek_b[1][$idx]['nama_indo'])) {
																	$nama_aspek = $aspek_b[1][$idx]['nama_indo'];
																} else {
																	$nama_aspek = $aspek_b[2][$idx]['nama_indo'];
																}
															} else if ($nilai_aspek_k == "c") {
																$nama_aspek = $aspek_c[$idx]['nama'];
															}
															echo '<tr><td>'.$no.'</td><td>'.$nama_aspek.'</td><td class="text-center">'.intval($pek['nilai']).'</td><td class="text-center">'.$pek['nilai_konversi'].'</td></tr>';
															
															$no++;
															$jml_nilai += intval($pek['nilai']);
															$jml_nilai_konversi += $pek['nilai_konversi'];
														}
													} else {
														echo '<tr><td colspan="3">Ujian belum selesai</td></tr>';
													}
												}
												?>
											</tbody>
											<thead>
												<tr>
													<th colspan="2">JUMLAH</th>
													<th class="text-center"><?=$jml_nilai;?></th>
													<th class="text-center"><?=$jml_nilai_konversi;?></th>
												</tr>
											</thead>
										</table>
									</div>
								</div>

								<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
									<h3>Cetak</h3>


								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
