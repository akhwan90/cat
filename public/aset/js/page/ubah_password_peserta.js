const form_edit_password_admin_peserta = document.getElementById("f_ubah_password_peserta");
form_edit_password_admin_peserta.addEventListener('submit', e => {
	e.preventDefault();
	var data = new FormData(e.target);

    $.ajax({
        type: "POST",
        url: base_url + "/peserta/ubah_password",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $("#tb_save").attr("disabled", true);
        	$("#tb_save").html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        },
        success: function (r){
            $("#tb_save").attr("disabled", false);
        	$("#tb_save").html('<i class="fa fa-check"></i> Simpan');
            alert(r.message);
        },
        error: function(xhr){
            $("#tb_save").attr("disabled", false);
        	$("#tb_save").html('<i class="fa fa-check"></i> Simpan');
        }
    });
	
});