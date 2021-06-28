<?php 
namespace App\Controllers\Admin;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use CodeIgniter\HTTP\RequestInterface;

class Ujian extends BaseController {
	protected $request;

    public function __construct() {
    	$this->request = \Config\Services::request();
    }

	public function index() {
		$d['p'] = 'admin/ujian';
		$d['js'] = 'ujian';
		$d['title'] = 'Daftar Tes';
		return view('template_admin', $d);
	}

	public function datatabel() {

        if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

            $start = $p['start'];
            $length = $p['length'];
            $draw = $p['draw'];
            $search = $p['search'];

            $b1 = $this->db->table('m_ujian');
            $b1->groupStart();
            $b1->like('nama', $search['value']);
            $b1->groupEnd();
            $b1->select('id');
            $d_total_row = $b1->countAllResults();

            // untuk datanya
            $b2 = $this->db->table('m_ujian');
            $b2->groupStart();
            $b2->like('nama', $search['value']);
            $b2->groupEnd();
            $b2->select('*');
            $b2->limit($length, $start);
            $b2->orderBy('id', 'asc');
            $q_datanya = $b2->get()->getResultArray();

            $data = array();
            $no = ($start+1);
            
            foreach ($q_datanya as $d) {
                $data_ok = array();
            
                $link = '<div class="btn-group">
                <a href="#" onclick="return edit('.$d['id'].');" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Edit</a>
                <a href="#" onclick="return hapus('.$d['id'].');" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Hapus</a>
                <a href="'.base_url('admin/ujian/peserta/'.$d['id']).'" class="btn btn-primary btn-sm"><i class="fa fa-users"></i> Peserta</a>
                <a href="'.base_url('admin/ujian/lihat_hasil/'.$d['id']).'" class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Hasil Ujian</a>
                </div>';
              
                $data_ok[] = $d['id'];
                $data_ok[] = $link;
                $data_ok[] = $d['nama'];
                $data_ok[] = tjs($d['waktu_mulai']);
                $data_ok[] = tjs($d['waktu_selesai']);

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

	public function lihat_hasil($id_ujian) {
        $b2 = $this->db->table('m_ujian_peserta a');
		if (session('level') == 2) {
			$b2->where('b.jenis_tes', 1);
			$b2->where('b.jenis_staff', 1);
		}
        $b2->select('a.*, b.nama nm_peserta, b.jenis_tes, b.jenis_staff, b.level_test, c.notes');
        $b2->where('a.id_ujian', $id_ujian);
        $b2->join('m_peserta b', 'a.id_peserta = b.id');
        $b2->join('hasil_rekomendasi c', 'a.id_peserta = c.id_peserta AND a.id_ujian = c.id_ujian', 'left');
        $b2->groupBy('a.id_peserta');
        $b2->orderBy('a.id', 'asc');
        $q_datanya = $b2->get()->getResultArray();

        $detil_tes = $this->db->table('m_ujian');
        $detil_tes->where('id', $id_ujian);
        $detil_tes->select('*');
        $get_detil_tes = $detil_tes->get()->getRowArray();

		$setting_sistem_seleksi = $this->sistem_seleksi;

        $d['setting_sistem_seleksi'] = $setting_sistem_seleksi;
        $d['data'] = $q_datanya;
        $d['detil_tes'] = $get_detil_tes;
		$d['p'] = 'admin/ujian_hasil';
		$d['js'] = 'ujian';
		$d['title'] = 'Hasil Tes : '.$get_detil_tes['nama'].', start: '.tjs($get_detil_tes['waktu_mulai']).' s.d. '.tjs($get_detil_tes['waktu_selesai']);
		return view('template_admin', $d);
	}

	public function lihat_hasil_detil2($id_ujian, $id_peserta) {
		// $id_peserta = session('peserta_id');
		$ujian = $this->db->table('m_ujian_peserta');
		$ujian->where('id_ujian', $id_ujian);
		$ujian->where('id_peserta', $id_peserta);
		$ujian->select('jenis');
		$ujian->groupBy('jenis');
		$get_ujian = $ujian->get()->getResultArray();

		$list_hasil = [];
		foreach ($get_ujian as $gu) {
			$jenis_ujian = strtolower($gu['jenis']);

			$hasil_ujian = $this->db->table('m_nilai_'.$jenis_ujian);
			$hasil_ujian->where('id_ujian', $id_ujian);
			$hasil_ujian->where('id_peserta', $id_peserta);
			$hasil_ujian->select('*');
			$get_hasil_ujian = $hasil_ujian->get()->getResultArray();

			$list_hasil[$jenis_ujian] = $get_hasil_ujian;
		}

		// echo json_encode($list_hasil);
		// exit;


		$d['p'] = 'admin/ujian_hasil_detil';
		$d['js'] = 'ujian_peserta_detil';
		$d['title'] = 'Hasil Ujian';

		$d['data'] = $list_hasil;
		$d['id_ujian'] = $id_ujian;
		$d['id_peserta'] = $id_peserta;

		$d['aspek_a'] = $this->aspek_jenis_a;
		$d['aspek_b'] = $this->aspek_jenis_b;
		$d['aspek_c'] = $this->aspek_jenis_c;


		return view('template_admin', $d);
	}

	public function lihat_hasil_detil($id_ujian, $id_peserta) {
		// $id_peserta = session('peserta_id');		
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$get_peserta = $peserta->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];

		$jenis_tes_peserta = $jenis_tes."-".$jenis_staff;


		$link_detil_cetak = '';
		
		if ($jenis_tes_peserta == "1-1") {
			$link_detil_cetak = base_url('admin/ujian/cetak_hasil_seleksi_non_staff/'.$id_ujian.'/'.$id_peserta);
		} else if ($jenis_tes_peserta == "1-2") {
			$link_detil_cetak = base_url('admin/ujian/cetak_hasil_seleksi_staff/'.$id_ujian.'/'.$id_peserta);
		} else if ($jenis_tes_peserta == "2-0") {
			$link_detil_cetak = base_url('admin/ujian/cetak_hasil_assesment/'.$id_ujian.'/'.$id_peserta);
		} 


		$d['p'] = 'admin/ujian_hasil_detil';
		$d['js'] = 'ujian_peserta_detil';
		$d['title'] = 'Hasil Ujian';
		$d['link_detil_cetak'] = $link_detil_cetak;

		$d['id_ujian'] = $id_ujian;
		$d['id_peserta'] = $id_peserta;

		return view('template_admin', $d);
	}

	public function cetak_hasil_seleksi_non_staff($id_ujian, $id_peserta) {

		// get detil peserta 
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$peserta->select('*');
		$get_peserta = $peserta->get()->getRowArray();

		// hasil rekomendasi
		$get_hasil_rekomendasi = $this->db->table('hasil_rekomendasi')
		->where('id_peserta', $id_peserta)
		->where('id_ujian', $id_ujian)
		->get()->getRowArray();

		$detil_tes = $this->db->table('m_ujian_peserta');
		$detil_tes->where('id_peserta', $id_peserta);
		$detil_tes->where('id_ujian', $id_ujian);
		$detil_tes->orderBy('last_activity', 'desc');
		$detil_tes->limit(1);
		$get_detil_tes = $detil_tes->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];
		$level_test = $get_peserta['level_test'];

		$jenis_peserta = $this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test];


		// ke nilai
		$list_hasil_a_b = [];
		$list_hasil_c = [];

		$hasil_ujian_a = $this->db->table('m_nilai_a');
		$hasil_ujian_a->where('id_ujian', $id_ujian);
		$hasil_ujian_a->where('id_peserta', $id_peserta);
		$hasil_ujian_a->select('*');
		$get_hasil_ujian_a = $hasil_ujian_a->get()->getResultArray();

		$hasil_ujian_b = $this->db->table('m_nilai_b');
		$hasil_ujian_b->where('id_ujian', $id_ujian);
		$hasil_ujian_b->where('id_peserta', $id_peserta);
		$hasil_ujian_b->select('*');
		$get_hasil_ujian_b = $hasil_ujian_b->get()->getResultArray();

		$hasil_ujian_c = $this->db->table('m_nilai_c');
		$hasil_ujian_c->where('id_ujian', $id_ujian);
		$hasil_ujian_c->where('id_peserta', $id_peserta);
		$hasil_ujian_c->select('*');
		$get_hasil_ujian_c = $hasil_ujian_c->get()->getResultArray();

		if (!empty($get_hasil_ujian_a)) {
			foreach ($get_hasil_ujian_a as $ua_k => $ua_v) {
				$idx_aspek = $ua_v['id_aspek'];
				$nilai = floatval($ua_v['nilai']);

				$list_hasil_a_b[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>konversi_non_staff($nilai),
					'detil_aspek'=>$this->aspek_jenis_a[$idx_aspek],
				];
			}
		}

		if (!empty($get_hasil_ujian_b)) {
			foreach ($get_hasil_ujian_b as $ub_k => $ub_v) {
				$idx_aspek = $ub_v['id_aspek'];
				$nilai = floatval($ub_v['nilai']);

				if (empty($this->aspek_jenis_b[1][$idx_aspek])) {
					$detil_aspek = $this->aspek_jenis_b[2][$idx_aspek];
				} else {
					$detil_aspek = $this->aspek_jenis_b[1][$idx_aspek];
				}

				$list_hasil_a_b[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>konversi_non_staff($nilai),
					'detil_aspek'=>$detil_aspek
				];
			}
		}

		


		if (!empty($get_hasil_ujian_c)) {
			foreach ($get_hasil_ujian_c as $ub_k => $ub_v) {
				$idx_aspek = $ub_v['id_aspek'];
				$nilai = floatval($ub_v['nilai']);
				$nilai_konversi = floatval($ub_v['nilai_konversi']);

				$detil_aspek = $this->aspek_jenis_c[$idx_aspek];

				$list_hasil_c[$idx_aspek] = [
					'nilai'=>$nilai,
					'nilai_konversi'=>$nilai_konversi,
					'detil_aspek'=>$detil_aspek
				];
			}
		}

		if (empty($list_hasil_a_b)) {
			exit('Nilai kompetensi belum masuk...');
		}

		if (empty($list_hasil_c)) {
			exit('Nilai C belum masuk...');
		}


		$d['get_hasil_rekomendasi'] = $get_hasil_rekomendasi;
		$d['p'] = 'peserta/ujian_hasil_detil';
		$d['js'] = 'ujian_peserta_detil';
		$d['title'] = 'Hasil Ujian';

		$d['list_hasil_a_b'] = $list_hasil_a_b;
		$d['list_hasil_c'] = $list_hasil_c;
		$d['detil_tes'] = $get_detil_tes;

		// echo json_encode($list_hasil_c);
		// exit;

		$d['jenis_peserta'] = $jenis_peserta;
		$d['id_ujian'] = $id_ujian;
		$d['peserta'] = $get_peserta;
		$d['sistem_seleksi'] = $this->sistem_seleksi;
		$d['konversi_keputusan_non_staff'] = $this->konversi_keputusan_non_staff;

		$d['aspek_a'] = $this->aspek_jenis_a;
		$d['aspek_b'] = $this->aspek_jenis_b;
		$d['aspek_c'] = $this->aspek_jenis_c;


		$bg_halaman1 = file_get_contents('./public/aset/bg_cetak.png');
		$base64 = 'data:image/png;base64,' . base64_encode($bg_halaman1);
		$d['bg_base64'] = $base64;

		if (empty($this->request->getGet())) {
			$html = view('page/admin/cetak_hasil_ujian', $d);
			// $webRoot = '/';
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html, 'UTF-8');
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'portrait');
			// Render the HTML as PDF
			$dompdf->set_option('defaultMediaType', 'all');
			$dompdf->set_option('isFontSubsettingEnabled', true);
			$dompdf->set_option('isRemoteEnabled', true);
			// $dompdf->setBasePath($webRoot);
			// Output the generated PDF to Browser
			// $dompdf->stream();
			$dompdf->render();
			$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
			exit(0);
		} else {
			return view('page/admin/cetak_hasil_ujian', $d);
		}
	}

	public function cetak_hasil_seleksi_staff($id_ujian, $id_peserta) {

		// get detil peserta 
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$peserta->select('*');
		$get_peserta = $peserta->get()->getRowArray();

		// hasil rekomendasi
		$get_hasil_rekomendasi = $this->db->table('hasil_rekomendasi')
		->where('id_peserta', $id_peserta)
		->where('id_ujian', $id_ujian)
		->get()->getRowArray();


		$detil_tes = $this->db->table('m_ujian_peserta');
		$detil_tes->where('id_peserta', $id_peserta);
		$detil_tes->where('id_ujian', $id_ujian);
		$detil_tes->orderBy('last_activity', 'desc');
		$detil_tes->limit(1);
		$get_detil_tes = $detil_tes->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];
		$level_test = $get_peserta['level_test'];

		$syarat_kompetensi = $this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test];
		$kompetensi_staff = $this->setting_kompetensi_seleksi_staff;

		// nilai kompetensi
		$nilai_kompetensi = $this->db->table('nilai_kompetensi');
		$nilai_kompetensi->where('id_ujian', $id_ujian);
		$nilai_kompetensi->where('id_peserta', $id_peserta);
		$nilai_kompetensi->where('jenis_tes', 1);
		$nilai_kompetensi->select('*');
		$get_nilai_kompetensi = $nilai_kompetensi->get()->getResultArray();

		// nilai c
		$nilai_c = $this->db->table('m_nilai_c');
		$nilai_c->where('id_ujian', $id_ujian);
		$nilai_c->where('id_peserta', $id_peserta);
		$nilai_c->select('*');
		$get_nilai_c = $nilai_c->get()->getResultArray();
		// nilai d
		$nilai_d = $this->db->table('m_nilai_d');
		$nilai_d->where('id_ujian', $id_ujian);
		$nilai_d->where('id_peserta', $id_peserta);
		$nilai_d->select('*');
		$get_nilai_d = $nilai_d->get()->getResultArray();

		$nilai_kompetensi_new = [];
		$nilai_c = [];
		$nilai_d = [];

		if (!empty($get_nilai_kompetensi)) {
			foreach ($get_nilai_kompetensi as $nkv) {
				$idx = $nkv['kompetensi'];
				$nilai_kompetensi_new_satu = [
					'nilai'=>$nkv['nilai'],
					'nilai_pembulatan'=>$nkv['nilai_pembulatan'],
					'nilai_konversi_keputusan'=>$nkv['nilai_konversi_keputusan'],
				];

				$nilai_kompetensi_new[$idx] = $nilai_kompetensi_new_satu;
			}
		}

		if (!empty($get_nilai_c)) {
			foreach ($get_nilai_c as $nkv) {
				$idx = $nkv['id_aspek'];
				$nilai_c_satu = [
					'nilai'=>intval($nkv['nilai']),
					'nilai_konversi'=>intval($nkv['nilai_konversi']),
					'detil_aspek'=>$this->aspek_jenis_c[$idx],
				];

				$nilai_c[$idx] = $nilai_c_satu;
			}
		}

		if (!empty($get_nilai_d)) {
			foreach ($get_nilai_d as $nkv) {
				$idx = $nkv['id_aspek'];
				$nilai_d_satu = [
					'nilai'=>intval($nkv['nilai']),
					'nilai_konversi'=>intval($nkv['nilai_konversi']),
					'detil_aspek'=>$this->aspek_jenis_d[$idx],
				];

				$nilai_d[$idx] = $nilai_d_satu;
			}
		}

		if (empty($get_nilai_kompetensi)) {
			exit('Nilai kompetensi belum masuk...');
		}

		if (empty($get_nilai_c)) {
			exit('Nilai C belum masuk...');
		}

		if (empty($get_nilai_d)) {
			exit('Nilai D belum masuk...');
		}


		$d['get_hasil_rekomendasi'] = $get_hasil_rekomendasi;
		$d['syarat_kompetensi'] = $syarat_kompetensi;
		$d['sistem_seleksi'] = $this->sistem_seleksi;
		$d['kompetensi_staff'] = $kompetensi_staff;
		$d['id_ujian'] = $id_ujian;
		$d['peserta'] = $get_peserta;
		$d['konversi'] = $this->setting_kompetensi_seleksi_staff;
		$d['nilai_kompetensi'] = $nilai_kompetensi_new;
		$d['nilai_c'] = $nilai_c;
		$d['nilai_d'] = $nilai_d;
		$d['detil_tes'] = $get_detil_tes;


		$bg_halaman1 = file_get_contents('./public/aset/bg_cetak.png');
		$base64 = 'data:image/png;base64,' . base64_encode($bg_halaman1);
		$d['bg_base64'] = $base64;

		if (empty($this->request->getGet())) {
			$html = view('page/admin/cetak_hasil_ujian_seleksi_staff', $d);
			// $webRoot = '/';
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html, 'UTF-8');
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'portrait');
			// Render the HTML as PDF
			$dompdf->set_option('defaultMediaType', 'all');
			$dompdf->set_option('isFontSubsettingEnabled', true);
			$dompdf->set_option('isRemoteEnabled', true);
			// $dompdf->setBasePath($webRoot);
			// Output the generated PDF to Browser
			// $dompdf->stream();
			$dompdf->render();
			$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
			exit(0);
		} else {
			return view('page/admin/cetak_hasil_ujian_seleksi_staff', $d);
		}	
	}

	public function cetak_hasil_assesment($id_ujian, $id_peserta) {

		// get detil peserta 
		$peserta = $this->db->table('m_peserta');
		$peserta->where('id', $id_peserta);
		$peserta->select('*');
		$get_peserta = $peserta->get()->getRowArray();

		// hasil rekomendasi
		$get_hasil_rekomendasi = $this->db->table('hasil_rekomendasi')
		->where('id_peserta', $id_peserta)
		->where('id_ujian', $id_ujian)
		->get()->getRowArray();

		$detil_tes = $this->db->table('m_ujian_peserta');
		$detil_tes->where('id_peserta', $id_peserta);
		$detil_tes->where('id_ujian', $id_ujian);
		$detil_tes->orderBy('last_activity', 'desc');
		$detil_tes->limit(1);
		$get_detil_tes = $detil_tes->get()->getRowArray();

		$jenis_tes = $get_peserta['jenis_tes'];
		$jenis_staff = $get_peserta['jenis_staff'];
		$level_test = $get_peserta['level_test'];

		$syarat_kompetensi = $this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test];
		$kompetensi_assesment = $this->setting_kompetensi_assesment;

		// nilai kompetensi
		$nilai_kompetensi = $this->db->table('nilai_kompetensi');
		$nilai_kompetensi->where('id_ujian', $id_ujian);
		$nilai_kompetensi->where('id_peserta', $id_peserta);
		$nilai_kompetensi->where('jenis_tes', 2);
		$nilai_kompetensi->select('*');
		$get_nilai_kompetensi = $nilai_kompetensi->get()->getResultArray();

		// nilai e
		$nilai_e = $this->db->table('m_nilai_e');
		$nilai_e->where('id_ujian', $id_ujian);
		$nilai_e->where('id_peserta', $id_peserta);
		$nilai_e->select('*');
		$get_nilai_e = $nilai_e->get()->getResultArray();

		$nilai_kompetensi_new = [];
		$nilai_e = [];

		if (!empty($get_nilai_kompetensi)) {
			foreach ($get_nilai_kompetensi as $nkv) {
				$idx = $nkv['kompetensi'];
				$nilai_kompetensi_new_satu = [
					'nilai'=>$nkv['nilai'],
					'nilai_pembulatan'=>$nkv['nilai_pembulatan'],
					'nilai_konversi_keputusan'=>$nkv['nilai_konversi_keputusan'],
				];

				$nilai_kompetensi_new[$idx] = $nilai_kompetensi_new_satu;
			}
		}

		if (!empty($get_nilai_e)) {
			foreach ($get_nilai_e as $nkv) {
				$idx = $nkv['id_aspek'];
				$nilai_e_satu = [
					'nilai'=>intval($nkv['nilai']),
					'nilai_konversi'=>intval($nkv['nilai_konversi']),
					'detil_aspek'=>$this->aspek_jenis_e[$idx],
				];

				$nilai_e[$idx] = $nilai_e_satu;
			}
		}

		if (empty($nilai_kompetensi_new)) {
			exit('Nilai kompetensi belum masuk...');
		}

		if (empty($nilai_e)) {
			exit('Nilai E belum masuk...');
		}


		$d['get_hasil_rekomendasi'] = $get_hasil_rekomendasi;
		$d['syarat_kompetensi'] = $syarat_kompetensi;
		$d['sistem_seleksi'] = $this->sistem_seleksi;
		$d['kompetensi_assesment'] = $kompetensi_assesment;
		$d['id_ujian'] = $id_ujian;
		$d['peserta'] = $get_peserta;
		$d['konversi'] = $this->setting_kompetensi_seleksi_staff;
		$d['nilai_kompetensi'] = $nilai_kompetensi_new;
		$d['nilai_e'] = $nilai_e;
		$d['detil_tes'] = $get_detil_tes;


		$bg_halaman1 = file_get_contents('./public/aset/bg_cetak.png');
		$base64 = 'data:image/png;base64,' . base64_encode($bg_halaman1);
		$d['bg_base64'] = $base64;

		if (empty($this->request->getGet())) {
			$html = view('page/admin/cetak_hasil_ujian_assesment', $d);
			// $webRoot = '/';
			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html, 'UTF-8');
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'portrait');
			// Render the HTML as PDF
			$dompdf->set_option('defaultMediaType', 'all');
			$dompdf->set_option('isFontSubsettingEnabled', true);
			$dompdf->set_option('isRemoteEnabled', true);
			// $dompdf->setBasePath($webRoot);
			// Output the generated PDF to Browser
			// $dompdf->stream();
			$dompdf->render();
			$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
			exit(0);
		} else {
			return view('page/admin/cetak_hasil_ujian_assesment', $d);
		}
	}

	public function detil() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$id = intval($p['id']);
			$builder = $this->db->table('m_ujian');
            $builder->where('id', $id);
            $builder->select('*');
            // echo $builder->getCompiledSelect();
            $data = $builder->get()->getRowArray();

            // pecah tgl mulai
            if (!empty($data)) {
            	$pc_waktu_mulai = explode(" ", $data['waktu_mulai']);
            	$pc_waktu_selesai = explode(" ", $data['waktu_selesai']);

            	$data['waktu_mulai_tgl'] = $pc_waktu_mulai[0];
            	$data['waktu_mulai_jam'] = substr($pc_waktu_mulai[1], 0, 5);
            	$data['waktu_selesai_tgl'] = $pc_waktu_selesai[0];
            	$data['waktu_selesai_jam'] = substr($pc_waktu_selesai[1], 0, 5);

	            return $this->response->setJSON([
	            	'success'=>true,
	            	'results'=>$data
	            ]);
            } else {
            	throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
		} else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
	}

	public function simpan() {
		if ($this->request->isAJAX()) {
			$p = $this->request->getPost();

			$validate = $this->validation->run($p, 'kompetensi');
			$errors = $this->validation->getErrors();


			$mode = $p['_mode'];
			$id = $p['_id'];

			$builder = $this->db->table('m_ujian');

			$gabung_waktu_mulai = $p['waktu_mulai_tgl']." ".$p['waktu_mulai_jam'];
			$gabung_waktu_selesai = $p['waktu_selesai_tgl']." ".$p['waktu_selesai_jam'];

			$data = [
		        'nama' => $p['nama'],
		        'waktu_mulai' => $gabung_waktu_mulai,
		        'waktu_selesai' => $gabung_waktu_selesai,
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

			$builder = $this->db->table('m_ujian');

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

	public function peserta($id_ujian) {
		$b2 = $this->db->table('m_peserta a');
		if (session('level') == 2) {
			$b2->where('a.jenis_tes', 1);
			$b2->where('a.jenis_staff', 1);
		}
        $b2->where('a.gelombang', $id_ujian);
        $b2->join('m_ujian b', 'b.id = a.gelombang');
        $b2->select('a.*, b.nama nm_gelombang');
        $b2->orderBy('a.id', 'asc');
        $q_datanya = $b2->get()->getResultArray();


		$detil_gelombang = $this->db->table('m_ujian a');
        $detil_gelombang->where('a.id', $id_ujian);
        $q_detil_gelombang = $detil_gelombang->get()->getRowArray();

		$setting_sistem_seleksi = $this->sistem_seleksi;

        $d['setting_sistem_seleksi'] = $setting_sistem_seleksi;
        $d['data'] = $q_datanya;
        $d['data_gelombang'] = $q_detil_gelombang;
		$d['p'] = 'admin/ujian_peserta';
		$d['js'] = 'ujian';
		$d['title'] = 'Daftar Peserta Ujian : '.$q_detil_gelombang['nama'];
		$d['id_ujian'] = $id_ujian;
		return view('template_admin', $d);
	}

	public function peserta_tambah($id_ujian) {
		$data_peserta = $this->db->table('m_peserta a');
		if (session('level') == 2) {
			$data_peserta->where('a.jenis_tes', 1);
			$data_peserta->where('a.jenis_staff', 1);
		}
		$data_peserta->where('gelombang < 1');
        $data_peserta->select('a.*');
        $data_peserta->orderBy('a.id', 'asc');
        $get_data_peserta = $data_peserta->get()->getResultArray();

        
		$detil_gelombang = $this->db->table('m_ujian a');
        $detil_gelombang->where('a.id', $id_ujian);
        $q_detil_gelombang = $detil_gelombang->get()->getRowArray();

		$setting_sistem_seleksi = $this->sistem_seleksi;

        $d['setting_sistem_seleksi'] = $setting_sistem_seleksi;
        $d['data'] = $get_data_peserta;
        $d['data_gelombang'] = $q_detil_gelombang;
		$d['p'] = 'admin/ujian_peserta_tambah';
		$d['js'] = 'ujian';
		$d['title'] = 'Tambah Peserta Ujian : '.$q_detil_gelombang['nama'];
		$d['id_ujian'] = $id_ujian;
		return view('template_admin', $d);
	}

	public function peserta_tambah_simpan() {
		$p = $this->request->getPost();

		$id_gelombang = intval($p['id_ujian']);

		$jumlah_ditambahkan = 0;
		if (!empty($p['id_peserta'])) {
			foreach ($p['id_peserta'] as $pk) {
				// langsung update nama gelombang nya

				$update_gelombang = $this->db->table('m_peserta');
		        $update_gelombang->where('id', $pk);
		        $q_update_gelombang = $update_gelombang->update([
		        	'gelombang'=>$id_gelombang
		        ]);

		        $jumlah_ditambahkan++;

			}
		}

		session()->setFlashdata('notif_update_gelombang', '<div class="alert alert-success">'.$jumlah_ditambahkan.' berhasil ditambahkan..</div>');
        return redirect()->to(base_url('admin/ujian/peserta/'.$id_gelombang));
	}

	public function peserta_hapus($id_ujian, $id_peserta) {
		$update_gelombang = $this->db->table('m_peserta');
        $update_gelombang->where('id', $id_peserta);
        $q_update_gelombang = $update_gelombang->update([
        	'gelombang'=>0
        ]);


		session()->setFlashdata('notif_update_gelombang', '<div class="alert alert-success">Berhasil dihapus</div>');
        return redirect()->to(base_url('admin/ujian/peserta/'.$id_ujian));
	}

	public function batalkan($id_ujian, $id_peserta) {
		$del_hasil_rekomendasi = $this->db->table('hasil_rekomendasi');
        $del_hasil_rekomendasi->where('id_ujian', $id_ujian);
        $del_hasil_rekomendasi->where('id_peserta', $id_peserta);
        $q_del_hasil_rekomendasi = $del_hasil_rekomendasi->delete();

		$del_m_nilai_a = $this->db->table('m_nilai_a');
        $del_m_nilai_a->where('id_ujian', $id_ujian);
        $del_m_nilai_a->where('id_peserta', $id_peserta);
        $q_del_m_nilai_a = $del_m_nilai_a->delete();

		$del_m_nilai_b = $this->db->table('m_nilai_b');
        $del_m_nilai_b->where('id_ujian', $id_ujian);
        $del_m_nilai_b->where('id_peserta', $id_peserta);
        $q_del_m_nilai_b = $del_m_nilai_b->delete();

		$del_m_nilai_c = $this->db->table('m_nilai_c');
        $del_m_nilai_c->where('id_ujian', $id_ujian);
        $del_m_nilai_c->where('id_peserta', $id_peserta);
        $q_del_m_nilai_c = $del_m_nilai_c->delete();

		$del_m_nilai_d = $this->db->table('m_nilai_d');
        $del_m_nilai_d->where('id_ujian', $id_ujian);
        $del_m_nilai_d->where('id_peserta', $id_peserta);
        $q_del_m_nilai_d = $del_m_nilai_d->delete();

		$del_m_nilai_e = $this->db->table('m_nilai_e');
        $del_m_nilai_e->where('id_ujian', $id_ujian);
        $del_m_nilai_e->where('id_peserta', $id_peserta);
        $q_del_m_nilai_e = $del_m_nilai_e->delete();

		$del_m_ujian_peserta = $this->db->table('m_ujian_peserta');
        $del_m_ujian_peserta->where('id_ujian', $id_ujian);
        $del_m_ujian_peserta->where('id_peserta', $id_peserta);
        $q_del_m_ujian_peserta = $del_m_ujian_peserta->delete();

		$del_nilai_kompetensi = $this->db->table('nilai_kompetensi');
        $del_nilai_kompetensi->where('id_ujian', $id_ujian);
        $del_nilai_kompetensi->where('id_peserta', $id_peserta);
        $q_del_nilai_kompetensi = $del_nilai_kompetensi->delete();

        return redirect()->to(base_url('admin/ujian/lihat_hasil/'.$id_ujian));
	}

	public function lihat_jawaban($id_ujian, $id_peserta) {
		$get_detil_jawaban = $this->db->table('m_ujian_peserta')
							->where('id_ujian', $id_ujian)
							->where('id_peserta', $id_peserta)
							->get()->getResultArray();

		$get_detil_peserta = $this->db->table('m_ujian_peserta a')
							->where('a.id_ujian', $id_ujian)
							->where('a.id_peserta', $id_peserta)
							->join('m_peserta b', 'a.id_peserta = b.id')
							->join('m_ujian c', 'a.id_ujian = c.id')
							->select('a.*, b.nama nm_peserta, b.jenis_tes, b.jenis_staff, b.level_test, c.nama nm_ujian, MIN(a.waktu_mulai) mulai, MAX(a.last_activity) selesai')
							->limit(1)
							->get()->getRowArray();

		$jenis_tes = $get_detil_peserta['jenis_tes'];
		$jenis_staff = $get_detil_peserta['jenis_staff'];
		$level_test = $get_detil_peserta['level_test'];

		$jenis_tes = $this->sistem_seleksi[$jenis_tes]['nama']." - ".$this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['nama']." - ".$this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test]['nama'];


		$d['detil_jawaban'] = $get_detil_jawaban;
		$d['detil_peserta_ujian'] = $get_detil_peserta;
		$d['jenis_tes'] = $jenis_tes;

		$d['p'] = 'admin/ujian_lihat_jawaban';
		$d['js'] = 'ujian';
		$d['title'] = 'Lihat Jawaban';
		$d['id_ujian'] = $id_ujian;
		$d['id_peserta'] = $id_peserta;
		return view('template_admin', $d);

	}

	public function lihat_jawaban_mendatar($id_ujian, $id_peserta) {
		$get_detil_jawaban = $this->db->table('m_ujian_peserta')
							->where('id_ujian', $id_ujian)
							->where('id_peserta', $id_peserta)
							->get()->getResultArray();

		$get_detil_peserta = $this->db->table('m_ujian_peserta a')
							->where('a.id_ujian', $id_ujian)
							->where('a.id_peserta', $id_peserta)
							->join('m_peserta b', 'a.id_peserta = b.id')
							->join('m_ujian c', 'a.id_ujian = c.id')
							->select('a.*, b.nama nm_peserta, b.jenis_tes, b.jenis_staff, b.level_test, c.nama nm_ujian, MIN(a.waktu_mulai) mulai, MAX(a.last_activity) selesai')
							->limit(1)
							->get()->getRowArray();

		$jenis_tes = $get_detil_peserta['jenis_tes'];
		$jenis_staff = $get_detil_peserta['jenis_staff'];
		$level_test = $get_detil_peserta['level_test'];

		$jenis_tes = $this->sistem_seleksi[$jenis_tes]['nama']." - ".$this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['nama']." - ".$this->sistem_seleksi[$jenis_tes]['sub'][$jenis_staff]['level'][$level_test]['nama'];


		$d['detil_jawaban'] = $get_detil_jawaban;
		$d['detil_peserta_ujian'] = $get_detil_peserta;
		$d['jenis_tes'] = $jenis_tes;

		$d['p'] = 'admin/ujian_lihat_jawaban_mendatar';
		$d['js'] = 'ujian';
		$d['title'] = 'Lihat Jawaban';
		$d['id_ujian'] = $id_ujian;
		$d['id_peserta'] = $id_peserta;

		// get detil nilai aspek a
		$d['aspek_a'] = $this->aspek_jenis_a;
		$m_nilai_a = $this->db->table('m_nilai_a')->where('id_ujian', $id_ujian)->where('id_peserta', $id_peserta)->get()->getResultArray();
		$d['m_nilai_a'] = [];
		foreach ($m_nilai_a as $mna) {
			$idx = $mna['id_aspek'];
			$d['m_nilai_a'][$idx] = $mna['nilai'];
		}
		// get detil nilai aspek b
		$d['aspek_b'] = $this->aspek_jenis_b;
		$m_nilai_b = $this->db->table('m_nilai_b')->where('id_ujian', $id_ujian)->where('id_peserta', $id_peserta)->get()->getResultArray();
		$d['m_nilai_b'] = [];
		foreach ($m_nilai_b as $mnb) {
			$idx = $mnb['id_aspek'];
			$d['m_nilai_b'][$idx] = $mnb['nilai'];
		}

		return view('template_admin', $d);

	}
}
