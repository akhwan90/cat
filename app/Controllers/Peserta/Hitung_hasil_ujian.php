<?php 
namespace App\Controllers\Peserta;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Hitung_hasil_ujian extends BaseController {
	
	public function hitung_satu() {
		$p = $this->request->getPost();
		$id_ujian = $p['id_ujian'];
		$id_peserta = session('peserta_id');
		$jenis = $p['jenis'];
		$bagian = $p['bagian'];
		$id_box = $p['id_box'];
		$jawaban = json_decode($p['jawaban'], true);

		$db1 = $this->db->table('m_ujian_peserta');
		$db1->where('id_ujian', $id_ujian);
		$db1->where('id_peserta', $id_peserta);
		$db1->where('jenis', $jenis);
		$db1->where('bagian', $bagian);
		$db1->select('detil');
		$get_detil_jawaban = $db1->get()->getRowArray();

		// get detil jawaban per id_ujian, id_peserta, jenis, bagian, konvert ke array
		$detil_jawaban_to_array = json_decode($get_detil_jawaban['detil'], true);
		// ambil index sesuai yg dibawa oleh parameter
		$detil_jawaban_per_soal = $detil_jawaban_to_array[$id_box];
		// ambil kunci
		$kunci_jawaban = $detil_jawaban_per_soal['kunci'];

		// init var awal
		$success = false;
		$message = "Jawaban salah";

		if ($jenis == "E" && $bagian == 1) {
			$success = true;
			$jawaban_fix = $jawaban[0];
			$status = 0;

			if ($kunci_jawaban == "F") {
				if ($jawaban_fix == "a") {
					$status = 1;
				} else if ($jawaban_fix == "b") {
					$status = 2;
				} else if ($jawaban_fix == "c") {
					$status = 3;
				} else if ($jawaban_fix == "d") {
					$status = 4;
				} else if ($jawaban_fix == "e") {
					$status = 5;
				}
			} else if ($kunci_jawaban == "U") {
				if ($jawaban_fix == "a") {
					$status = 5;
				} else if ($jawaban_fix == "b") {
					$status = 4;
				} else if ($jawaban_fix == "c") {
					$status = 3;
				} else if ($jawaban_fix == "d") {
					$status = 2;
				} else if ($jawaban_fix == "e") {
					$status = 1;
				}
			}

			$message = "Nilai: ".$status;
			
			$new_kunci_jawaban = $detil_jawaban_per_soal;
			$new_kunci_jawaban['jawaban'] = $jawaban_fix;
			$new_kunci_jawaban['status'] = $status;


			// replace dari detil jawaban lama ke new
			$detil_jawaban_to_array[$id_box] = $new_kunci_jawaban;

			// update db, hanya field detil saja..
			$update = $this->db->table('m_ujian_peserta');
			$update->where('id_ujian', $id_ujian);
			$update->where('id_peserta', $id_peserta);
			$update->where('jenis', $jenis);
			$update->where('bagian', $bagian);
			$update->update([
				'detil'=>json_encode($detil_jawaban_to_array),
				'last_activity'=>date('Y-m-d H:i:s')
			]);
		} else if ($jenis == "B" && $bagian == 2) {
			// cek jawaban sama dengan kunci tidak
			if (in_array($jawaban[0], $kunci_jawaban)) {
				$success = true;
				$message = "Jawaban benar";
			} 

			// buat variabel baru, untuk update db
			// copy isinya dari detil per soal
			$new_kunci_jawaban = $detil_jawaban_per_soal;
			$new_kunci_jawaban['jawaban'] = $jawaban;
			$new_kunci_jawaban['status'] = 0;
			if ($success) {
				$new_kunci_jawaban['status'] = 1;
			}

			// replace dari detil jawaban lama ke new
			$detil_jawaban_to_array[$id_box] = $new_kunci_jawaban;

			// update db, hanya field detil saja..
			$update = $this->db->table('m_ujian_peserta');
			$update->where('id_ujian', $id_ujian);
			$update->where('id_peserta', $id_peserta);
			$update->where('jenis', $jenis);
			$update->where('bagian', $bagian);
			$update->update([
				'detil'=>json_encode($detil_jawaban_to_array),
				'last_activity'=>date('Y-m-d H:i:s')
			]);
		} else {
			// cek jawaban sama dengan kunci tidak
			if (empty(array_diff($kunci_jawaban, $jawaban))) {
				$success = true;
				$message = "Jawaban benar";
			}

			// buat variabel baru, untuk update db
			// copy isinya dari detil per soal
			$new_kunci_jawaban = $detil_jawaban_per_soal;
			$new_kunci_jawaban['jawaban'] = $jawaban;
			$new_kunci_jawaban['status'] = 0;
			if ($success) {
				$new_kunci_jawaban['status'] = 1;
			}

			// replace dari detil jawaban lama ke new
			$detil_jawaban_to_array[$id_box] = $new_kunci_jawaban;

			// update db, hanya field detil saja..
			$update = $this->db->table('m_ujian_peserta');
			$update->where('id_ujian', $id_ujian);
			$update->where('id_peserta', $id_peserta);
			$update->where('jenis', $jenis);
			$update->where('bagian', $bagian);
			$update->update([
				'detil'=>json_encode($detil_jawaban_to_array),
				'last_activity'=>date('Y-m-d H:i:s')
			]);
		}

		log_message('error', 'Success: '.$success.', message: '.$message);

		$ret = [
        	'success'=>$success,
        	'message'=>$message,
        	'jenis'=>$jenis,
        	'bagian'=>$bagian,
        	'add'=>0,
		];
		return $this->response->setJSON($ret);
	}

	public function selesai_from_url($id_ujian, $id_peserta) {
		$return = "";
		
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$get_peserta = $peserta->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];

		$jenis_tes_peserta = $jenis_tes."-".$jenis_staff;
		$jenis_staff = $get_peserta['level_test'];

		if ($jenis_tes_peserta == "1-1") {
			// jika jenis tes a
			// Seleksi Non Staff
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_c = $this->hitung_c($id_ujian, $id_peserta);
			$hitung_akhir_seleksi_non_staff = $this->hitung_akhir_seleksi_non_staff($id_ujian, $id_peserta);

			$return .= "Hitung A: ".$hitung_a;
			$return .= "Hitung B: ".$hitung_b;
			$return .= "Hitung C: ".$hitung_c;
			$return .= $hitung_akhir_seleksi_non_staff;

		} else if ($jenis_tes_peserta == "1-2") {
			// jika jenis tes a
			// Seleksi Staff
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_c = $this->hitung_c($id_ujian, $id_peserta);
			$hitung_d = $this->hitung_d($id_ujian, $id_peserta);
			$hitung_kompetensi = $this->hitung_kompetensi($id_ujian, $id_peserta, 1, $jenis_staff);

			$return .= "Hitung A: ".$hitung_a;
			$return .= "Hitung B: ".$hitung_b;
			$return .= "Hitung C: ".$hitung_c;
			$return .= "Hitung D: ".$hitung_d;
			$return .= "Hitung Kompetensi: ".$hitung_kompetensi;
		} else if ($jenis_tes_peserta == "2-0") {
			// jika jenis tes a
			// Assesment
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_e = $this->hitung_e($id_ujian, $id_peserta);
			$hitung_kompetensi = $this->hitung_kompetensi($id_ujian, $id_peserta, 2, $jenis_staff);

			$return .= "Hitung A: ".$hitung_a;
			$return .= "Hitung B: ".$hitung_b;
			$return .= "Hitung E: ".$hitung_e;
			$return .= "Hitung Kompetensi: ".$hitung_kompetensi;
		} 

		// $return = 'Ujian telah diselesaikan, dan tersimpan..';

		$success = true;
		$message = $return;

		$ret = [
        	'success'=>$success,
        	'message'=>$message,
        	'add'=>$jenis_tes_peserta,
		];
		return $this->response->setJSON($ret);
	}

	public function selesai_ujian() {
		$p = $this->request->getPost();
		$id_ujian = $p['id_ujian'];
		$id_peserta = session('peserta_id');
		$jenis = $p['jenis'];
		$bagian = $p['bagian'];

		$return = "";

		// get detil peserta 
		
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$get_peserta = $peserta->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];

		$jenis_tes_peserta = $jenis_tes."-".$jenis_staff;
		$jenis_staff = $get_peserta['level_test'];

		if ($jenis_tes_peserta == "1-1") {
			// jika jenis tes a
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_c = $this->hitung_c($id_ujian, $id_peserta);
			$hitung_akhir_seleksi_non_staff = $this->hitung_akhir_seleksi_non_staff($id_ujian, $id_peserta);

			$return .= $hitung_a;
			$return .= $hitung_b;
			$return .= $hitung_c;
			$return .= $hitung_akhir_seleksi_non_staff;
		} else if ($jenis_tes_peserta == "1-2") {
			// jika jenis tes a
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_c = $this->hitung_c($id_ujian, $id_peserta);
			$hitung_d = $this->hitung_d($id_ujian, $id_peserta);
			$hitung_kompetensi = $this->hitung_kompetensi($id_ujian, $id_peserta, 1, $jenis_staff);

			// $return .= $hitung_a;
			// $return .= $hitung_b;
			// $return .= $hitung_c;
			// $return .= $hitung_d;
			$return .= $hitung_kompetensi;
		} else if ($jenis_tes_peserta == "2-0") {
			// jika jenis tes a
			$hitung_a = $this->hitung_a($id_ujian, $id_peserta);
			$hitung_b = $this->hitung_b($id_ujian, $id_peserta);
			$hitung_e = $this->hitung_e($id_ujian, $id_peserta);
			$hitung_kompetensi = $this->hitung_kompetensi($id_ujian, $id_peserta, 2, $jenis_staff);

			// $return .= $hitung_a;
			// $return .= $hitung_b;
			// $return .= $hitung_e;
			$return .= $hitung_kompetensi;
		} 

		// update selesai ujian
		$update_selesai = $this->db->table('hasil_rekomendasi');
		$update_selesai->where('id_ujian', $id_ujian);
		$update_selesai->where('id_peserta', $id_peserta);
		$do_update_selesai = $update_selesai->update([
			'is_selesai'=>1,
		]);

		// end update selesai ujian
		// 

		$return = 'Ujian telah diselesaikan, dan tersimpan..';

		$success = true;
		$message = $return;

		$ret = [
        	'success'=>$success,
        	'message'=>$message,
        	'add'=>$jenis_tes_peserta,
		];
		return $this->response->setJSON($ret);
	}

	public function selesai_bagian() {
		$p = $this->request->getPost();
		$id_ujian = $p['id_ujian'];
		$id_peserta = session('peserta_id');
		$jenis = $p['jenis'];
		$bagian = $p['bagian'];

		// get detil ujian peserta
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$get_peserta = $peserta->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];

		$jenis_tes_peserta = $jenis_tes."-".$jenis_staff;

		if ($jenis_tes_peserta == "1-1") {
			$list_ujian = ['A1','A2','A3','A4','B1','B2','C1'];
		} else if ($jenis_tes_peserta == "1-2") {
			$list_ujian = ['A1','A2','A3','A4','B1','B2','C1','D1'];
		} else if ($jenis_tes_peserta == "2-0") {
			$list_ujian = ['A1','A2','A3','A4','B1','B2','E1'];
		} 

		$index_sekarang = array_search($jenis.$bagian, $list_ujian);
		$index_terakhir = array_search(end($list_ujian), $list_ujian);


		if ($index_sekarang == $index_terakhir) {
			$next_bagian = $list_ujian[$index_sekarang];
			$is_selesai_ujian = true;
		} else {
			$next_bagian = $list_ujian[($index_sekarang + 1)];
			$is_selesai_ujian = false;
		}

		$message = "";

		if ($jenis == 'A') {
			$hitung = $this->hitung_a($id_ujian, $id_peserta);
			$message = $hitung;
		} else if ($jenis == 'B') {
			$hitung = $this->hitung_b($id_ujian, $id_peserta);
			$message = $hitung;
		} else if ($jenis == 'C') {
			$hitung = $this->hitung_c($id_ujian, $id_peserta);
			$message = $hitung;
		} else if ($jenis == 'D') {
			$hitung = $this->hitung_d($id_ujian, $id_peserta);
			$message = $hitung;
		} else if ($jenis == 'E') {
			$hitung = $this->hitung_e($id_ujian, $id_peserta);
			$message = $hitung;
		} 

		// update selesai bagian
		$update_selesai = $this->db->table('m_ujian_peserta');
		$update_selesai->where('id_ujian', $id_ujian);
		$update_selesai->where('id_peserta', $id_peserta);
		$update_selesai->where('jenis', $jenis);
		$update_selesai->where('bagian', $bagian);
		$do_update_selesai = $update_selesai->update([
			'is_selesai_mengerjakan'=>1,
		]);

		// end update selesai bagian

		$balik = [
			'next'=>[
				'jenis'=>substr($next_bagian, 0, 1),
				'bagian'=>substr($next_bagian, 1, 1),
			],
			'is_selesai_bagian'=>true,
			'is_selesai_ujian'=>$is_selesai_ujian,
			'message'=>$message
		];

		return $this->response->setJSON($balik);
	}

	public function hitung_a($id_ujian, $id_peserta) {

		$jawaban_a = $this->db->table('m_ujian_peserta');
		$jawaban_a->where('id_ujian', $id_ujian);
		$jawaban_a->where('id_peserta', $id_peserta);
		$jawaban_a->where('jenis', 'A');
		$jawaban_a->select('jenis, bagian, detil');
		$get_jawaban_a = $jawaban_a->get()->getResultArray();

		$jawaban_per_bagian = [];

		foreach ($get_jawaban_a as $gja) {
			$bagian = $gja['bagian'];
			$jawaban_per_bagian[$bagian] = json_decode($gja['detil'], true);
		}


		$hasil = ['1'=>0,'2'=>0,'3'=>0,'4'=>0];
		foreach ($jawaban_per_bagian as $bagian_k => $bagian_v) {
			foreach ($bagian_v as $jawaban_k => $jawaban_v) {
				$hasil[$bagian_k] = $hasil[$bagian_k] + $jawaban_v['status'];
			}
		}
		
		$int_score = 0;
		$nilai_a_new = [];

		foreach ($hasil as $hk => $hv) {
			$int_score += $hv;
			$nilai = $this->aspek_jenis_a[$hk]['konversi'][$hv];
			$nilai_a_new[$hk] = $nilai;

		}
		$nilai_5 = $this->aspek_jenis_a[5]['konversi'][$int_score];
		$nilai_a_new[5] = $nilai_5;


		$insert = 0;
		$update = 0;

		foreach ($nilai_a_new as $nbk => $nbv) {
			$cek_nilai_b = $this->db->table('m_nilai_a');
			$cek_nilai_b->where('id_ujian', $id_ujian);
			$cek_nilai_b->where('id_peserta', $id_peserta);
			$cek_nilai_b->where('id_aspek', $nbk);
			$cek_nilai_b->select('id');
			$get_nilai_b = $cek_nilai_b->countAllResults();

			if ($get_nilai_b < 1) {
				$builder = $this->db->table('m_nilai_a');
				$queri = $builder->insert([
					'id_ujian'=>$id_ujian,
					'id_peserta'=>$id_peserta,
					'id_aspek'=>$nbk,
					'nilai'=>$nbv
				]);
				$insert++;
			} else {
				$builder = $this->db->table('m_nilai_a');
				$builder->where('id_ujian', $id_ujian);
				$builder->where('id_peserta', $id_peserta);
				$builder->where('id_aspek', $nbk);
				$queri = $builder->update([
					'nilai'=>$nbv
				]);
				$update++;
			}
		}

		return "Insert: ".$insert.", update: ".$update;
		// return $ret_text."<br>Tipe queri intelegence: ".$tipe_queri;
	}	

	public function hitung_b($id_ujian, $id_peserta) {

		$jawaban_b = $this->db->table('m_ujian_peserta');
		$jawaban_b->where('id_ujian', $id_ujian);
		$jawaban_b->where('id_peserta', $id_peserta);
		$jawaban_b->where('jenis', 'B');
		$jawaban_b->select('jenis, bagian, detil');
		$get_jawaban_b = $jawaban_b->get()->getResultArray();

		$jawaban_per_bagian = [];

		foreach ($get_jawaban_b as $gjb) {
			$bagian = $gjb['bagian'];
			$jawaban_per_bagian[$bagian] = json_decode($gjb['detil'], true);
		}


		$hasil = [];
		$no = 1;

		$konversi = $this->aspek_jenis_b;

		$nilai1 = [];
		$nomor_jawaban = [];
		foreach ($konversi[1] as $kk => $kv) {
			foreach ($kv['nomor_soal'] as $so) {
				if (empty($nilai1[$kk])) {
					$nilai1[$kk] = $jawaban_per_bagian[1][$so]['status'];
					$nomor_jawaban[$kk][$so] = $jawaban_per_bagian[1][$so]['status'];
				} else {
					$nilai1[$kk] += $jawaban_per_bagian[1][$so]['status'];
					$nomor_jawaban[$kk][$so] = $jawaban_per_bagian[1][$so]['status'];
				}
			}
		}

		$nilai2 = [];
		$nomor_jawaban2 = [];
		foreach ($konversi[2] as $kk => $kv) {
			if(intval($kk) != 29) {
				foreach ($kv['nomor_soal'] as $so) {
					if (empty($nilai2[$kk])) {
						$nilai2[$kk] = $jawaban_per_bagian[2][$so]['status'];
						$nomor_jawaban2[$kk][$so] = $jawaban_per_bagian[2][$so]['status'];
					} else {
						$nilai2[$kk] += $jawaban_per_bagian[2][$so]['status'];
						$nomor_jawaban2[$kk][$so] = $jawaban_per_bagian[2][$so]['status'];
					}
				}
			}
		}


		$nilai1_new = [];
		foreach ($nilai1 as $n1k => $n1v) {
			$nilai1_new[$n1k] = round((($n1v * (4/9)) + 1), 3);
		}

		$nilai2_new = [];
		$nilai_ins = 0;
		foreach ($nilai2 as $n2k => $n2v) {
			$nilai2_new[$n2k] = ($n2v * (4/18)) + 1;
			$nilai_ins += $n2v;
		}


		$nilai_ins_new = ($nilai_ins * (4/54)) + 1;
		$nilai2_new['29'] = $nilai_ins_new;

		$nilai_b_merge = $nilai1_new + $nilai2_new;

		$insert = 0;
		$update = 0;

		foreach ($nilai_b_merge as $nbk => $nbv) {
			$cek_nilai_b = $this->db->table('m_nilai_b');
			$cek_nilai_b->where('id_ujian', $id_ujian);
			$cek_nilai_b->where('id_peserta', $id_peserta);
			$cek_nilai_b->where('id_aspek', $nbk);
			$cek_nilai_b->select('id');
			$get_nilai_b = $cek_nilai_b->countAllResults();

			if ($get_nilai_b < 1) {
				$builder = $this->db->table('m_nilai_b');
				$queri = $builder->insert([
					'id_ujian'=>$id_ujian,
					'id_peserta'=>$id_peserta,
					'id_aspek'=>$nbk,
					'nilai'=>$nbv
				]);
				$insert++;
			} else {
				$builder = $this->db->table('m_nilai_b');
				$builder->where('id_ujian', $id_ujian);
				$builder->where('id_peserta', $id_peserta);
				$builder->where('id_aspek', $nbk);
				$queri = $builder->update([
					'nilai'=>$nbv
				]);
				$update++;
			}
		}

		return "Insert: ".$insert.", update: ".$update;
	}

	public function hitung_c($id_ujian, $id_peserta) {
		$jawaban_c = $this->db->table('m_ujian_peserta');
		$jawaban_c->where('id_ujian', $id_ujian);
		$jawaban_c->where('id_peserta', $id_peserta);
		$jawaban_c->where('jenis', 'C');
		$jawaban_c->where('bagian', 1);
		$jawaban_c->select('detil');
		$get_jawaban_c = $jawaban_c->get()->getRowArray();
		$get_jawaban_c_detil = json_decode($get_jawaban_c['detil'], true);

		$nilai_per_aspek = [];
		$no = 1;

		$konversi = $this->aspek_jenis_c;

		foreach ($konversi as $kk => $kv) {
			foreach ($kv['nomor_soal'] as $so) {
				if (empty($nilai_per_aspek[$kk])) {
					$nilai_per_aspek[$kk] = $get_jawaban_c_detil[$so]['status'];
				} else {
					$nilai_per_aspek[$kk] += $get_jawaban_c_detil[$so]['status'];
				}
			}
		}

		$nilai_new = [];
		$jenis_queri = [];
		$jml_queri = 0;
		$insert = 0;
		$update = 0;
		foreach ($nilai_per_aspek as $n1k => $n1v) {
			$nilai = $n1v;
			$nilai_konversi = $this->konversi_1_nilai_c($n1v);
			$nilai_new[$n1k] = $nilai_konversi;

			// cek sudah ada 
			$cek_sudah_ada_nilai = $this->db->table('m_nilai_c');
			$cek_sudah_ada_nilai->where('id_ujian', $id_ujian);
			$cek_sudah_ada_nilai->where('id_peserta', $id_peserta);
			$cek_sudah_ada_nilai->where('id_aspek', $n1k);
			$cek_sudah_ada_nilai->select('nilai');
			$get_cek_sudah_ada_nilai = $cek_sudah_ada_nilai->countAllResults();

			if ($get_cek_sudah_ada_nilai < 1) {
				$simpan = $this->db->table('m_nilai_c');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->insert([
					'id_ujian'=>$id_ujian,
					'id_peserta'=>$id_peserta,
					'id_aspek'=>$n1k,
					'nilai'=>$nilai,
					'nilai_konversi'=>$nilai_konversi,
				]);
				$jenis_queri[$n1k] = "insert";
				$insert++;
			} else {
				$simpan = $this->db->table('m_nilai_c');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->update(['nilai'=>$nilai, 'nilai_konversi'=>$nilai_konversi]);
				$jenis_queri[$n1k] = "update";
				$update++;
			}

			if ($queri) {
				$jml_queri++;
			}
		}

		return "Insert: ".$insert.", update: ".$update;
	}

	public function hitung_d($id_ujian, $id_peserta) {
		$jawaban_d = $this->db->table('m_ujian_peserta');
		$jawaban_d->where('id_ujian', $id_ujian);
		$jawaban_d->where('id_peserta', $id_peserta);
		$jawaban_d->where('jenis', 'D');
		$jawaban_d->where('bagian', 1);
		$jawaban_d->select('detil');
		$get_jawaban_d = $jawaban_d->get()->getRowArray();
		$get_jawaban_d_detil = json_decode($get_jawaban_d['detil'], true);

		$nilai_per_aspek = [];
		$no = 1;

		$konversi = $this->aspek_jenis_d;

		foreach ($konversi as $kk => $kv) {
			foreach ($kv['nomor_soal'] as $so) {
				if (empty($nilai_per_aspek[$kk])) {
					$nilai_per_aspek[$kk] = $get_jawaban_d_detil[$so]['status'];
				} else {
					$nilai_per_aspek[$kk] += $get_jawaban_d_detil[$so]['status'];
				}
			}
		}

		$nilai_new = [];
		$jenis_queri = [];
		$jml_queri = 0;
		$insert = 0;
		$update = 0;
		foreach ($nilai_per_aspek as $n1k => $n1v) {
			$nilai = $n1v;
			$nilai_konversi = $this->konversi_1_nilai_c($n1v);
			$nilai_new[$n1k] = $nilai_konversi;

			// cek sudah ada 
			$cek_sudah_ada_nilai = $this->db->table('m_nilai_d');
			$cek_sudah_ada_nilai->where('id_ujian', $id_ujian);
			$cek_sudah_ada_nilai->where('id_peserta', $id_peserta);
			$cek_sudah_ada_nilai->where('id_aspek', $n1k);
			$cek_sudah_ada_nilai->select('nilai');
			$get_cek_sudah_ada_nilai = $cek_sudah_ada_nilai->countAllResults();

			if ($get_cek_sudah_ada_nilai < 1) {
				$simpan = $this->db->table('m_nilai_d');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->insert([
					'id_ujian'=>$id_ujian,
					'id_peserta'=>$id_peserta,
					'id_aspek'=>$n1k,
					'nilai'=>$nilai,
					'nilai_konversi'=>$nilai_konversi,
				]);
				$jenis_queri[$n1k] = "insert";
				$insert++;
			} else {
				$simpan = $this->db->table('m_nilai_d');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->update(['nilai'=>$nilai, 'nilai_konversi'=>$nilai_konversi]);
				$jenis_queri[$n1k] = "update";
				$update++;
			}

			if ($queri) {
				$jml_queri++;
			}
		}

		return "Insert: ".$insert.", update: ".$update;
	}

	public function hitung_e($id_ujian, $id_peserta) {
		$jawaban_d = $this->db->table('m_ujian_peserta');
		$jawaban_d->where('id_ujian', $id_ujian);
		$jawaban_d->where('id_peserta', $id_peserta);
		$jawaban_d->where('jenis', 'E');
		$jawaban_d->where('bagian', 1);
		$jawaban_d->select('detil');
		$get_jawaban_d = $jawaban_d->get()->getRowArray();
		$get_jawaban_d_detil = json_decode($get_jawaban_d['detil'], true);

		$nilai_per_aspek = [];
		$no = 1;

		$konversi = $this->aspek_jenis_e;

		foreach ($konversi as $kk => $kv) {
			foreach ($kv['nomor_soal'] as $so) {
				if (empty($nilai_per_aspek[$kk])) {
					$nilai_per_aspek[$kk] = $get_jawaban_d_detil[$so]['status'];
				} else {
					$nilai_per_aspek[$kk] += $get_jawaban_d_detil[$so]['status'];
				}
			}
		}


		$nilai_new = [];
		$jenis_queri = [];
		$jml_queri = 0;
		$insert = 0;
		$update = 0;

		// $tampung_dulu = [];

		foreach ($nilai_per_aspek as $n1k => $n1v) {
			$nilai = $n1v;
			$nilai_persepuluh = ((($n1v - 10)/10)*2.5);
			$nilai_konversi = $this->konversi_1_nilai_e($n1k, $nilai_persepuluh);

			$to_nilai_new = [
				// 'aspek'=>$n1k,
				// 'n1v'=>$n1v,
				'nilai'=>$nilai_persepuluh,
				'nilai_konversi'=>$nilai_konversi,
				'nilai_asli'=>$nilai
			];
			$nilai_new = $to_nilai_new;

			// $tampung_dulu[] = $to_nilai_new;

			// cek sudah ada 
			$cek_sudah_ada_nilai = $this->db->table('m_nilai_e');
			$cek_sudah_ada_nilai->where('id_ujian', $id_ujian);
			$cek_sudah_ada_nilai->where('id_peserta', $id_peserta);
			$cek_sudah_ada_nilai->where('id_aspek', $n1k);
			$cek_sudah_ada_nilai->select('nilai');
			$get_cek_sudah_ada_nilai = $cek_sudah_ada_nilai->countAllResults();

			if ($get_cek_sudah_ada_nilai < 1) {
				$simpan = $this->db->table('m_nilai_e');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->insert([
					'id_ujian'=>$id_ujian,
					'id_peserta'=>$id_peserta,
					'id_aspek'=>$n1k,
					'nilai'=>$nilai_persepuluh,
					'nilai_konversi'=>$nilai_konversi,
					'nilai_asli'=>$nilai,
				]);
				$jenis_queri[$n1k] = "insert";
				$insert++;
			} else {
				$simpan = $this->db->table('m_nilai_e');
		        $simpan->where('id_ujian', $id_ujian);
		        $simpan->where('id_peserta', $id_peserta);
				$simpan->where('id_aspek', $n1k);
				$queri = $simpan->update([
					'nilai'=>$nilai_persepuluh, 
					'nilai_konversi'=>$nilai_konversi,
					'nilai_asli'=>$nilai,
				]);
				$jenis_queri[$n1k] = "update";
				$update++;
			}

			if ($queri) {
				$jml_queri++;
			}
		}

		// echo json_encode($tampung_dulu);
		// exit;

		// return json_encode($nilai_new);

		return "Insert: ".$insert.", update: ".$update;
	}

	public function hitung_akhir_seleksi_non_staff($id_ujian, $id_peserta) {
		// get detil peserta 
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$peserta->select('*');
		$get_peserta = $peserta->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];
		$level_test = $get_peserta['level_test'];

		$jenis_peserta = $this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test];

		// ke nilai
		$list_hasil_a_b = [];
		$list_hasil_c = [];

		$hasil_ujian_a = $this->db->table('m_nilai_a');
		$hasil_ujian_a->where('id_ujian', $id_ujian);
		$hasil_ujian_a->where('id_peserta', $id_peserta);
		$hasil_ujian_a->select('*');
		$get_hasil_ujian_a = $hasil_ujian_a->get()->getResultArray();

		$hasil_ujian_b = $this->db->table('m_nilai_b');
		$hasil_ujian_b->where('id_ujian', $id_ujian);
		$hasil_ujian_b->where('id_peserta', $id_peserta);
		$hasil_ujian_b->select('*');
		$get_hasil_ujian_b = $hasil_ujian_b->get()->getResultArray();

		$hasil_ujian_c = $this->db->table('m_nilai_c');
		$hasil_ujian_c->where('id_ujian', $id_ujian);
		$hasil_ujian_c->where('id_peserta', $id_peserta);
		$hasil_ujian_c->select('*');
		$get_hasil_ujian_c = $hasil_ujian_c->get()->getResultArray();

		if (!empty($get_hasil_ujian_a)) {
			foreach ($get_hasil_ujian_a as $ua_k => $ua_v) {
				$idx_aspek = $ua_v['id_aspek'];
				$nilai = floatval($ua_v['nilai']);

				$list_hasil_a_b[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>konversi_non_staff($nilai),
					'detil_aspek'=>$this->aspek_jenis_a[$idx_aspek],
				];
			}
		}

		if (!empty($get_hasil_ujian_b)) {
			foreach ($get_hasil_ujian_b as $ub_k => $ub_v) {
				$idx_aspek = $ub_v['id_aspek'];
				$nilai = floatval($ub_v['nilai']);

				if (empty($this->aspek_jenis_b[1][$idx_aspek])) {
					$detil_aspek = $this->aspek_jenis_b[2][$idx_aspek];
				} else {
					$detil_aspek = $this->aspek_jenis_b[1][$idx_aspek];
				}

				$list_hasil_a_b[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>konversi_non_staff($nilai),
					'detil_aspek'=>$detil_aspek
				];
			}
		}

		if (!empty($get_hasil_ujian_c)) {
			foreach ($get_hasil_ujian_c as $ub_k => $ub_v) {
				$idx_aspek = $ub_v['id_aspek'];
				$nilai = floatval($ub_v['nilai']);
				$nilai_konversi = floatval($ub_v['nilai_konversi']);

				$detil_aspek = $this->aspek_jenis_c[$idx_aspek];

				$list_hasil_c[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>$nilai_konversi,
					'detil_aspek'=>$detil_aspek
				];
			}
		}

		$konversi_keputusan_non_staff = $this->konversi_keputusan_non_staff;

		$jml_nilai_konversi_keputusan = 0;
		$jml_nilai_kritikal_samadengan_2 = 0;
		$jml_nilai_umum_samadengan_2 = 0;
		$jml_nilai_skor_1 = 0;
		foreach ($jenis_peserta['critical'] as $cv) {
			$nilai_konversi = empty($list_hasil_a_b[$cv]['nilai_konversi']) ? 0 : $list_hasil_a_b[$cv]['nilai_konversi'];
			$nilai_konversi_keputusan = empty($konversi_keputusan_non_staff['critical'][$nilai_konversi]) ? 0 : $konversi_keputusan_non_staff['critical'][$nilai_konversi];
			$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;
			if ($nilai_konversi == 2) {
				$jml_nilai_kritikal_samadengan_2++;
			}
			if ($nilai_konversi == 1) {
				$jml_nilai_skor_1++;
			}
		}

		foreach ($jenis_peserta['umum'] as $cv) {
			$nilai_konversi = $list_hasil_a_b[$cv]['nilai_konversi'];
			$nilai_konversi_keputusan = $konversi_keputusan_non_staff['umum'][$nilai_konversi];
			$jml_nilai_konversi_keputusan += $nilai_konversi_keputusan;
			if ($nilai_konversi == 2) {
				$jml_nilai_umum_samadengan_2++;
			}
			if ($nilai_konversi == 1) {
				$jml_nilai_skor_1++;
			}
		}

		// DEBUG
		// echo 'Kritikal = 2 => '.$jml_nilai_kritikal_samadengan_2."<br>";
		// echo 'Umum = 2 => '.$jml_nilai_umum_samadengan_2."<br>";
		// echo 'Skor 1 => '.$jml_nilai_skor_1."<br>";
		// exit;

		// SYARAT SELEKSI NON STAFF direkomendasikan jika 
		// <= 2 nilai kritikal = 2
		// <= 6 nilai umum = 2
		// < 1 nilai skor = 1
		if ($jml_nilai_kritikal_samadengan_2 <= 2 
			&& $jml_nilai_umum_samadengan_2 <= 6
			&& $jml_nilai_skor_1 < 1) {
			$keputusan_akhir = 1;
			$keterangan_rekomendasi = "Direkomendasikan";
		} else {
			$keputusan_akhir = 0;
			$keterangan_rekomendasi = "Tidak Direkomendasikan";
		}

		// echo "Rekomendasi : ".$keterangan_rekomendasi;
		// exit;

		$total_nilai_keputusan = $jml_nilai_konversi_keputusan;

		// pensiun per januari 2021
		// $keputusan = konversi_rekomendasi_non_staff($jml_nilai_konversi_keputusan);
		// $keterangan_rekomendasi = "Tidak Direkomendasikan";
		// $keputusan_akhir = 0;

		// if ($keputusan) {
		// 	$keterangan_rekomendasi = "Direkomendasikan";
		// 	$keputusan_akhir = 1;
		// }

		// update ke tabel
		$get_cek_rekomendasi = $this->db->table('hasil_rekomendasi')
								->where('id_ujian', $id_ujian)
								->where('id_peserta', $id_peserta)
								->select('id')
								->get()->getRowArray();

		$p_save_rekomendasi = [
			'rekomendasi'=>$keputusan_akhir,
			'nilai'=>$total_nilai_keputusan,
			'notes'=>$keterangan_rekomendasi
		];

		if (empty($get_cek_rekomendasi)) {
			$p_save_rekomendasi['id_peserta'] = $id_peserta;
			$p_save_rekomendasi['id_ujian'] = $id_ujian;

			$builder = $this->db->table('hasil_rekomendasi');
			$queri = $builder->insert($p_save_rekomendasi);
		} else {
            $builder = $this->db->table('hasil_rekomendasi');
            $builder->where('id_ujian', $id_ujian);
            $builder->where('id_peserta', $id_peserta);
			$queri = $builder->update($p_save_rekomendasi);
		}

		return true;
	}

	public function hitung_kompetensi($id_ujian, $id_peserta, $jenis_tes, $jenis_staff) {
		if ($jenis_tes == 1) {
			$setting_competensi = $this->setting_kompetensi_seleksi_staff;
		} else if ($jenis_tes == 2) {
			$setting_competensi = $this->setting_kompetensi_assesment;
		}

		$db_nilai_a = $this->db->table('m_nilai_a');
		$db_nilai_a->where('id_ujian', $id_ujian);
		$db_nilai_a->where('id_peserta', $id_peserta);
		$db_nilai_a->select('*');
		$get_nilai_a = $db_nilai_a->get()->getResultArray();

		$db_nilai_b = $this->db->table('m_nilai_b');
		$db_nilai_b->where('id_ujian', $id_ujian);
		$db_nilai_b->where('id_peserta', $id_peserta);
		$db_nilai_b->select('*');
		$get_nilai_b = $db_nilai_b->get()->getResultArray();

		$nilai_gabung_a_b = [];
		if (!empty($get_nilai_a)) {
			foreach ($get_nilai_a as $na) {
				$idx = $na['id_aspek'];
				$nilai_gabung_a_b[$idx] = $na['nilai'];
			}
		}
		if (!empty($get_nilai_b)) {
			foreach ($get_nilai_b as $nb) {
				$idx = $nb['id_aspek'];
				$nilai_gabung_a_b[$idx] = $nb['nilai'];
			}
		}

		
		$nilai_kompetensi = [];
		$debug_message = '';
		foreach ($setting_competensi as $sc_x => $sc_v) {
			if (empty($nilai_kompetensi[$sc_x])) {
				$nilai_kompetensi[$sc_x] = 0;
			}
			foreach ($sc_v['persentase'] as $aspek_k => $aspek_v) {
				// kali
				$nilai_aspek_db = $nilai_gabung_a_b[$aspek_k];
				$nilai_porsi = floatval($nilai_aspek_db) * $aspek_v;
				$nilai_kompetensi[$sc_x] += $nilai_porsi; 

			}
		}

		// $nilai_kompetensi_new = [];
		$insert = 0;
		$update = 0;

		$teks_queri = '';
		$total_nilai_keputusan = 0;

		if ($jenis_tes == 1) {
			$penentuan_critical_atau_bukan = $this->sistem_seleksi[1]['sub'][2]['level'][$jenis_staff];
		} else if ($jenis_tes == 2) {
			$penentuan_critical_atau_bukan = $this->sistem_seleksi[2]['sub'][0]['level'][$jenis_staff];
		}

		// echo json_encode($penentuan_critical_atau_bukan);
		// exit;

		// $tampung_dulu = []; // untuk debug


		$jml_nilai_kritikal_samadengan_2 = 0;
		$jml_nilai_umum_samadengan_2 = 0;
		$jml_nilai_skor_1 = 0;

		foreach ($nilai_kompetensi as $nk_k => $nk_v) {
			$nilai_konversi_keputusan = 0;
			$nilai_pembulatan = 0;

			if ($jenis_tes == 1) {
				$nilai_pembulatan = $this->pembulatan_tes_seleksi_staff($nk_v);
				if (in_array($nk_k, $penentuan_critical_atau_bukan['critical'])) {
					$nilai_konversi_keputusan = $this->konversi_pembulatan_tes_seleksi_staff($nilai_pembulatan, 'critical');

					if ($nilai_pembulatan == 2) {
						$jml_nilai_kritikal_samadengan_2++;
					}
				}

				if (in_array($nk_k, $penentuan_critical_atau_bukan['umum'])) {
					$nilai_konversi_keputusan = $this->konversi_pembulatan_tes_seleksi_staff($nilai_pembulatan, 'umum');
					
					if ($nilai_pembulatan == 2) {
						$jml_nilai_umum_samadengan_2++;
					}
				}
					
				if ($nilai_pembulatan == 1) {
					$jml_nilai_skor_1++;
				}

			} else if ($jenis_tes == 2) {
				$nilai_pembulatan = $this->pembulatan_tes_assesment($nk_v);
				if (in_array($nk_k, $penentuan_critical_atau_bukan['critical'])) {
					$nilai_konversi_keputusan = $this->konversi_pembulatan_tes_assesment($nilai_pembulatan, 'critical');
				}

				if (in_array($nk_k, $penentuan_critical_atau_bukan['umum'])) {
					$nilai_konversi_keputusan = $this->konversi_pembulatan_tes_assesment($nilai_pembulatan, 'umum');
				}
			}

			// cek sudah ada 
			$db_cek_nk = $this->db->table('nilai_kompetensi');
			$db_cek_nk->where('id_ujian', $id_ujian);
			$db_cek_nk->where('id_peserta', $id_peserta);
			$db_cek_nk->where('kompetensi', $nk_k);
			$db_cek_nk->where('jenis_tes', $jenis_tes);
			$db_cek_nk->select('id');
			$get_nk = $db_cek_nk->get()->getRowArray();

			$p_save_kompetensi = [
				// 'kompetensi'=>$nk_k, // untuk debug
				'nilai'=>$nk_v,
				'nilai_pembulatan'=>$nilai_pembulatan,
				'nilai_konversi_keputusan'=>$nilai_konversi_keputusan
			];

			// $tampung_dulu[] = $p_save_kompetensi; // untuk debug

			// simpan ke total nilai nilai keputusan
			$total_nilai_keputusan += $nilai_konversi_keputusan;

			$teks_queri .= json_encode($p_save_kompetensi)."<br>";

			if (empty($get_nk)) {
				$p_save_kompetensi['id_peserta'] = $id_peserta;
				$p_save_kompetensi['id_ujian'] = $id_ujian;
				$p_save_kompetensi['kompetensi'] = $nk_k;
				$p_save_kompetensi['jenis_tes'] = $jenis_tes;

				$builder = $this->db->table('nilai_kompetensi');
				$queri = $builder->insert($p_save_kompetensi);
				$insert++;
			} else {
	            $builder = $this->db->table('nilai_kompetensi');
	            $builder->where('id_ujian', $id_ujian);
	            $builder->where('id_peserta', $id_peserta);
	            $builder->where('kompetensi', $nk_k);
				$builder->where('jenis_tes', $jenis_tes);
				$queri = $builder->update($p_save_kompetensi);
				$update++;
			}
		}

		// konversi hasil akhir keputusan MULAI
		// todo : konvert ke fungsi
		$keputusan_akhir = 0;
		$keterangan_rekomendasi = '';

		if ($jenis_tes == 1) {
			// DEBUG
			// echo 'Kritikal = 2 => '.$jml_nilai_kritikal_samadengan_2."<br>";
			// echo 'Umum = 2 => '.$jml_nilai_umum_samadengan_2."<br>";
			// echo 'Skor 1 => '.$jml_nilai_skor_1."<br>";
			// exit;

			// SYARAT SELEKSI STAFF direkomendasikan jika 
			// <= 1 nilai kritikal = 2
			// <= 3 nilai umum = 2
			// < 1 nilai skor = 1
			if ($jml_nilai_kritikal_samadengan_2 <= 1 
				&& $jml_nilai_umum_samadengan_2 <= 3
				&& $jml_nilai_skor_1 < 1) {
				$keputusan_akhir = 1;
				$keterangan_rekomendasi = "Direkomendasikan";
			} else {
				$keputusan_akhir = 2;
				$keterangan_rekomendasi = "Tidak Direkomendasikan";
			}

			// debug
			// echo "Rekomendasi: ".$keterangan_rekomendasi;
			// exit;

			//  per januari 2021, gak dipakai
			/*
			if ($total_nilai_keputusan >= -2) {
				$keputusan_akhir = 1;
				$keterangan_rekomendasi = "Direkomendasikan";
			} else { 
				$keputusan_akhir = 2;
				$keterangan_rekomendasi = "Tidak Direkomendasikan";
			} 
			*/
		} else if ($jenis_tes == 2) {
			if ($total_nilai_keputusan >= -1) {
				$keputusan_akhir = 1;
				$keterangan_rekomendasi = "Ready";
			} else if ($total_nilai_keputusan < -1 && $total_nilai_keputusan >= -6) { 
				$keputusan_akhir = 2;
				$keterangan_rekomendasi = "Need Development";
			} else if ($total_nilai_keputusan < -6) { 
				$keputusan_akhir = 3;
				$keterangan_rekomendasi = "Not Ready";
			} 
		}

		// INPUT TABLE NILAI HASIL AKHIR
		// cek sudah ada 
		$db_cek_rekomendasi = $this->db->table('hasil_rekomendasi');
		$db_cek_rekomendasi->where('id_ujian', $id_ujian);
		$db_cek_rekomendasi->where('id_peserta', $id_peserta);
		$db_cek_rekomendasi->select('id');
		$get_cek_rekomendasi = $db_cek_rekomendasi->get()->getRowArray();

		$p_save_rekomendasi = [
			'rekomendasi'=>$keputusan_akhir,
			'nilai'=>$total_nilai_keputusan,
			'notes'=>$keterangan_rekomendasi
		];

		if (empty($get_cek_rekomendasi)) {
			$p_save_rekomendasi['id_peserta'] = $id_peserta;
			$p_save_rekomendasi['id_ujian'] = $id_ujian;

			$builder = $this->db->table('hasil_rekomendasi');
			$queri = $builder->insert($p_save_rekomendasi);
		} else {
            $builder = $this->db->table('hasil_rekomendasi');
            $builder->where('id_ujian', $id_ujian);
            $builder->where('id_peserta', $id_peserta);
			$queri = $builder->update($p_save_rekomendasi);
		}
		// konversi hasil akhir keputusan AKHIR

		// return json_encode($nilai_kompetensi);
		// return $teks_queri;
		// exit;
		// return "Insert : ".$insert.", update: ".$update."<br>";
		return "Insert : ".$insert.", update: ".$update.", Total nilai konversi keputusan : ".$total_nilai_keputusan."<br>";
	}

	public function pembulatan_tes_seleksi_staff($nilai) {
		if ($nilai >= 1 && $nilai <= 1.8) {
			$ret = 1;
		} else if ($nilai >= 1.801 && $nilai <= 2.6) {
			$ret = 2;
		} else if ($nilai >= 2.601 && $nilai <= 3.4) {
			$ret = 3;
		} else if ($nilai >= 3.401 && $nilai <= 4.2) {
			$ret = 4;
		} else if ($nilai >= 4.201 && $nilai <= 5) {
			$ret = 5;
		} else {
			$ret = 0;
		}

		return $ret;
	}

	public function konversi_pembulatan_tes_seleksi_staff($nilai, $critical_or_umum) {
		$ret = 0;
		if ($critical_or_umum == 'critical') {
			if ($nilai == 1) {
				$ret = -5;
			} else if ($nilai == 2) {
				$ret = -5;
			} else if ($nilai == 3) {
				$ret = 0;
			} else if ($nilai == 4) {
				$ret = 0.14;
			} else if ($nilai == 5) {
				$ret = 0.18;
			} 
		} else if ($critical_or_umum == 'umum') {
			if ($nilai == 1) {
				$ret = -5;
			} else if ($nilai == 2) {
				$ret = -1;
			} else if ($nilai == 3) {
				$ret = 0;
			} else if ($nilai == 4) {
				$ret = 0.11;
			} else if ($nilai == 5) {
				$ret = 0.13;
			} 
		} 

		return $ret;
	}

	public function pembulatan_tes_assesment($nilai) {
		// kalau dari excel 1,
		// tetapi ada nilai 0.999 nggak masuk
		// sehingga ditambahkan sampai dengan 0

		if ($nilai >= 0 && $nilai <= 1.8) {
			$ret = 1;
		} else if ($nilai >= 1.801 && $nilai <= 2.6) {
			$ret = 2;
		} else if ($nilai >= 2.601 && $nilai <= 3.4) {
			$ret = 3;
		} else if ($nilai >= 3.401 && $nilai <= 4.2) {
			$ret = 4;
		} else if ($nilai >= 4.201 && $nilai <= 5) {
			$ret = 5;
		} else {
			$ret = 0;
		}

		return $ret;
	}

	public function konversi_pembulatan_tes_assesment($nilai, $critical_or_umum) {
		$ret = 0;
		if ($critical_or_umum == 'critical') {
			if ($nilai == 1) {
				$ret = -12;
			} else if ($nilai == 2) {
				$ret = -3;
			} else if ($nilai == 3) {
				$ret = 0;
			} else if ($nilai == 4) {
				$ret = 0.07;
			} else if ($nilai == 5) {
				$ret = 0.09;
			} 
		} else if ($critical_or_umum == 'umum') {
			if ($nilai == 1) {
				$ret = -6;
			} else if ($nilai == 2) {
				$ret = -1.1;
			} else if ($nilai == 3) {
				$ret = 0;
			} else if ($nilai == 4) {
				$ret = 0.05;
			} else if ($nilai == 5) {
				$ret = 0.06;
			} 
		} 

		return $ret;
	}

	public function konversi_1_nilai_c($nilai) {
		if ($nilai >= 0 && $nilai <= 0) {
			$ret = 1;
		} else if ($nilai >= 1 && $nilai <= 3) {
			$ret = 2;
		} else if ($nilai >= 4 && $nilai <= 6) {
			$ret = 3;
		} else if ($nilai >= 7 && $nilai <= 8) {
			$ret = 4;
		} else if ($nilai >= 9 && $nilai <= 10) {
			$ret = 5;
		} else {
			$ret = 0;
		}

		return $ret;
	}

	public function konversi_1_nilai_e($aspek, $nilai) {
		/*
		1	0 s/d 2.41		0 s/d 3.73		0 s/d 3.58		0 s/d 3.13		0 s/d 4.28
		2	2.42 s/d 5.56	3.74 s/d 6.10	3.59 s/d 6.30	3.14 s/d 5.99	4.29 s/d 6.64
		3	5.57 s/d 10		6.11 s/d 10		6.31 s/d 10		6.00 s/d 10		6.65 s/d 10
		*/

		$ret = 0;
		if ($aspek == 1) {
			if ($nilai >= 0 && $nilai < 2.42) {
				$ret = 1;
			} else if ($nilai >= 2.42 && $nilai < 5.57) {
				$ret = 2;
			} else if ($nilai >= 5.57 && $nilai <= 10) {
				$ret = 3;
			}
		} else if ($aspek == 2) {
			if ($nilai >= 0 && $nilai < 3.74) {
				$ret = 1;
			} else if ($nilai >= 3.74 && $nilai < 6.11) {
				$ret = 2;
			} else if ($nilai >= 6.11 && $nilai <= 10) {
				$ret = 3;
			}
		} else if ($aspek == 3) {
			if ($nilai >= 0 && $nilai < 3.59) {
				$ret = 1;
			} else if ($nilai >= 3.59 && $nilai < 6.31) {
				$ret = 2;
			} else if ($nilai >= 6.31 && $nilai <= 10) {
				$ret = 3;
			}
		} else if ($aspek == 4) {
			if ($nilai >= 0 && $nilai < 3.14) {
				$ret = 1;
			} else if ($nilai >= 3.14 && $nilai < 6) {
				$ret = 2;
			} else if ($nilai >= 6 && $nilai <= 10) {
				$ret = 3;
			}
		} else if ($aspek == 5) {
			if ($nilai >= 0 && $nilai < 4.29) {
				$ret = 1;
			} else if ($nilai >= 4.29 && $nilai < 6.65) {
				$ret = 2;
			} else if ($nilai >= 6.65 && $nilai <= 10) {
				$ret = 3;
			}
		} 

		return $ret;
	}

	public function tes_hasil_akhir($id_ujian, $id_peserta) {
		echo json_encode($this->hitung_akhir_seleksi_non_staff($id_ujian, $id_peserta));
		exit;
	}

	public function ujicoba_hitung_a($id_ujian, $id_peserta) {
		echo $this->hitung_a($id_ujian, $id_peserta);
	}

	public function ujicoba_hitung_b($id_ujian, $id_peserta) {
		return $this->response->setJSON(($this->hitung_b($id_ujian, $id_peserta)));
	}

	// tidak berfungsi
	public function hitung_big_five($id_ujian, $id_peserta) {
		$get_jawaban = $this->db->table('m_ujian_peserta');
		$get_jawaban->where('id_ujian', $id_ujian);
		$get_jawaban->where('id_peserta', $id_peserta);
		$get_jawaban->where('jenis', 'E');
		$get_jawaban->where('bagian', 1);
		$get_jawaban->select('detil');
		$q_get_jawaban = $get_jawaban->get()->getRowArray();
		$array_jawaban = json_decode($q_get_jawaban['detil'], true);		
	
		$konversi_1 = [
			'Extraversion' => [1, 6, 11, 16, 21, 26, 31, 36, 41, 46],
			'Agreeableness' => [2, 7, 12, 17, 22, 27, 32, 37, 42, 47],
			'Conscientiousness' => [3, 8, 13, 18, 23, 28, 33, 38, 43, 48],
			'Emotional Stability' => [4, 9, 14, 19, 24, 29, 34, 39, 44, 49],
			'Openness' => [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
		];

		$nilai1 = [];
		foreach ($konversi_1 as $kk => $kv) {
			foreach ($kv as $so) {
				if (empty($nilai1[$kk])) {
					$nilai1[$kk] = $array_jawaban[$so]['status'];
				} else {
					$nilai1[$kk] += $array_jawaban[$so]['status'];
				}
			}
		}

		$insert = 0;
		$update = 0;

		foreach ($nilai1 as $nbf_k => $nbf_v) {
			// cek sudah ada 
			$db_cek_nbf = $this->db->table('nilai_bigfive');
			$db_cek_nbf->where('id_ujian', $id_ujian);
			$db_cek_nbf->where('id_peserta', $id_peserta);
			$db_cek_nbf->where('kompetensi', $nbf_k);
			$db_cek_nbf->select('id');
			$get_nbf = $db_cek_nbf->get()->getRowArray();

			$new_nilai = (($nbf_v-10)/10)*2.5;

			$p_save_bigfive = [
				// 'nilai_asli'=>$nbf_v,
				'nilai'=>$new_nilai,
				'nilai_konversi'=>konversi_bigfive($nbf_k, $new_nilai)
			];

			if (empty($get_nbf)) {
				$p_save_bigfive['id_peserta'] = $id_peserta;
				$p_save_bigfive['id_ujian'] = $id_ujian;
				$p_save_bigfive['kompetensi'] = $nbf_k;

				$builder = $this->db->table('nilai_bigfive');
				$queri = $builder->insert($p_save_bigfive);
				$insert++;
			} else {
	            $builder = $this->db->table('nilai_bigfive');
	            $builder->where('id_ujian', $id_ujian);
	            $builder->where('id_peserta', $id_peserta);
				$builder->where('kompetensi', $nbf_k);
				$queri = $builder->update($p_save_bigfive);
				$update++;
			}
		}

		return "Insert: ".$insert.", update: ".$update;
	}

	public function show_kompetensi($id_ujian, $id_peserta) {
		$setting_competensi = $this->setting_kompetensi;

		$db_nk = $this->db->table('nilai_kompetensi');
		$db_nk->where('id_ujian', $id_ujian);
		$db_nk->where('id_peserta', $id_peserta);
		$db_nk->select('*');
		$get_nilkom = $db_nk->get()->getResultArray();

		$arr_nilai = [];
		if (!empty($get_nilkom)) {
			foreach ($get_nilkom as $nk_k => $nk_v) {
				$idx = $nk_v['kompetensi'];
				$arr_nilai[$idx] = $nk_v['nilai'];
			}
		}

		$db_pst = $this->db->table('m_peserta');
		$db_pst->where('id', $id_peserta);
		$db_pst->select('*');
		$get_peserta = $db_pst->get()->getRowArray();

		$level_tes = $get_peserta['level_test'];

		$detil_level_tes = $this->level_tes[$level_tes];
		$nilai_critical = $detil_level_tes['critical'];
		$nilai_umum = $detil_level_tes['umum'];

		$nil_kom_baru = [];
		foreach ($arr_nilai as $an_k => $an_v) {
			$nilai_to_int = intval($an_v);
			$nil_kom_baru_satu = 0;

			if (in_array($an_k, $nilai_critical)) {
				$nil_kom_baru_satu = $this->konversi_skor_kompetensi['critical'][$nilai_to_int];
			}
			if (in_array($an_k, $nilai_umum)) {
				$nil_kom_baru_satu = $this->konversi_skor_kompetensi['umum'][$nilai_to_int];
			}


			$db_upd_nilai = $this->db->table('nilai_kompetensi');
			$db_upd_nilai->where('id_ujian', $id_ujian);
			$db_upd_nilai->where('id_peserta', $id_peserta);
			$db_upd_nilai->where('kompetensi', $an_k);
			$queri = $db_upd_nilai->update(['nilai_konversi'=>$nil_kom_baru_satu]);

			$nil_kom_baru[$an_k] = $nil_kom_baru_satu;
		}

		$total_nilai_akhir = 0;
		foreach ($nil_kom_baru as $nkb_k => $nkb_v) {
			$total_nilai_akhir += $nkb_v;
		}

		$rekomendasi = konversi_rekomendasi($total_nilai_akhir);

		$cek_nilai_akhir = $this->db->table('nilai_akhir');
		$cek_nilai_akhir->where('id_ujian', $id_ujian);
		$cek_nilai_akhir->where('id_peserta', $id_peserta);
		$get_nilai_akhir = $cek_nilai_akhir->get()->getRowArray();

		$save_tabel_nilai_akhir = $this->db->table('nilai_akhir');

		$tipe = "";
		$success = false;
		if (empty($get_nilai_akhir)) {
			$queri_save_tabel_nilai_akhir = $save_tabel_nilai_akhir->insert([
				'id_ujian'=>$id_ujian,
				'id_peserta'=>$id_peserta,
				'nilai_akhir'=>$total_nilai_akhir,
				'rekomendasi'=>$rekomendasi
			]);
			$tipe = "insert";
			$success = $queri_save_tabel_nilai_akhir;
		} else {
			$save_tabel_nilai_akhir->where('id_ujian', $id_ujian);
			$save_tabel_nilai_akhir->where('id_peserta', $id_peserta);
			$queri_save_tabel_nilai_akhir = $save_tabel_nilai_akhir->update([
				'nilai_akhir'=>$total_nilai_akhir,
				'rekomendasi'=>$rekomendasi
			]);
			$tipe = "update";
			$success = $queri_save_tabel_nilai_akhir;
		}

		$ret = ['success'=>$success,'tipe'=>$tipe];

		echo json_encode($ret);
	}
}
