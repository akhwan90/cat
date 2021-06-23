<?php 
namespace App\Controllers\Admin;


use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Siswa extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/siswa';
		$d['js'] = 'siswa';
		$d['title'] = 'Data Siswa';

		return view('template_admin', $d);
	}

	public function form_import() {
		$d['p'] = 'admin/peserta_form_import';
		$d['js'] = 'peserta';
		$d['title'] = 'Import Data Siswa';
		$d['title_icon'] = '<i class="fa fa-upload"></i> ';
		$d['p_level_test'] = [''=>'-'];


		return view('template_admin', $d);
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('m_siswa');
            $builder->groupStart();
            $builder->like('nama', $search['value']);
            $builder->orLike('nim', $search['value']);
            $builder->orLike('jurusan', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAll();

            // untuk datanya
            $builder = $this->db->table('m_siswa a');
            $builder->groupStart();
            $builder->like('nama', $search['value']);
            $builder->orLike('nim', $search['value']);
            $builder->orLike('jurusan', $search['value']);
            $builder->groupEnd();
            $builder->join('m_admin b', 'a.nim = b.username', 'left');
            $builder->select('a.*, b.username');
            $builder->limit($length, $start);
            $builder->orderBy('a.id', 'asc');
            $q_datanya = $builder->get()->getResultArray();

            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '<div class="btn-group">
                <a href="#" onclick="return edit('.$d['id'].');" class="btn btn-success" title="Edit Data"><i class="fa fa-edit"></i> </a>
                <a href="#" onclick="return hapus('.$d['id'].');" class="btn btn-danger" title="Hapus Data"><i class="fa fa-times"></i> </a>';
                if ($d['username'] != null) {
                	$link .= ' <a href="#" onclick="return reset('.$d['id'].');" class="btn btn-secondary" title="Reset Password"><i class="fa fa-random"></i> </a>';
                }
                $link .= '</div>';

                $username = $d['username'];
                if (empty($username)) {
                	$username = '<a href="#" onclick="return aktifkan_user_satu(\''.$d['nim'].'\');" class="btn btn-primary btn-sm" title="Aktifkan user"><i class="fa fa-user"></i> Aktifkan User</a>';
                }

                $data_ok[] = $no;
                $data_ok[] = $d['nim'];
                $data_ok[] = $d['nama'];
                $data_ok[] = $username;
                $data_ok[] = $link;

                $data[] = $data_ok;

                $no++;
            }

            $json_data = array(
				"draw" => $draw,
				"iTotalRecords" => $d_total_row,
				"iTotalDisplayRecords" => $d_total_row,
				"data" => $data
			);

			return $this->response->setJSON($json_data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function detil() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = intval($p['id']);
			$builder = $this->db->table('m_siswa');
            $builder->where('id', $id);
            $builder->select('*');
            // echo $builder->getCompiledSelect();
            $data = $builder->get()->getRowArray();

            return $this->response->setJSON([
            	'success'=>true,
            	'results'=>$data
            ]);
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function simpan() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$mode = $p['_mode'];

			$validation =  \Config\Services::validation();
			$validation_rules = [
			    // 'p1' => 'required',
			    '_id' => 'required',
			    '_mode' => 'required',
			    'nama' => 'required|min_length[6]',
			    'nim' => 'required|min_length[6]',
			];

			$validation->setRules($validation_rules);
			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();


			$id = $p['_id'];

			$builder = $this->db->table('m_siswa');

			$data = [
		        'nama' => $p['nama'],
		        'nim' => $p['nim'],
		        'jurusan' => $p['jurusan'],
			];

			if (!$errors) {
				if ($mode == "add") {
					$queri = $builder->insert($data);
				} else {
					$builder->where('id', $id);
					$queri = $builder->update($data);
				}

				$success = false;
				if ($queri) {
					$success = true;
				}

	            return $this->response->setJSON([
	            	'success'=>$success,
	            	'message'=>'Tersimpan'
	            ]);
	        } else {
	        	return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
	        }
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function hapus() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = $p['id'];

			$builder = $this->db->table('m_siswa');

			$builder->where('id', $id);
			$queri = $builder->delete();

			// cek di tabel m_admin 
			$hapus_admin = $this->db->table('m_admin')->where('kon_id', $id)->where('level', 'siswa')->delete();

			$success = false;
			if ($queri) {
				$success = true;
			}

            return $this->response->setJSON([
            	'success'=>$success,
            	'message'=>'Dihapus'
            ]);
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function aktifkan_user() {
		if ($this->request->isAJAX()) {
			// helper('text');
			$builder = $this->db->table('m_siswa a');
			$builder->where('b.username IS NULL');
            $builder->join('m_admin b', 'a.nim = b.username', 'left');
            $builder->select('a.*, b.username');
            $get_username_belum_aktif = $builder->get()->getResultArray();

            $success = 0;
            $fail = 0;

            if (!empty($get_username_belum_aktif)) {
            	foreach ($get_username_belum_aktif as $guba) {
            		$siswa_id = $guba['id'];
            		$username = $guba['nim'];
            		$password = md5($username);

            		$cek_username = $this->db->table('m_admin')
            				->where('username', $username)
            				->get()->getRowArray();

            		if (empty($cek_username)) {
	            		$update = $this->db->table('m_admin');
			            $q_update = $update->insert([
			            	'username'=>$username,
			            	'password'=>$password,
			            	'level'=>'siswa',
			            	'kon_id'=>$siswa_id,
			            ]);

			            if ($q_update) {
			            	$success++;
			            } else {
			            	$fail++;
			            }
			        } else {
			        	$fail++;
			        }

		            
            	}
            }

            return $this->response->setJSON([
            	'success'=>true,
            	'message'=>'Sukses : '.$success.', Gagal: '.$fail,
            ]);

		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}


	public function aktifkan_user_satu($nim) {
		if ($this->request->isAJAX()) {
			// helper('text');

			$builder = $this->db->table('m_siswa');
			$builder->where('nim', $nim);
            $builder->select('*');
            $get_siswa = $builder->get()->getRowArray();

            if (!empty($get_siswa)) {
            	// cek username di tabel admin
            	$cek_nim_tabel_admin = $this->db->table('m_admin')
            					->where('username', $get_siswa['nim'])
            					->where('level', 'siswa')
            					->countAllResults();
            	$id_siswa = $get_siswa['id'];

            	if ($cek_nim_tabel_admin < 1) {
            		$insert_tabel_admin = $this->db->table('m_admin')->insert([
            			'username'=>$nim,
            			'password'=>md5($nim),
            			'level'=>'siswa',
            			'kon_id'=>$id_siswa
            		]);

            		$ret = [
            			'success'=>true,
            			'message'=>'Username '.$nim.' berhasil diaktifkan. '
            		];
            	} else {
            		$ret = [
            			'success'=>false,
            			'message'=>'Username '.$nim.' sudah diaktifkan sebelumnya. '
            		];
            	}
            } else {
        		$ret = [
        			'success'=>false,
        			'message'=>'Username '.$nim.' tidak ditemukan... '
        		];
        	}

            return $this->response->setJSON($ret);

		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function reset_password() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$validation =  \Config\Services::validation();

			$validation_rules = [
			    // 'p1' => 'required',
			    'id_peserta' => 'required',
			    'password_baru' => 'required|min_length[6]',
			];

			$validation->setRules($validation_rules);

			// $validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ($errors) {
				return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
			} else {
				$id = $p['id_peserta'];
				$builder = $this->db->table('m_admin');
	            $data['password'] = md5($p['password_baru']);
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

	public function import_ok() {
		$p = $this->request->getPost();

		$validated_upload = $this->validate([
		    'file_excel' => 'uploaded[file_excel]|max_size[file_excel,1024]|ext_in[file_excel,xlsx]'
		]);

		$file = $this->request->getFile('file_excel');


		$errors = $this->validation->getErrors();

		if ($validated_upload){
			$file_foto = $file->getRandomName();
			$file->move('./uploads/temp', $file_foto);

			$filePath = './uploads/temp/'.$file_foto;
			$reader = ReaderEntityFactory::createReaderFromFile($filePath);

			$reader->open($filePath);

			$berhasil = 0;
			$gagal = 0;

			foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getIndex() === 0) { 
                    $no = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                    	if ($no > 0) {
	                        $cells = $row->toArray();

	                        $data_peserta = [
	                        	'nama'=>$cells[1],
	                        	'nim'=>$cells[0],
	                        ];

	                        $builder = $this->db->table('m_siswa');

							$queri = $builder->insert($data_peserta);

							if ($queri) {
								$berhasil++;
							} else {
								$gagal++;
							}
	                    }

	                    $no++;
                    }
                }
            }


			$reader->close();

            return redirect()->to(base_url('admin/siswa/form_import'))->with('errors_upload_peserta', '<div class="alert alert-success">Berhasil : '.$berhasil.', gagal : '.$gagal.'</div>');

		} else {
			session()->setFlashdata('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
            return redirect()->to(base_url('admin/siswa/form_import'))->with('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
		}
	}

	public function kirim_email() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = intval($p['mdl_kirim_email_id']);
			$email = $p['alamat_email'];

			$peserta = $this->db->table('m_peserta');
			$peserta->where('id', $id);
			$update_email = $peserta->update([
				'email'=>$p['alamat_email'],
			]);

			$get_detil_peserta = $this->db->table('m_peserta a')
								->where('a.id', $id)
								->join('m_ujian b', 'a.gelombang = b.id', 'left')
								->select('a.*, b.nama nm_gelombang, b.waktu_mulai, b.waktu_selesai')
            					->get()->getRowArray();

            $get_setting_email = $this->db->table('email')
					            ->where('id', 1)
					            ->get()->getRowArray();


            // MULAI KIRIM EMAIL
            $email = \Config\Services::email();

            $arr_cari = [
            	'{{nama}}',
            	'{{base_url}}',
            	'{{username}}',
            	'{{nm_gelombang}}',
            	'{{waktu_mulai}}',
            	'{{waktu_selesai}}'
            ];

            $arr_ganti = [
            	$get_detil_peserta['nama'],
            	base_url(),
            	$get_detil_peserta['username'],
            	$get_detil_peserta['nm_gelombang'],
            	tjs($get_detil_peserta['waktu_mulai']),
            	tjs($get_detil_peserta['waktu_selesai'])
            ];

            $teks_email = str_replace($arr_cari, $arr_ganti, $get_setting_email['format_email']);

            $config['protocol'] = 'smtp';
			$config['wordWrap'] = true;
			$config['SMTPHost'] = $get_setting_email['smtp_host'];
			$config['SMTPUser'] = $get_setting_email['smtp_user'];
			$config['SMTPPass'] = $get_setting_email['smtp_password'];
			$config['SMTPPort'] = $get_setting_email['smtp_port'];
			$config['mailType'] = 'html';

			$email->initialize($config);

			$email->setFrom($get_setting_email['email_from'], $get_setting_email['email_from_label']);
			$email->setTo($get_detil_peserta['email']);
			$email->setSubject($get_setting_email['email_subject']);
			$email->setMessage($teks_email);

			$kirim_email = $email->send();

			if ($kirim_email) {
				return $this->response->setJSON([
	            	'success'=>true,
	            	'message'=>'Email terkirim'
	            ]);
			} else {
				return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>'Email tidak terkirim. Error message : '."\n\n".$email->printDebugger(['headers', 'subject', 'body'])
	            ]);
			}


		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}
}
