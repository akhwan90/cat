<a href="#" class="btn btn-primary" style="
	bottom: 10px;
    position: fixed;
    right: 10px;
    z-index: 12000;
    margin: 0px;
    padding: 0px;
    clear: both;
    text-align: center;" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-question"></i> PETUNJUK PENGERJAAN</a>

<main class="main">
	<div id="loading"></div>
	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="list_soal">
						<!-- <ul class="nav nav-tabs mb-3">
							<?php 
							/*foreach ($jenis_bagian as $jenis_v) {
								$aktiv = '';
								if ($jenis.$bagian == $jenis_v['jenis'].$jenis_v['bagian']) {
									$aktiv = 'active';
								}
								echo '
								<li class="nav-item">
									<a class="nav-link '.$aktiv.'" href="'.base_url('/peserta/ikuti_ujian/ok_tes/'.$id_ujian.'/'.$jenis_v['jenis'].'/'.$jenis_v['bagian']).'"><strong>'.$jenis_v['jenis'].'-'.$jenis_v['bagian'].'</strong></a>
								</li>';
							}*/
							?>
						</ul> -->


						<input type="hidden" name="jml_soal" id="jml_soal" value="<?=count($list_soal);?>">
						<input type="hidden" name="soal_aktif" id="soal_aktif" value="1">
						<input type="hidden" name="id_soal_aktif" id="id_soal_aktif" value="<?=$list_soal[0]['id'];?>">
						<input type="hidden" name="sisa_waktu" id="sisa_waktu" value="<?=$sisa_waktu;?>">
						<input type="hidden" name="id_ujian" id="id_ujian" value="<?=$id_ujian;?>">
						<?php 
						$nomor_kotak = 0;
						$nomor_soal = 1;
						$kotak_terisi = [];

						foreach ($list_soal as $ls) {

								
							$gambar['soal'] = is_file("./public/uploads/gambar_soal/".$ls['file']) ? '<p><img src="'.base_url('/public/uploads/gambar_soal/'.$ls['file']).'" class="img-thumbnail"></p>' : '';
							$gambar['a'] = is_file("./public/uploads/gamba_opsi/".$ls['media_a']) ? '<p><img src="'.base_url('/public/uploads/gamba_opsi/'.$ls['media_a']).'" class="img-thumbnail"></p>' : '';
							$gambar['b'] = is_file("./public/uploads/gamba_opsi/".$ls['media_b']) ? '<p><img src="'.base_url('/public/uploads/gamba_opsi/'.$ls['media_b']).'" class="img-thumbnail"></p>' : '';
							$gambar['c'] = is_file("./public/uploads/gamba_opsi/".$ls['media_c']) ? '<p><img src="'.base_url('/public/uploads/gamba_opsi/'.$ls['media_c']).'" class="img-thumbnail"></p>' : '';
							$gambar['d'] = is_file("./public/uploads/gamba_opsi/".$ls['media_d']) ? '<p><img src="'.base_url('/public/uploads/gamba_opsi/'.$ls['media_d']).'" class="img-thumbnail"></p>' : '';
							$gambar['e'] = is_file("./public/uploads/gamba_opsi/".$ls['media_e']) ? '<p><img src="'.base_url('/public/uploads/gamba_opsi/'.$ls['media_e']).'" class="img-thumbnail"></p>' : '';

							echo '
							<div class="card" id="kotak_'.$nomor_kotak.'">
								<div class="card-header">Soal Nomor '.$nomor_soal.'</div>
								<div class="card-body">
								<div class="card card-body soal_freeze">'.$ls['soal'].'
								'.$gambar['soal'].'
								</div>
								<div class="row">';

								for ($i = 1; $i <= (count($huruf_opsi) - 1); $i++) {
									
									$checked_aktif = "";
									if (strtoupper($huruf_opsi[$i]) == strtoupper($ls['jawaban_peserta'])) {
										$checked_aktif = "checked";
										$kotak_terisi[$nomor_kotak] = true;
									}

									echo '<div class="col-lg-12">
									<label style="padding: 10px 15px 5px 15px">
									<input type="radio" name="jawaban_'.$nomor_soal.'" value="'.$huruf_opsi[$i].'" '.$checked_aktif.'> '.$ls['opsi_'.$huruf_opsi[$i]].'
										'.$gambar[$huruf_opsi[$i]].'
									</label>
									</div>';
								}

							echo '</div></div></div>';
							$nomor_kotak++;
							$nomor_soal++;
						}
						?>
					</div>

					<div class="card border-primary card-body text-primary">

						<div class="row pl-3">
							<a href="#" onclick="return prev();" id="tb_prev" data-nomorsoal="0" data-islast="0" class="btn btn-info btn-lg"><i class="fa fa-arrow-left"></i> Sebelumnya</a> &nbsp;
							<a href="#" onclick="return next();" id="tb_next" data-nomorsoal="0" class="btn btn-info btn-lg">Selanjutnya <i class="fa fa-arrow-right"></i></a> &nbsp;
							<a href="#" onclick="return selesai_ujian();" id="tb_selesai" class="btn btn-danger btn-lg float-right"><i class="fa fa-minus-circle"></i> Selesai</a>
						</div>
					</div>

					<input type="hidden" name="kotak_terisi" id="kotak_terisi" value='<?=json_encode($kotak_terisi);?>'>
				</div>

			</div>
		</div>
	</div>
</main>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-question"></i> PETUNJUK PENGERJAAN</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

