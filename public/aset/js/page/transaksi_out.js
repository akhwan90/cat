const uri_modul = base_url + 'index.php/transaksi_out/';

dt();

function dt() {
    pagination("datatabel", uri_modul + "datatabel", [], 50);
}