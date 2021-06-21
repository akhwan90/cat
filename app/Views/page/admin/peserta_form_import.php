<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><?=$title_icon.$title;?></div>
						<div class="card-body">
							<a href="<?=base_url();?>/public/aset/format_import_peserta.xlsx" class="btn btn-primary mb-4"><i class="fa fa-download"></i> Download Format</a>
							<?=session()->getFlashdata('errors_upload_peserta');?>
							<form action="<?=base_url();?>/admin/peserta/import_ok" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label for="">Level Test</label>
									<?=form_dropdown('level_test', $p_level_test, '', 'class="form-control" id="level_test"');?>
								</div>
								<div class="form-group">
									<label for="">File Excel</label>
									<input type="file" name="file_excel" class="form-control">
								</div>
								<div class="form-group mt-4">
									<button class="btn btn-success" type="submit"><i class="fa fa-upload"></i> Import</button>
									<a href="<?=base_url('/admin/peserta');?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>