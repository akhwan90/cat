<?php 
namespace App\Controllers\Admin;

require_once './vendor/box/spout/src/Spout/Autoloader/autoload.php';

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Admin extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/admin';
		$d['js'] = 'admin';
		$d['title'] = 'Admin';
		$d['p_level'] = [
			'1'=>'Administrator',
			'2'=>'Operator'
		];


		return view('template_admin', $d);
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('admins');
            $builder->select('id');
            $d_total_row = $builder->countAll();

            // untuk datanya
            $builder = $this->db->table('admins a');
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
                $level = "Administrator";
                if ($d['level'] == "2") {
                	$level = "Operator";
                }

                $data_ok[] = $no;
                $data_ok[] = $link;
                $data_ok[] = $d['username'];
                $data_ok[] = $d['nama'];
                $data_ok[] = $level;

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
			$builder = $this->db->table('admins');
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

			if ($mode == "add") {
				$validation_rules = [
				    'username' => 'required|min_length[6]|is_unique[admins.username]',
				    'password' => 'required|min_length[6]',
				    'nama' => 'required',
				    'level' => 'required',
				];
			} else if ($mode == "edit") {
				$validation_rules = [
				    'nama' => 'required',
				    'level' => 'required',
				];

				if ($p['username'] != "") {
					$validation_rules['username'] = 'min_length[6]';
				}
				if ($p['password'] != "") {
					$validation_rules['password'] = 'min_length[6]';
				}
			}

			$validation->setRules($validation_rules);

			// $validate = $this->validation->run($p, 'peserta');
			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();

			if ($errors) {
				return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>implode("\n", $errors)
	            ]);
			} else {
				$id = $p['_id'];
				$builder = $this->db->table('admins');
				$data = [
			        'nama' => $p['nama'],
			        'level' => $p['level'],
				];

				// $password = password_hash($p[, PASSWORD_DEFAULT)

				if ($mode == "add") {
		            $data['username'] = $p['username'];
		            $data['password'] = password_hash($p['password'], PASSWORD_DEFAULT);
		            $data['aktif'] = 1;
		            $data['logs'] = date('Y-m-d H:i:s');
		            $data['create_at'] = date('Y-m-d H:i:s');

					$queri = $builder->insert($data);
				} else {
					if (trim($p['password']) != "") {
		            	$data['password'] = password_hash($p['password'], PASSWORD_DEFAULT);
					}

					$builder->where('id', $id);
					$queri = $builder->update($data);
				}

				$success = false;

				if ($queri) {
					$success = true;
				}

	            return $this->response->setJSON([
	            	'success'=>$success,
	            	'message'=>'Tersimpan. '
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

			$builder = $this->db->table('admins');

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

	public function reset_password($id) {
		if ($this->request->isAJAX()) {
			// helper('text');

			$builder = $this->db->table('m_peserta');
			$builder->where('id', $id);
            $builder->select('*');
            $get_username = $builder->get()->getRowArray();

            $success = 0;
            $fail = 0;

            if (!empty($get_username)) {
        		$userid = $get_username['id'];
        		$username = 'P'.str_pad($userid, 6, '0', STR_PAD_LEFT);
        		$password = md5($username);

        		$update = $this->db->table('m_peserta');
				$update->where('id', $userid);
	            $q_update = $update->update([
	            	'password'=>$password
	            ]);

	            if ($q_update) {
	            	$success++;
	            } else {
	            	$fail++;
	            }
            }

            return $this->response->setJSON([
            	'success'=>true,
            	'message'=>'Berhasil direset',
            ]);

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

			$detil_peserta = $this->db->table('m_peserta a');
            $detil_peserta->where('a.id', $id);
            $detil_peserta->join('m_ujian b', 'a.gelombang = b.id', 'left');
            $detil_peserta->select('a.*, b.nama nm_gelombang, b.waktu_mulai, b.waktu_selesai');
            $get_detil_peserta = $detil_peserta->get()->getRowArray();

			// curl kirim email
			$array_email = [
				"api_key"=>"api-4B3BD3CAFFAD11EAA085F23C91C88F4E",
				"html_body"=>
				"<p>Kepada Bapak/Ibu ".$get_detil_peserta['nama']."</p>
				<p>Username dan password untuk masuk ke akun anda di system assesment online kami adalah sebagai berikut : </p>
				<p>Sehubungan dengan proses assessment di PT Cipta Kridatama, kami meminta anda untuk mengerjakan assessment online berikut ini. Email ini bertujuan untuk memberikan informasi pengerjaan assessment dan memberikan alamat URL assessment yang perlu anda buka. Jika anda tidak yakin dengan undangan email ini silahkan hubungi recruitment.talent@ciptakridatama.co.id</p>
				<p>Berikut adalah akses anda dalam mengerjakan assessment dengan menekan tautan link ".base_url('/peserta')." ini, masukan username dan passwordnya </p>
				<p>Pada Test ini akan ada 4 test yang memiliki waktu maksimal pengerjaan 3 – 4 jam.<p>
                <h3>Pastikan anda dalam kondisi yang fit dan siap untuk mengerjakan serangkaian test ini. </h3>
				<p>
				Username : ".$get_detil_peserta['username']."<br>
				Password : ".$get_detil_peserta['username']."<br>
				Gelombang Tets: ".$get_detil_peserta['nm_gelombang']."<br>
				Waktu Tes: ".tjs($get_detil_peserta['waktu_mulai'])." s.d. ".tjs($get_detil_peserta['waktu_selesai'])." 
				</p>
				<p>Apabila anda menemui kesulitan dalam membuka halaman assessment Anda,  silahkan hubungi email  recruitment.talent@ciptakridatama.co.id</p>
				<p>Hormat Kami</p>
				<p><b>(PT Cipta Kridatama)</b></p>
				",
				"sender"=>"Assesment Online <recruitment.talent@ciptakridatama.co.id>",
				"subject"=>"Assesment Online",
				"text_body"=>"Kepada Bapak/Ibu ".$get_detil_peserta['nama']."
				Sehubungan dengan proses assessment di PT Cipta Kridatama, Kami meminta anda untuk mengerjakan assessment online berikut ini. Email ini bertujuan untuk memberikan informasi pengerjaan assessment dan memberikan alamat URL assessment yang perlu anda buka. Jika anda tidak yakin dengan undangan email ini silahkan hubungi recruitment.talent@ciptakridatama.co.id 
				Berikut adalah akses anda dalam mengerjakan assessment dengan menekan tautan link www.assess-ir.com/login ini, masukan username dan passwordnya 
				Pada Test ini akan ada 4 test yang memiliki waktu maksimal pengerjaan 3 – 4 jam.
                Pastikan anda dalam kondisi yang fit dan siap untuk mengerjakan serangkaian test ini. 
				Username : ".$get_detil_peserta['username']."<br>
				Password : ".$get_detil_peserta['username']."<br>
				Gelombang Tes: ".$get_detil_peserta['nm_gelombang']."<br>
				Waktu Tes: ".tjs($get_detil_peserta['waktu_mulai'])." s.d. ".tjs($get_detil_peserta['waktu_selesai'])." 
				Apabila anda menemui kesulitan dalam membuka halaman Assessment Anda,  silahkan hubungi email  recruitment.talent@ciptakridatama.co.id
				Hormat Kami
				(PT Cipta Kridatama)",
				"to"=>[
					$get_detil_peserta['nama']." <".$get_detil_peserta['email'].">"
				]
			];

			$email_detil_json = json_encode($array_email);
		    $url = "https://api.smtp2go.com/v3/email/send";

		    $ch = curl_init( $url );
		    curl_setopt( $ch, CURLOPT_POSTFIELDS, $email_detil_json );
		    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		    $result = curl_exec($ch);

		    $error_curl = curl_error($ch);

		    curl_close($ch);

		    if ($error_curl) {
	        	return $this->response->setJSON([
	            	'success'=>false,
	            	'message'=>'Email curl salah '.$error_curl
	            ]);
		    } else {
		        $result_to_array = json_decode($result, true);
		        
		        if (!empty($result_to_array['data']['succeeded'])) {
		            if ($result_to_array['data']['succeeded'] == 1) {
		                return $this->response->setJSON([
			            	'success'=>true,
			            	'message'=>'Email terkirim'
			            ]);
		            } else {
			        	return $this->response->setJSON([
			            	'success'=>false,
			            	'message'=>'Email curl salah : gagal kirim email'
			            ]);
		            }
		        } else {
		        	return $this->response->setJSON([
		            	'success'=>false,
		            	'message'=>'Email curl salah : Data success tidak ditemukan'
		            ]);
		        }
		    }
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}
}
