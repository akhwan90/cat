<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> <?=$title;?></div>
						<div class="card-body">

							<div class="table-responsive">
								<table class="table table-bordered table-sm" id="datatabel">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="10%" class="text-center">Aksi</th>
											<th width="20%" class="text-center">Jenis Tes</th>
											<th width="20%" class="text-center">Jenis Staff</th>
											<th width="20%" class="text-center">Jenis Ujian</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$no = 1;
										if (!empty($jenis_ujian)) {
											foreach ($jenis_ujian as $ju) {
												echo '
												<tr>
													<td>'.$no.'</td>
													<td>
														<a href="'.base_url('admin/jenis_ujian/edit/'.$ju['id']).'" class="btn btn-success btn-sm col-lg-12"><i class="fa fa-edit"></i> Edit</a>
													</td>
													<td>'.$ju['jenis_tes_nama'].'</td>
													<td>'.$ju['jenis_staff_nama'].'</td>
													<td>'.$ju['level_tes_nama'].'</td>
												</tr>';

												$no++;
											}
										} else {
											echo '<tr><td colspan="3">-</td></tr>';
										}
										?>
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
