<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Instansi extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/instansi';
		$d['js'] = 'instansi';
		$d['title'] = 'Data Instansi Pengguna';

		$d['instansi'] = $this->db->table('instansi')->where('id', 1)->get()->getRowArray();
		$d['logo'] = '';
		if (is_file('./uploads/logo/'.$d['instansi']['logo'])) {
			$d['logo'] = base_url('/uploads/logo/'.$d['instansi']['logo']);
		}

		return view('template_admin', $d);
	}

	public function edit() {
		$d['p'] = 'admin/instansi_form';
		$d['js'] = 'instansi';
		$d['title'] = 'Edit Data Instansi Pengguna';

		$d['instansi'] = $this->db->table('instansi')->where('id', 1)->get()->getRowArray();
		$d['logo'] = '';
		if (is_file('./uploads/logo/'.$d['instansi']['logo'])) {
			$d['logo'] = base_url('/uploads/logo/'.$d['instansi']['logo']);
		}

		return view('template_admin', $d);
	}


	public function save() {

		$validation =  \Config\Services::validation();
		$validation_rules = [
		    'nama' => 'required|min_length[3]',
		    'logo' => [
                'uploaded[logo]',
                'mime_in[logo,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[logo,512]',
            ],
		];

		$validation->setRules($validation_rules);
		$validation->withRequest($this->request)->run();
		$errors = $validation->getErrors();


        if (!$errors) {
			$file = $this->request->getFile('logo');
			if ($file->isValid()) {
				$p = $this->request->getPost();
				$newName = $file->getRandomName();

				$file->move('./uploads/logo', $newName);

				// get file lama 
				$get_file_lama = $this->db->table('instansi')->where('id', 1)->get()->getRow()->logo;
				@unlink('./uploads/logo/'.$get_file_lama);
				
				$this->db->table('instansi')
				->where('id', 1)
				->update([
					'nama'=>$p['nama'],
					'logo'=>$newName,
				]);

				return redirect()->to(base_url('/admin/instansi'))->with('error', '<div class="alert alert-success">Ubah berhasil</div>');
			} else {
			    // throw new \RuntimeException($file->getErrorString().'('.$file->getError().')');
				
				return redirect()->to(base_url('/admin/instansi/edit'))->with('error', '<div class="alert alert-danger">'.$file->getError().'</div>');				
			}
		} else {
			return redirect()->to(base_url('/admin/instansi/edit'))->with('error', '<div class="alert alert-danger">'.implode("\n", $errors).'</div>');
		}
	}

}
