<div class="sidebar">
	<nav class="sidebar-nav ps">
		<ul class="nav">
			<li class="nav-title">Menu Admin</li>

			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/dashboard');?>">
					<i class="nav-icon icon-speedometer"></i> Dashboard
				</a>
			</li>

			<?php 
			if (session('level') == "admin") {
			?>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/siswa');?>">
					<i class="nav-icon fa fa-user"></i> Siswa
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/guru');?>">
					<i class="nav-icon fa fa-briefcase"></i> Guru
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/mapel');?>">
					<i class="nav-icon fa fa-book"></i> Mata Pelajaran
				</a>
			</li>
		<?php } else if (session('level') == "guru") { ?>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/ujian');?>">
					<i class="nav-icon fa fa-edit"></i> Ujian
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/soal');?>">
					<i class="nav-icon fa fa-file"></i> Soal
				</a>
			</li>
		<?php } else if (session('level') == "siswa") { ?>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('peserta/ujian');?>">
					<i class="nav-icon fa fa-edit"></i> Ujian
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('peserta/ujian_history');?>">
					<i class="nav-icon fa fa-file"></i> History Ujian
				</a>
			</li>
		<?php } ?>
		</ul>
	</nav>
	<button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
