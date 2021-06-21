<header class="app-header navbar">
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
	<span class="navbar-toggler-icon"></span>
</button>
<a class="navbar-brand" href="<?=base_url();?>">
	<span style="margin-left: 10px; font-weight: bold; font-family: 'Teko', sans-serif; color: #f86c6b; font-size: 27pt">POS</span>
	<img class="navbar-brand-minimized" src="<?=base_url();?>public/aset/img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
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
		<img class="img-avatar" src="<?=base_url();?>public/aset/img/avatars/9.png" alt="admin@bootstrapmaster.com">
	</a>
	<div class="dropdown-menu dropdown-menu-right">
		<div class="dropdown-header text-center">
		  <strong><?=session('nama');?></strong>
	  </div>

	  <a class="dropdown-item" href="<?=base_url('index.php/auth/profile');?>">
		  <i class="fa fa-user"></i> Profile</a>
		  <a class="dropdown-item" href="<?=base_url('auth/logout');?>">
			  <i class="fa fa-lock"></i> Logout</a>
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
