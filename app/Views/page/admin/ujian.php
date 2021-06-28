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
							<!-- <a href="<?=base_url();?>/admin/mapel/form_import" class="btn btn-outline-warning btn-lg mb-4"><i class="fa fa-users"></i> Tambahkan Banyak</a> -->

							<div class="table-responsive">
								<table class="table table-bordered table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="30%" class="text-center">Nama Ujian</th>
											<th width="15%" class="text-center">Tgl Mulai</th>
											<th width="15%" class="text-center">Tgl Selesai</th>
											<th width="15%" class="text-center">Token</th>
											<th width="20%" class="text-center">Aksi</th>
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
						<label for="">Nama Ujian</label>
						<input type="text" name="nama" id="nama" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Mapel</label>
						<?=form_dropdown('id_mapel', $p_mapel, '', 'class="form-control" id="id_mapel"');?>
					</div>
					<div class="form-group">
						<label for="">Jumlah Soal</label>
						<input type="number" name="jumlah_soal" id="jumlah_soal" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Waktu Pengerjaan (menit)</label>
						<input type="number" name="waktu" id="waktu" class="form-control">
					</div>
					<div class="form-group">
						<label for="">Acak Soal</label>
						<?=form_dropdown('jenis', $p_acak, '', 'class="form-control" id="jenis"');?>
					</div>
					<div class="form-group">
						<label for="">Tgl Mulai Bisa Dikerjakan</label>
						<input type="datetime-local" name="tgl_mulai" id="tgl_mulai" class="form-control" min="<?=date('Y-m-d')."T".date('H:i');?>">
					</div>
					<div class="form-group">
						<label for="">Tgl Maksimal Bisa Dikerjakan</label>
						<input type="datetime-local" name="terlambat" id="terlambat" class="form-control" min="<?=date('Y-m-d')."T".date('H:i');?>">
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
