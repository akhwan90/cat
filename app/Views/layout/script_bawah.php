<div class="modal fade" id="ModalSerbaguna" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ModalSerbaguna_teks_title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="ModalSerbaguna_teks_isi">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- CoreUI and necessary plugins-->
<script src="<?=base_url();?>/aset/vendors/popper.js/js/popper.min.js"></script>
<script src="<?=base_url();?>/aset/vendors/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>/aset/vendors/pace-progress/js/pace.min.js"></script>
<script src="<?=base_url();?>/aset/vendors/perfect-scrollbar/js/perfect-scrollbar.min.js"></script>
<script src="<?=base_url();?>/aset/vendors/@coreui/coreui/js/coreui.min.js"></script>
<!-- Plugins and scripts required by this view-->
<script src="<?=base_url();?>/aset/vendors/chart.js/js/Chart.min.js"></script>
<script src="<?=base_url();?>/aset/vendors/@coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips.min.js"></script>

<!-- Alert -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<!-- Datatable -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

<script type="text/javascript">
	base_url = "<?=base_url();?>";
</script>

<script src="<?=base_url();?>/aset/js/jquery.countdown.min.js"></script>
<script src="<?=base_url();?>/aset/js/page/base.js"></script>

<?php
if (isset($enable_editor) && $enable_editor) {
	echo '<script src="'.base_url().'/aset/editor/ckeditor.js"></script>';
}
?>

<?php
if (isset($js)) {
	if (!is_array($js)) {
		if (!empty($js)) {
			if (is_file('./aset/js/page/' . $js . ".js")) {
				echo '<script src="' . base_url('aset/js/page/' . $js . ".js?v=" . time()) . '"></script>';
			}
		}
	} else {
		if (!empty($js)) {
			foreach ($js as $javascript) {
				if (is_file('./aset/js/page/' . $javascript . ".js")) {
					echo '<script src="' . base_url('aset/js/page/' . $javascript . ".js?v=" . time()) . '"></script>';
				}
			}
		}
	}
}
?>
