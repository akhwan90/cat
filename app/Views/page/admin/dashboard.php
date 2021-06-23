<main class="main">
	<!-- Breadcrumb-->
	<?php // $this->load->view('layout/breadcrumb');?>

	<div class="container-fluid mt-4">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">

					<div class="row">
						<div class="col-lg-3">
							<div class="card text-white bg-primary">
								<div class="card-header">Jumlah Siswa</div>
								<div class="card-body text-right"><h1><?=$jml_peserta;?></h1></div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="card text-white bg-success">
								<div class="card-header">Jumlah Guru</div>
								<div class="card-body text-right"><h1><?=$jml_guru;?></h1></div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</main>
