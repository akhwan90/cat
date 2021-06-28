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

	public function detil_soal($id) {
		$get_soal = $this->db->table('tr_guru_tes_soal a')
					->where('a.id_guru_tes', $id)
					->join('m_soal b', 'a.id_soal = b.id')
					->select('b.*')
					->get()->getResultArray();

		return $this->response->setJSON([
			'soal'=>$get_soal,
			'id_ujian'=>$id,
		]);
	}

	public function get_soal($id_ujian) {

		$get_soal = $this->db->table('m_soal')->where('id_guru', session('id'))
					->get()->getResultArray();

		return $this->response->setJSON([
			'soal'=>$get_soal,
		]);
	}

	
}
