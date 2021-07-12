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
		$cek_waktu_ujian = $this->db->table('tr_ikut_ujian a');
		$cek_waktu_ujian->join('tr_guru_tes b', 'a.id_tes = b.id');
		$cek_waktu_ujian->join('m_siswa c', 'a.id_user = c.id');
		$cek_waktu_ujian->join('m_admin d', "d.kon_id = a.id_user AND d.level = 'siswa'");
		$cek_waktu_ujian->where('a.id_tes', $id_ujian);
		$cek_waktu_ujian->where('a.id_user', session('kon_id'));
		$cek_waktu_ujian->select('a.*, b.*, c.*');
		$get_cek_waktu_ujian = $cek_waktu_ujian->get()->getRowArray();

		if (empty($get_cek_waktu_ujian)) {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Tidak terdaftar</div>');
		}

		if (strtotime('now') < strtotime($get_cek_waktu_ujian['tgl_mulai']) || strtotime('now') > strtotime($get_cek_waktu_ujian['terlambat'])) {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Sudah selesai</div>');
		}

		if ($get_cek_waktu_ujian['status'] == "Y") {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Sudah selesai mengerjakan</div>');
		}



		if ($get_cek_waktu_ujian['status'] == 'N') {
			// generate soal
			$jenis_acakan = $get_cek_waktu_ujian['jenis'];
			$get_lama_pengerjaan = $get_cek_waktu_ujian['waktu'];
			
			$builder = $this->db->table('tr_guru_tes_soal a');
			$builder->where('a.id_guru_tes', $id_ujian);
			if ($jenis_acakan == "acak") {
				$builder->orderBy('RAND()');
			} else {
				$builder->orderBy('a.urutan', 'asc');
			}
			$builder->join('m_soal b', 'a.id_soal = b.id');
			$builder->select('a.*, b.jawaban');
			$get_soal = $builder->get()->getResultArray();

			$tampung_soal = [];
			$list_soal = [];
			if (!empty($get_soal)) {
				foreach ($get_soal as $soal) {
					$kei = $soal['id_soal'];
					$p_satu_soal = [
						'id_soal'=>$soal['id_soal'],
						'kunci'=>$soal['jawaban'],
						'jawaban'=>'',
					];
					$tampung_soal[$kei] = $p_satu_soal;
					$list_soal[] = $soal['id_soal'];
				}
			}


			$waktu_sekarang = date('Y-m-d H:i:s');
			$waktu_harus_selesai = date('Y-m-d H:i:s', strtotime($waktu_sekarang) + ($get_lama_pengerjaan * 60));

			$this->db->table('tr_ikut_ujian')
			->where('id_tes', $id_ujian)
			->where('id_user', session('kon_id'))
			->update([
				'list_jawaban'=>json_encode($tampung_soal),
				'list_soal'=>json_encode($list_soal),
				'tgl_mulai'=>$waktu_sekarang,
				'tgl_selesai'=>$waktu_harus_selesai,
				'status'=>'D',
			]);

		} 

		// echo $this->db->getLastQuery();
		return redirect()->to(base_url('peserta/ikuti_ujian/kerjakan/'.$id_ujian));
	}

	public function kerjakan($id_ujian) {
		// get detil peserta 
		$detil_peserta = $this->db->table('tr_ikut_ujian a');
		$detil_peserta->join('tr_guru_tes b', 'a.id_tes = b.id');
		$detil_peserta->join('m_siswa c', 'a.id_user = c.id');
		$detil_peserta->join('m_admin d', "d.kon_id = a.id_user AND d.level = 'siswa'");
		$detil_peserta->where('a.id_tes', $id_ujian);
		$detil_peserta->where('a.id_user', session('kon_id'));
		$detil_peserta->select('a.*, b.*, c.*');
		$get_detil_peserta = $detil_peserta->get()->getRowArray();

		// jika data peserta tidak ditemukan
		if (empty($get_detil_peserta)) {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Tidak terdaftar</div>');
		}

		// jika waktu melebihi atau kurang
		if (strtotime('now') < strtotime($get_detil_peserta['tgl_mulai']) || strtotime('now') > strtotime($get_detil_peserta['terlambat'])) {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Sudah selesai</div>');
		}

		if ($get_detil_peserta['status'] == "Y") {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Sudah selesai mengerjakan</div>');
		}

		$list_soal = $get_detil_peserta['list_soal'];
		$list_soal_array = json_decode($get_detil_peserta['list_soal'], true);
		$list_jawaban_array = json_decode($get_detil_peserta['list_jawaban'], true);

		// jika list soal tidak ditemukan
		if (empty($list_soal_array)) {
			return redirect()->to(base_url('peserta/ujian'))->with('error', '<div class="alert alert-danger">Daftar soal kosong..</div>');
		}


		$q_get_soal = $this->db->table('m_soal')
		->whereIn('id', $list_soal_array)
		->orderBy('FIELD (id,'.implode(",", $list_soal_array).')')
		->get()->getResultArray();
		// ->getCompiledSelect();

		$list_soals = [];
		if (!empty($q_get_soal)) {
			foreach ($q_get_soal as $soal) {
				$idx = $soal['id'];
				$soal['jawaban_peserta'] = $list_jawaban_array[$idx]['jawaban'];
				$list_soals[] = $soal;
			}
		}

		$waktu_sekarang = new \DateTime(date('Y-m-d H:i:s'));
		$waktu_harus_selesai = new \DateTime($get_detil_peserta['tgl_selesai']);

		$diff = date_diff($waktu_harus_selesai,$waktu_sekarang);

		if ($diff->invert < 1) {
			$d['sisa_waktu'] = 0;
		} else {
			$d['sisa_waktu'] = (($diff->h * 60 * 60) + ($diff->i * 60) + $diff->s);
		}
		$d['list_soal'] = $list_soals;
		$d['huruf_opsi'] = $this->opsi_huruf;

		// echo json_encode($list_soals);
		// exit;

		// echo json_encode($d['huruf_opsi']);
		// exit;

		$d['id_ujian'] = $id_ujian;

		$d['p'] = 'peserta/v_soal';
		$d['js'] = 'ujian_peserta_ok';
		$d['title'] = 'Soal Ujian';
		return view('template_ujian', $d);

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
