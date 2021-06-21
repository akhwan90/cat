<center class="mt-5" style=""><img src="<?=base_url('public/aset/depan/img/wallpaper-breakdown-1-01.png');?>" style="margin-top: 15px; margin-left: 10px; width: 100px; z-index: 3000; position:relative;"></center>
<div class="container" style="margin-top: -50px;">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 col-sm-6">
            <div class="card card-body" style="background: #7b7fa2; border: solid 1px #5c6873">
                <!-- <h3 style="text-transform: uppercase; font-size: 20px; color: #fff; font-weight: bold; text-align: center; width: 100%">
                    Online Selection & Assessment<br>
                    CIPTA KRIDATAMA
                </h3> -->

                <?=form_open(base_url('peserta/auth/login'), 'class="form mt-5" style="width: 100%"');?>
                <input type="hidden" name="goto" value="">
                <?=session('error_login');?>
                <div class="form-group">
                    <label for="" class="text-white">Username</label>
                    <?=form_input('usernames', '', 'class="form-control form-control-lg" autofocus required placeholder="Username Anda.." style="border-radius: 0"');?>
                </div>
                <div class="form-group">
                    <label for="" class="text-white">Password</label>
                  <?=form_password('passwords', '', 'class="form-control form-control-lg" required placeholder="Password Anda.." style="border-radius: 0"');?>
                </div>
                <div class="form-group">
                    <label for="" class="text-white">Captcha</label>
                    <div class="input-group mb-4">
                      <?=$capcha;?>
                      <?=form_input('captcha', '', 'class="form-control form-control-lg" required placeholder="Captcha.." style="border-radius: 0"');?> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary px-4 col-12 btn-lg" type="submit" style="border-radius: 0">Login</button>
                        <!--<a href="<?=base_url();?>" class="btn btn-secondary px-4 col-12 mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>-->
                    </div>
                    <!-- <div class="col-6 text-right">
                      <button class="btn btn-link px-0" type="button" onclick="return alert('Perubahan password, sementara baru bisa dilakukan oleh admin instansi. Silakan hubungi Admin Instansi...');">Lupa password?</button>
                    </div> -->
                </div>
                <?=form_close();?>
            </div>
        </div>
    </div>
</div>



<!-- <center><img src="<?=base_url('public/aset/ck.png');?>" style="margin-top: 10px; margin-left: 10px; width: 100px"></center>
<div class="container mt-3">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card-group">
				<div class="card p-4">
					<div class="card-body">
						<h3 style="text-transform: uppercase; font-size: 20px">
							<span style="color: #03a9f4">Online Selection & Assessment System</span>
						</h3>
						<?=form_open(base_url('peserta/auth/login'), 'class="form"');?>
						<input type="hidden" name="goto" value="">
						<p class="text-muted">Login dengan username dan password Anda</p>
						<?=session('error_login');?>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="icon-user"></i>
								</span>
							</div>
							<?=form_input('usernames', '', 'class="form-control" autofocus required placeholder="Username Anda.."');?>
						</div>
						<div class="input-group mb-4">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="icon-lock"></i>
								</span>
							</div>
							<?=form_password('passwords', '', 'class="form-control" required placeholder="Password Anda.."');?>
						</div>
						<!-- <div class="g-recaptcha mb-2" data-sitekey="6LcbnpYUAAAAANs_xgs9-MMaiV3uWEYgYeFzMQz2"></div> 
						<div class="input-group mb-4">
							<?=$capcha;?>
							<?=form_input('captcha', '', 'class="form-control" required placeholder="Ketikkan kode di samping.."');?>	
						</div>
						<div class="row">
							<div class="col-12">
								<button class="btn btn-primary px-4 col-12" type="submit">Login</button>
                      			<!--<a href="<?=base_url();?>" class="btn btn-secondary px-4 col-12 mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>-->
							</div>
                    <!-- <div class="col-6 text-right">
                      <button class="btn btn-link px-0" type="button" onclick="return alert('Perubahan password, sementara baru bisa dilakukan oleh admin instansi. Silakan hubungi Admin Instansi...');">Lupa password?</button>
                  </div> 
              </div>
          </form>
      </div>
  </div>
</div>


<br>
</div>
</div>
</div>
 -->
