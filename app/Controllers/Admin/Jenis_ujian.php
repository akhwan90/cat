<?php 
namespace App\Controllers\Admin;

require_once './vendor/box/spout/src/Spout/Autoloader/autoload.php';

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Jenis_ujian extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/jenis_ujian';
		$d['js'] = [];
		$d['title'] = 'Jenis Ujian';


		$jenis_ujian = $this->db->table('m_posisi');
		$d['jenis_ujian'] = $jenis_ujian->get()->getResultArray();

		return view('template_admin', $d);
	}

	public function edit($id) {

		$posisi = $this->db->table('m_posisi');
        $posisi->where('id', $id);
        $posisi->select('*');
        $get_posisi = $posisi->get()->getRowArray();

        $d['p'] = 'admin/jenis_ujian_form';
		$d['js'] = [];
		$d['title'] = 'Jenis Ujian';
		$d['detil'] = $get_posisi;

		return view('template_admin', $d);
	}

	public function simpan() {
		$p = $this->request->getPost();

		$builder = $this->db->table('m_posisi');
		$id = $p['id'];

		$data = [
	        'level_tes_nama' => $p['level_tes_nama'],
		];

		$builder->where('id', $id);
		$queri = $builder->update($data);

		return redirect()->to(base_url('admin/jenis_ujian'));
	
	}


}
