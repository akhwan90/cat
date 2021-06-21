<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">
							<a href="#" onclick="return edit(0);" class="btn btn-outline-primary btn-lg mb-4"><i class="fa fa-plus-circle"></i> Tambah Data</a>
							<a href="#" onclick="return aktifkan_user();" class="btn btn-outline-success btn-lg mb-4"><i class="fa fa-user"></i> Aktifkan User</a>
							<a href="<?=base_url();?>/admin/peserta/form_import" class="btn btn-outline-warning btn-lg mb-4"><i class="fa fa-users"></i> Tambahkan Banyak</a>

							<div class="table-responsive">
								<table class="table table-bordered table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No/SN</th>
											<th width="10%" class="text-center">Aksi</th>
											<th width="15%" class="text-center">Nama</th>
											<th width="5%" class="text-center">Jenis Test</th>
											<th width="10%" class="text-center">Jenis Staff</th>
											<th width="10%" class="text-center">Level Test</th>
											<th width="5%" class="text-center">Pendidikan</th>
											<th width="10%" class="text-center">Username</th>
										</tr>
									</thead>
									<tbody>
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

<div class="modal fade" id="mdl_edit" tabindex="-1" role="dialog" aria-labelledby="mdl_edit" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="#" id="mdl_edit_form" enctype="multipart/form-data">
				<input type="hidden" name="_id" id="_id">
				<input type="hidden" name="_mode" id="_mode">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><?=$title;?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="">Nomor / SN</label>
						<input type="text" name="nomor" id="nomor" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Nama</label>
						<input type="text" name="nama" id="nama" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Email</label>
						<input type="email" name="email" id="email" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Posisi</label>
						<input type="text" name="posisi_saat_ini" id="posisi_saat_ini" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Level Test</label>
						<?=form_dropdown('level_test', $p_level_test, '', 'class="form-control" id="level_test"');?>
						<!-- <input type="text" name="level_test" id="level_test" class="form-control"> -->
					</div>
					<div class="form-group">
						<label for="">Tempat Lahir</label>
						<input type="text" name="tmp_lahir" id="tmp_lahir" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Tanggal Lahir</label>
						<input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Jenis Kelamin</label>
						<?=form_dropdown('jenis_kelamin', $p_jk, '', 'class="form-control" id="jenis_kelamin');?>
						<!-- <input type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control"> -->
					</div>
					<div class="form-group">
						<label for="">Pendidikan</label>
						<?=form_dropdown('pendidikan', $p_pendidikan, '', 'class="form-control" id="pendidikan');?>
						<!-- <input type="text" name="pendidikan" id="pendidikan" class="form-control"> -->
					</div>
					<div class="form-group">
						<label for="">Foto Peserta</label>
						<input type="file" name="foto_peserta" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_edit_tb_save">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>


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


<div class="modal fade" id="mdl_reset_pass" tabindex="-1" role="dialog" aria-labelledby="mdl_reset_pass" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="post" action="#" id="mdl_reset_pass_form" onsubmit="return reset_ok();">
				<input type="hidden" name="mdl_reset_pass_id" id="mdl_reset_pass_id">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="">Password baru</label>
						<input type="text" name="password_baru" id="password_baru" class="form-control">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_reset_pass_tb_save">Simpan</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
