const uri_modul = base_url + '/admin/soal/';

console.log('tess');
dt();

function dt() {
    // alert('Hallo');
    pagination("datatabel", uri_modul + "datatabel", [], 50);
}

