// $('.list_soal > .card').not("#kotak_0").hide();
load_detikan();
pilih_soal(0);
load_navigasi();


function load_detikan() {
	let waktu_harus_selesai = $("#sisa_waktu").val();
	let sisa_waktu = new Date().getTime() + (1000 * parseInt(waktu_harus_selesai));

	// console.log(sisa_waktu);

	$('#countdown').countdown(sisa_waktu, function(event) {
		var totalHours = event.offset.totalDays * 24 + event.offset.hours;
		$(this).html(event.strftime(totalHours + ':%M:%S'));
	}).on('finish.countdown', function() {
		// alert('Waktu selesai');
		selesai_bagian(true);
    });
}

function load_navigasi() {
	let jml_soal = $("#jml_soal").val();
	let soal_terisi = $("#kotak_terisi").val();
	let soal_terisi_json = JSON.parse(soal_terisi);


	jml_soal = parseInt(jml_soal);

	let no_soal_not_array = 1;
	let text_nav_soal = '';
	for (let i = 0; i < jml_soal; i++) {
		let background = '#fff';
		if (soal_terisi_json[i]) {
			background = 'green';
		}

		text_nav_soal += '<a href="#" id="navigasi_soal_'+(i+1)+'" onclick="return pilih_soal('+i+');" style="padding: 5px 0px; margin-right: 5px; margin-bottom: 5px; width: 35px; background: '+background+'; color: #000; float: left; display: inline; text-align: center">'+no_soal_not_array+'</a> ';
		no_soal_not_array++;
	}
	$("#nav_soal").html(text_nav_soal);
}

function pilih_soal(id_box) {
	$('.list_soal > .card').show();
	$('.list_soal > .card').not("#kotak_" + id_box).hide();

	let jml_soal = $("#jml_soal").val();
	jml_soal = parseInt(jml_soal);

	id_box = id_box + 1;

	let is_last = false;
	let is_first = false;
	let nomorsoal_next = 0;
	let nomorsoal_prev = 0;
	let next = (id_box + 1);
	let prev = (id_box - 1);

	if (next >= jml_soal) {
		nomorsoal_next = jml_soal;
	} else {
		nomorsoal_next = next;
	}

	if (prev <= 0) {
		nomorsoal_prev = 1;
		nomorsoal_next = 2;
		is_first = true;
	} else {
		nomorsoal_prev = prev;
	}

	$("#tb_prev").attr('data-nomorsoal', nomorsoal_prev);
	$("#tb_next").attr('data-nomorsoal', nomorsoal_next);

	let id_box_new = $("#soal_aktif").val();
	simpan(id_box_new);

	$("#soal_aktif").val(id_box);

	return false;
}

function next() {

	let jml_soal = $("#jml_soal").val();
	let posisi_aktif = $("#soal_aktif").val();

	jml_soal = parseInt(jml_soal);
	posisi_aktif = parseInt(posisi_aktif);

	let next = posisi_aktif + 1;

	if (next > jml_soal) {
		simpan(posisi_aktif);
		selesai_bagian();
	} else {
		pilih_soal(posisi_aktif);
		return false;
	}
}
function prev() {
	let prev_box = $("#tb_prev").attr('data-nomorsoal');
	prev_box = parseInt(prev_box);
	prev_box = (prev_box - 1);

	pilih_soal(prev_box);
	return false;

}

function simpan(id_box) {
	let jenis = $("#jenis").val();
	let bagian = $("#bagian").val();
	let id_ujian = $("#id_ujian").val();
	let is_checkbox = $("#is_checkbox").val();

	let jawaban_new = [];
	let jawaban = [];

	if (is_checkbox == "1") {
		document.getElementsByName('jawaban_'+id_box).forEach(function(chk){
			if(chk.checked){
				jawaban.push(chk.value);
			}
		});
		
	   	jawaban_new = JSON.stringify(jawaban);
	} else {
		let jwbn = $('input[name="jawaban_'+id_box+'"]:checked').val();
	    jawaban.push(jwbn);
	   	jawaban_new = JSON.stringify(jawaban);
	}


	let data = {id_box: id_box, jenis: jenis, bagian: bagian, id_ujian: id_ujian, jawaban: jawaban_new};

	$.ajax({
	    type: "POST",
	    data: data,
	    url: base_url + "/peserta/hitung_hasil/hitung_satu",
	    beforeSend: function(){
	    	$('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 100%; opacity: 0.3; padding: 150px auto">Loading...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {  
	    	$('#loading').html('');
	    	if (jawaban[0] !== undefined) {
		    	$("#navigasi_soal_"+id_box).css('background', 'green');
		    }
	    	// console.log(r.message);
	    },
	    error: function(xhr) {
	    	$('#loading').html('');
	        console.log(xhr)
	    }
	});
	
	return false;
}


function selesai_bagian(is_dari_waktu_habis=false) {
	if (!is_dari_waktu_habis) {
		if (confirm('Anda sudah sampai pada soal terakhir bagian ini. Apakah Anda akan mengakhiri bagian ini..?')) {
			let jenis = $("#jenis").val();
			let bagian = $("#bagian").val();
			let id_ujian = $("#id_ujian").val();
			
			let data = {jenis: jenis, bagian: bagian, id_ujian: id_ujian};

			$.ajax({
			    type: "POST",
			    data: data,
			    url: base_url + "/peserta/hitung_hasil/selesai_bagian",
			    beforeSend: function(){
			    	$('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 100%">Loading...</div>');
			    },
			    success: function(r, textStatus, jqXHR) {  
			    	$('#loading').html('');
			    	// $('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 200px">'+r.message+'</div>');
			    	// console.log(r.message);
			    	// alert(r.message);
			    	if (r.is_selesai_bagian == true) {
				    	if (r.is_selesai_ujian == true) {
					    	// alert('ujian selesai');
					    	selesai_ujian();
					    } else {
					    	window.open(base_url + "/peserta/ikuti_ujian/baca_petunjuk/" + id_ujian + '/' + r.next.jenis + '/' + r.next.bagian, "_self");
				    	}
				    }	 

			    },
			    error: function(xhr) {
			    	$('#loading').html('');
			        console.log(xhr)
			    }
			});
			
			return false;
		}
	} else {
		alert('Waktu mengerjakan ujian sudah habis...!');

		let jenis = $("#jenis").val();
		let bagian = $("#bagian").val();
		let id_ujian = $("#id_ujian").val();
		
		let data = {jenis: jenis, bagian: bagian, id_ujian: id_ujian};

		$.ajax({
		    type: "POST",
		    data: data,
		    url: base_url + "/peserta/hitung_hasil/selesai_bagian",
		    beforeSend: function(){
		    	$('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 100%">Loading...</div>');
		    },
		    success: function(r, textStatus, jqXHR) {  
		    	$('#loading').html('');
		    	// $('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 200px">'+r.message+'</div>');
		    	// console.log(r.message);
		    	// alert(r.message);
		    	if (r.is_selesai_bagian == true) {
			    	if (r.is_selesai_ujian == true) {
				    	// alert('ujian selesai');
				    	selesai_ujian();
				    } else {
				    	window.open(base_url + "/peserta/ikuti_ujian/baca_petunjuk/" + id_ujian + '/' + r.next.jenis + '/' + r.next.bagian, "_self");
			    	}
			    }	 

		    },
		    error: function(xhr) {
		    	$('#loading').html('');
		        console.log(xhr)
		    }
		});
		
		return false;
	}
}


function selesai_ujian() {
	let jenis = $("#jenis").val();
	let bagian = $("#bagian").val();
	let id_ujian = $("#id_ujian").val();
	
	let data = {jenis: jenis, bagian: bagian, id_ujian: id_ujian};

	$.ajax({
	    type: "POST",
	    data: data,
	    url: base_url + "/peserta/hitung_hasil/selesai_ujian",
	    beforeSend: function(){
	    	$('#loading').html('<div style="background: #333; color: #fff; width: 100%; height: 100%">Loading...</div>');
	    },
	    success: function(r, textStatus, jqXHR) {  
	    	$('#loading').html('');
	    	alert(r.message);
		    window.open(base_url + "/peserta", "_self");
	    },
	    error: function(xhr) {
	    	$('#loading').html('');
	        console.log(xhr)
	    }
	});
	
	return false;
}