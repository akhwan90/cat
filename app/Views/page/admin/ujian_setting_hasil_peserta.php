<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-print"></i> <?=$title;?></div>
						<div class="card-body">
							<h5>Detil Ujian</h5>
							<table class="table table-bordered table-sm">
								<tr><th width="30%">Nama Peserta</th><td width="70%"><?=$detil_ujian['nama'];?></td></tr>
								<tr><th>Tgl Mulai Ujian</th><td><?=$detil_ujian['tgl_mulai'];?></td></tr>
								<tr><th>Tgl Selesai Ujian</th><td><?=$detil_ujian['tgl_selesai'];?></td></tr>
								<tr><th>Nilai</th><td><?=floatval($detil_ujian['nilai']);?></td></tr>
							</table>

							<h5 class="mt-4">Detil Pengerjaan</h5>

							<table class="table table-sm table-hover">
								<thead>
									<tr>
										<th>Nomor</th>
										<th>Kunci Jawaban</th>
										<th>Jawaban Siswa</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									$no = 1;
									if (!empty($detil_pengerjaan)) {
										foreach ($detil_pengerjaan as $soal) {
											$status = ($soal['jawaban'] == $soal['kunci']) ? '<i class="fa fa-check text-success"></i> ' : '<i class="fa fa-minus-circle text-danger"></i> ';
											echo '
											<tr>
											<td>'.$no.'</td>
											<td>'.$soal['kunci'].'</td>
											<td>'.$soal['jawaban'].'</td>
											<td>'.$status.'</td>
											</tr>';

											$no++;
										}
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
</main>
