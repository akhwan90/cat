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

							<div class="table-responsive">
								<table class="table table-bordered table-sm table-hover">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="20%" class="text-center">Nama Ujian</th>
											<th width="15%" class="text-center">Mulai</th>
											<th width="15%" class="text-center">Selesai</th>
											<th width="15%" class="text-center">Status Selesai</th>
											<th width="30%" class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php 

										
										if (!empty($data_tes)) {
											$no = 1;
											foreach ($data_tes as $tes) {

												if (strtotime('now') < strtotime($tes['tgl_mulai'])) {
													$link_ikuti = '<a href="#" class="btn btn-danger col-lg-12"><i class="fa fa-minus-circle"></i> Belum waktunya mengerjakan</a>';
												} else if (strtotime('now') > strtotime($tes['terlambat'])) {
													$link_ikuti = '<a href="#" class="btn btn-danger col-lg-12"><i class="fa fa-minus-circle"></i> Waktu test telah selesai</a>';
												} else {
													if ($tes['status'] == "N") {
														$link_ikuti = '<a href="'.base_url('peserta/ikuti_ujian/ok/'.$tes['id']).'" class="btn btn-success col-lg-12"><i class="fa fa-edit"></i> Ikuti test</a>';
													} else if ($tes['status'] == "D") {
														$link_ikuti = '<a href="'.base_url('peserta/ikuti_ujian/ok/'.$tes['id']).'" class="btn btn-success col-lg-12"><i class="fa fa-edit"></i> Lanjutkan Mengerjakan</a>';
													} else if ($tes['status'] == "Y") {
														$link_ikuti = '<a href="#" class="btn btn-danger col-lg-12"><i class="fa fa-minus-circle"></i> Anda sudah mengerjakan</a>';
													}
												}

												$status_selesai = '<i class="fa fa-check"></i> Belum Dikerjakan';
												if ($tes['status'] == 'Y') {
													$status_selesai = '<i class="fa fa-minus-circle"></i> Sudah Selesai';
												} else if ($tes['status'] == 'D') {
													$status_selesai = '<i class="fa fa-edit"></i> Sedang Dikerjakan';
												}

												echo '
												<tr>
													<td class="text-center">'.$no.'</td> 
													<td>'.$tes['nama_ujian'].'</td> 
													<td class="text-center">'.tjs($tes['tgl_mulai']).'</td> 
													<td class="text-center">'.tjs($tes['terlambat']).'</td> 
													<td class="text-center">'.$status_selesai.'</td> 
													<td class="text-center">'.$link_ikuti.'</td> 
												</tr>';

												$no++;
											}
										} else {
											echo '<tr><td colspan="6">-</td></tr>';
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
