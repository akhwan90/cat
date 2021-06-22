const uri_modul = base_url + 'index.php/transaksi_in/';
const id_transaksi = parseInt($("#_id").val());

dt(id_transaksi);

function dt(id_transaksi) {
    pagination("datatabel", uri_modul + "datatabel_detil/"+id_transaksi, [], 50);
    $.ajax({
        type: "GET",
        url: uri_modul + "total_transaksi/"+id_transaksi,
        success: function(r, textStatus, jqXHR) {	
        	$("#total_nominal_transaksi").val(r.total_transaksi);
        },
        error: function(xhr) {
    		console.log(xhr)
        }
    });
    
}

$('#jumlah, #harga').keyup(function(event) {
	if(event.which >= 37 && event.which <= 40) return;
	$(this).val(function(index, value) {
		return value
		.replace(/\D/g, "")
		.replace(/\B(?=(\d{3})+(?!\d))/g, ".")
		;
	});
});



function get_stock() {
  let id_barang = $("#id_barang").val();
  $.ajax({
    type: "GET",
    url: uri_modul + "detil_stok_barang/" + id_barang,
    beforeSend: function(){
      // $("#frm_vendor_akta input, select, button").attr("disabled", true);
    },
    success: function(r, textStatus, jqXHR) {  
      // $("#frm_vendor_akta input, select, button").attr("disabled", false);
      if (r.success == false) {
        alert(r.message);
      } else {
        $("#harga").val(r.harga);
        $("#stock").val(r.stok);
      }
    },
    error: function(xhr) {
      console.log(xhr)
    }
  });
  
}

function simpan_transaksi() {
	let data = $("#form_transaksi_in").serialize();
	$.ajax({
	    type: "POST",
	    data: data,
	    url: uri_modul + "simpan_transaksi",
	    beforeSend: function(){
			$("#catatan").attr("disabled", true);
			$("#btn_transaksi").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
			$("#catatan").attr("disabled", false);
			$("#btn_transaksi").attr("disabled", false);

	        if (r.success == false) {
	            alert(r.message);
	        } else {
	        	if (r.mode == "add") {
	        		window.open(uri_modul + "edit/"+r.id_trans, '_self');
	        	} 
	        }
	    },
	    error: function(xhr) {
			$("#catatan").attr("disabled", false);
			$("#btn_transaksi").attr("disabled", false);

			console.log(xhr)
	    }
	});
	
	return false;
}

function simpan_detil() {
	let data = $("#form_transaksi_in_detil").serialize();
	$.ajax({
	    type: "POST",
	    data: data,
	    url: uri_modul + "simpan_detil",
	    beforeSend: function(){
			$("#jumlah").attr("disabled", true);
			$("#harga").attr("disabled", true);
			$("#btn_transaksi_detil").attr("disabled", true);
		},
	    success: function(r, textStatus, jqXHR) {	
			$("#jumlah").attr("disabled", false);
			$("#harga").attr("disabled", false);
			$("#btn_transaksi_detil").attr("disabled", false);

	        if (r.success == false) {
	            alert(r.message);
	        } else {
				$("#id_barang").val("");
				$("#jumlah").val("");
				$("#harga").val("");
				dt(id_transaksi);
	        }
	    },
	    error: function(xhr) {
			$("#jumlah").attr("disabled", false);
			$("#harga").attr("disabled", false);
			$("#btn_transaksi_detil").attr("disabled", false);

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
		$("#satuan").val('');
		$("#stok").val('');
		$("#harga_beli").val('');
		$("#harga_jual").val('');
	} else {
		$.ajax({
		    type: "POST",
		    data: {id: id},
		    url: uri_modul + "detil/",
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
					$("#satuan").val(r.results.satuan);
					$("#stok").val(r.results.stok);
					$("#harga_beli").val(r.results.harga_beli);
					$("#harga_jual").val(r.results.harga_jual);
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

function hapus(id_transaksi, id_barang) {
	if (confirm('Yakin akan dihapus..?')) {
		$.ajax({
		    type: "POST",
		    data: {id_transaksi: id_transaksi, id_barang: id_barang},
		    url: uri_modul + "hapus",
		    success: function(r, textStatus, jqXHR) {	
		        if (r.success == false) {
		            alert(r.message);
		        } else {
		            alert(r.message);
					dt(id_transaksi);
		        }
		    },
		    error: function(xhr) {
				console.log(xhr)
		    }
		});
	}

	return false;
}
