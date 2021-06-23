<?php 
namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Dashboard extends BaseController {
	
	public function index() {

		$d['p'] = 'admin/dashboard';
		$d['title'] = 'Dashboard';

		$d['jml_peserta'] = $this->db->table('m_siswa')->countAllResults();
		$d['jml_guru'] = $this->db->table('m_guru')->countAllResults();
 
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
