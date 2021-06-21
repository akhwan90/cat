<?php 
namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Dashboard extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/dashboard';
		$d['title'] = 'Dashboard';

		$jml_peserta = $this->db->table('m_peserta');
		$jml_peserta->select('id');
		$d['jml_peserta'] = $jml_peserta->countAll();

		// $jml_peserta_rekomendasi = $this->db->table('hasil_rekomendasi');
		// $jml_peserta_rekomendasi->where('notes', 'Ready');
		// $jml_peserta_rekomendasi->orWhere('notes', 'Direkomendasikan');
		// $jml_peserta_rekomendasi->select('id');
		// $d['jml_peserta_rekomendasi'] = $jml_peserta_rekomendasi->countAll();

		// $jml_peserta_rekomendasi_tdk = $this->db->table('hasil_rekomendasi');
		// $jml_peserta_rekomendasi_tdk->where('notes', 'Not Ready');
		// $jml_peserta_rekomendasi_tdk->orWhere('notes', 'Tidak Direkomendasikan');
		// $jml_peserta_rekomendasi_tdk->select('id');
		// $d['jml_peserta_rekomendasi_tdk'] = $jml_peserta_rekomendasi_tdk->countAll();

		$d['jml_peserta_seleksi_non_staff'] = count($this->db->table('m_peserta b')
		->where('b.jenis_tes', 1)
		->where('b.jenis_staff', 1)
		->select('b.id')
		->get()->getResultArray());
		
		$d['jml_peserta_seleksi_staff'] = count($this->db->table('m_peserta b')
		->where('b.jenis_tes', 1)
		->where('b.jenis_staff', 2)
		->select('b.id')
		->get()->getResultArray());
		
		$d['jml_peserta_assesment'] = count($this->db->table('m_peserta b')
		->where('b.jenis_tes', 2)
		->where('b.jenis_staff', 0)
		->select('b.id')
		->get()->getResultArray());

		$d['hasil_ujian_per_jenis'] = $this->db->query("
				SELECT 
				SUM(case when (b.jenis_tes = 1 AND b.jenis_staff = 1 AND a.notes = 'Direkomendasikan') then 1 else 0 end) non_staff_rekomendasi,
				SUM(case when (b.jenis_tes = 1 AND b.jenis_staff = 1 AND a.notes = 'Tidak Direkomendasikan') then 1 else 0 end) non_staff_tidak_rekomendasi,
				SUM(case when (b.jenis_tes = 1 AND b.jenis_staff = 2 AND a.notes = 'Direkomendasikan') then 1 else 0 end) staff_rekomendasi,
				SUM(case when (b.jenis_tes = 1 AND b.jenis_staff = 2 AND a.notes = 'Tidak Direkomendasikan') then 1 else 0 end) staff_tidak_rekomendasi,
				SUM(case when (b.jenis_tes = 2 AND b.jenis_staff = 0 AND a.notes = 'Ready') then 1 else 0 end) assesment_ready,
				SUM(case when (b.jenis_tes = 2 AND b.jenis_staff = 0 AND a.notes = 'Need Development') then 1 else 0 end) assesment_need_development,
				SUM(case when (b.jenis_tes = 2 AND b.jenis_staff = 0 AND a.notes = 'Not Ready') then 1 else 0 end) assesment_not_ready
				FROM hasil_rekomendasi a
				INNER JOIN m_peserta b ON a.id_peserta = b.id
		")->getRowArray();

		return view('template_admin', $d);
	}

	public function ubah_password() {
		$d['p'] = 'admin/ubah_password';
		$d['js'] = 'ubah_password';
		$d['title'] = 'Ubah Password';
		return view('template_admin', $d);
	}

	public function ubah_password_ok() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$validation =  \Config\Services::validation();

			$validation_rules = [
			    // 'p1' => 'required',
			    'p2' => 'required|min_length[6]',
			    'p3' => 'required|min_length[6]|matches[p2]',
			];

			$validation->setRules($validation_rules);

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ($errors) {
				return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
			} else {
				$id = session('id');
				$builder = $this->db->table('admins');
	            $data['password'] = password_hash($p['p3'], PASSWORD_DEFAULT);
				$builder->where('id', $id);
				$queri = $builder->update($data);

				$success = false;

				if ($queri) {
					$success = true;
				}

	            return $this->response->setJSON([
	            	'success'=>$success,
	            	'message'=>'Password telah diubah.. '
	            ]);
	        } 
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}
}
