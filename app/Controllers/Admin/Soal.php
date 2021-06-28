<?php 
namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Soal extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/soal';
		$d['js'] = 'soal';
		$d['title'] = 'Soal';
		return view('template_admin', $d);
	}


	public function import() {
		$d['p'] = 'admin/soal_form_import';
		$d['js'] = 'soal';
		$d['title'] = 'Import Data Soal';
		$d['title_icon'] = '<i class="fa fa-upload"></i> ';

		$get_mapel = $this->db->table('m_mapel')->get()->getResultArray();
	    $d['p_mapel'] = [''=>'-'];
	    if (!empty($get_mapel)) {
	    	foreach ($get_mapel as $mpl) {
	    		$idx = $mpl['id'];
	    		$d['p_mapel'][$idx] = $mpl['nama'];
	    	}
	    }

		return view('template_admin', $d);
	}

	public function import_ok() {
		$p = $this->request->getPost();

		$validated_upload = $this->validate([
		    'file_excel' => 'uploaded[file_excel]|max_size[file_excel,1024]|ext_in[file_excel,xlsx]'
		]);

		$file = $this->request->getFile('file_excel');


		$errors = $this->validation->getErrors();

		if ($validated_upload){
			$file_excel = $file->getRandomName();
			$file->move('./uploads/temp', $file_excel);

			$filePath = './uploads/temp/'.$file_excel;
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

	                        $data_soal = [
	                        	'id_guru'=>session('id'),
	                        	'id_mapel'=>$p['id_mapel'],
	                        	'bobot'=>0,
	                        	'soal'=>$cells[0],
	                        	'opsi_a'=>$cells[1],
	                        	'opsi_b'=>$cells[2],
	                        	'opsi_c'=>$cells[3],
	                        	'opsi_d'=>$cells[4],
	                        	'opsi_e'=>$cells[5],
	                        	'jawaban'=>strtolower($cells[6]),
	                        	'tgl_input'=>date('Y-m-d H:i:s'),
	                        	'jml_benar'=>0,
	                        	'jml_salah'=>0,
	                        ];

	                        $builder = $this->db->table('m_soal');

							$queri = $builder->insert($data_soal);

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

            return redirect()->to(base_url('admin/soal/form_import'))->with('errors_upload_peserta', '<div class="alert alert-success">Berhasil : '.$berhasil.', gagal : '.$gagal.'</div>');

		} else {
            return redirect()->to(base_url('admin/soal/import'))->with('errors_upload_peserta', '<div class="alert alert-danger">Terjadi kesalahan : '.json_encode($errors).'</div>');
		}
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('m_soal');
            $builder->groupStart();
            $builder->where('id_guru', session('id'));
            $builder->groupEnd();
            $builder->groupStart();
            $builder->like('soal', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAllResults();

            // untuk data
            $b2 = $this->db->table('m_soal');
            $builder->groupStart();
            $builder->where('id_guru', session('id'));
            $builder->groupEnd();
            $builder->groupStart();
            $builder->like('soal', $search['value']);
            $builder->groupEnd();
            $b2->select('*');
            $b2->limit($length, $start);
            $b2->orderBy('id', 'asc');
            $q_datanya = $b2->get()->getResultArray();


            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '
                <a href="'.base_url().'/admin/soal/edit/'.$d['id'].'" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</a>
                <a href="#" onclick="return hapus('.$d['id'].');" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Hapus</a>';
              
                $data_ok[] = $no;
                $data_ok[] = $link;
                $data_ok[] = $d['soal'];

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

	public function edit($id_soal) {
		// get detil isi soal = 
		if (intval($id_soal) > 0) {
			$builder = $this->db->table('m_soal');
			$builder->where('id', $id_soal);
	        $detil_soal = $builder->get()->getRowArray();
	    } else {
	    	$detil_soal = [
	    		'id'=>0,
	    		'id_guru'=>'',
	    		'id_mapel'=>old('id_mapel', ''),
	    		'file'=>'',
	    		'soal'=>old('soal', ''),
	    		'opsi_a'=>old('opsi_a', ''),
	    		'opsi_b'=>old('opsi_b', ''),
	    		'opsi_c'=>old('opsi_c', ''),
	    		'opsi_d'=>old('opsi_d', ''),
	    		'opsi_e'=>old('opsi_e', ''),
	    		'media_a'=>'',
	    		'media_b'=>'',
	    		'media_c'=>'',
	    		'media_d'=>'',
	    		'media_e'=>'',
	    		'jawaban'=>'',
	    	];
	    }

		$get_mapel = $this->db->table('m_mapel')->get()->getResultArray();
	    $p_mapel = [''=>'-'];
	    if (!empty($get_mapel)) {
	    	foreach ($get_mapel as $mpl) {
	    		$idx = $mpl['id'];
	    		$p_mapel[$idx] = $mpl['nama'];
	    	}
	    }

		$img_src_soal = '';
		$file_path = ROOTPATH.'/public/uploads/gambar_soal/'.$detil_soal['file'];
		if (is_file($file_path)) {
			$img_src_soal = '<img src="'.base_url().'/uploads/gambar_soal/'.$detil_soal['file'].'" id="soal_preview" class="mt-2" style="width: 240px">';
		}

		$form = form_open_multipart(base_url('/admin/soal/save')).'
		<input type="hidden" name="id" id="id" value="'.$detil_soal['id'].'">

		<div class="form-row mb-4">
			<div class="col-lg-9">
				<label>MaPel</label>
				'.form_dropdown('id_mapel', $p_mapel, $detil_soal['id_mapel'], 'class="form-control"').'
			</div>
		</div>
		<div class="form-row mb-4">
			<div class="col-lg-9">
				<label>Soal</label>
				<textarea name="soal" id="soal" class="form-control" required>'.$detil_soal['soal'].'</textarea>
				<div class="custom-file mt-1">
					<input type="file" name="file" id="file" class="custom-file-input" id="customFile">
					<label class="custom-file-label" for="customFile">Upload file soal</label>
				</div>
			</div>
			<div class="col-lg-3">'.$img_src_soal.'</div>
		</div>';

		for ($i = 1; $i <= $this->jml_opsi; $i++) {
			$img_src_opsi = '';
			$huruf_opsi = $this->opsi_huruf[$i];

			$file_path = ROOTPATH.'/public/uploads/gambar_opsi/'.$detil_soal['media_'.$huruf_opsi];
			if (is_file($file_path)) {
				$img_src_opsi = '<img src="'.base_url().'/public/uploads/gambar_opsi/'.$detil_soal['media_'.$huruf_opsi].'" id="soal_preview" class="mt-2" style="width: 200px">';
			}

			$form .= '<div class="form-row mb-4">
					<div class="col-lg-9">
						<label>Opsi '.strtoupper($huruf_opsi).'</label>
						<textarea name="opsi_'.$huruf_opsi.'" id="opsi_'.$huruf_opsi.'" class="form-control text-editor" required>'.$detil_soal['opsi_'.$huruf_opsi].'</textarea>
						<div class="custom-file mt-1">
							<input type="file" name="media_'.$huruf_opsi.'" id="media_'.$huruf_opsi.'" class="custom-file-input" id="customFile">
							<label class="custom-file-label" for="customFile">Upload file opsi '.strtoupper($huruf_opsi).'</label>
						</div>
					</div>
					<div class="col-lg-3">'.$img_src_opsi.'</div>
				</div>';

		}

		$form .= '<div class="form-row mb-4">
			<div class="col-lg-3">
			<label>Kunci Jawaban</label>
			'.form_dropdown('jawaban', $this->p_jawaban, $detil_soal['jawaban'], 'class="form-control"').'
			</div>
			</div>';

		$form .= '
			<button type="submit" class="btn btn-primary btn-lg" id="tb_simpan"><i class="fa fa-check"></i> Simpan</button>
			<a href="'.base_url('/admin/soal').'" class="btn btn-secondary btn-lg"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>';
		$form .= form_close();

		$d['p'] = 'admin/soal_form';
		$d['js'] = 'soal_form';
		$d['title'] = 'Form Soal';
		$d['enable_editor'] = true;
		$d['html_form'] = $form;
		return view('template_admin', $d);
	}

	public function save() {
		$p = $this->request->getPost();

		$validation =  \Config\Services::validation();
		$validation->setRules([
			'soal' => 'required',
			'id_mapel' => 'required',
			'jawaban' => 'required',
			'file' => 'max_size[file,1024]|mime_in[file,image/png,image/jpg,image/jpeg]',
			'media_a' => 'max_size[media_a,1024]|mime_in[media_a,image/png,image/jpg,image/jpeg]',
			'media_b' => 'max_size[media_b,1024]|mime_in[media_b,image/png,image/jpg,image/jpeg]',
			'media_c' => 'max_size[media_c,1024]|mime_in[media_c,image/png,image/jpg,image/jpeg]',
			'media_d' => 'max_size[media_d,1024]|mime_in[media_d,image/png,image/jpg,image/jpeg]',
			'media_e' => 'max_size[media_e,1024]|mime_in[media_e,image/png,image/jpg,image/jpeg]',
		]);
		$validation->withRequest($this->request)->run();
		$errors = $validation->getErrors();


		if ($errors) {
			return redirect()->back()->withInput()->with('errors', '<div class="alert alert-danger">'.implode("<br>", $validation->getErrors()).'</div>');
		} else {
			$id = intval($p['id']);

			$nama_file_upload = ['file', 'media_a', 'media_b', 'media_c', 'media_d', 'media_e'];

			$pdata = [
				'id_guru'=>session('id'),
				'id_mapel'=>$p['id_mapel'],
				'bobot'=>0,
				'soal'=>$p['soal'],
				'opsi_a'=>$p['opsi_a'],
				'opsi_b'=>$p['opsi_b'],
				'opsi_c'=>$p['opsi_c'],
				'opsi_d'=>$p['opsi_d'],
				'opsi_e'=>$p['opsi_e'],
				'jawaban'=>$p['jawaban'],
				'tgl_input'=>date('Y-m-d H:i:s'),
				'jml_benar'=>0,
				'jml_salah'=>0,
			];

			if ($id > 0) {
				// get file 
				$get_file = $this->db->table('m_soal')
				->select('file, media_a, media_b, media_c, media_d, media_e')
				->where('id', $id)->get()->getRowArray();

			}

			foreach ($nama_file_upload as $fu) {
				$file_satu = $this->request->getFile($fu);
				if ($file_satu->isValid() && ! $file_satu->hasMoved()) {
					$newName = $file_satu->getRandomName();
					if ($fu == "file") {
						$file_satu->move('./uploads/gambar_soal', $newName);
						$pdata['tipe_file'] = $file_satu->getClientExtension();

						if (is_file('./uploads/gambar_soal/'.$get_file['file'])) {
							@unlink('./uploads/gambar_soal/'.$get_file['file']);
						}
					} else {
						$file_satu->move('./uploads/gambar_opsi', $newName);

						if (is_file('./uploads/gambar_opsi/'.$get_file[$fu])) {
							@unlink('./uploads/gambar_opsi/'.$get_file[$fu]);
						}
					}
					$pdata[$fu] = $newName;
				}
			}

			if ($id < 1) {
				$this->db->table('m_soal')->insert($pdata);
			} else {
				$this->db->table('m_soal')->where('id', $id)->update($pdata);
			}

			return redirect()->to(base_url('/admin/soal/edit/'.$id))->with('errors', '<div class="alert alert-success">Disimpan</div>');

		}
	}

	public function form_soal_save() {
		$p = $this->request->getPost();

		$idx_jenis_bagian = $p['jenis'].$p['bagian'];
		$dtl_jenis_bagian = $this->jenis_soal[$idx_jenis_bagian];
		$jml_soal = $dtl_jenis_bagian['jml_soal'];

		$kunci = empty($p['kunci']) ? [] : (array) $p['kunci'];

		$jumlah_kunci = count($kunci);
		$setelah_simpan_input_lagi = empty($p['setelah_simpan_input_lagi']) ? 0 : intval($p['setelah_simpan_input_lagi']);

		if ($dtl_jenis_bagian['jenis'] != "E") {
			if ($jumlah_kunci < $dtl_jenis_bagian['jml_harus_jawab']) {
				$ret = [
					'success'=>false,
					'message'=>'Jumlah kunci jawaban harus '.$dtl_jenis_bagian['jml_harus_jawab'],
					'id'=>0,
					'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
				];
			} else if (intval($p['urutan']) > $jml_soal) {
				$ret = [
					'success'=>false,
					'message'=>'Urutan maksimal '.$jml_soal,
					'id'=>0,
					'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
				];
			} else if (count($kunci) < 1) {
				$ret = [
					'success'=>false,
					'message'=>'Kunci jawaban belum diinput..',
					'id'=>0,
					'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
				];
			} else {
				$id_soal = intval($p['id_soal']);

				$pdata = [
					'jenis'=>$p['jenis'],
					'bagian'=>$p['bagian'],
					'urutan'=>$p['urutan'],
					'soal_text'=>$p['soal'],
				];

				if ($dtl_jenis_bagian['jenis'] != "E") {
					$pdata['kunci'] = json_encode($kunci);
				} else {
					$favorable = $p['favorable'];

					$pdata['favorable'] = $p['favorable'];
					
					if ($favorable == "F") {
						$pdata['opsi_a_text'] = 'STS';
						$pdata['opsi_a_nilai'] = 5;
						$pdata['opsi_b_text'] = 'TS';
						$pdata['opsi_b_nilai'] = 4;
						$pdata['opsi_c_text'] = 'N';
						$pdata['opsi_c_nilai'] = 3;
						$pdata['opsi_d_text'] = 'S';
						$pdata['opsi_d_nilai'] = 2;
						$pdata['opsi_e_text'] = 'SS';
						$pdata['opsi_e_nilai'] = 1;
					} else {
						$pdata['opsi_a_text'] = 'STS';
						$pdata['opsi_a_nilai'] = 1;
						$pdata['opsi_b_text'] = 'TS';
						$pdata['opsi_b_nilai'] = 2;
						$pdata['opsi_c_text'] = 'N';
						$pdata['opsi_c_nilai'] = 3;
						$pdata['opsi_d_text'] = 'S';
						$pdata['opsi_d_nilai'] = 4;
						$pdata['opsi_e_text'] = 'SS';
						$pdata['opsi_e_nilai'] = 5;
					}
				}

				for ($j = 0; $j < $dtl_jenis_bagian['jml_opsi']; $j++) {
					$huruf_opsi = $this->huruf_opsi[$j];
					if (!empty($p['opsi_text'][$huruf_opsi])) {
						$pdata['opsi_'.$huruf_opsi.'_text'] = $p['opsi_text'][$huruf_opsi];
					}
				}


				if ($id_soal < 1) {
		            $builder = $this->db->table('m_soal');
					$queri = $builder->insert($pdata);
					$id = $this->db->insertID();
					$tipe = "insert";
				} else {
		            $builder = $this->db->table('m_soal');
		            $builder->where('id', $id_soal);
					$queri = $builder->update($pdata);
					$id = $id_soal;
					$tipe = "update";
				}

				// upload file 	
				$max_size = 2000000;
				$allowed_type_upload = ['jpg','png','gif'];

				$upload_opsi_ok = 0;
				$soal_gambar_update = [];

				foreach ($_FILES as $opsi_file_k => $opsi_file_v) {
					if ($_FILES[$opsi_file_k]['name'] != "") {
						$substr_nama_file = substr($opsi_file_k, 0, 9);

						if ($substr_nama_file === "soal_file") {
							$nama_file = $substr_nama_file.'_'.str_pad($id, 6, '0', STR_PAD_LEFT);
						} else if ($substr_nama_file === "opsi_file") {
							$nama_file = $opsi_file_k.'_'.str_pad($id, 6, '0', STR_PAD_LEFT);
						} 

						$upload_yes = $this->upload_file($opsi_file_k, $allowed_type_upload, $max_size, "public/upload", $nama_file);

						if (is_array($upload_yes)) {
							if ($substr_nama_file === "soal_file") {
								$soal_gambar_update['soal_gambar'] = $upload_yes['filename'].".".$upload_yes['filetype'];
							} else if ($substr_nama_file === "opsi_file") {
								$soal_gambar_update[$opsi_file_k] = $upload_yes['filename'].".".$upload_yes['filetype'];
							} 
							$upload_opsi_ok++;
						} else {
							log_message('error', 'Error upload: '.$upload_yes);
						}
					} 
				}

				if ($upload_opsi_ok > 0) {
					$builder = $this->db->table('m_soal');
		            $builder->where('id', $id);
					$queri = $builder->update($soal_gambar_update);


					if ($queri) {
						$ret = [
							'success'=>true,
							'message'=>'Soal disimpan ('.$tipe.'). Jml upload ok: '.$upload_opsi_ok,
							'id'=>$id,
							'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
						];
					} else {
						$ret = [
							'success'=>false,
							'message'=>'Terjadi kesalahan',
							'id'=>0,
							'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
						];
					}
				} else {
					$ret = [
						'success'=>true,
						'message'=>'Soal disimpan ('.$tipe.'). Jml upload ok: '.$upload_opsi_ok,
						'id'=>$id,
						'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
					];
				}
			}
		} else {
			$id_soal = intval($p['id_soal']);

			$pdata = [
				'jenis'=>$p['jenis'],
				'bagian'=>$p['bagian'],
				'soal_text'=>$p['soal'],
			];

			if ($dtl_jenis_bagian['jenis'] != "E") {
				$pdata['kunci'] = json_encode($kunci);
			} else {
				$favorable = $p['favorable'];

				$pdata['favorable'] = $p['favorable'];
				
				if ($favorable == "F") {
					$pdata['opsi_a_text'] = 'STS';
					$pdata['opsi_a_nilai'] = 5;
					$pdata['opsi_b_text'] = 'TS';
					$pdata['opsi_b_nilai'] = 4;
					$pdata['opsi_c_text'] = 'N';
					$pdata['opsi_c_nilai'] = 3;
					$pdata['opsi_d_text'] = 'S';
					$pdata['opsi_d_nilai'] = 2;
					$pdata['opsi_e_text'] = 'SS';
					$pdata['opsi_e_nilai'] = 1;
				} else {
					$pdata['opsi_a_text'] = 'STS';
					$pdata['opsi_a_nilai'] = 1;
					$pdata['opsi_b_text'] = 'TS';
					$pdata['opsi_b_nilai'] = 2;
					$pdata['opsi_c_text'] = 'N';
					$pdata['opsi_c_nilai'] = 3;
					$pdata['opsi_d_text'] = 'S';
					$pdata['opsi_d_nilai'] = 4;
					$pdata['opsi_e_text'] = 'SS';
					$pdata['opsi_e_nilai'] = 5;
				}
			}

			for ($j = 0; $j < $dtl_jenis_bagian['jml_opsi']; $j++) {
				$huruf_opsi = $this->huruf_opsi[$j];
				if (!empty($p['opsi_text'][$huruf_opsi])) {
					$pdata['opsi_'.$huruf_opsi.'_text'] = $p['opsi_text'][$huruf_opsi];
				}
			}


			if ($id_soal < 1) {
	            $builder = $this->db->table('m_soal');
				$queri = $builder->insert($pdata);
				$id = $this->db->insertID();
				$tipe = "insert";
			} else {
	            $builder = $this->db->table('m_soal');
	            $builder->where('id', $id_soal);
				$queri = $builder->update($pdata);
				$id = $id_soal;
				$tipe = "update";
			}

			// upload file 	
			$max_size = 2000000;
			$allowed_type_upload = ['jpg','png','gif'];

			$upload_opsi_ok = 0;
			$soal_gambar_update = [];

			foreach ($_FILES as $opsi_file_k => $opsi_file_v) {
				if ($_FILES[$opsi_file_k]['name'] != "") {
					$substr_nama_file = substr($opsi_file_k, 0, 9);

					if ($substr_nama_file === "soal_file") {
						$nama_file = $substr_nama_file.'_'.str_pad($id, 6, '0', STR_PAD_LEFT);
					} else if ($substr_nama_file === "opsi_file") {
						$nama_file = $opsi_file_k.'_'.str_pad($id, 6, '0', STR_PAD_LEFT);
					} 

					$upload_yes = $this->upload_file($opsi_file_k, $allowed_type_upload, $max_size, "public/upload", $nama_file);

					if (is_array($upload_yes)) {
						if ($substr_nama_file === "soal_file") {
							$soal_gambar_update['soal_gambar'] = $upload_yes['filename'].".".$upload_yes['filetype'];
						} else if ($substr_nama_file === "opsi_file") {
							$soal_gambar_update[$opsi_file_k] = $upload_yes['filename'].".".$upload_yes['filetype'];
						} 
						$upload_opsi_ok++;
					} else {
						log_message('error', 'Error upload: '.$upload_yes);
					}
				} 
			}

			if ($upload_opsi_ok > 0) {
				$builder = $this->db->table('m_soal');
	            $builder->where('id', $id);
				$queri = $builder->update($soal_gambar_update);


				if ($queri) {
					$ret = [
						'success'=>true,
						'message'=>'Soal disimpan ('.$tipe.'). Jml upload ok: '.$upload_opsi_ok,
						'id'=>$id,
						'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
					];
				} else {
					$ret = [
						'success'=>false,
						'message'=>'Terjadi kesalahan',
						'id'=>0,
						'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
					];
				}
			} else {
				$ret = [
					'success'=>true,
					'message'=>'Soal disimpan ('.$tipe.'). Jml upload ok: '.$upload_opsi_ok,
					'id'=>$id,
					'setelah_simpan_input_lagi'=>$setelah_simpan_input_lagi
				];
			}
		}

		return $this->response->setJSON($ret);
	}

	public function upload_file($name, $allowed, $max_size, $target, $filename, $replace=true) {
		$imageFileType = strtolower(pathinfo($_FILES[$name]["name"],PATHINFO_EXTENSION));
		$target_dir = ROOTPATH . $target;
		$target_file = $target_dir . '/' . $filename . '.' . $imageFileType;
		$uploadOk = 0;

		// Check if image file is a actual image or fake image
		$check = getimagesize($_FILES[$name]["tmp_name"]);
		if($check === false) {
			$uploadOk = 1;
		}

		// Check if file already exists
		if (!$replace) {
			if (file_exists($target_file)) {
				$uploadOk = 2;
			}
		}

		// Check file size
		if ($_FILES[$name]["size"] > $max_size) {
			$uploadOk = 3;
		}
		// Allow certain file formats
		if(!(in_array($imageFileType, $allowed))) {
			$uploadOk = 4;
		}

		if ($uploadOk === 0) {
			if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
				return [
					'filename'=>$filename,
					'filetype'=>$imageFileType
				];
			} else {
				return 5;
			}
		} else {
			return $uploadOk;
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

			$validate = $this->validation->run($p, 'peserta');
			$errors = $this->validation->getErrors();


			$mode = $p['_mode'];
			$id = $p['_id'];

			$builder = $this->db->table('m_peserta');

			$data = [
		        'nama' => $p['nama'],
		        'nomor' => $p['nomor'],
		        'level_test' => $p['level_test'],
		        'usia' => $p['usia'],
		        'jenis_kelamin' => $p['jenis_kelamin'],
		        'pendidikan' => $p['pendidikan'],
			];

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

			$builder = $this->db->table('m_soal');

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

	public function gen_soal($jenis_bagian) {
		$detil_jenis = $this->jenis_soal[$jenis_bagian];
		$jenis = $detil_jenis['jenis'];
		$bagian = $detil_jenis['bagian'];

		if ($jenis_bagian == "E1") {
			$favorable_arr = [1, 3, 5, 7, 9, 11, 13, 15, 17, 19, 21, 23, 25, 27, 31, 33, 35, 37, 40, 41, 42, 43, 45, 47, 48, 50];
			$unfavorable_arr = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 29, 30, ];

			for ($i = 1; $i <= $detil_jenis['jml_soal']; $i++) {
				$this->huruf_opsi = ['a','b','c','d','e'];

				if (in_array($i, $favorable_arr)) {
					$favorable = "F";
				} else if (in_array($i, $unfavorable_arr)) {
					$favorable = "U";
				}

				$pdata = [
					'jenis'=>$jenis,
					'bagian'=>$bagian,
					'soal_text'=>'Kunci : '.$favorable,
					'kunci'=>json_encode([]),
				];

				$pdata['favorable'] = $favorable;
				
				if ($favorable == "F") {
					$pdata['opsi_a_text'] = 'STS';
					$pdata['opsi_a_nilai'] = 5;
					$pdata['opsi_b_text'] = 'TS';
					$pdata['opsi_b_nilai'] = 4;
					$pdata['opsi_c_text'] = 'N';
					$pdata['opsi_c_nilai'] = 3;
					$pdata['opsi_d_text'] = 'S';
					$pdata['opsi_d_nilai'] = 2;
					$pdata['opsi_e_text'] = 'SS';
					$pdata['opsi_e_nilai'] = 1;
				} else {
					$pdata['opsi_a_text'] = 'STS';
					$pdata['opsi_a_nilai'] = 1;
					$pdata['opsi_b_text'] = 'TS';
					$pdata['opsi_b_nilai'] = 2;
					$pdata['opsi_c_text'] = 'N';
					$pdata['opsi_c_nilai'] = 3;
					$pdata['opsi_d_text'] = 'S';
					$pdata['opsi_d_nilai'] = 4;
					$pdata['opsi_e_text'] = 'SS';
					$pdata['opsi_e_nilai'] = 5;
				}

				$ba2 = $this->db->table('m_soal');
				$ba2->insert($pdata);
			}

		} else if ($jenis_bagian == "A2") {
			for ($i = 1; $i <= $detil_jenis['jml_soal']; $i++) {
				$this->huruf_opsi = ['a','b','c','d','e'];

				$kunci1 = array_rand($this->huruf_opsi, 2);

				$kunci_to_array = [];
				foreach ($kunci1 as $k1) {
					$kunci_to_array[] = $this->huruf_opsi[$k1];
				}
				$kunci_text = implode(" ", $kunci_to_array);

				// echo "No. ".$i." : ".$kunci_text."<br>";
				$p_soal = [
					'jenis'=>$jenis,
					'bagian'=>$bagian,
					'soal_text'=>'Kunci : '.$kunci_text,
					'kunci'=>json_encode($kunci_to_array),
				];

				for ($j = 0; $j < ($detil_jenis['jml_opsi']); $j++) {
					$p_soal['opsi_'.$this->huruf_opsi[$j].'_text'] = "Opsi ".$this->huruf_opsi[$j];
				}

				$ba2 = $this->db->table('m_soal');
				$ba2->insert($p_soal);
				// echo json_encode($p_soal)."<br>";
			}
		} else {
			for ($i = 1; $i <= $detil_jenis['jml_soal']; $i++) {
				$get_indeks = rand(0, ($detil_jenis['jml_opsi']-1));
				$kunci = $this->huruf_opsi[$get_indeks];

				echo "No. ".$i." : ".$kunci."<br>";
				$kunci_to_array = (array) $kunci;
				$p_soal = [
					'jenis'=>$jenis,
					'bagian'=>$bagian,
					'soal_text'=>'Kunci : '.$kunci,
					'kunci'=>json_encode($kunci_to_array),
				];

				for ($j = 0; $j < ($detil_jenis['jml_opsi']); $j++) {
					$p_soal['opsi_'.$this->huruf_opsi[$j].'_text'] = "Opsi ".$this->huruf_opsi[$j];
				}

				$ba2 = $this->db->table('m_soal');
				$ba2->insert($p_soal);
				// echo json_encode($p_soal)."<br>";
				// echo "No. ".$i." : ".rand(0, $detil_jenis['jml_opsi'])."<br>";
			}
		}
	}

	public function update_urutan($jenis, $bagian) {

		$builder = $this->db->table('m_soal');
		$builder->where('jenis', $jenis);
		$builder->where('bagian', $bagian);
        $builder->select('*');
        $data = $builder->get()->getResultArray();

        $no = 1;
        foreach ($data as $dt) {
        	
        	$upd = $this->db->table('m_soal');
        	$upd->where('id', $dt['id']);
        	$upd->update(['urutan'=>$no]);

        	$no++;
        }

	}

}
