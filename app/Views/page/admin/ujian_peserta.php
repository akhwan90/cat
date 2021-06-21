<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-users"></i> <?=$title;?></div>
						<div class="card-body">

							<a href="<?=base_url('/admin/ujian/peserta_tambah/'.$id_ujian);?>" class="btn btn-primary mb-3">Tambah peserta</a>
							<a href="<?=base_url('/admin/ujian');?>" class="btn btn-secondary mb-3">Kembali</a>

							<?=session('notif_update_gelombang');?>

							<div class="table-responsive">
								<table class="table table-bordered table-hover table-sm">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="20%" class="text-center">Aksi</th>
											<th width="10%" class="text-center">Username</th>
											<th width="35%" class="text-center">Nama Peserta</th>
											<th width="30%" class="text-center">Jenis Ujian</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$no = 1;
										if (!empty($data)) {
											foreach ($data as $d) {

												$link_detil_cetak = '';


												$jenis_tes = $d['jenis_tes'];
												$jenis_staff = $d['jenis_staff'];
												$level_test = $d['level_test'];
												$id_ujian = $d['gelombang'];
												$id_peserta = $d['id'];

												$link_detil_cetak = base_url('admin/ujian/peserta_hapus/'.$id_ujian.'/'.$id_peserta);

												$jenis_tes = $setting_sistem_seleksi[$jenis_tes]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test]['nama'];

												echo '
													<tr>
														<td class="text-center">'.$no.'</td> 
														<td class="text-center">
															<a href="'.$link_detil_cetak.'" class="btn btn-danger"><i class="fa fa-times"></i> Hapus</a>
															<a href="#" onclick="return kirim_email('.$d['id'].', \''.$d['email'].'\');" title="Kirim Email" class="btn btn-secondary"><i class="fa fa-envelope"></i> Kirim Email</a>
														</td> 
														<td>'.$d['username'].'</td> 
														<td>'.$d['nama'].'</td> 
														<td>'.$jenis_tes.'</td> 
													</tr>';
												
												$no++;
											}
										} else {
											echo '<tr><td colspan="5">-</td></tr>';
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



<div class="modal fade" id="mdl_kirim_email" tabindex="-1" role="dialog" aria-labelledby="mdl_kirim_email" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="#" id="mdl_kirim_email_form">
				<input type="hidden" name="mdl_kirim_email_id" id="mdl_kirim_email_id">
				<input type="hidden" name="mdl_kirim_email_mode" id="mdl_kirim_email_mode">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Kirim Email</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="">Alamat Email</label>
						<input type="text" name="alamat_email" id="alamat_email" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_kirim_email_tb_save">Kirim</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
