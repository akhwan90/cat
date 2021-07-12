<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Captcha;

class Auth extends BaseController {
	
	public function __construct() {
        $this->captcha = new Captcha();
    }

	public function index() {
		if (session('is_login')) {
			return redirect()->to(base_url('/admin'));
		}

		$this->captcha->reset();
		$d['capcha'] = $this->captcha->html('#000', '#fff');

		// get instansi 
		$get_instansi = $this->db->table('instansi')->where('id', 1)->get()->getRowArray();

		if (!empty($get_instansi)) {
			$d['instansi_nama'] = $get_instansi['nama'];
			$d['instansi_logo'] = is_file('./uploads/logo/'.$get_instansi['logo']) ? base_url('/uploads/logo/'.$get_instansi['logo']) : '#';
		} else {
			$d['instansi_nama'] = 'Nama Instansi belum disetting';
			$d['instansi_logo'] = 'Logo belum disetting';
		}


		$d['title'] = "Login Aplikasi";
		
		return view('login', $d);
	}

	public function logout() {
		$this->session->destroy();
		return redirect()->to('auth');
	}

	public function login() {
		$var_post = $this->request->getPost();

		$builder = $this->db->table('m_admin');
		$builder->where('username', $var_post['usernames']);
		$data = $builder->get()->getRowArray();

		
		$captcha_post = $var_post['captcha'];
		$cek_captcha = $this->captcha->check($captcha_post);
 
        if($cek_captcha) {

			if (!empty($data)) {
				$password = md5($var_post['passwords']);

				if ($password === $data['password']) {

					unset($data['password']);

					// get instansi 
					$get_instansi = $this->db->table('instansi')->where('id', 1)->get()->getRowArray();

					$newdata = $data;
					$newdata['is_login'] = true;
					$newdata['instansi_nama'] = $get_instansi['nama'];
					$newdata['instansi_logo'] = is_file('./uploads/logo/'.$get_instansi['logo']) ? base_url('/uploads/logo/'.$get_instansi['logo']) : '#';

					$this->session->set($newdata);

					return redirect()->to(base_url('admin'));
				} else {
					return redirect()->back()->with('error_login', '<div class="alert alert-danger">Login gagal (2)</div>');
				}
			} else {
				return redirect()->back()->with('error_login', '<div class="alert alert-danger">Login gagal (1)</div>');
			}
		} else {
			return redirect()->back()->with('error_login', '<div class="alert alert-danger">Silakan klik Google Captcha (I\'m not a robot)</div>');
		}
	}

}
