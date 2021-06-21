<main class="main">
	<!-- Breadcrumb-->
	<?php // $this->load->view('layout/breadcrumb');?>

	<div class="container-fluid mt-4">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-random"></i> <?=$title;?></div>
						<div class="card-body">
							<?=form_open(base_url('admin/ubah_password'), 'id="f_ubah_password_peserta"');?>
							<!-- <div class="form-group">
								<label for="">Password Lama</label>
								<?=form_password('p1', '', 'class="form-control" required id="p1"');?>
							</div> -->
							<div class="form-group">
								<label for="">Password Baru</label>
								<?=form_password('p2', '', 'class="form-control" required id="p2"');?>
							</div>
							<div class="form-group">
								<label for="">Ulangi Password Baru</label>
								<?=form_password('p3', '', 'class="form-control" required id="p3"');?>
							</div>
							<div class="form-group">
								<button class="btn btn-primary" type="submit" id="tb_save"><i class="fa fa-check"></i> Simpan</button>
								<a href="<?=base_url('admin');?>" class="btn btn-secondary">Kembali</a>
							</div>

							<?=form_close();?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
