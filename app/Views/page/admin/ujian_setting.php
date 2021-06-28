<main class="main">
	<input type="hidden" name="id_ujian" id="id_ujian" value="<?=$detil_ujian['id'];?>">
	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">

							<a href="#" onclick="return show_soal();" class="btn btn-success">Soal</a>
							<a href="#" onclick="return tambah_dari_bank_soal();" class="btn btn-success">Tambah Soal Dari Bank Soal</a>
							<a href="#" class="btn btn-success">Tambah Soal Dari Input Soal</a>
							<a href="#" onclick="return show_peserta();" class="btn btn-primary">Peserta</a>
							<a href="#" onclick="return tambah_peserta();" class="btn btn-primary">Tambah Peserta</a>



							<div class="page">
									
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>

<div class="modal fade" id="mdl_tambah_soal" tabindex="-1" role="dialog" aria-labelledby="mdl_tambah_soal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form method="post" action="#" id="mdl_tambah_soal_form" enctype="multipart/form-data">
				<div class="modal-body">
					<div id="list_soal"></div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_tambah_soal_tb_save">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			</form>
		</div>
	</div>
</div>
