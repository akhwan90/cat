<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">
							<a href="#" onclick="return edit(0);" class="btn btn-outline-success btn-lg mb-4"><i class="fa fa-plus-circle"></i> Tambah Data</a>

							<div class="table-responsive">
								<table class="table table-bordered table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="25%" class="text-center">Aksi</th>
											<th width="30%" class="text-center">Nama Ujian</th>
											<th width="20%" class="text-center">Mulai</th>
											<th width="20%" class="text-center">Selesai</th>
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
			<form method="post" action="#" onsubmit="return simpan();" id="mdl_edit_form">
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
						<div class="form-row">
						<div class="col-lg-8">
							<label for="">Waktu Mulai (Tgl)</label>
							<input type="date" name="waktu_mulai_tgl" id="waktu_mulai_tgl" class="form-control">
						</div>
						<div class="col-lg-4">
							<label for="">Waktu Mulai (Jam)</label>
							<input type="time" name="waktu_mulai_jam" id="waktu_mulai_jam" class="form-control">
						</div>
						</div>
					</div>
					<div class="form-group">
						<div class="form-row">
						<div class="col-lg-8">
							<label for="">Waktu Selesai (Tgl)</label>
							<input type="date" name="waktu_selesai_tgl" id="waktu_selesai_tgl" class="form-control">
						</div>
						<div class="col-lg-4">
							<label for="">Waktu Selesai (Jam)</label>
							<input type="time" name="waktu_selesai_jam" id="waktu_selesai_jam" class="form-control">
						</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submi" class="btn btn-primary" id="mdl_edit_tb_save">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
