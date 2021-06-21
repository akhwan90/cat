<main class="main">

	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-print"></i> <?=$title;?></div>
						<div class="card-body">
							<a href="<?=base_url('admin/ujian');?>" class="btn btn-outline-secondary btn-lg mb-3"><i class="fa fa-arrow-left"></i> Kembali</a>
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-sm">
									<thead>
										<tr>
											<th width="5%" class="text-center">No</th>
											<th width="25%" class="text-center">Aksi</th>
											<th width="25%" class="text-center">Nama Peserta</th>
											<th width="25%" class="text-center">Jenis Ujian</th>
											<th width="20%" class="text-center">Rekomendasi</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										$no = 1;
										if (!empty($data)) {
											foreach ($data as $d) {

												$link_detil_cetak = '';


												$jenis_tes = $d['jenis_tes'];
												$jenis_staff = $d['jenis_staff'];
												$level_test = $d['level_test'];
												$id_ujian = $d['id_ujian'];
												$id_peserta = $d['id_peserta'];

												$link_detil_jawaban = base_url('admin/ujian/lihat_jawaban/'.$id_ujian.'/'.$id_peserta);;

												$jenis_tes_peserta = $jenis_tes."-".$jenis_staff;
		
												if ($jenis_tes_peserta == "1-1") {
													$link_detil_cetak = base_url('admin/ujian/cetak_hasil_seleksi_non_staff/'.$id_ujian.'/'.$id_peserta);
												} else if ($jenis_tes_peserta == "1-2") {
													$link_detil_cetak = base_url('admin/ujian/cetak_hasil_seleksi_staff/'.$id_ujian.'/'.$id_peserta);
												} else if ($jenis_tes_peserta == "2-0") {
													$link_detil_cetak = base_url('admin/ujian/cetak_hasil_assesment/'.$id_ujian.'/'.$id_peserta);
												} 

												$jenis_tes = $setting_sistem_seleksi[$jenis_tes]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['nama']." - ".$setting_sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test]['nama'];

												$notes = $d['notes'];
												if ($notes == "") {
													$notes = '<a href="'.base_url('admin/ujian/selesai/'.$id_ujian.'/'.$id_peserta).'" class="btn btn-warning">Selesaikan</a>';
												}

												echo '
													<tr>
														<td class="text-center">'.$no.'</td> 
														<td class="text-center">
														<div class="btn-group col-lg-12">
															<a href="'.base_url('admin/ujian/batalkan/'.$id_ujian.'/'.$id_peserta).'" class="btn btn-outline-warning btn-sm" onclick="return confirm(\'Anda yakin..?\');" title="Batalkan ujian"><i class="fa fa-times"></i> Batalkan</a>
															<a href="'.$link_detil_cetak.'" target="_blank" class="btn btn-outline-success btn-sm" title="Lihat Hasil Ujian"><i class="fa fa-print"></i> Hasil</a>
															<a href="'.$link_detil_jawaban.'" class="btn btn-outline-info btn-sm" title="Lihat Jawaban"><i class="fa fa-search"></i> Lihat Jawaban</a>
														</div>
														</td> 
														<td>'.$d['nm_peserta'].'</td> 
														<td>'.$jenis_tes.'</td> 
														<td>'.$notes.'</td> 
													</tr>';
												
												$no++;
											}
										} else {
											echo '<tr><td colspan="5">-</td></tr>';
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
