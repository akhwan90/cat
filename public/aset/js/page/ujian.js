const uri_modul = base_url + '/admin/ujian/';

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


function edit(id) {
	id = parseInt(id);
	if (id < 1) {
		$("#_id").val(0);
		$("#_mode").val('add');
		$("#nama").val('');
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
					$("#nama").val(r.results.nama_ujian);
					$("#id_mapel").val(r.results.id_mapel);
					$("#jumlah_soal").val(r.results.jumlah_soal);
					$("#waktu").val(r.results.waktu);
					$("#jenis").val(r.results.jenis);
					$("#tgl_mulai").val(r.results.tgl_mulai);
					$("#terlambat").val(r.results.terlambat);
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

function refresh_token(id) {
	id = parseInt(id);
	$.ajax({
	    type: "GET",
	    url: uri_modul + "refresh_token/"+id,
	    success: function(r, textStatus, jqXHR) {	
	        if (r.success == false) {
	            alert(r.message);
	        } else {
	        	dt();
	        }
	    },
	    error: function(xhr) {
			console.log(xhr)
	    }
	});

	return false;
}
