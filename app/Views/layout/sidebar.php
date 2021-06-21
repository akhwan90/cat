<div class="sidebar">
	<nav class="sidebar-nav ps">
		<ul class="nav">
			<li class="nav-title">Menu Admin</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/dashboard');?>">
					<i class="nav-icon icon-speedometer"></i> Dashboard
				</a>
			</li>
			<!-- <li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/aspek');?>">
					<i class="nav-icon fa fa-th-list"></i> Aspek
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/kompetensi');?>">
					<i class="nav-icon fa fa-th-list"></i> Kompetensi
				</a>
			</li> -->
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/peserta');?>">
					<i class="nav-icon fa fa-users"></i> Peserta
				</a>
			</li>
			<?php 
			if (session('level') == 1) {
			?>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/soal');?>">
					<i class="nav-icon fa fa-edit"></i> Soal
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/ujian');?>">
					<i class="nav-icon fa fa-calendar"></i> Test
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/admin');?>">
					<i class="nav-icon fa fa-users"></i> Admin
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/email');?>">
					<i class="nav-icon fa fa-envelope"></i> Setting Email
				</a>
			</li>
			<li class="nav-item open">
				<a class="nav-link" href="<?=base_url('admin/jenis_ujian');?>">
					<i class="nav-icon fa fa-edit"></i> Jenis Ujian
				</a>
			</li>
			<?php } ?>
		</ul>
	</nav>
	<button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
