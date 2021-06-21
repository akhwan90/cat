<main class="main">
	<div id="loading"></div>
	<div class="mt-3 ml-3 mr-3 mb-3">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><?=$title;?></div>
						<div class="card-body">
							<?=$jenis_bagian_petunjuk;?>
							<a href="<?=base_url('peserta/ikuti_ujian/ok_tes/'.$id_ujian.'/'.$jenis.'/'.$bagian);?>" class="btn btn-danger btn-lg mt-4" onclick="return confirm('Anda yakin..?');"><i class="fa fa-play"></i> MULAI UJIAN</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>