<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">

							<div class="table-responsive">
								<table class="table table-bordered table-hover table-sm">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="10%" class="text-center">Aksi</th>
											<th width="15%" class="text-center">Jenis / Bagian</th>
											<th width="70%" class="text-center">Keterangan</th>
											<!-- <th width="10%" class="text-center">Jumlah Harus Jawab</th>
											<th width="10%" class="text-center">Jumlah Opsi</th>
											<th width="10%" class="text-center">Multi Kunci</th>
											<th width="10%" class="text-center">Jml Soal Tersedia</th> -->
										</tr>
									</thead>
									<tbody>
										<?php 
										if (!empty($jenis_soal)) {
											$no = 1;
											foreach ($jenis_soal as $jr => $js) {
												echo '
													<tr>
														<td class="text-center">'.$no.'</td>
														<td><a href="'.base_url('admin/soal/detil_per_jenis/'.$js['jenis'].'/'.$js['bagian']).'" class="btn btn-primary col-lg-12"><i class="fa fa-search"></i> Detil</a></td>
														<td class="text-center">'.$js['jenis'].'/'.$js['bagian'].'</td>
														<td class="">'.$js['keterangan'].'</td>
														<!--<td class="text-center">'.$js['jml_harus_jawab'].'</td>
														<td class="text-center">'.$js['jml_opsi'].'</td>
														<td class="text-center">'.$js['multi_kunci'].'</td>
														<td class="text-center">'.$js['jml_soal'].'</td>-->
													</tr>';
												$no++;
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
	</div>
</main>
