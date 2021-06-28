<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">
							<a href="<?=base_url('/admin/soal/edit/0');?>" class="btn btn-primary mb-2"><i class="fa fa-plus"></i> Tambah Soal</a>
							<a href="<?=base_url('/admin/soal/import');?>" class="btn btn-secondary mb-2"><i class="fa fa-upload"></i> Import Soal</a>

							<div class="table-responsive">
								<table class="table table-bordered table-hover table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="20%" class="text-center">Aksi</th>
											<th width="75%" class="text-center">Keterangan</th>
											<!-- <th width="10%" class="text-center">Jumlah Harus Jawab</th>
											<th width="10%" class="text-center">Jumlah Opsi</th>
											<th width="10%" class="text-center">Multi Kunci</th>
											<th width="10%" class="text-center">Jml Soal Tersedia</th> -->
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
