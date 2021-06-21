<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">

							<?=form_open(base_url('admin/jenis_ujian/simpan'), '', ['id'=>$detil['id']]);?>

							<div class="form-group">
								<label for="">Jenis Tes</label>
								<?=form_input('jenis_tes', $detil['jenis_tes_nama'], 'class="form-control" disabled');?>
							</div>
							<div class="form-group">
								<label for="">Jenis Staff</label>
								<?=form_input('jenis_staff', $detil['jenis_staff_nama'], 'class="form-control" disabled');?>
							</div>
							<div class="form-group">
								<label for="">Level Tes</label>
								<?=form_input('level_tes_nama', $detil['level_tes_nama'], 'class="form-control"');?>
							</div>

							<div class="form-group mt-3">
								<button class="btn btn-primary" type="submit">Simpan</button>
								<a href="<?=base_url('admin/jenis_ujian');?>" class="btn btn-secondary">Kembali</a>
							</div>


							<?=form_close();?>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>