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
							<div class="form_group">
								<label for="">Nama Instansi</label>
								<p><i><?=$instansi['nama'];?></i></p>
							</div>
							<div class="form_group">
								<label for="">Logo Instansi</label>
								<p><img src="<?=$logo;?>" style="width: 100px;"></p>
							</div>

							<a href="<?=base_url('/admin/instansi/edit');?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
