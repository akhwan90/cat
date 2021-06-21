<?php 
namespace App\Controllers\Peserta;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Libraries\Captcha;

class Auth extends BaseController {

	public function __construct() {
        $this->captcha = new Captcha();
    }
	
	public function index() {
		$this->captcha->reset();
		$d['capcha'] = $this->captcha->html('#000', '#fff');
		$d['title'] = "Login Peserta Tes";
		
		return view('login_peserta', $d);
	}

	public function logout() {
		$this->session->destroy();
		return redirect()->to('auth');
	}

	public function dashboard() {
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', session('peserta_id'));
		$get_peserta = $peserta->get()->getRowArray();
		if (empty($get_peserta['posisi_saat_ini'])) {
			$get_peserta['posisi_saat_ini'] = "";
		}

		$d['p_jk'] = $this->p_jk;
		$d['p_pendidikan'] = $this->p_pendidikan;

		$foto = '';
        if (is_file('./public/foto_peserta/'.$get_peserta['foto'])) {
        	$foto = '<img src="'.base_url('public/foto_peserta/'.$get_peserta['foto']).'" style="width: 200px">';
        }

		$d['foto'] = $foto;
		$d['disable'] = 'disabled';
		if ($this->request->getGet('mode') == "edit") {
			$d['disable'] = '';
		}

		$d['peserta'] = $get_peserta;
		$d['p'] = 'peserta/dashboard';
		$d['title'] = 'Dashboard Peserta';
		return view('template_peserta', $d);
	}

	public function perbarui_data_ok() {
		$var_post = $this->request->getPost();

		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', session('peserta_id'));
		$update_peserta = $peserta->update($var_post);

		return redirect()->to(base_url('peserta'));
	}

	public function login() {
		$var_post = $this->request->getPost();

		$builder = $this->db->table('m_peserta');
		$builder->where('username', $var_post['usernames']);
		$data = $builder->get()->getRowArray();

		// google recaptcha
		// $recaptchaResponse = trim($var_post['g-recaptcha-response']);
  //       $secret='6LcbnpYUAAAAAKTn1DRsa0hboyvqIiZ4KgMAlwkO'; // Secret key 
  //       $credential = array(
  //             'secret' => $secret,
  //             'response' => $var_post['g-recaptcha-response']
  //       );
  //       $verify = curl_init();
  //       curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
  //       curl_setopt($verify, CURLOPT_POST, true);
  //       curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($credential));
  //       curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
  //       curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
  //       $response = curl_exec($verify);
 
  //       $status= json_decode($response, true);

		$captcha_post = $var_post['captcha'];
		$cek_captcha = $this->captcha->check($captcha_post);

        if($cek_captcha) {
			if (!empty($data)) {
				if (md5($var_post['passwords']) == $data['password']) {

					unset($data['password']);

					$new_data_for_session = [];
					foreach ($data as $data_login_k => $data_login_v) {
						$new_data_for_session['peserta_'.$data_login_k] = $data_login_v;
					}

					$newdata = $new_data_for_session;
					$newdata['is_login_peserta'] = true;
					$this->session->set($newdata);

					return redirect()->to(base_url('peserta'));
				} else {
					return redirect()->back()->with('error_login', '<div class="alert alert-danger">Login gagal (2)</div>');
				}
			} else {
				return redirect()->back()->with('error_login', '<div class="alert alert-danger">Login gagal (1)</div>');
			}
		} else {
			return redirect()->back()->with('error_login', '<div class="alert alert-danger">Silakan masukkan kode captcha</div>');
		}
	}


	/* UBAH PASSwORD*/

	public function ubah_password() {
		$d['p'] = 'peserta/ubah_password';
		$d['js'] = 'ubah_password_peserta';
		$d['title'] = 'Ubah Password';
		return view('template_peserta', $d);
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
				$id = session('peserta_id');
				$builder = $this->db->table('m_peserta');
	            $data['password'] = md5($p['p3']);
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
