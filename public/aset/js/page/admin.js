const uri_modul = base_url + '/admin/admin/';

function dt() {
    pagination("datatabel", uri_modul + "datatabel", [], 50);
}

dt();

const form_peserta = document.getElementById("mdl_edit_form");
form_peserta.addEventListener('submit', e => {
	e.preventDefault();
	var data = new FormData(e.target);

    $.ajax({
        type: "POST",
        url: uri_modul + "simpan",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $("#mdl_edit_tb_save").attr("disabled", true);
        	$("#mdl_edit_tb_save").html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        },
        success: function (r){
            $("#mdl_edit_tb_save").attr("disabled", false);
        	$("#mdl_edit_tb_save").html('<i class="fa fa-check"></i> Simpan');
            alert(r.message);
            if (r.success) {
	        	dt();
				$("#mdl_edit").modal('hide');
			}
        },
        error: function(xhr){
            $("#mdl_edit_tb_save").attr("disabled", false);
        	$("#mdl_edit_tb_save").html('<i class="fa fa-check"></i> Simpan');
        }
    });
	
});


/*
function simpan() {
	let data = $("#mdl_edit_form").serialize();
	$.ajax({
	    type: "POST",
	    data: data,
	    url: uri_modul + "simpan",
	    beforeSend: function(){
			$("#mdl_edit_form input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	$("#mdl_edit_form input, select, button").attr("disabled", false);
	        if (r.success == false) {
	            alert(r.message);
	        } else {
				$("#mdl_edit").modal('hide');
				dt();
	        }
	    },
	    error: function(xhr) {
			$("#mdl_edit_form input, select, button").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
	return false;
}
*/

function edit(id) {
	id = parseInt(id);
	if (id < 1) {
		$("#_id").val(0);
		$("#_mode").val('add');
		$("#username").val('');
		$("#password").val('');
		$("#nama").val('');
		$("#level").val('');
	} else {
		$.ajax({
		    type: "POST",
		    data: {id: id},
		    url: uri_modul + "detil",
		    beforeSend: function(){
				$("#mdl_edit_form input, select, button").attr("disabled", true);
			},
		    success: function(r, textStatus, jqXHR) {	
		    	$("#mdl_edit_form input, select, button").attr("disabled", false);
		        if (r.success == false) {
		            alert(r.message);
		        } else {
					$("#_id").val(r.results.id);
					$("#_mode").val('edit');
					$("#username").val(r.results.username);
					$("#password").val('');
					$("#nama").val(r.results.nama);
					$("#level").val(r.results.level);
		        }
		    },
		    error: function(xhr) {
				$("#mdl_edit_form input, select, button").attr("disabled", true);
				console.log(xhr)
		    }
		});
	}

	$("#mdl_edit").modal('show');
	return false;
}

function kirim_email(id, email) {
	$("#mdl_kirim_email_id").val(id);
	$("#alamat_email").val(email);
	$("#mdl_kirim_email").modal('show');
	$('#mdl_kirim_email').on('shown.bs.modal', function () {
	    $('#alamat_email').focus();
	});
	return false;
}

const form_kirim_email = document.getElementById("mdl_kirim_email_form");
form_kirim_email.addEventListener('submit', e => {
	e.preventDefault();
	var data = new FormData(e.target);

    $.ajax({
        type: "POST",
        url: uri_modul + "kirim_email",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $("#mdl_kirim_email_tb_save").attr("disabled", true);
        	$("#mdl_kirim_email_tb_save").html('<i class="fa fa-spinner fa-spin"></i> Mengirimkan email...');
        },
        success: function (r){
            $("#mdl_kirim_email_tb_save").attr("disabled", false);
        	$("#mdl_kirim_email_tb_save").html('<i class="fa fa-check"></i> Kirim');
            alert(r.message);
            if (r.success) {
				$("#mdl_kirim_email").modal('hide');
			}
        },
        error: function(xhr){
            $("#mdl_kirim_email_tb_save").attr("disabled", false);
        	$("#mdl_kirim_email_tb_save").html('<i class="fa fa-check"></i> Kirim');
        }
    });
	
});


function hapus(id) {
	if (confirm('Yakin akan dihapus..?')) {
		$.ajax({
		    type: "POST",
		    data: {id: id},
		    url: uri_modul + "hapus",
		    success: function(r, textStatus, jqXHR) {	
		        if (r.success == false) {
		            alert(r.message);
		        } else {
		            alert(r.message);
					dt();
		        }
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});
	}

	return false;
}

function aktifkan_user() {
	if (confirm('Akan mengaktifkan user yang belum aktif..?')) {
		$.ajax({
		    type: "GET",
		    url: uri_modul + "aktifkan_user",
		    success: function(r, textStatus, jqXHR) {	
		        if (r.success == false) {
		            alert(r.message);
		        } else {
		            alert(r.message);
					dt();
		        }
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});
	}

	return false;
}

function reset(id) {
	if (confirm('Akan mereset password username ini..? Password baru = username..')) {
		$.ajax({
		    type: "GET",
		    url: uri_modul + "reset_password/"+id,
		    success: function(r, textStatus, jqXHR) {	
		        if (r.success == false) {
		            alert(r.message);
		        } else {
		            alert(r.message);
					dt();
		        }
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});
	}

	return false;
}

