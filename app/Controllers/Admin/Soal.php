<?php 
namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Soal extends BaseController {
	
	public function index() {
		$d['jenis_soal'] = $this->jenis_soal;

		$d['p'] = 'admin/soal';
		$d['js'] = 'soal';
		$d['title'] = 'Soal';
		return view('template_admin', $d);
	}

	public function detil_per_jenis($jenis, $bagian) {

		$d['p'] = 'admin/soal_detil_per_jenis';
		$d['js'] = 'soal';
		$d['title'] = 'Soal Jenis '.$jenis.' Bagian '.$bagian;
		$d['jenis'] = $jenis;
		$d['bagian'] = $bagian;

		return view('template_admin', $d);
	}

	public function form_soal($jenis, $bagian, $id_soal) {
		$idx_detil_jenis_soal = $jenis.$bagian;
		$detil_jenis_soal = $this->jenis_soal[$idx_detil_jenis_soal];

		// get detil isi soal = 
		if (intval($id_soal) > 0) {
			$builder = $this->db->table('m_soal');
			$builder->where('id', $id_soal);
	        $detil_soal = $builder->get()->getRowArray();
	    } else {
	    	$detil_soal = [
	    		'id'=>0,
	    		'jenis'=>$jenis,
	    		'bagian'=>$bagian,
	    		'urutan'=>'',
	    		'soal_text'=>'',
	    		'soal_gambar'=>'',
	    		'kunci'=>"[]",
	    		'opsi_a_text'=>'',
	    		'opsi_a_gambar'=>'',
	    		'opsi_a_nilai'=>0,
	    		'opsi_b_text'=>'',
	    		'opsi_b_gambar'=>'',
	    		'opsi_b_nilai'=>0,
	    		'opsi_c_text'=>'',
	    		'opsi_c_gambar'=>'',
	    		'opsi_c_nilai'=>0,
	    		'opsi_d_text'=>'',
	    		'opsi_d_gambar'=>'',
	    		'opsi_d_nilai'=>0,
	    		'opsi_e_text'=>'',
	    		'opsi_e_gambar'=>'',
	    		'opsi_e_nilai'=>0,
	    		'opsi_f_text'=>'',
	    		'opsi_f_gambar'=>'',
	    		'opsi_f_nilai'=>0,
	    	];
	    }


		$jml_harus_jawab = $detil_jenis_soal['jml_harus_jawab'];
		$multi_kunci = $detil_jenis_soal['multi_kunci'];
		$jml_opsi = $detil_jenis_soal['jml_opsi'];
		$soal_gambar = $detil_soal['soal_gambar'];
		$jenis_soal = $detil_soal['jenis'];

		$pecah_kunci = json_decode($detil_soal['kunci'], true);

		$opsi = $this->huruf_opsi;

		$img_src_soal = '#';
		$file_path = ROOTPATH.'/public/upload/'.$detil_soal['soal_gambar'];
		if (is_file($file_path)) {
			$img_src_soal = base_url().'/public/upload/'.$soal_gambar;
		}

		$form = '<form action="#" id="form_soal" method="post" enctype="multipart/form-data">
		<input type="hidden" name="jenis" id="jenis" value="'.$jenis.'">
		<input type="hidden" name="bagian" id="bagian" value="'.$bagian.'">
		<input type="hidden" name="id_soal" id="id_soal" value="'.$id_soal.'">

		<div class="form-group mb-4">
			<label>Urutan Nomor Soal</label>
			<input type="number" name="urutan" id="urutan" class="form-control" required value="'.$detil_soal['urutan'].'">
			<label>Soal</label>
			<input type="text" name="soal" id="soal" class="form-control" required value="'.$detil_soal['soal_text'].'">
			<input type="file" name="soal_file" id="soal_file" class="mt-1">
			<img src="'.$img_src_soal.'" id="soal_preview" class="mt-2" style="width: 200px">
		</div>';

		// jika jenis soal bukan E
		if ($jenis_soal != "E") {
			// jika kunci jawaban lebih dari 1
			if ($jml_harus_jawab > 1 || $multi_kunci) {
				for ($j = 0; $j < $jml_opsi; $j++) {
					$idx_opsi = $opsi[$j];
					$selekted = '';
					if (in_array($idx_opsi, $pecah_kunci)) {
						$selekted = 'checked';
					}
					$value_opsi = empty($detil_soal['opsi_'.$idx_opsi.'_text']) ? '' : $detil_soal['opsi_'.$idx_opsi.'_text'];

					$img_src = '#';

					$file_image = empty($detil_soal['opsi_file_'.$idx_opsi]) ? '' : $detil_soal['opsi_file_'.$idx_opsi];

					if (array_key_exists('opsi_file_'.$idx_opsi, $detil_soal) == false) {
						$file_image = $detil_soal['opsi_'.$idx_opsi.'_gambar'];
					}

					$file_path = ROOTPATH.'/public/upload/'.$file_image;
					if (is_file($file_path)) {
						$img_src = base_url().'/public/upload/'.$file_image;
					}

					$form .= '
					<div class="form-group mt-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">'.$idx_opsi.'</span>
							</div>
							<div class="input-group-prepend">
								<span class="input-group-text">
									<input type="checkbox" name="kunci[]" id="opsi_text'.$idx_opsi.'" value="'.$idx_opsi.'" '.$selekted.'>
								</span>
							</div>
							<input type="text" name="opsi_text['.$idx_opsi.']" id="opsi_text_'.$idx_opsi.'" class="form-control" value="'.$value_opsi.'">
						</div>
					</div>
					<div class="form-group mt-1">
						<input type="file" name="opsi_file_'.$idx_opsi.'" id="opsi_file_'.$idx_opsi.'" class="mt-1">
						<img src="'.$img_src.'"  id="opsi_preview_'.$idx_opsi.'" class="mt-2" style="width: 200px">
					</div>';
				}
				// jika kunci jawaban = 1
			} else {
				for ($j = 0; $j < $jml_opsi; $j++) {
					$idx_opsi = $opsi[$j];
					$selekted = '';
					if (in_array($idx_opsi, $pecah_kunci)) {
						$selekted = 'checked';
					}
					$value_opsi = empty($detil_soal['opsi_'.$idx_opsi.'_text']) ? '' : $detil_soal['opsi_'.$idx_opsi.'_text'];

					$img_src = '#';
					if (!empty($detil_soal['opsi_file_'.$idx_opsi])) {
						$file_path = ROOTPATH.'/public/upload/'.$detil_soal['opsi_file_'.$idx_opsi];
						if (is_file($file_path)) {
							$img_src = base_url().'/public/upload/'.$detil_soal['opsi_file_'.$idx_opsi];
						}
					}

					$form .= '
					<div class="form-group mt-3">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">'.$idx_opsi.'</span>
							</div>
							<div class="input-group-prepend">
								<span class="input-group-text">
									<input type="radio" name="kunci" id="opsi_'.$idx_opsi.'" value="'.$idx_opsi.'" '.$selekted.'>
								</span>
							</div>
							<input type="text" name="opsi_text['.$idx_opsi.']" id="opsi_text_'.$idx_opsi.'" class="form-control" value="'.$value_opsi.'">
						</div>
						<input type="file" name="opsi_file_'.$idx_opsi.'" id="opsi_file_'.$idx_opsi.'" class="mt-1">
						<img src="'.$img_src.'"  id="opsi_preview_'.$idx_opsi.'" class="mt-2" style="width: 200px">
					</div>
					';
				}
			
			}
		// jika jenis soal E
		} else {
			$form .= '<div class="form-group mt-4">
			<div><label>Jenis</label>'.form_dropdown('favorable', [''=>'','F'=>'Favorable','U'=>'Unfavorable'], '', 'class="form-control" required').'</div></div>';
		}

		$form .= '<div class="form-group mt-4">
			<div><label><input type="checkbox" name="setelah_simpan_input_lagi" value="1" checked> Setelah simpan, input lagi</label></div>

			<button type="submit" class="btn btn-primary btn-lg" id="tb_simpan"><i class="fa fa-check"></i> Simpan</button>
			<a href="'.base_url().'/admin/soal/detil_per_jenis/'.$jenis.'/'.$bagian.'" class="btn btn-secondary btn-lg"><i class="fa fa-arrow-left"></i> Kembali</a>
		</div>';
		$form .= '</form>';

		$d['p'] = 'admin/soal_form';
		$d['js'] = 'soal_form';
		$d['title'] = 'Form Soal';
		$d['html_form'] = $form;
		return view('template_admin', $d);
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

	public function tes($id_soal) {
		$builder = $this->db->table('m_soal');
        $builder->where('id', $id_soal);
        $builder->select('opsi_gambar');
        $data = json_decode($builder->get()->getRow()->opsi_gambar, true);
		
		echo var_dump($data);
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

	public function datatabel_detil_jenis($jenis, $bagian) {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $builder = $this->db->table('m_soal');
            $builder->groupStart();
            $builder->where('jenis', $jenis);
            $builder->where('bagian', $bagian);
            $builder->groupEnd();
            $builder->groupStart();
            $builder->like('soal_text', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAllResults();

            // untuk data
            $b2 = $this->db->table('m_soal');
            $b2->groupStart();
            $b2->where('jenis', $jenis);
            $b2->where('bagian', $bagian);
            $b2->groupEnd();
            $b2->groupStart();
            $b2->like('soal_text', $search['value']);
            $b2->groupEnd();
            $b2->select('*');
            $b2->limit($length, $start);
            $b2->orderBy('id', 'asc');
            $q_datanya = $b2->get()->getResultArray();


            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '
                <a href="'.base_url().'/admin/soal/form_soal/'.$d['jenis'].'/'.$d['bagian'].'/'.$d['id'].'" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</a>
                <a href="#" onclick="return hapus('.$d['id'].');" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Hapus</a>';
              
                $data_ok[] = $no;
                $data_ok[] = $link;
                $data_ok[] = $d['urutan'];
                $data_ok[] = $d['soal_text'];

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
