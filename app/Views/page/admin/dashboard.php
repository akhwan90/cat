<main class="main">
	<!-- Breadcrumb-->
	<?php // $this->load->view('layout/breadcrumb');?>

	<div class="container-fluid mt-4">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<!-- <pre>Username : <?=json_encode(session('username'), JSON_PRETTY_PRINT);?></pre>
					<pre>Level : <?=json_encode(session('level'), JSON_PRETTY_PRINT);?></pre> -->

					<div class="row">
						<div class="col-lg-3">
							<div class="card text-white bg-primary">
								<div class="card-header">Jumlah Peserta</div>
								<div class="card-body text-right"><h1><?=$jml_peserta;?></h1></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card text-white bg-success">
								<div class="card-header">Jumlah Peserta Seleksi Non Staff</div>
								<div class="card-body text-right"><h1><?=$jml_peserta_seleksi_non_staff;?></h1></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card text-white bg-warning">
								<div class="card-header">Jumlah Peserta Seleksi Staff</div>
								<div class="card-body text-right"><h1><?=$jml_peserta_seleksi_staff;?></h1></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card text-white bg-danger">
								<div class="card-header">Jumlah Peserta Assessment</div>
								<div class="card-body text-right"><h1><?=$jml_peserta_assesment;?></h1></div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6 col-lg-6">
							<div class="card">
								<div class="card-header bg-success text-center text-white">
									<h3>Seleksi Non Staff</h3>
								</div>
								<div class="card-body row text-center">
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['non_staff_rekomendasi'];?></h2></div>
										<div class="text-uppercase text-muted small">Direkomendasikan</div>
									</div>
									<div class="c-vr" style="width: 1px; background-color: rgba(0,0,21,.2);"></div>
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['non_staff_tidak_rekomendasi'];?></h2></div>
										<div class="text-uppercase text-muted small">Tidak Direkomendasikan</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-lg-6">
							<div class="card">
								<div class="card-header bg-warning text-center text-white">
									<h3>Seleksi Staff</h3>
								</div>
								<div class="card-body row text-center">
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['staff_rekomendasi'];?></h2></div>
										<div class="text-uppercase text-muted small">Direkomendasikan</div>
									</div>
									<div class="c-vr" style="width: 1px; background-color: rgba(0,0,21,.2);"></div>
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['staff_tidak_rekomendasi'];?></h2></div>
										<div class="text-uppercase text-muted small">Tidak Direkomendasikan</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 col-lg-12">
							<div class="card">
								<div class="card-header bg-danger text-center text-white">
									<h3>Assesment</h3>
								</div>
								<div class="card-body row text-center">
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['assesment_ready'];?></h2></div>
										<div class="text-uppercase text-muted small">Ready</div>
									</div>
									<div class="c-vr" style="width: 1px; background-color: rgba(0,0,21,.2);"></div>
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['assesment_need_development'];?></h2></div>
										<div class="text-uppercase text-muted small">Need Development</div>
									</div>
									<div class="c-vr" style="width: 1px; background-color: rgba(0,0,21,.2);"></div>
									<div class="col">
										<div class="text-value-xl"><h2><?=$hasil_ujian_per_jenis['assesment_not_ready'];?></h2></div>
										<div class="text-uppercase text-muted small">Not Ready</div>
									</div>
								</div>
							</div>
						</div>
					</div>


				</div>

			</div>
		</div>
	</div>
</main>
