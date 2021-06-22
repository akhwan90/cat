list_aduan();

function list_aduan(url=base_url + "index.php/front/pengaduan_list") {
	$.ajax({
	    type: "POST",
	    url: url,
	    beforeSend: function() {
			$("#list_aduan").html('<div class="alert alert-info text-center">Loading...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {
	    	let html = '';
			$("#page_aduan").html(r.paginate);
	
			$.each( r.data, function( k, v ) {
				const t = v.create_at.split(/[- :]/);
				const event = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
				const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

				html += '<div class="card mb-2">'+
						'<div class="card-header"><strong>'+v.nama_pengadu+'</strong> <i>pada</i> '+event.toLocaleDateString(undefined, options)+'</div>'+
        				'<div class="card-body">'+v.isi_aduan+'<div class="mt-2"><strong>Jawaban: </strong><br><i>'+v.jawaban+'</i></div></div>'+
        				'</div>';
			});
				
	    	$("#list_aduan").html(html);
	    },
	    error: function(xhr) {
	    	$("#list_aduan").html('<div class="alert alert-danger text-center">'+xhr.responseJSON.message+'</div>');
	    }
	});
	
	// return false;
}

function isi_aduan() {
	$("#mdl_aduan").modal('show');

	return false;
}


function simpan_aduan() {
	let data = $("#mdl_aduan_form").serialize();

	$.ajax({
	    type: "POST",
	    data: data,
	    url: base_url + "index.php/front/pengaduan_save",
	    beforeSend: function() {
			$("#mdl_aduan_btn").attr('disabled', true);
	    },
	    success: function(r, textStatus, jqXHR) {

			$("#mdl_aduan_btn").attr('disabled', false);
			$("#nik_anda").val('');
			$("#nik_diadukan").val('');
			$("#isi_aduan").val('');
			grecaptcha.reset();

			alert(r.message);
			$("#mdl_aduan").modal('hide');
	    },
	    error: function(xhr) {
			$("#mdl_aduan_btn").attr('disabled', false);
	    	console.log(xhr);
	    	grecaptcha.reset();
	    }
	});

	return false;
}

document.addEventListener('click', function(e) {
	if (e.target.classList.contains('page-link')) {
		e.preventDefault();
		const ahref = e.target.getAttribute('href');
		list_aduan(ahref);
	}
});

$("#checkbox_ada_dilaporkan").on('click', function() {
	if (!this.checked) {
		$("#identitas_dilaporkan").hide();
		$("#ada_dilaporkan").val(0);
	} else {
		$("#identitas_dilaporkan").show();
		$("#ada_dilaporkan").val(1);
		$("#nik_diadukan").focus();
	}
});

$("#nik_anda").on('blur', function() {
	let nik = $(this).val();

	$.ajax({
	    type: "GET",
	    url: base_url + "index.php/front/pengaduan_get_nik/" + nik + "/0",
	    beforeSend: function(){
			$("#mdl_aduan_form input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	$("#mdl_aduan_form input, select, button").attr("disabled", false);
	    	$("#nama_anda").val(r.nama);
	    },
	    error: function(xhr) {
	    	$("#mdl_aduan_form input, select, button").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
});

$("#nik_diadukan").on('blur', function() {
	let nik = $(this).val();

	$.ajax({
	    type: "GET",
	    url: base_url + "index.php/front/pengaduan_get_nik/" + nik + "/0",
	    beforeSend: function(){
			$("#mdl_aduan_form input, select, button").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
	    	$("#mdl_aduan_form input, select, button").attr("disabled", false);
	    	$("#nama_diadukan").val(r.nama);
	    	$("#alamat_diadukan").val(r.alamat + ", RT: " + r.no_rt + ", RW: " + r.no_rw);
	    	$("#desa_diadukan").val(r.kel_name);
	    	$("#kecamatan_diadukan").val(r.kec_name);
	    },
	    error: function(xhr) {
	    	$("#mdl_aduan_form input, select, button").attr("disabled", false);
			console.log(xhr)
	    }
	});
	
});