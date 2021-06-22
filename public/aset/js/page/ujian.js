const uri_modul = base_url + '/admin/ujian/';

function dt() {
    pagination("datatabel", uri_modul + "datatabel", [], 50);
}

dt();

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


function edit(id) {
	id = parseInt(id);
	if (id < 1) {
		$("#_id").val(0);
		$("#_mode").val('add');
		$("#nama").val('');
		$("#waktu_mulai_tgl").val('');
		$("#waktu_mulai_jam").val('');
		$("#waktu_selesai_tgl").val('');
		$("#waktu_selesai_jam").val('');
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
					$("#nama").val(r.results.nama);
					$("#waktu_mulai_tgl").val(r.results.waktu_mulai_tgl);
					$("#waktu_mulai_jam").val(r.results.waktu_mulai_jam);
					$("#waktu_selesai_tgl").val(r.results.waktu_selesai_tgl);
					$("#waktu_selesai_jam").val(r.results.waktu_selesai_jam);
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


// KIRIM EMAIL
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
        url: base_url + '/admin/peserta/kirim_email',
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