const id_ujian = $("#id_ujian").val();

const uri_modul = base_url + '/admin/ujian/setting/'+id_ujian;

$("#pilih_semua").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

function tambah_dari_bank_soal() {
	$.ajax({
	    type: "GET",
	    url: uri_modul + "/get_soal",
	    beforeSend: function(){
			$("#mdl_tambah_soal_tb_save").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {
	    	let htm = '<table class="table table-bordered table-sm">'+
	    				'<thead><tr>'+
	    				'<th width="5%" class="text-center">'+
	    				'<input type="checkbox" name="pilih_semua" id="pilih_semua"></th>'+
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
}
