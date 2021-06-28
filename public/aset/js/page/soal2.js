const uri_modul = base_url + '/admin/soal/';
const jenis = $("#jenis").val();
const bagian = $("#bagian").val();

function dt() {
    pagination("datatabel", uri_modul + "datatabel/"+jenis+"/"+bagian, [], 50);
}

dt();

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
