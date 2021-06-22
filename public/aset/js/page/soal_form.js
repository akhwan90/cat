const jenis = document.getElementById("jenis").value;
const bagian = document.getElementById("bagian").value;
const id_soal = document.getElementById("id_soal").value;

const soal = document.getElementById("soal");
const opsi_text_a = document.getElementById("opsi_text_a");
const opsi_text_b = document.getElementById("opsi_text_b");
const opsi_text_c = document.getElementById("opsi_text_c");
const opsi_text_d = document.getElementById("opsi_text_d");
const opsi_text_e = document.getElementById("opsi_text_e");
const opsi_text_f = document.getElementById("opsi_text_f");

const soal_file = document.getElementById("soal_file");
const opsi_file_a = document.getElementById("opsi_file_a");
const opsi_file_b = document.getElementById("opsi_file_b");
const opsi_file_c = document.getElementById("opsi_file_c");
const opsi_file_d = document.getElementById("opsi_file_d");
const opsi_file_e = document.getElementById("opsi_file_e");
const opsi_file_f = document.getElementById("opsi_file_f");

const soal_preview = document.getElementById("soal_preview");
const opsi_preview_a = document.getElementById("opsi_preview_a");
const opsi_preview_b = document.getElementById("opsi_preview_b");
const opsi_preview_c = document.getElementById("opsi_preview_c");
const opsi_preview_d = document.getElementById("opsi_preview_d");
const opsi_preview_e = document.getElementById("opsi_preview_e");
const opsi_preview_f = document.getElementById("opsi_preview_f");

// console.log(opsi_preview_f);

soal_preview.style.display = 'none';
// opsi_preview_a.style.display = 'none';
// opsi_preview_b.style.display = 'none';
// opsi_preview_c.style.display = 'none';
// opsi_preview_d.style.display = 'none';
// opsi_preview_e.style.display = 'none';
// opsi_preview_f.style.display = 'none';

update_preview(soal_file, '#soal_preview');
if (opsi_file_a !== null) {
	update_preview(opsi_file_a, '#opsi_preview_a');
}
if (opsi_file_b !== null) {
	update_preview(opsi_file_b, '#opsi_preview_b');
}
if (opsi_file_c !== null) {
	update_preview(opsi_file_c, '#opsi_preview_c');
}
if (opsi_file_d !== null) {
	update_preview(opsi_file_d, '#opsi_preview_d');
}
if (opsi_file_e !== null) {
	update_preview(opsi_file_e, '#opsi_preview_e');
}
if (opsi_file_f !== null) {
	update_preview(opsi_file_f, '#opsi_preview_f');
}

soal.addEventListener('paste', e => {
	soal_file.files = e.clipboardData.files;
	update_preview(soal_file, '#soal_preview');
});

if (opsi_text_a !== null) {
	opsi_text_a.addEventListener('paste', e => {
		opsi_file_a.files = e.clipboardData.files;
		update_preview(opsi_file_a, '#opsi_preview_a');
	});
}
if (opsi_text_b !== null) {
	opsi_text_b.addEventListener('paste', e => {
		opsi_file_b.files = e.clipboardData.files;
		update_preview(opsi_file_b, '#opsi_preview_b');
	});
}
if (opsi_text_c !== null) {
	opsi_text_c.addEventListener('paste', e => {
		opsi_file_c.files = e.clipboardData.files;
		update_preview(opsi_file_c, '#opsi_preview_c');
	});
}
if (opsi_text_d !== null) {
	opsi_text_d.addEventListener('paste', e => {
		opsi_file_d.files = e.clipboardData.files;
		update_preview(opsi_file_d, '#opsi_preview_d');
	});
}
if (opsi_text_e !== null) {
	opsi_text_e.addEventListener('paste', e => {
		opsi_file_e.files = e.clipboardData.files;
		update_preview(opsi_file_e, '#opsi_preview_e');
	});
}
if (opsi_text_f !== null) {
	opsi_text_f.addEventListener('paste', e => {
		opsi_file_f.files = e.clipboardData.files;
		update_preview(opsi_file_f, '#opsi_preview_f');
	});
}

soal_file.addEventListener('change', e => {
	update_preview(soal_file, '#soal_preview');
});
if (opsi_file_a !== null) {
	opsi_file_a.addEventListener('change', e => {
		update_preview(opsi_file_a, '#opsi_preview_a');
	});
}
if (opsi_file_b !== null) {
	opsi_file_b.addEventListener('change', e => {
		update_preview(opsi_file_b, '#opsi_preview_b');
	});
}
if (opsi_file_c !== null) {
	opsi_file_c.addEventListener('change', e => {
		update_preview(opsi_file_c, '#opsi_preview_c');
	});
}
if (opsi_file_d !== null) {
	opsi_file_d.addEventListener('change', e => {
		update_preview(opsi_file_d, '#opsi_preview_d');
	});
}
if (opsi_file_e !== null) {
	opsi_file_e.addEventListener('change', e => {
		update_preview(opsi_file_e, '#opsi_preview_e');
	});
}
if (opsi_file_f !== null) {
	opsi_file_f.addEventListener('change', e => {
		update_preview(opsi_file_f, '#opsi_preview_f');
	});
}


function update_preview(nama_file_upload, img_target) {
	let src = $(img_target).attr('src');

	if (nama_file_upload.files && nama_file_upload.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$(img_target).attr('src', e.target.result);
		}
		reader.readAsDataURL(nama_file_upload.files[0]);
		$(img_target).show();
		nama_file_upload.style.display = 'none';
	} else if (src != '#') {
		$(img_target).show();
		nama_file_upload.style.display = 'none';
	} else {
		$(img_target).hide();
		nama_file_upload.style.display = 'block';
	}
}



const form_soal = document.getElementById("form_soal");

form_soal.addEventListener('submit', e => {
	e.preventDefault();
	var data = new FormData(e.target);

    $.ajax({
        type: "POST",
        url: base_url + "/admin/soal/form_soal_save",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $("#tb_simpan").attr("disabled", true);
        	$("#tb_simpan").html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        },
        success: function (r){
            $("#tb_simpan").attr("disabled", false);
            alert(r.message);
        	$("#tb_simpan").html('<i class="fa fa-check"></i> Simpan');

        	if (r.success) {
	        	if (r.setelah_simpan_input_lagi == 0) {
	        		window.open(base_url + "/admin/soal/detil_per_jenis/" + jenis + "/" + bagian, '_self');
	        	} else if (r.setelah_simpan_input_lagi == 1) {
	        		window.open(base_url + "/admin/soal/form_soal/" + jenis + "/" + bagian + '/0', '_self');
	        	}
	        }
        },
        error: function(xhr){
            $("#tb_simpan").attr("disabled", false);
        	$("#tb_simpan").html('<i class="fa fa-check"></i> Simpan');
        }
    });
	
});
