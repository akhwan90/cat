<center class="mt-3" style="">

    <div class="justify-content-center">
        <div class="col-md-6 col-lg-4 col-sm-12">
		<h4 style="color: white; text-transform: uppercase; padding: 3px 0">Aplikasi CAT</h4>
		<h3 style="color: red; text-transform: uppercase; padding: 3px 0; margin-top: -10px"><?=$instansi_nama;?></h3>

		<img src="<?=$instansi_logo;?>" style="margin-top: 10px; margin-left: 10px; width: 100px; z-index: 3000; position:relative; width: 100px; height: 100px;" class="img-thumbnail">
	</div>

</center>
<div class="container" style="margin-top: -50px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 col-sm-12">
            <div class="card card-body bg-secondary">
                <?=form_open(base_url('admin/auth/login'), 'class="form mt-4" style="width: 100%; z-index: 2500"');?>
                <input type="hidden" name="goto" value="">
                <p class="mt-2"><?=session('error_login');?></p>
                <div class="form-group">
                    <label for="" class="text-dark">Username</label>
                    <?=form_input('usernames', '', 'class="form-control form-control-lg" autofocus required placeholder="Username Anda.."');?>
                </div>
                <div class="form-group">
                    <label for="" class="text-dark">Password</label>
                  <?=form_password('passwords', '', 'class="form-control form-control-lg" required placeholder="Password Anda.."');?>
                </div>
                <div class="form-group">
                    <label for="" class="text-dark">Captcha</label>
                    <div class="input-group mb-4">
                      <?=$capcha;?>
                      <?=form_input('captcha', '', 'class="form-control form-control-lg" required placeholder="Captcha.."');?> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-success px-4 col-12 btn-lg text-dark" type="submit">Login</button>
                    </div>
                </div>
                <?=form_close();?>
            </div>
            <center class="mb-2"><a class="text-white" href="https://nur-akhwan.blogspot.com" target="_blank">&copy; Copyright: Nur Akhwan</a></center>
        </div>
    </div>
</div>
