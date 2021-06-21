<center><img src="<?=base_url('/aset/ck_blue.png');?>" style="margin-top: 10px; margin-left: 10px; width: 100px"></center>
<div class="container mt-3">
      <div class="row justify-content-center">
		<div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
				<h3 style="text-transform: uppercase; font-size: 20px">
					<span style="color: #03a9f4">Online Selection & Assessment</span>
				</h3>
                <?=form_open(base_url('admin/auth/login'), 'class="form"');?>
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
                  <!-- <div class="g-recaptcha mb-2" data-sitekey="6LcbnpYUAAAAANs_xgs9-MMaiV3uWEYgYeFzMQz2"></div> -->
                  <div class="input-group mb-4">
                    <?=$capcha;?>
                    <?=form_input('captcha', '', 'class="form-control" required placeholder="Ketikkan kode di samping.."');?> 
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <button class="btn btn-primary px-4 col-12" type="submit">Login</button>
                      <a href="<?=base_url();?>" class="btn btn-secondary px-4 col-12 mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                    <!-- <div class="col-6 text-right">
                      <button class="btn btn-link px-0" type="button" onclick="return alert('Perubahan password, sementara baru bisa dilakukan oleh admin instansi. Silakan hubungi Admin Instansi...');">Lupa password?</button>
                    </div> -->
                  </div>
                </form>
              </div>
            </div>
          </div>
          

          <br>
        </div>
      </div>
    </div>
