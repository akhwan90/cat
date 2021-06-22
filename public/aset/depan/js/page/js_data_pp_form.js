function hitung(id){
    let h1 = $(".pp_h1_"+id).val();
    let tambah = $(".tambah_"+id).val();
    let kurang = $(".kurang_"+id).val();
    let pp = $(".pp_"+id).val();

    var sum = 0;
    h1 = parseInt(h1);
    tambah = parseInt(tambah);
    kurang = parseInt(kurang);
    
    let salo = (h1+tambah)-kurang;
    
    
    $(".pp_"+id).val(salo);
}