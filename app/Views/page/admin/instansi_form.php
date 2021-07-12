<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">
							<?=session()->getFlashdata('error');?>
							<?=form_open_multipart(base_url('/admin/instansi/save'));?>
							<div class="form_group">
								<label for="">Nama Instansi</label>
								<?=form_input('nama', $instansi['nama'], 'class="form-control" required');?>
								<p><i><?=$instansi['nama'];?></i></p>
							</div>
							<div class="form_group">
								<label for="">Logo Instansi</label>
								<?=form_upload('logo', '', 'class="form-control"');?>
								<p><img src="<?=$logo;?>" class="mt-2" style="width: 100px;"></p>
							</div>

							<button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Simpan</button>
							<a href="<?=base_url('/admin/instansi');?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
							<?=form_close();?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
