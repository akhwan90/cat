<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\Captcha;

class Auth extends BaseController {
	
	public function __construct() {
        $this->captcha = new Captcha();
    }

	public function index() {
		$this->captcha->reset();
		$d['capcha'] = $this->captcha->html('#000', '#fff');

		$d['title'] = "Login Aplikasi";
		
		return view('login', $d);
	}

	public function logout() {
		$this->session->destroy();
		return redirect()->to('auth');
	}

	public function login() {
		$var_post = $this->request->getPost();

		$builder = $this->db->table('admins');
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
				if (password_verify($var_post['passwords'], $data['password'])) {

					unset($data['password']);
					$newdata = $data;
					$newdata['is_login'] = true;
					$this->session->set($newdata);

					return redirect()->to(base_url('admin/dashboard'));
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
