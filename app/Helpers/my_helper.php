<?php 
function tjs($waktu) {
	$ret = false;

	if ($waktu != null) {
		$pc_waktu = explode(" ", $waktu);
		if (!empty($pc_waktu)) {
			$tgl = $pc_waktu[0];
			$pc_tgl = explode("-", $tgl);

			$text = $pc_tgl[2]."-".$pc_tgl[1]."-".$pc_tgl[0];

			if (!empty($pc_waktu[1])) {
				$text .= " ".substr($pc_waktu[1], 0, 5);
			}

			return $text;
		} else {
			return false;
		}
	}
}

function hitung_umur($tanggal_lahir){
	$birthDate = new DateTime($tanggal_lahir);
	$today = new DateTime("today");
	if ($birthDate > $today) { 
	    exit("0 tahun 0 bulan 0 hari");
	}
	$y = $today->diff($birthDate)->y;
	$m = $today->diff($birthDate)->m;
	$d = $today->diff($birthDate)->d;
	return $y." tahun ";
	//return $y." tahun ".$m." bulan ".$d." hari";
}

function konversi_rekomendasi($total_nilai_akhir) {
	$rekomendasi = "";
	if ($total_nilai_akhir < -2) {
		$rekomendasi = "Tidak direkomendasikan";
	} else if ($total_nilai_akhir >= -2) {
		$rekomendasi = "Direkomendasikan";
	}

	return $rekomendasi;
}

function konversi_rekomendasi_non_staff($total_nilai_akhir) {
	$rekomendasi = "";
	if ($total_nilai_akhir < -4) {
		$rekomendasi = false;
	} else if ($total_nilai_akhir >= -4) {
		$rekomendasi = true;
	}

	return $rekomendasi;
}
function konversi_non_staff($nilai) {
	if ($nilai >= 1 && $nilai < 1.801) {
		$ret = 1;
	} else if ($nilai >= 1.801 && $nilai < 2.601) {
		$ret = 2;
	} else if ($nilai >= 2.601 && $nilai < 3.401) {
		$ret = 3;
	} else if ($nilai >= 3.401 && $nilai < 4.201) {
		$ret = 4;
	} else if ($nilai >= 4.201 && $nilai <= 5) {
		$ret = 5;
	} else {
		$ret = 0;
	}

	return $ret;
}
?>
