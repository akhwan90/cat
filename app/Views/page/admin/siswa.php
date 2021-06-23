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
							<a href="<?=base_url();?>/admin/siswa/form_import" class="btn btn-outline-warning btn-lg mb-4"><i class="fa fa-users"></i> Tambahkan Banyak</a>

							<div class="table-responsive">
								<table class="table table-bordered table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="15%" class="text-center">NIM</th>
											<th width="50%" class="text-center">Nama</th>
											<th width="20%" class="text-center">Username</th>
											<th width="10%" class="text-center">Aksi</th>
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
						<label for="">Nama</label>
						<input type="text" name="nama" id="nama" class="form-control">
					</div>
					<div class="form-group">
						<label for="">NIM</label>
						<input type="text" name="nim" id="nim" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Jurusan</label>
						<input type="text" name="jurusan" id="jurusan" class="form-control">
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
