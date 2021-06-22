function cari() {
	let nik = $("#nik").val();
	let no_kk = $("#no_kk").val();

	$.ajax({
	    type: "POST",
	    data: {nik: nik, no_kk: no_kk },
	    url: base_url + "index.php/front/cari_bantuan",
	    beforeSend: function() {
			$("#detil_bantuan").html('<div class="alert alert-info text-center">Loading...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {
	    	let b01 = (parseInt(r.b01) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b02 = (parseInt(r.b02) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b03 = (parseInt(r.b03) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b04 = (parseInt(r.b04) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b05 = (parseInt(r.b05) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b06 = (parseInt(r.b06) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b07 = (parseInt(r.b07) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b08 = (parseInt(r.b08) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b09 = (parseInt(r.b09) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b10 = (parseInt(r.b10) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b11 = (parseInt(r.b11) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b12 = (parseInt(r.b12) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b13 = (parseInt(r.b13) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';
	    	let b14 = (parseInt(r.b14) == 1) ? '<h5 class="d-none d-lg-block"><span class="badge badge-success"><i class="icofont-checked"></i> Anda mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-success"><i class="icofont-checked"></i> Ya</span></h5>' : '<h5 class="d-none d-lg-block"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Anda tidak mendapatkan bantuan ini</span></h5><h5 class="d-lg-none"><span class="badge badge-danger"><i class="icofont-minus-circle"></i> Tidak</span></h5>';


	    	let html = '<div class="card"><div class="card-body"><table class="table table-bordered">'+
	    	'<tr><td width="50%">Nama</td><td width="50%">'+r.nama+'</td></tr>'+
	    	'<tr><td>Alamat</td><td>'+r.alamat+'</td></tr>'+
	    	'<tr><td>01. Program Keluarga Harapan (PKH)</td><td>'+b01+'</td></tr>'+
	    	'<tr><td>02. Bantuan Sosial Pangan (Reguler) Kemensos RI</td><td>'+b02+'</td></tr>'+
	    	'<tr><td>03. Bantuan Sosial Pangan (Perluasan) Kemensos RI</td><td>'+b03+'</td></tr>'+
	    	'<tr><td>04. BPNT APBD Kabupaten</td><td>'+b04+'</td></tr>'+
	    	'<tr><td>05. Bantuan Sosial (Bansos) APBD DIY</td><td>'+b05+'</td></tr>'+
	    	'<tr><td>06. Bantuan Langsung Tunai Dana Desa (BLT DD) Kemendes</td><td>'+b06+'</td></tr>'+
	    	'<tr><td>07. Bantuan Langsung Tunai (BLT DAU) Kelurahan Wates</td><td>'+b07+'</td></tr>'+
	    	'<tr><td>08. Bantuan Sosial/Bansos APBD Kabupaten</td><td>'+b08+'</td></tr>'+
	    	'<tr><td>09. Bantuan Sosial Tunai Kemensos RI</td><td>'+b09+'</td></tr>'+
	    	'<tr><td>10. Kartu Prakerja</td><td>'+b10+'</td></tr>'+
	    	'<tr><td>11. Penerima Bantuan Iuran Jaminan Kesehatan APBN/PBI-JK APBN</td><td>'+b11+'</td></tr>'+
	    	'<tr><td>12. Penerima Bantuan Iuran Jaminan Kesehatan APBD/PBI-JK ABPD</td><td>'+b12+'</td></tr>'+
	    	'<tr><td>13. Bantuan Difabel</td><td>'+b13+'</td></tr>'+
	    	'<tr><td>14. Bantuan Orang Dengan Gangguan Jiwa</td><td>'+b14+'</td></tr>'+
	    	'</table></div></div>';

	    	$("#detil_bantuan").html(html);
	    },
	    error: function(xhr) {
	        alert(xhr.responseJSON.message);
	    	$("#detil_bantuan").html('<div class="alert alert-danger text-center">'+xhr.responseJSON.message+'</div>');
	    }
	});
	
	// return false;
}

function pilih_desa() {
	let kecamatan = $("#kecamatan").val();

	$.ajax({
	    type: "POST",
	    data: {kecamatan: kecamatan},
	    url: base_url + "index.php/front/kel_per_kec",
	    beforeSend: function() {
	    	$("#kelurahan").attr('disabled', true);
	    },
	    success: function(r, textStatus, jqXHR) {
	    	let htm = '';

	    	if (r.length > 0) {
		    	$("#kelurahan").attr('disabled', false);


		    	$.each( r, function( key, val ) {
		    		htm += '<option value="'+val.desa+'">'+val.desa+'</option>';
		    	});

		    	$("#kelurahan").html(htm);
		    } else {
		    	$("#kelurahan").html('');
		    }

	    },
	    error: function(xhr) {
	    	$("#kelurahan").attr('disabled', true);
	    }
	});
}

function rekap_desa() {
	let kecamatan = $("#kecamatan").val();
	let kelurahan = $("#kelurahan").val();
	let jenis_bantuan = $("#jenis_bantuan").val();

	$.ajax({
	    type: "POST",
	    data: {kecamatan: kecamatan, kelurahan: kelurahan, jenis_bantuan: jenis_bantuan},
	    url: base_url + "index.php/front/cari_rekap",
	    beforeSend: function() {
	    	$("#table_results").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Sedang mencari ...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {
	    	let htm = '';
	    	if (r.length > 0) {
		    	htm += '<div class="alert alert-info mb-2"><h3>Data ditemukan : '+r.length+'</h3></div>'+
		    	'<table class="table table-bordered">'+
		    	'<thead>'+
		    	'<tr>'+
		    		'<th width="5%">No</th>'+
		    		'<th width="30%">Nama</th>'+
		    		// '<th width="25%">Alamat</th>'+
		    		'<th width="20%">Kelurahan</th>'+
		    		'<th width="20%">Kecamatan</th>'+
		    	'</tr>'+
		    	'</thead><tbody>';

		    	let no = 1;
		    	$.each( r, function( key, val ) {
		    		htm += '<tr><td>'+no+'</td><td>'+val.nama+'</td><td>'+val.desa+'</td><td>'+val.kecamatan+'</td></tr>';
		    		no++;
		    	});

		    	htm += '</tbody></table>';
		    } else {
		    	htm += '<div class="alert alert-danger">Data tidak ditemukan</div>';
		    }

	    	$("#table_results").html(htm);

	    },
	    error: function(xhr) {
	    	// console.log(xhr);
	    	$("#table_results").html('<div class="alert alert-danger">'+xhr.responseJSON.message+'</div>');
	    }
	});
}

function rekap_data_desa() {
	let jenis_bantuan = $("#jenis_bantuan").val();

	$.ajax({
	    type: "POST",
	    data: {jenis_bantuan: jenis_bantuan},
	    url: base_url + "index.php/front/rekap_kalurahan_ajax",
	    beforeSend: function() {
	    	$("#table_results").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Sedang mencari ...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {
	    	let htm = '';
	    	if (r.length > 0) {
		    	htm += 
		    	'<table class="table table-bordered table-sm">'+
		    	'<thead>'+
		    	'<tr>'+
		    		'<th width="5%">No</th>'+
		    		'<th width="60%">Nama</th>'+
		    		// '<th width="25%">Alamat</th>'+
		    		'<th width="35%">Jumlah</th>'+
		    	'</tr>'+
		    	'</thead><tbody>';

		    	let no = 1;
		    	$.each( r, function( key, val ) {
		    		if (val.is_desa) {
			    		htm += '<tr><td></td><td style="padding-left: 50px">'+val.nama_desa+'</td><td class="text-right">'+Number(val.jumlah).toLocaleString()+'</td></tr>';
		    		} else {
		    			htm += '<tr style="font-weight: bold"><td>'+no+'</td><td>'+val.nama_desa+'</td><td class="text-right">'+Number(val.jumlah).toLocaleString()+'</td></tr>';
		    			no++;
		    		}
		    	});

		    	htm += '</tbody></table>';
		    } else {
		    	htm += '<div class="alert alert-danger">Data tidak ditemukan</div>';
		    }

	    	$("#table_results").html(htm);

	    },
	    error: function(xhr) {
	    	// console.log(xhr);
	    	$("#table_results").html('<div class="alert alert-danger">'+xhr+'</div>');
	    }
	});
}
