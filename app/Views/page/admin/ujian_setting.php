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

							<a href="<?=base_url('/admin/ujian');?>" class="btn btn-secondary mr-3"><i class="fa fa-arrow-left"></i> Kembali</a>
							<a href="#soal" onclick="return load_soal(<?=$detil_ujian['id'];?>);" class="btn btn-success"><i class="fa fa-file"></i> Soal</a>
							<a href="#" onclick="return tambah_dari_bank_soal();" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Soal Dari Bank Soal</a>
							<!-- <a href="#" class="btn btn-success">Tambah Soal Dari Input Soal</a> -->
							<a href="#peserta" onclick="return load_peserta(<?=$detil_ujian['id'];?>);" class="btn btn-primary"><i class="fa fa-users"></i> Peserta</a>
							<a href="#" onclick="return tambah_peserta();" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Peserta</a>
							<a href="<?=base_url('/admin/ujian/setting/'.$detil_ujian['id'].'/cetak_hasil');?>" class="btn btn-danger" target="_blank"><i class="fa fa-print"></i> Cetak Hasil</a>
							<a href="<?=base_url('/admin/ujian/setting/'.$detil_ujian['id'].'/cetak_hasil?to=excel');?>" class="btn btn-danger" target="_blank"><i class="fa fa-download"></i> Download Hasil (Excel)</a>


							<h5 class="mt-3" id="page_title"></h5>

							<div id="page" class="mt-2">
									
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
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambahkan soal</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?=form_open('#', 'id="form_pilih_soal" onsubmit="return simpan_ujian_soal();"');?>
				<div class="modal-body">
					<div id="list_soal"></div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_tambah_soal_tb_save">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			<?=form_close();?>
		</div>
	</div>
</div>



<div class="modal fade" id="mdl_tambah_peserta" tabindex="-1" role="dialog" aria-labelledby="mdl_tambah_peserta" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambahkan Peserta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?=form_open('#', 'id="form_pilih_peserta" onsubmit="return simpan_ujian_peserta();"');?>
				<div class="modal-body">
					<div id="list_peserta"></div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="mdl_tambah_peserta_tb_save">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
				</div>
			<?=form_close();?>
		</div>
	</div>
</div>
