<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Ujian extends BaseController {
	
	public function index() {
		$d['p'] = 'admin/ujian';
		$d['js'] = 'ujian';
		$d['title'] = 'Data Ujian';

		$get_mapel = $this->db->table('m_mapel')->get()->getResultArray();
		$d['p_mapel'] = [''=>'-'];
		if (!empty($get_mapel)) {
			foreach ($get_mapel as $mp) {
				$idx = $mp['id'];
				$d['p_mapel'][$idx] = $mp['nama'];
			}
		}

		$d['p_acak'] = [
			''=>'-',
			'acak'=>'Ya',
			'set'=>'Sesuai Urutan'
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

            $builder = $this->db->table('tr_guru_tes');
            $builder->where('id_guru', session('id'));
            $builder->groupStart();
            $builder->like('nama_ujian', $search['value']);
            $builder->groupEnd();
            $builder->select('id');
            $d_total_row = $builder->countAll();

            // untuk datanya
            $builder = $this->db->table('tr_guru_tes a');
            $builder->where('id_guru', session('id'));
            $builder->groupStart();
            $builder->like('nama_ujian', $search['value']);
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

                $link .= ' <a href="'.base_url('/admin/ujian/setting/'.$d['id']).'" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Detil</a>';


                $data_ok[] = $no;
                $data_ok[] = $d['nama_ujian'];
                $data_ok[] = $d['tgl_mulai'];
                $data_ok[] = $d['terlambat'];
                $data_ok[] = $d['token'] . '&nbsp; <a href="#" onclick="return refresh_token('.$d['id'].');" title="Refresh TOKEN"><i class="fa fa-refresh"></i></a>';
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
			$builder = $this->db->table('tr_guru_tes');
            $builder->where('id', $id);
            $builder->select('*');
            // echo $builder->getCompiledSelect();
            $get_data = $builder->get()->getRowArray();

            $data = NULL;
            if (!empty($get_data)) {
            	$get_data_new = $get_data;
            	$get_data_new['tgl_mulai'] = str_replace(" ", "T", $get_data['tgl_mulai']);
            	$get_data_new['terlambat'] = str_replace(" ", "T", $get_data['terlambat']);
            	$data = $get_data_new;
            }


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
			    'id_mapel' => 'required',
			    'nama' => 'required|min_length[2]',
			    'jumlah_soal' => 'required',
			    'waktu' => 'required',
			    'jenis' => 'required',
			    'tgl_mulai' => 'required',
			    'terlambat' => 'required',
			];

			$validation->setRules($validation_rules);
			$validation->withRequest($this->request)->run();
			$errors = $validation->getErrors();


			$id = $p['_id'];

			$builder = $this->db->table('tr_guru_tes');

			helper('text');
			$token = random_string('numeric', 6);

			$data = [
		        'id_guru' => session('id'),
		        'id_mapel' => $p['id_mapel'],
		        'nama_ujian' => $p['nama'],
		        'jumlah_soal' => $p['jumlah_soal'],
		        'waktu' => $p['waktu'],
		        'jenis' => $p['jenis'],
		        'tgl_mulai' => $p['tgl_mulai'],
		        'terlambat' => $p['terlambat'],
		        'token' => $token,
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

			$builder = $this->db->table('tr_guru_tes');

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

	public function refresh_token($id) {
		helper('text');
		$token = random_string('numeric', 6);
	
		$id = intval($id);

		$refresh_token = $this->db->table('tr_guru_tes')->where('id', $id)->update([
			'token'=>$token
		]);


        return $this->response->setJSON([
        	'success'=>true,
        	'message'=>'Berhasil'
        ]);
	}

}
