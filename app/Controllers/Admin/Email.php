<?php 
namespace App\Controllers\Admin;

require_once './vendor/box/spout/src/Spout/Autoloader/autoload.php';

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Email extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/email';
		$d['js'] = 'email';
		$d['title'] = 'Setting Email';

		$builder = $this->db->table('email');
        $builder->where('id', 1);
        $builder->select('*');
        $d['email'] = $builder->get()->getRowArray();

		return view('template_admin', $d);
	}

	public function save() {
		$p = $this->request->getPost();

		$builder = $this->db->table('email');
		$builder->where('id', 1);
		$queri = $builder->update($p);

		return redirect()->to(base_url('admin/email'));
	}

}
