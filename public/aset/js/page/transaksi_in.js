const uri_modul = base_url + 'index.php/transaksi_in/';

dt();

function dt() {
    pagination("datatabel", uri_modul + "datatabel", [], 50);
}