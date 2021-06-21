<main class="main">
	<!-- Breadcrumb-->
	<?php // $this->load->view('layout/breadcrumb');?>

	<div class="container-fluid mt-4">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-home"></i> MENU PESERTA</div>
						<div class="card-body">
							<form action="<?=base_url('peserta/perbarui_data_ok');?>" method="post">
								<div class="mb-3"><?=$foto;?></div>
								
								<div class="from-row form-group">
									<label for="">No/SN</label>
									<?=form_input('nomor', $peserta['nomor'], 'class="form-control" required disabled');?>
								</div>
								<div class="from-row form-group">
									<label for="">Nama</label>
									<?=form_input('nama', $peserta['nama'], 'class="form-control" required '.$disable);?>
								</div>
								<div class="row form-group">
									<div class="col-lg-4">
										<label for="">Tempat Lahir</label>
										<?=form_input('tmp_lahir', $peserta['tmp_lahir'], 'class="form-control" required '.$disable);?>
									</div>
									<div class="col-lg-3">
										<label for="">Tanggal Lahir</label>
										<input type="date" name="tgl_lahir" value="<?=$peserta['tgl_lahir'];?>" class="form-control" required <?=$disable;?>>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-lg-4">
										<label for="">Jenis Kelamin</label>
										<?=form_dropdown('jenis_kelamin', $p_jk, $peserta['jenis_kelamin'], 'class="form-control" required '.$disable);?>
									</div>
									<div class="col-lg-3">
										<label for="">Pendidikan</label>
										<?=form_dropdown('pendidikan', $p_pendidikan, $peserta['pendidikan'], 'class="form-control" required '.$disable);?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-lg-4">
										<label for="">Posisi Saat Sekarang</label>
										<?=form_input('posisi_saat_ini', $peserta['posisi_saat_ini'], 'class="form-control"  required '.$disable);?>
									</div>
									<div class="col-lg-2">
										<label for="">Lama Jabatan (Tahun)</label>
										<input type="number" name="lama_jabatan_tahun" value="<?=$peserta['lama_jabatan_tahun']?>" class="form-control" required <?=$disable;?>>
									</div>
									<div class="col-lg-2">
										<label for="">Lama Jabatan (Bulan)</label>
										<input type="number" name="lama_jabatan_bulan" value="<?=$peserta['lama_jabatan_bulan']?>" class="form-control" required <?=$disable;?>>
									</div>
								</div>
								<div class="form-group">
									<?php 
									if ($disable == "disabled") {
									?>
									<a href="<?=base_url('/peserta?mode=edit');?>" class="btn btn-primary btn-lg">Perbarui Data</a>
									<?php } else { ?>
									<button type="submit" class="btn btn-primary btn-lg">Simpan</button>
									<a class="btn btn-secondary btn-lg" href="<?=base_url('/peserta');?>">Batal</a>
									<?php } ?>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
