function pagination(indentifier, url, config, pagelength=10) {
	$('#'+indentifier).DataTable({
		"ordering": false,
		"columnDefs": config,
		"bProcessing": true,
		"serverSide": true,
		"bDestroy" : true,
		"pageLength": pagelength,
		"ajax":{
			url : url,
			type: "post",
			data: function (d) {
				d.filter = $('#filter').val();
				d.urutkan = $('#urutkan').val();
				d.orderby = $('#orderby').val();
				d.kecamatan = $('#kecamatan').val();
				d.desa = $('#desa').val();
			},
			error: function(xhr){  // error handling code
				console.log("gagal");
			}
		}
	});
}
