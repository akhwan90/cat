<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-users"></i> <?=$title;?></div>
						<div class="card-body">

							<a href="<?=base_url('/admin/ujian/peserta/'.$id_ujian);?>" class="btn btn-secondary mb-3">Kembali</a>

							<form action="<?=base_url('admin/ujian/peserta_tambah_simpan');?>" method="post">
								<input type="hidden" name="id_ujian" value="<?=$id_ujian;?>">

								<div class="table-responsive">
									<table class="table table-bordered table-hover table-sm">
										<thead>
											<tr>
												<th width="5%" class="text-center">No</th>
												<th width="5%" class="text-center">Pilih</th>
												<th width="10%" class="text-center">Username</th>
												<th width="40%" class="text-center">Nama Peserta</th>
												<th width="40%" class="text-center">Jenis Tes</th>
											</tr>
										</thead>
										<tbody>
											<?php 
											$no = 1;
											if (!empty($data)) {
												foreach ($data as $d) {

													$jenis_tes = $d['jenis_tes'];
													$jenis_staff = $d['jenis_staff'];
													$level_test = $d['level_test'];

													$jenis_tes = $setting_sistem_seleksi[$jenis_tes]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test]['nama'];

													echo '
														<tr>
															<td class="text-center">'.$no.'</td> 
															<td class="text-center">
																<input type="checkbox" name="id_peserta[]" value="'.$d['id'].'" id="chk_'.$d['id'].'">
															</td> 
															<td>'.$d['username'].'</td>
															<td><label for="chk_'.$d['id'].'">'.$d['nama'].'</label></td> 
															<td>'.$jenis_tes.'</td> 
														</tr>';
													
													$no++;
												}
											} else {
												echo '<tr><td colspan="4">-</td></tr>';
											}
											?>
										</tbody>
									</table>
								</div>
								<button type="submit" class="btn btn-primary btn-lg">Simpan</button>
							</form>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>