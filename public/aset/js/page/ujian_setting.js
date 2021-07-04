const id_ujian = $("#id_ujian").val();

const uri_modul = base_url + '/admin/ujian/setting/'+id_ujian;


load_soal(id_ujian);

function load_soal(id_ujian) {
	$("#page_title").html("Daftar soal");
	$.ajax({
	    type: "GET",
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/detil_soal",
	    beforeSend: function(){
	    	// $("#page").html('<i class="fa fa-spinner"></i> Memuat ....');
			// $("#frm_vendor_akta input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	let htm = '<table class="table table-bordered table-sm">'+
	    				'<thead><tr>'+
	    				'<th width="10%" class="text-center">No Urut</th>'+
	    				// '<input type="checkbox" name="pilih_semua" id="pilih_semua"></th>'+
	    				'<th width="75%">Soal</th>'+
	    				'<th width="15%">Aksi</th>'+
	    				'</tr></thead><tbody>';
	    	let no = 1;
	    	$.each( r.soal, function( key, val ) {
	    		htm += '<tr><td class="text-center">'+val.urutan+
	    				'</td><td>'+val.soal+'</td>'+
	    				'<td>'+
	    				'<a href="#" onclick="return hapus_detil_soal('+id_ujian+', '+val.id_soal+');" class="btn btn-danger"><i class="fa fa-times"></i> </a>'+
	    				'<div class="btn-group ml-2">'+
	    				'<a href="#" onclick="return up_urutan_soal('+id_ujian+', '+val.id_soal+');" class="btn btn-primary"><i class="fa fa-arrow-up"></i> </a>'+
	    				'<a href="#" onclick="return down_urutan_soal('+id_ujian+', '+val.id_soal+');" class="btn btn-secondary"><i class="fa fa-arrow-down"></i> </a>'+
	    				'</div></tr>';
	    		no++;
	    	});

	    	htm += '</tbody></table>';

	    	$("#page").html(htm);
	    },
	    error: function(xhr) {
			console.log(xhr)
	    }
	});
}

function tambah_dari_bank_soal() {
	$.ajax({
	    type: "GET",
	    url: uri_modul + "/get_soal",
	    beforeSend: function(){
			$("#mdl_tambah_soal_tb_save").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {
	    	$("#mdl_tambah_soal_tb_save").attr("disabled", false);
	    	let htm = '<table class="table table-bordered table-sm">'+
	    				'<thead><tr>'+
	    				'<th width="5%" class="text-center">&nbsp;</th>'+
	    				// '<input type="checkbox" name="pilih_semua" id="pilih_semua"></th>'+
	    				'<th width="95%">Soal</th>'+
	    				'</tr></thead><tbody>';

	    	$.each( r.soal, function( key, val ) {
	    		htm += '<tr><td class="text-center">'+
	    				'<input type="checkbox" id="id_soal_'+val.id+'" name="id_soal[]" value="'+val.id+'">'+
	    				'</td><td><label for="id_soal_'+val.id+'">'+val.soal+'</label></td></tr>';
	    	});

	    	htm += '</tbody></table>';

	    	$("#list_soal").html(htm);
	    },
	    error: function(xhr) {
	    	$("#mdl_tambah_soal_tb_save").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
	$("#mdl_tambah_soal").modal('show');
	return false;
}

function simpan_ujian_soal() {
	let pdata = $("#form_pilih_soal").serialize();
	$.ajax({
	    type: "POST",
	    data: pdata,
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/simpan_ujian_soal",
	    beforeSend: function(){
			$("#form_pilih_soal input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	$("#form_pilih_soal input, select, button").attr("disabled", false);
	        if (r.success == false) {
	            alert(r.message);
	        } else {
	            alert(r.message);
	            load_soal(r.id_ujian);
	            $("#mdl_tambah_soal").modal('hide');
	        }
	    },
	    error: function(xhr) {
	    	$("#form_pilih_soal input, select, button").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
	return false;
}

function hapus_detil_soal(id_ujian, id_soal) {
	if (confirm('Anda yakin..?')) {
		$.ajax({
		    type: "GET",
		    url: base_url + "/admin/ujian/setting/" + id_ujian + "/hapus/" + id_soal,
		    beforeSend: function(){
				// $("#frm_vendor_akta input, select, button").attr("disabled", true);
			},
		    success: function(r, textStatus, jqXHR) {
		    	load_soal(id_ujian);
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});

		return false;
	}
}

function up_urutan_soal(id_ujian, id_soal) {
	$.ajax({
	    type: "GET",
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/up_soal/" + id_soal,
	    beforeSend: function(){
			// $("#frm_vendor_akta input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {
	    	// alert(r.message);
	    	load_soal(id_ujian);
	    },
	    error: function(xhr) {
			console.log(xhr.responseJSON.message)
	    }
	});

	return false;
}

function down_urutan_soal(id_ujian, id_soal) {
	$.ajax({
	    type: "GET",
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/down_soal/" + id_soal,
	    beforeSend: function(){
			// $("#frm_vendor_akta input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {
	    	// alert(r.message);
	    	load_soal(id_ujian);
	    },
	    error: function(xhr) {
			console.log(xhr.responseJSON.message)
	    }
	});

	return false;
}

function load_peserta(id_ujian) {
	$("#page_title").html("Daftar Peserta");
	$.ajax({
	    type: "GET",
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/detil_peserta",
	    beforeSend: function(){
	    	// $("#page").html('<i class="fa fa-spinner"></i> Memuat ....');
			// $("#frm_vendor_akta input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	let htm = '<table class="table table-bordered table-sm">'+
	    				'<thead><tr>'+
	    				'<th width="5%" class="text-center">No</th>'+
	    				// '<input type="checkbox" name="pilih_semua" id="pilih_semua"></th>'+
	    				'<th width="20%">NIM</th>'+
	    				'<th width="70%">Nama</th>'+
	    				'<th width="5%">Hapus</th>'+
	    				'</tr></thead><tbody>';
	    	let no = 1;
	    	$.each( r.peserta, function( key, val ) {
	    		htm += '<tr><td class="text-center">'+no+'</td>'+
	    				'<td>'+val.nim+'</td>'+
	    				'<td>'+val.nama+'</td>'+
	    				'<td><a href="#" onclick="return hapus_detil_peserta('+id_ujian+', '+val.id+');" '+
	    				'class="btn btn-danger"><i class="fa fa-times"></i> </a></tr>';
	    		no++;
	    	});

	    	htm += '</tbody></table>';

	    	$("#page").html(htm);
	    },
	    error: function(xhr) {
			console.log(xhr)
	    }
	});
}

function tambah_peserta() {
	$.ajax({
	    type: "GET",
	    url: uri_modul + "/get_peserta",
	    beforeSend: function(){
			$("#mdl_tambah_peserta_tb_save").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {
	    	$("#mdl_tambah_peserta_tb_save").attr("disabled", false);
	    	let htm = '<table class="table table-bordered table-sm">'+
	    				'<thead><tr>'+
	    				'<th width="5%" class="text-center">&nbsp;</th>'+
	    				// '<input type="checkbox" name="pilih_semua" id="pilih_semua"></th>'+
	    				'<th width="95%">Nama Siswa</th>'+
	    				'</tr></thead><tbody>';

	    	$.each( r.peserta, function( key, val ) {
	    		htm += '<tr><td class="text-center">'+
	    				'<input type="checkbox" id="id_peserta_'+val.id+'" name="id_peserta[]" value="'+val.id+'">'+
	    				'</td><td><label for="id_peserta_'+val.id+'">'+val.nama+'</label></td></tr>';
	    	});

	    	htm += '</tbody></table>';

	    	$("#list_peserta").html(htm);
	    },
	    error: function(xhr) {
	    	$("#mdl_tambah_peserta_tb_save").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
	$("#mdl_tambah_peserta").modal('show');
	return false;
}

function simpan_ujian_peserta() {
	let pdata = $("#form_pilih_peserta").serialize();
	$.ajax({
	    type: "POST",
	    data: pdata,
	    url: base_url + "/admin/ujian/setting/" + id_ujian + "/simpan_ujian_peserta",
	    beforeSend: function(){
			$("#form_pilih_peserta input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	$("#form_pilih_peserta input, select, button").attr("disabled", false);
	        if (r.success == false) {
	            alert(r.message);
	        } else {
	            alert(r.message);
	            load_peserta(r.id_ujian);
	            $("#mdl_tambah_peserta").modal('hide');
	        }
	    },
	    error: function(xhr) {
	    	$("#form_pilih_peserta input, select, button").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
	return false;
}

function hapus_detil_peserta(id_ujian, id_peserta) {
	if (confirm('Anda yakin..?')) {
		$.ajax({
		    type: "GET",
		    url: base_url + "/admin/ujian/setting/" + id_ujian + "/hapus_peserta/" + id_peserta,
		    beforeSend: function(){
				// $("#frm_vendor_akta input, select, button").attr("disabled", true);
			},
		    success: function(r, textStatus, jqXHR) {
		    	load_peserta(id_ujian);
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});

		return false;
	}
}
