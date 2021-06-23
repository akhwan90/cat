<?php 
namespace App\Controllers\Admin;


use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Mapel extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/mapel';
		$d['js'] = 'mapel';
		$d['title'] = 'Data Mata Pelajaran';

		return view('template_admin', $d);
	}

	public function form_import() {
		$d['p'] = 'admin/mapel_form_import';
		$d['js'] = 'mapel';
		$d['title'] = 'Import Data Mata Pelajaran';
		$d['title_icon'] = '<i class="fa fa-upload"></i> ';

		return view('template_admin', $d);
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('m_mapel');
            $builder->groupStart();
            $builder->like('nama', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAll();

            // untuk datanya
            $builder = $this->db->table('m_mapel a');
            $builder->groupStart();
            $builder->like('nama', $search['value']);
            $builder->groupEnd();
            $builder->select('a.*');
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
                $link .= '</div>';


                $data_ok[] = $no;
                $data_ok[] = $d['nama'];
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
			$builder = $this->db->table('m_mapel');
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
			    'nama' => 'required|min_length[3]',
			];

			$validation->setRules($validation_rules);
			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();


			$id = $p['_id'];

			$builder = $this->db->table('m_mapel');

			$data = [
		        'nama' => $p['nama'],
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

			$builder = $this->db->table('m_guru');

			$builder->where('id', $id);
			$queri = $builder->delete();

			// cek di tabel m_admin 
			$hapus_admin = $this->db->table('m_admin')->where('kon_id', $id)->where('level', 'guru')->delete();

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
			$builder = $this->db->table('m_guru a');
			$builder->where('b.username IS NULL');
            $builder->join('m_admin b', 'a.nip = b.username', 'left');
            $builder->select('a.*, b.username');
            $get_username_belum_aktif = $builder->get()->getResultArray();

            $success = 0;
            $fail = 0;

            if (!empty($get_username_belum_aktif)) {
            	foreach ($get_username_belum_aktif as $guba) {
            		$siswa_id = $guba['id'];
            		$username = $guba['nip'];
            		$password = md5($username);

            		$cek_username = $this->db->table('m_admin')
            				->where('username', $username)
            				->get()->getRowArray();

            		if (empty($cek_username)) {
	            		$update = $this->db->table('m_admin');
			            $q_update = $update->insert([
			            	'username'=>$username,
			            	'password'=>$password,
			            	'level'=>'guru',
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

			$builder = $this->db->table('m_guru');
			$builder->where('nip', $nim);
            $builder->select('*');
            $get_siswa = $builder->get()->getRowArray();

            if (!empty($get_siswa)) {
            	// cek username di tabel admin
            	$cek_nim_tabel_admin = $this->db->table('m_admin')
            					->where('username', $get_siswa['nip'])
            					->where('level', 'guru')
            					->countAllResults();
            	$id_siswa = $get_siswa['id'];

            	if ($cek_nim_tabel_admin < 1) {
            		$insert_tabel_admin = $this->db->table('m_admin')->insert([
            			'username'=>$nim,
            			'password'=>md5($nim),
            			'level'=>'guru',
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
	                        	'nip'=>$cells[0],
	                        ];

	                        $builder = $this->db->table('m_guru');

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

            return redirect()->to(base_url('admin/guru/form_import'))->with('errors_upload_peserta', '<div class="alert alert-success">Berhasil : '.$berhasil.', gagal : '.$gagal.'</div>');

		} else {
			session()->setFlashdata('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
            return redirect()->to(base_url('admin/guru/form_import'))->with('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
		}
	}
}
