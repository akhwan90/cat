<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Ujian_setting extends BaseController {
	
	public function index($id) {
		$d['p'] = 'admin/ujian_setting';
		$d['js'] = 'ujian_setting';
		$d['title'] = 'Setting Ujian';

		$d['detil_ujian'] = $this->db->table('tr_guru_tes')->where('id', $id)->get()->getRowArray();

		return view('template_admin', $d);
	}

	public function get_soal($id_ujian) {

		$builder = $this->db->table('m_soal a');
		$builder->where('a.id_guru', session('id'));
		$builder->where('b.id_soal IS NULL');
		$builder->join('tr_guru_tes_soal b', 'a.id = b.id_soal', 'left');
		$builder->select('a.*');
		$get_soal = $builder->get()->getResultArray();

		return $this->response->setJSON([
			'soal'=>$get_soal,
		]);
	}

	public function detil_soal($id) {
		$get_soal = $this->db->table('tr_guru_tes_soal a')
					->where('a.id_guru_tes', $id)
					->join('m_soal b', 'a.id_soal = b.id')
					->select('b.*, a.*')
					->orderBy('a.urutan', 'asc')
					->get()->getResultArray();

		return $this->response->setJSON([
			'soal'=>$get_soal,
			'id_ujian'=>$id,
		]);
	}

	public function simpan_ujian_soal($id) {
		$p = $this->request->getPost();
		$id_ujian = intval($id);
		$jml_diambil = count($p['id_soal']);

		$jml_insert = 0;
		if ($jml_diambil > 0) {
			foreach ($p['id_soal'] as $id_soal) {
				// cek per satu
				$cek_sudah_ada = $this->db->table('tr_guru_tes_soal')
				->where('id_soal', $id_soal)
				->where('id_guru_tes', $id)
				->get()->getResultArray();

				// get urutan 
				$get_urutan = $this->db->table('tr_guru_tes_soal')
				->where('id_guru_tes', $id)
				->select('IFNULL(MAX(urutan),0) urutan')
				->get()->getRow()->urutan;

				$urutan = $get_urutan + 1;

				if (empty($cek_sudah_ada)) {
					$this->db->table('tr_guru_tes_soal')
					->insert([
						'id_guru_tes'=>$id,
						'id_soal'=>$id_soal,
						'urutan'=>$urutan,
					]);

					$jml_insert++;
				}
			}		
		}

		return $this->response->setJSON([
			'message'=>$jml_insert." soal berhasil ditambahkan",
			'id_ujian'=>$id,
		]);
	}

	public function hapus($id_ujian, $id_soal) {

		$hapus = $this->db->table('tr_guru_tes_soal')
		->where('id_guru_tes', $id_ujian)
		->where('id_soal', $id_soal)
		->delete();

		if ($hapus) {
			return $this->response->setJSON([
				'message'=>"Soal berhasil dihapuskan",
				'id_ujian'=>$id_ujian,
			]);
		}
	}

	public function up_soal($id_ujian, $id_soal) {

		$get_soal = $this->db->table('tr_guru_tes_soal')
		->where('id_guru_tes', $id_ujian)
		->where('id_soal', $id_soal)
		->get()->getRowArray();


		if (!empty($get_soal)) {
			$urutan = intval($get_soal['urutan']);

			$get_urutan_sebelum = $this->db->table('tr_guru_tes_soal')
			->where('id_guru_tes', $id_ujian)
			->where('urutan < ', $urutan)
			->limit(1)
			->orderBy('urutan', 'desc')
			->select('*')
			->get()->getRowArray();

			if (!empty($get_urutan_sebelum)) {
				// update sebelumnya menjadi 1000
				$queri1 = "Sekarang : ".$get_soal['id']."\n";
				$queri1 .= "Urutan sebelum : ".$get_urutan_sebelum['id']."\n";
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_urutan_sebelum['id'])
				->update([
					'urutan'=>1000
				]);

				// update sekarang ke posisi sebelum 
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_soal['id'])
				->update([
					'urutan'=>$get_urutan_sebelum['urutan']
				]);

				// kembalikan yg sebelum ke sekarang
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_urutan_sebelum['id'])
				->update([
					'urutan'=>$get_soal['urutan']
				]);

				return $this->response->setJSON([
					'message'=>"Soal berhasil dinaikkan urutannya..",
					'id_ujian'=>$id_ujian,
				]);
			} else {
				return $this->response->setJSON([
					'message'=>"No",
					'id_ujian'=>$id_ujian,
				]);
			}
		} else {
			return $this->response->setJSON([
				'message'=>"No",
				'id_ujian'=>$id_ujian,
			]);
		}
	}

	public function down_soal($id_ujian, $id_soal) {

		$get_soal = $this->db->table('tr_guru_tes_soal')
		->where('id_guru_tes', $id_ujian)
		->where('id_soal', $id_soal)
		->get()->getRowArray();


		if (!empty($get_soal)) {
			$urutan = intval($get_soal['urutan']);

			$get_urutan_sebelum = $this->db->table('tr_guru_tes_soal')
			->where('id_guru_tes', $id_ujian)
			->where('urutan > ', $urutan)
			->limit(1)
			->orderBy('urutan', 'asc')
			->select('*')
			->get()->getRowArray();

			if (!empty($get_urutan_sebelum)) {
				// update sebelumnya menjadi 1000
				$queri1 = "Sekarang : ".$get_soal['id']."\n";
				$queri1 .= "Urutan sebelum : ".$get_urutan_sebelum['id']."\n";
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_urutan_sebelum['id'])
				->update([
					'urutan'=>1000
				]);

				// update sekarang ke posisi sebelum 
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_soal['id'])
				->update([
					'urutan'=>$get_urutan_sebelum['urutan']
				]);

				// kembalikan yg sebelum ke sekarang
				$this->db->table('tr_guru_tes_soal')
				->where('id', $get_urutan_sebelum['id'])
				->update([
					'urutan'=>$get_soal['urutan']
				]);

				return $this->response->setJSON([
					'message'=>"Soal berhasil diturunkan urutannya..",
					'id_ujian'=>$id_ujian,
				]);
			} else {
				return $this->response->setStatusCode(500)->setJSON([
					'message'=>"No",
					'id_ujian'=>$id_ujian,
				]);
			}
		} else {
			return $this->response->setStatusCode(500)->setJSON([
				'message'=>"No",
				'id_ujian'=>$id_ujian,
			]);
		}
	}

	public function detil_peserta($id) {
		$get_soal = $this->db->table('tr_ikut_ujian a')
					->where('a.id_tes', $id)
					->join('m_siswa b', 'a.id_user = b.id')
					->select('b.*')
					->get()->getResultArray();

		return $this->response->setJSON([
			'peserta'=>$get_soal,
			'id_ujian'=>$id,
		]);
	}

	public function get_peserta($id_ujian) {

		$builder = $this->db->table('m_siswa a');
		$builder->where('b.id_user IS NULL');
		$builder->join('tr_ikut_ujian b', 'a.id = b.id_user', 'left');
		$builder->select('a.*');
		$get_soal = $builder->get()->getResultArray();

		return $this->response->setJSON([
			'peserta'=>$get_soal,
		]);
	}

	public function simpan_ujian_peserta($id) {
		$p = $this->request->getPost();
		$id_ujian = intval($id);
		$jml_diambil = count($p['id_peserta']);

		$jml_insert = 0;
		if ($jml_diambil > 0) {
			foreach ($p['id_peserta'] as $id_peserta) {
				// cek per satu
				$cek_sudah_ada = $this->db->table('tr_ikut_ujian')
				->where('id_user', $id_peserta)
				->where('id_tes', $id_ujian)
				->get()->getResultArray();

				if (empty($cek_sudah_ada)) {
					$this->db->table('tr_ikut_ujian')
					->insert([
						'id_tes'=>$id_ujian,
						'id_user'=>$id_peserta,
					]);

					$jml_insert++;
				}
			}		
		}

		return $this->response->setJSON([
			'message'=>$jml_insert." peserta berhasil ditambahkan",
			'id_ujian'=>$id,
		]);
	}

	public function hapus_peserta($id_ujian, $id_peserta) {

		$hapus = $this->db->table('tr_ikut_ujian')
		->where('id_tes', $id_ujian)
		->where('id_user', $id_peserta)
		->delete();

		if ($hapus) {
			return $this->response->setJSON([
				'message'=>"Peserta berhasil dihapuskan",
				'id_ujian'=>$id_ujian,
			]);
		}
	}

	
}
