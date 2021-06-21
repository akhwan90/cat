<main class="main">
	<!-- Breadcrumb-->
	<?php // $this->load->view('layout/breadcrumb');?>

	<div class="container-fluid mt-4">
		<div class="animated fadeIn">
			<div class="row">
				<!-- KONTEN SEPERTIGA TENGAH -->
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header"><i class="fa fa-envelope"></i> <?=$title;?></div>
						<div class="card-body">
							<?=form_open(base_url('/admin/email/save'), '');?>
							<div class="form-group form-row">
								<div class="col-lg-3">
									<label for="">SMTP Host</label>
									<?=form_input('smtp_host', $email['smtp_host'], 'class="form-control" required');?>
								</div>
								<div class="col-lg-3">
									<label for="">SMTP Port</label>
									<?=form_input('smtp_port', $email['smtp_port'], 'class="form-control" required');?>
								</div>
								<div class="col-lg-3">
									<label for="">SMTP User</label>
									<?=form_input('smtp_user', $email['smtp_user'], 'class="form-control" required');?>
								</div>
								<div class="col-lg-3">
									<label for="">SMTP Password</label>
									<?=form_input('smtp_password', $email['smtp_password'], 'class="form-control" required');?>
								</div>
							</div>
							<div class="form-group form-row">
								<div class="col-lg-6">
									<label for="">Email from</label>
									<?=form_input('email_from', $email['email_from'], 'class="form-control" required');?>
								</div>
								<div class="col-lg-6">
									<label for="">Email from label</label>
									<?=form_input('email_from_label', $email['email_from_label'], 'class="form-control" required');?>
								</div>
							</div>
							<div class="form-group form-row">
								<div class="col-lg-12">
									<label for="">Subject Email</label>
									<?=form_input('email_subject', $email['email_subject'], 'class="form-control" required');?>
								</div>
							</div>
							<div class="form-group">
								<label for="">Format Email</label>
								<?=form_textarea('format_email', $email['format_email'], 'class="form-control" required');?>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Simpan</button>
								<a href="<?=base_url('admin');?>" class="btn btn-secondary">Kembali</a>
							</div>

							<?=form_close();?>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</main>
