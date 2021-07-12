<header class="app-header navbar">
	<button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="<?=base_url();?>">
		<!-- <span style="margin-left: 10px; font-weight: bold; font-family: 'Teko', sans-serif; color: #f86c6b; font-size: 27pt">CK</span> -->
		<img width="40" height="30" class="navbar-brand-minimized" src="<?=session('instansi_logo');?>" alt="CK">
		<img width="55" height="44" class="d-inline-block align-top" src="<?=session('instansi_logo');?>" alt="CK">
	</a>
	<button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
		<span class="navbar-toggler-icon"></span>
	</button>
	<ul class="nav navbar-nav d-md-down-none">
		
	</ul>
	<ul class="nav navbar-nav ml-auto">
		<?php 
		if (session('is_login')) {
		?>
		<li class="nav-item d-md-down-none">
			<a class="nav-link" href="#">
				<?=session('username');?>
			</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
				<h2><i class="fa fa-user"></i></h2>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<div class="dropdown-header text-center">
					<strong><?=session('username');?></strong>
				</div>

				<?php 
					echo '
					<a class="dropdown-item" href="'.base_url('admin/ubah_password').'"><i class="fa fa-random"></i> Ubah Password</a>
					<a class="dropdown-item" href="'.base_url('admin/logout').'"><i class="fa fa-lock"></i> Logout</a>
					';
				?>
		  		
			</div>
		</li>
		<?php } else { ?>
		<li class="nav-item d-md-down-none">
			<a class="nav-link" href="<?=base_url('index.php/auth');?>">
				Login
			</a>
		</li>
		<?php } ?>
	</ul>
	<button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
		&nbsp;
	</button>
</header>
