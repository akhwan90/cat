<?php 
namespace App\Controllers\Peserta;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Ikuti_ujian extends BaseController {
	
	public function index() {
		echo '<p>';
		echo "Now: ".date('Y-m-d H:i:s')."<br>";
		echo "Now + 30: ".date('Y-m-d H:i:s', strtotime("+30 seconds"));
		
		echo '</p>';
	}

	public function ok($id_ujian) {
		// cek sudah waktunya belum / sudah kedaluarsa 

		$cek_waktu_ujian = $this->db->table('m_ujian');
		$cek_waktu_ujian->where('id', $id_ujian);
		$cek_waktu_ujian->select('*');
		$get_cek_waktu_ujian = $cek_waktu_ujian->get()->getRowArray();

		if (empty($get_cek_waktu_ujian)) {
			return redirect()->to(base_url('peserta/ujian?dari=ok1'));
		}

		if (strtotime('now') < strtotime($get_cek_waktu_ujian['waktu_mulai']) || strtotime('now') > strtotime($get_cek_waktu_ujian['waktu_selesai'])) {
			return redirect()->to(base_url('peserta/ujian?dari=ok2'));
		}

		// cek status sudah selesai ujian 
		$cek_waktu_ujian = $this->db->table('hasil_rekomendasi');
		$cek_waktu_ujian->where('id_ujian', $id_ujian);
		$cek_waktu_ujian->where('id_peserta', session('peserta_id'));
		$cek_waktu_ujian->select('is_selesai');
		$get_cek_status_ujian = $cek_waktu_ujian->get()->getRow();

		if (!empty($get_cek_status_ujian)) {
			if ($get_cek_status_ujian->is_selesai > 0) {
				return redirect()->to(base_url('peserta/ujian?dari=ok3'));
			}
		}


		// cek sudah ada belum 		
		$b0 = $this->db->table('m_ujian_peserta');
		$b0->where('id_ujian', $id_ujian);
		$b0->where('id_peserta', session('peserta_id'));
		$b0->select('id');
		$get_sdh_ujian = $b0->countAllResults();

		// get detil peserta 
		$detil_peserta = $this->db->table('m_peserta');
		$detil_peserta->where('id', session('peserta_id'));
		$detil_peserta->select('*');
		$get_detil_peserta = $detil_peserta->get()->getRowArray();

		if (!empty($get_detil_peserta)) {
			$jenis_tes = $get_detil_peserta['jenis_tes'];
			$jenis_staff = $get_detil_peserta['jenis_staff'];
			$level_test = $get_detil_peserta['level_test'];

			$tes_boleh_diikuti = $this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['tes_diikuti'];


			if ($get_sdh_ujian < 1) {
				// insert ke tabel ikut ujian dulu..
				$jenis_bagian = $this->jenis_soal;

				// $tampung_soal = [];
				foreach ($jenis_bagian as $jbk => $jbv) {

					$jenis = $jbv['jenis'];
					$bagian = $jbv['bagian'];

					if (in_array($jenis, $tes_boleh_diikuti)) {
						$max_jml_soal = $this->jenis_soal[$jenis.$bagian]['jml_soal'];

						$ba1 = $this->db->table('m_soal');
						$ba1->where('jenis', $jenis);
						$ba1->where('bagian', $bagian);
						$ba1->orderBy('id');
						$ba1->select('id, kunci, favorable');
						$ba1->limit($max_jml_soal);
						$get_ba1 = $ba1->get()->getResultArray();

						// $tampung_soal[$jenis][$bagian] = [];
						$soal_jenis_bagian = [];
						$no_soal = 1;
						foreach ($get_ba1 as $soal) {
							// $idx_jenis_bagian = $soal['id'];
							$idx_jenis_bagian = $no_soal;
							
							if ($jenis == "E" && $bagian == 1) {
								$kunci = $soal['favorable'];
								$jawaban = "";
							} else {
								$kunci = json_decode($soal['kunci'], true);
								$jawaban = [];
							}

							$soal_jenis_bagian[$idx_jenis_bagian] = [
								'soal_id'=>$soal['id'],
								'kunci'=>$kunci,
								'jawaban'=>$jawaban,
								'status'=>0,
							];

							$no_soal++;
						}

						$input_ujian_peserta = [
							'id_ujian'=>$id_ujian,
							'id_peserta'=>session('peserta_id'),
							'jenis'=>$jenis,
							'bagian'=>$bagian,
							'detil'=>json_encode($soal_jenis_bagian),
							'jml_benar'=>0,
							'jml_salah'=>0,
						];

						$ba2 = $this->db->table('m_ujian_peserta');
						$ba2->insert($input_ujian_peserta);
					}
				}

				// simpan ke tabel rekomendasi
				$p_save_rekomendasi = [
					'id_peserta'=>session('peserta_id'),
					'id_ujian'=>$id_ujian,
					'rekomendasi'=>0,
					'nilai'=>0,
					'notes'=>'',
					'is_selesai'=>0,
				];

				$builder = $this->db->table('hasil_rekomendasi');
				$queri = $builder->insert($p_save_rekomendasi);
			} 

			return redirect()->to(base_url('peserta/ikuti_ujian/baca_petunjuk/'.$id_ujian.'/A/1'));
		} else {
			exit('peserta tidak ditemukan');
		}
	}

	public function baca_petunjuk($id_ujian, $jenis, $bagian) {
		// cek sudah waktunya belum / sudah kedaluarsa 

		$cek_waktu_ujian = $this->db->table('m_ujian');
		$cek_waktu_ujian->where('id', $id_ujian);
		$cek_waktu_ujian->select('*');
		$get_cek_waktu_ujian = $cek_waktu_ujian->get()->getRowArray();

		if (empty($get_cek_waktu_ujian)) {
			return redirect()->to(base_url('peserta/ujian?dari=baca_petunjuk1'));
		}

		if (strtotime('now') < strtotime($get_cek_waktu_ujian['waktu_mulai']) || strtotime('now') > strtotime($get_cek_waktu_ujian['waktu_selesai'])) {
			return redirect()->to(base_url('peserta/ujian?dari=baca_petunjuk2'));
		}

		// cek status sudah selesai ujian 
		$cek_waktu_ujian = $this->db->table('hasil_rekomendasi');
		$cek_waktu_ujian->where('id_ujian', $id_ujian);
		$cek_waktu_ujian->where('id_peserta', session('peserta_id'));
		$cek_waktu_ujian->select('is_selesai');
		$get_cek_status_ujian = $cek_waktu_ujian->get()->getRow()->is_selesai;

		if ($get_cek_status_ujian > 0) {
			return redirect()->to(base_url('peserta/ujian?dari=baca_petunjuk3'));
		}

		

		$d['jenis_bagian_detil'] = $this->jenis_soal[$jenis.$bagian];
		$d['jenis_bagian_petunjuk'] = $this->text_petunjuk_bagian[$jenis.$bagian];

		$d['id_ujian'] = $id_ujian;
		$d['jenis'] = $jenis;
		$d['bagian'] = $bagian;
		
		$d['p'] = 'peserta/v_soal_petunjuk';
		$d['js'] = 'ujian_peserta_ok';
		$d['title'] = 'Petunjuk Soal';
		return view('template_ujian', $d);
	}

	public function ok_tes($id_ujian, $jenis, $bagian) {
		// cek sudah waktunya belum / sudah kedaluarsa 

		$cek_waktu_ujian = $this->db->table('m_ujian');
		$cek_waktu_ujian->where('id', $id_ujian);
		$cek_waktu_ujian->select('*');
		$get_cek_waktu_ujian = $cek_waktu_ujian->get()->getRowArray();

		if (empty($get_cek_waktu_ujian)) {
			return redirect()->to(base_url('peserta/ujian?dari=ok_tes1'));
		}

		if (strtotime('now') < strtotime($get_cek_waktu_ujian['waktu_mulai']) || strtotime('now') > strtotime($get_cek_waktu_ujian['waktu_selesai'])) {
			return redirect()->to(base_url('peserta/ujian?dari=ok_tes2'));
		}

		// cek status sudah selesai ujian 
		$cek_waktu_ujian = $this->db->table('hasil_rekomendasi');
		$cek_waktu_ujian->where('id_ujian', $id_ujian);
		$cek_waktu_ujian->where('id_peserta', session('peserta_id'));
		$cek_waktu_ujian->select('is_selesai');
		$get_cek_status_ujian = $cek_waktu_ujian->get()->getRow()->is_selesai;

		if ($get_cek_status_ujian > 0) {
			return redirect()->to(base_url('peserta/ujian?dari=ok_tes3'));
		}


		// $jatah_waktu_mengerjakan = 20 * 60; // 60 menit
		$jatah_waktu_mengerjakan = $this->jenis_soal[$jenis.$bagian]['waktu']; // 60 menit

		// list semua bagian soal		
		// $d['jenis_bagian'] = $this->jenis_soal;
		$d['huruf_opsi'] = $this->huruf_opsi;
		$d['jenis_bagian_detil'] = $this->jenis_soal[$jenis.$bagian];
		$d['jenis_bagian_petunjuk'] = $this->text_petunjuk_bagian[$jenis.$bagian];

		// get list bagian 
		$id_peserta = session('peserta_id');
		// $data_list_jenis_ujian = $this->db->table('m_ujian_peserta');
		// $data_list_jenis_ujian->where('id_ujian', $id_ujian);
		// $data_list_jenis_ujian->where('id_peserta', $id_peserta);
		// $data_list_jenis_ujian->select('jenis, bagian');
		// $data_list_jenis_ujian->orderBy('jenis', 'ASC');
		// $get_data_list_jenis_ujian = $data_list_jenis_ujian->get()->getResultArray();
		// $d['jenis_bagian'] = $get_data_list_jenis_ujian;

		// cek sudah mengerjakan sebelumnya ?
		$cek_sudah_mengerjakan_sebelumnya = $this->db->table('m_ujian_peserta');
		$cek_sudah_mengerjakan_sebelumnya->where('id_ujian', $id_ujian);
		$cek_sudah_mengerjakan_sebelumnya->where('id_peserta', $id_peserta);
		$cek_sudah_mengerjakan_sebelumnya->where('jenis', $jenis);
		$cek_sudah_mengerjakan_sebelumnya->where('bagian', $bagian);
		$cek_sudah_mengerjakan_sebelumnya->select('is_sdh_mengerjakan');
		$get_cek_sudah_mengerjakan_sebelumnya = $cek_sudah_mengerjakan_sebelumnya->get()->getRowArray();

		if ($get_cek_sudah_mengerjakan_sebelumnya['is_sdh_mengerjakan'] == 0) {	
			$waktu_sekarang = date('Y-m-d H:i:s');
			$waktu_harus_selesai = date('Y-m-d H:i:s', strtotime($waktu_sekarang) + $this->jenis_soal[$jenis.$bagian]['waktu']);
			// $waktu_harus_selesai = date($waktu_sekarang, time() + $this->jenis_soal[$jenis.$bagian]['waktu']);	

			// update status dan waktu mulai 		
			$update_status_ujian_per_bagian = $this->db->table('m_ujian_peserta');
			$update_status_ujian_per_bagian->where('id_ujian', $id_ujian);
			$update_status_ujian_per_bagian->where('id_peserta', $id_peserta);
			$update_status_ujian_per_bagian->where('jenis', $jenis);
			$update_status_ujian_per_bagian->where('bagian', $bagian);
			$get_update_status_ujian_per_bagian = $update_status_ujian_per_bagian->update([
				'is_sdh_mengerjakan'=>1,
				'waktu_mulai'=>$waktu_sekarang,
				'last_activity'=>$waktu_sekarang,
				'waktu_harus_selesai'=>$waktu_harus_selesai
			]);
		}

		// get waktu berjalan
		$waktu_berjalan = $this->db->table('m_ujian_peserta');
		$waktu_berjalan->where('id_ujian', $id_ujian);
		$waktu_berjalan->where('id_peserta', $id_peserta);
		$waktu_berjalan->where('jenis', $jenis);
		$waktu_berjalan->where('bagian', $bagian);
		$waktu_berjalan->select('waktu_mulai, waktu_harus_selesai, last_activity');
		$get_waktu_berjalan = $waktu_berjalan->get()->getRowArray();

		$waktu_mulai = $get_waktu_berjalan['waktu_mulai'];
		$waktu_harus_selesai = $get_waktu_berjalan['waktu_harus_selesai'];
		$sisa_waktu_asli = strtotime($waktu_harus_selesai) - strtotime(date('Y-m-d H:i:s'));


		$d['sisa_waktu'] = $sisa_waktu_asli;

		// cek sudah ada tes..?
		$id_peserta = session('peserta_id');
		$db1 = $this->db->table('m_ujian_peserta');
		$db1->where('id_ujian', $id_ujian);
		$db1->where('id_peserta', $id_peserta);
		$db1->where('jenis', $jenis);
		$db1->where('bagian', $bagian);
		$data_ujian = $db1->get()->getRowArray();

		if (!empty($data_ujian)) {
			$list_soal = json_decode($data_ujian['detil'], true);
			$list_id_soal = [];
			if (!empty($list_soal)) {
				foreach ($list_soal as $lss) {
					$list_id_soal[] = $lss['soal_id'];
				}
			}

			$q_get_soal = $this->db->query("SELECT * FROM m_soal WHERE id IN ? ORDER BY FIELD (id,".implode(",", $list_id_soal).")", [$list_id_soal])->getResultArray();
			$d['list_soal'] = $q_get_soal;

			$d['id_ujian'] = $id_ujian;
			$d['jenis'] = $jenis;
			$d['bagian'] = $bagian;
			$d['is_checkbox'] = 0;

			if (($jenis.$bagian) == "A2") {
				$d['is_checkbox'] = 1;
			}

			$d['jawaban_terisi'] = $list_soal;

			// echo json_encode($d['jawaban_terisi']);
			// exit;

			$d['p'] = 'peserta/v_soal';
			$d['js'] = 'ujian_peserta_ok';
			$d['title'] = 'Soal Ujian';
			return view('template_ujian', $d);
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

	}

	public function simpan() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$validate = $this->validation->run($p, 'kompetensi');
			$errors = $this->validation->getErrors();


			$mode = $p['_mode'];
			$id = $p['_id'];

			$builder = $this->db->table('m_ujian');

			$gabung_waktu_mulai = $p['waktu_mulai_tgl']." ".$p['waktu_mulai_jam'];
			$gabung_waktu_selesai = $p['waktu_selesai_tgl']." ".$p['waktu_selesai_jam'];

			$data = [
		        'nama' => $p['nama'],
		        'waktu_mulai' => $gabung_waktu_mulai,
		        'waktu_selesai' => $gabung_waktu_selesai,
			];

			if (!$errors) {
				if ($mode == "add") {
					// get id 
		            $builder->select('(IFNULL(MAX(id),0)+1) id_terakhir');
		            $builder->limit(1);
		            $builder->orderBy('id', 'desc');
		            $id_terakhir = $builder->get()->getRow()->id_terakhir;

		            $data['id'] = $id_terakhir;
					$queri = $builder->insert($data);
				} else {
					$builder->where('id', $id);
					$queri = $builder->update($data);
				}

				$success = false;
				if ($queri) {
					$success = true;
				}

	            return $this->response->setJSON([
	            	'success'=>$success,
	            	'message'=>'Tersimpan'
	            ]);
	        } else {
	        	return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
	        }
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function hapus() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = $p['id'];

			$builder = $this->db->table('m_ujian');

			$builder->where('id', $id);
			$queri = $builder->delete();

			$success = false;
			if ($queri) {
				$success = true;
			}

            return $this->response->setJSON([
            	'success'=>$success,
            	'message'=>'Dihapus'
            ]);
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

}
