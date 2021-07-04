<?php 
namespace App\Controllers\Peserta;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Ujian extends BaseController {
	
	public function index() {
		$list_tes = $this->db->table('tr_ikut_ujian a');
		$list_tes->where('c.id', session('id'));
		$list_tes->join('m_siswa b', 'a.id_user = b.id', 'left');
		$list_tes->join('m_admin c', "b.id = c.kon_id AND c.level = 'siswa'");
        $list_tes->select('a.*, b.nama');
        $list_tes->groupBy('a.id');
        $get_list_tes = $list_tes->get()->getResultArray();

        // print_r($get_list_tes);
        // exit;

        // echo $this->db->getLastQuery();
        // exit;

		$d['data_tes'] = $get_list_tes;
		$d['p'] = 'peserta/ujian';
		$d['js'] = 'ujian_peserta';
		$d['title'] = 'Daftar Test';
		return view('template_peserta', $d);
	}

	/*

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $b1 = $this->db->table('m_ujian');
            $b1->groupStart();
            $b1->like('nama', $search['value']);
            $b1->groupEnd();
            $b1->select('id');
            $d_total_row = $b1->countAllResults();

            // untuk datanya
            $b2 = $this->db->table('m_ujian');
            $b2->groupStart();
            $b2->like('nama', $search['value']);
            $b2->groupEnd();
            $b2->select('*');
            $b2->limit($length, $start);
            $b2->orderBy('id', 'asc');
            $q_datanya = $b2->get()->getResultArray();

            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '
                	<a href="'.base_url('peserta/ikuti_ujian/ok/'.$d['id']).'" class="btn btn-success"><i class="fa fa-edit"></i> Ikuti</a>
                	<!-- <a href="'.base_url('peserta/ujian/lihat_hasil/'.$d['id']).'" class="btn btn-success"><i class="fa fa-th-list"></i> Lihat Hasil</a> -->
                ';
              
                $data_ok[] = $d['id'];
                $data_ok[] = $d['nama'];
                $data_ok[] = tjs($d['waktu_mulai']);
                $data_ok[] = tjs($d['waktu_selesai']);
                $data_ok[] = $link;

                $data[] = $data_ok;
            }

            $json_data = array(
				"draw" => $draw,
				"iTotalRecords" => $d_total_row,
				"iTotalDisplayRecords" => $d_total_row,
				"data" => $data
			);

			return $this->response->setJSON($json_data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}*/

	public function detil() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = intval($p['id']);
			$builder = $this->db->table('m_ujian');
            $builder->where('id', $id);
            $builder->select('*');
            // echo $builder->getCompiledSelect();
            $data = $builder->get()->getRowArray();

            // pecah tgl mulai
            if (!empty($data)) {
            	$pc_waktu_mulai = explode(" ", $data['waktu_mulai']);
            	$pc_waktu_selesai = explode(" ", $data['waktu_selesai']);

            	$data['waktu_mulai_tgl'] = $pc_waktu_mulai[0];
            	$data['waktu_mulai_jam'] = substr($pc_waktu_mulai[1], 0, 5);
            	$data['waktu_selesai_tgl'] = $pc_waktu_selesai[0];
            	$data['waktu_selesai_jam'] = substr($pc_waktu_selesai[1], 0, 5);

	            return $this->response->setJSON([
	            	'success'=>true,
	            	'results'=>$data
	            ]);
            } else {
            	throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
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

	public function lihat_hasil($id_ujian) {
		$id_peserta = session('peserta_id');

		$ujian = $this->db->table('m_ujian_peserta');
		$ujian->where('id_ujian', $id_ujian);
		$ujian->where('id_peserta', $id_peserta);
		$ujian->select('jenis');
		$ujian->groupBy('jenis');
		$get_ujian = $ujian->get()->getResultArray();

		$list_hasil = [];
		foreach ($get_ujian as $gu) {
			$jenis_ujian = strtolower($gu['jenis']);

			$hasil_ujian = $this->db->table('m_nilai_'.$jenis_ujian);
			$hasil_ujian->where('id_ujian', $id_ujian);
			$hasil_ujian->where('id_peserta', $id_peserta);
			$hasil_ujian->select('*');
			$get_hasil_ujian = $hasil_ujian->get()->getResultArray();

			$list_hasil[$jenis_ujian] = $get_hasil_ujian;
		}

		// echo json_encode($list_hasil);
		// exit;


		$d['p'] = 'peserta/ujian_hasil_detil';
		$d['js'] = 'ujian_peserta_detil';
		$d['title'] = 'Hasil Ujian';

		$d['data'] = $list_hasil;
		$d['id_ujian'] = $id_ujian;

		$d['aspek_a'] = $this->aspek_jenis_a;
		$d['aspek_b'] = $this->aspek_jenis_b;
		$d['aspek_c'] = $this->aspek_jenis_c;


		return view('template_peserta', $d);
	}

	public function cetak_1($id_ujian) {
		$id_peserta = session('peserta_id');

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


		$d['p'] = 'peserta/ujian_hasil_detil';
		$d['js'] = 'ujian_peserta_detil';
		$d['title'] = 'Hasil Ujian';

		$d['list_hasil_a_b'] = $list_hasil_a_b;
		$d['list_hasil_c'] = $list_hasil_c;

		// echo json_encode($list_hasil_c);
		// exit;

		$d['jenis_peserta'] = $jenis_peserta;
		$d['id_ujian'] = $id_ujian;
		$d['peserta'] = $get_peserta;
		$d['sistem_seleksi'] = $this->sistem_seleksi;
		$d['konversi_keputusan_non_staff'] = $this->konversi_keputusan_non_staff;

		$d['aspek_a'] = $this->aspek_jenis_a;
		$d['aspek_b'] = $this->aspek_jenis_b;
		$d['aspek_c'] = $this->aspek_jenis_c;


		return view('page/peserta/cetak_hasil_ujian', $d);
	}

}
