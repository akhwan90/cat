<?php 
namespace App\Controllers\Admin;

require_once './vendor/box/spout/src/Spout/Autoloader/autoload.php';

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Peserta extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/peserta';
		$d['js'] = 'peserta';
		$d['title'] = 'Peserta';
		$d['p_jk'] = $this->p_jk;
		$d['p_pendidikan'] = $this->p_pendidikan;

		$d['p_jenis_tes'] = [''=>'-'];
		$d['p_jenis_staff'] = [''=>'-'];
		$d['p_level_test'] = [''=>'-'];

		$setting_sistem_seleksi = $this->sistem_seleksi;
		foreach ($setting_sistem_seleksi as $ltk => $ltv) {
			$d['p_jenis_tes'][$ltk] = $ltv['nama'];
		}

		// jenis staff = 
		foreach ($setting_sistem_seleksi[1]['sub'] as $abk => $abv) {			
			foreach ($abv['level'] as $lvk => $lvv) {
				$d['p_level_test']['1-'.$abk.'-'.$lvk] = $setting_sistem_seleksi[1]['nama']." - ".$abv['nama']." - ".$lvv['nama'];
			}
		}

		// jika bukan operator. operator = 2
		if (session('level') == 1) {
			foreach ($setting_sistem_seleksi[2]['sub'] as $abk => $abv) {			
				foreach ($abv['level'] as $lvk => $lvv) {
					$d['p_level_test']['2-'.$abk.'-'.$lvk] = $setting_sistem_seleksi[1]['nama']." - ".$abv['nama']." - ".$lvv['nama'];
				}
			}
		}

		// foreach ($setting_sistem_seleksi[2]['sub'] as $lvk => $lvv) {
		// 	$d['p_level_test']['2-0-'.$lvk] = $setting_sistem_seleksi[2]['nama']." - ".$lvv['name'];
		// }



		return view('template_admin', $d);
	}
	public function form_import() {
		$d['p'] = 'admin/peserta_form_import';
		$d['js'] = 'peserta';
		$d['title'] = 'Import Data Peserta';
		$d['title_icon'] = '<i class="fa fa-upload"></i> ';
		$d['p_level_test'] = [''=>'-'];

		$setting_sistem_seleksi = $this->sistem_seleksi;
		// jenis staff = 
		foreach ($setting_sistem_seleksi[1]['sub'] as $abk => $abv) {			
			foreach ($abv['level'] as $lvk => $lvv) {
				$d['p_level_test']['1-'.$abk.'-'.$lvk] = $setting_sistem_seleksi[1]['nama']." - ".$abv['nama']." - ".$lvv['nama'];
			}
		}

		foreach ($setting_sistem_seleksi[2]['sub'] as $abk => $abv) {			
			foreach ($abv['level'] as $lvk => $lvv) {
				$d['p_level_test']['2-'.$abk.'-'.$lvk] = $setting_sistem_seleksi[1]['nama']." - ".$abv['nama']." - ".$lvv['nama'];
			}
		}

		return view('template_admin', $d);
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('m_peserta');
            if (session('level') == 2) {
            	$builder->where('jenis_tes', 1);
            	$builder->where('jenis_staff', 1);
            }
            $builder->groupStart();
            $builder->like('nama', $search['value']);
            $builder->orLike('jenis_kelamin', $search['value']);
            $builder->orLike('pendidikan', $search['value']);
            $builder->orLike('level_test', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAll();

            // untuk datanya
            $builder = $this->db->table('m_peserta a');
            if (session('level') == 2) {
            	$builder->where('jenis_tes', 1);
            	$builder->where('jenis_staff', 1);
            }
            $builder->groupStart();
            $builder->like('a.nama', $search['value']);
            $builder->orLike('a.jenis_kelamin', $search['value']);
            $builder->orLike('a.pendidikan', $search['value']);
            $builder->orLike('a.level_test', $search['value']);
            $builder->groupEnd();
            $builder->join('m_ujian b', 'a.gelombang = b.id', 'left');
            $builder->select('a.*, b.nama gelombang');
            $builder->limit($length, $start);
            $builder->orderBy('a.id', 'asc');
            $q_datanya = $builder->get()->getResultArray();

            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '<div class="btn-group">
                <a href="#" onclick="return edit('.$d['id'].');" class="btn btn-success" title="Edit Data"><i class="fa fa-edit"></i> </a>
                <a href="#" onclick="return hapus('.$d['id'].');" class="btn btn-danger" title="Hapus Data"><i class="fa fa-times"></i> </a>
                <a href="#" onclick="return kirim_email('.$d['id'].', \''.$d['email'].'\');" class="btn btn-warning" title="Kirim Email"><i class="fa fa-envelope fa-fw"></i> </a>';
                if ($d['username'] != null) {
                	$link .= ' <a href="#" onclick="return reset('.$d['id'].');" class="btn btn-secondary" title="Reset Password"><i class="fa fa-random"></i> </a>';
                }

                $link .= '</div>';

                $foto = '';
                if (is_file('./public/foto_peserta/'.$d['foto'])) {
                	$foto = '<img src="'.base_url('public/foto_peserta/'.$d['foto']).'" style="width: 100px">';
                }

                $idx_jenis_tes = $d['jenis_tes'];
                $idx_jenis_staff = $d['jenis_staff'];
                $idx_level_test = $d['level_test'];
              	$jenis_tes = $this->sistem_seleksi[$idx_jenis_tes]['nama'];
              	$jenis_staff = $this->sistem_seleksi[$idx_jenis_tes]['sub'][$idx_jenis_staff]['nama'];
              	$level_tes = $this->sistem_seleksi[$idx_jenis_tes]['sub'][$idx_jenis_staff]['level'][$idx_level_test]['nama'];
              	// $level_tes = $idx_level_tes;

                $data_ok[] = $d['nomor'];
                $data_ok[] = $link;
                // $data_ok[] = $foto;
                // $data_ok[] = $d['nama']."<br>Gel: ".$d['gelombang'];
                $data_ok[] = $d['nama'];
                $data_ok[] = $jenis_tes;
                $data_ok[] = $jenis_staff;
                $data_ok[] = $level_tes;
                // $data_ok[] = $d['tmp_lahir'].", ".tjs($d['tgl_lahir']);
                // $data_ok[] = $d['jenis_kelamin'];
                $data_ok[] = $d['pendidikan'];
                $data_ok[] = $d['username'];

                $data[] = $data_ok;
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
			$builder = $this->db->table('m_peserta');
            $builder->where('id', $id);
            $builder->select('*');
            // echo $builder->getCompiledSelect();
            $data = $builder->get()->getRowArray();
            $data['level_test'] = $data['jenis_tes'].'-'.$data['jenis_staff'].'-'.$data['level_test'];

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

			$file_name = false;
			$file_foto = false;

			// print_r($_FILES);
			// exit;

			//if ($mode == "add") {
			if (!empty($_FILES['foto_peserta']['size'] > 0)) {
				$validated_upload = $this->validate([
				    'foto_peserta' => 'uploaded[foto_peserta]|max_size[foto_peserta,1024]|is_image[foto_peserta]|ext_in[foto_peserta,png,jpg,gif,jpeg]'
				]);

				$file = $this->request->getFile('foto_peserta');


				if ($validated_upload){
					$file_foto = $file->getRandomName();
					$file->move('./public/foto_peserta', $file_foto);
				}

			}

			$validate = $this->validation->run($p, 'peserta');
			$errors = $this->validation->getErrors();


			$id = $p['_id'];
			$pc_level_tes = explode("-", $p['level_test']);
			$jenis_tes = $pc_level_tes[0];
			$jenis_staff = $pc_level_tes[1];
			$level_test = $pc_level_tes[2];

			$builder = $this->db->table('m_peserta');

			$data = [
		        'nama' => $p['nama'],
		        'email' => $p['email'],
		        'posisi_saat_ini' => $p['posisi_saat_ini'],
		        'nomor' => $p['nomor'],
		        'jenis_tes' => $jenis_tes,
		        'jenis_staff' => $jenis_staff,
		        'level_test' => $level_test,
		        'tmp_lahir' => $p['tmp_lahir'],
		        'tgl_lahir' => $p['tgl_lahir'],
		        'jenis_kelamin' => $p['jenis_kelamin'],
		        'pendidikan' => $p['pendidikan'],
		        'lama_jabatan_tahun' => 0,
		        'lama_jabatan_bulan' => 0,
			];

			if ($file_foto) {
				$data['foto'] = $file_foto;
			}

			if (!$errors) {
				if ($mode == "add") {
					// get id 
		            $builder->select('(IFNULL(MAX(id),0)+1) id_terakhir');
		            $builder->limit(1);
		            $builder->orderBy('id', 'desc');
		            $id_terakhir = $builder->get()->getRow()->id_terakhir;

		            $data['id'] = $id_terakhir;
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

			$builder = $this->db->table('m_peserta');

			$builder->where('id', $id);
			$queri = $builder->delete();

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

			$builder = $this->db->table('m_peserta');
			$builder->where('nomor != ', '');
			$builder->where('username', NULL);
			$builder->where('password', NULL);
            $builder->select('*');
            $get_username_belum_aktif = $builder->get()->getResultArray();

            $success = 0;
            $fail = 0;

            if (!empty($get_username_belum_aktif)) {
            	foreach ($get_username_belum_aktif as $guba) {
            		$userid = $guba['id'];
            		$username = 'P'.str_pad($userid, 6, '0', STR_PAD_LEFT);
            		$password = md5($username);

            		$update = $this->db->table('m_peserta');
					$update->where('id', $userid);
		            $q_update = $update->update([
		            	'username'=>$username,
		            	'password'=>$password
		            ]);

		            if ($q_update) {
		            	$success++;
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

			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ($errors) {
				return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
			} else {
				$id = $p['id_peserta'];
				$builder = $this->db->table('m_peserta');
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
			$file->move(WRITEPATH.'uploads/temp', $file_foto);

			$filePath = WRITEPATH.'uploads/temp/'.$file_foto;
			$reader = ReaderEntityFactory::createReaderFromFile($filePath);

			$reader->open($filePath);

			$pc_level_tes = explode("-", $p['level_test']);

			$berhasil = 0;
			$gagal = 0;

			foreach ($reader->getSheetIterator() as $sheet) {
                if ($sheet->getIndex() === 0) { 
                    $no = 0;
                    foreach ($sheet->getRowIterator() as $row) {
                    	if ($no > 0) {
	                        $cells = $row->toArray();

	                        $data_peserta = [
	                        	'jenis_tes'=>$pc_level_tes[0],
	                        	'jenis_staff'=>$pc_level_tes[1],
	                        	'level_test'=>$pc_level_tes[2],
	                        	'nomor'=>$cells[0],
	                        	'nama'=>$cells[1],
	                        	'tmp_lahir'=>$cells[2],
	                        	'tgl_lahir'=>$cells[3],
	                        	'jenis_kelamin'=>$cells[4],
	                        	'pendidikan'=>$cells[5],
	                        	'email'=>$cells[6],
	                        	'posisi_saat_ini'=>$cells[7],
	                        ];

	                        $builder = $this->db->table('m_peserta');

	                        $builder->select('(IFNULL(MAX(id),0)+1) id_terakhir');
				            $builder->limit(1);
				            $builder->orderBy('id', 'desc');
				            $id_terakhir = $builder->get()->getRow()->id_terakhir;

				            $data_peserta['id'] = $id_terakhir;
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

			session()->setFlashdata('errors_upload_peserta', '<div class="alert alert-success">Berhasil : '.$berhasil.', gagal : '.$gagal.'</div>');
            return redirect()->to(base_url('admin/peserta/form_import'));

		} else {
			session()->setFlashdata('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
            return redirect()->to(base_url('admin/peserta/form_import'));
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
