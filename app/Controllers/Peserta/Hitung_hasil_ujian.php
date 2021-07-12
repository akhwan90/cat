<?php 
namespace App\Controllers\Peserta;
use CodeIgniter\Controller;
use App\Controllers\BaseController;

class Hitung_hasil_ujian extends BaseController {
	
	public function hitung_satu() {
		$p = $this->request->getPost();
		$id_ujian = $p['id_ujian'];
		$id_peserta = session('kon_id');
		$id_box = $p['id_box'];
		$jawaban = empty($p['jawaban']) ? "" : $p['jawaban'];
		$id_array_jawaban = ($id_box - 1);



		$db1 = $this->db->table('tr_ikut_ujian');
		$db1->where('id_tes', $id_ujian);
		$db1->where('id_user', $id_peserta);
		$db1->select('list_soal, list_jawaban');
		$get_detil_jawaban = $db1->get()->getRowArray();

		// get detil jawaban per id_ujian, id_peserta, jenis, bagian, konvert ke array
		$detil_soal_to_array = json_decode($get_detil_jawaban['list_soal'], true);
		$detil_jawaban_to_array = json_decode($get_detil_jawaban['list_jawaban'], true);

		$key_list_jawaban = $detil_soal_to_array[$id_array_jawaban];

		// ambil index sesuai yg dibawa oleh parameter
		$detil_jawaban_per_soal = $detil_jawaban_to_array[$key_list_jawaban];
		// ambil kunci
		$kunci_jawaban = $detil_jawaban_per_soal['kunci'];

		// init var awal
		$success = false;
		$message = "Jawaban salah";
		
		// cek jawaban sama dengan kunci tidak
		if (strtoupper($kunci_jawaban) == strtoupper($jawaban)) {
			$success = true;
			$message = "Jawaban benar";
		}

		// buat variabel baru, untuk update db
		// copy isinya dari detil per soal
		$new_kunci_jawaban = $detil_jawaban_per_soal;
		$new_kunci_jawaban['jawaban'] = $jawaban;
		$new_kunci_jawaban['status'] = 0;
		if ($success) {
			$new_kunci_jawaban['status'] = 1;
		}

		// replace dari detil jawaban lama ke new
		$detil_jawaban_to_array[$key_list_jawaban] = $new_kunci_jawaban;

		// update db, hanya field detil saja..
		$update = $this->db->table('tr_ikut_ujian');
		$update->where('id_tes', $id_ujian);
		$update->where('id_user', $id_peserta);
		$update->update([
			'list_jawaban'=>json_encode($detil_jawaban_to_array),
			// 'last_activity'=>date('Y-m-d H:i:s')
		]);

		log_message('error', 'Success: '.$success.', message: '.$message);

		$ret = [
        	'success'=>true,
        	'message'=>'OK',
        	'jawaban'=>$jawaban,
        	'add'=>0,
		];
		return $this->response->setJSON($ret);
	}


	public function selesai($id_ujian) {
		// get detil peserta 
		$detil_peserta = $this->db->table('tr_ikut_ujian a');
		$detil_peserta->join('tr_guru_tes b', 'a.id_tes = b.id');
		$detil_peserta->join('m_siswa c', 'a.id_user = c.id');
		$detil_peserta->join('m_admin d', "d.kon_id = a.id_user AND d.level = 'siswa'");
		$detil_peserta->where('a.id_tes', $id_ujian);
		$detil_peserta->where('a.id_user', session('kon_id'));
		$detil_peserta->select('a.*, b.*, c.*');
		$get_detil_peserta = $detil_peserta->get()->getRowArray();

		if (empty($get_detil_peserta)) {
			return redirect()->to(base_url('peserta/ujian'));
		} else {
			$get_jawaban_to_array = json_decode($get_detil_peserta['list_jawaban'], true);

			$jumlah_benar = 0;
			$jumlah_salah = 0;
			$jumlah_soal = 0;
			if (!empty($get_jawaban_to_array)) {
				foreach ($get_jawaban_to_array as $jawaban) {
					if ($jawaban['status'] == 1) {
						$jumlah_benar++;
					} else {
						$jumlah_salah++;
					}
					$jumlah_soal++;
				}
			}

			// echo "Benar: ".$jumlah_benar."<br>Salah: ".$jumlah_salah."<br>Jumlah: ".$jumlah_soal;
			$nilai_perseratus = ($jumlah_benar / $jumlah_soal) * 100;

			$this->db->table('tr_ikut_ujian')
			->where('id_tes', $id_ujian)
			->where('id_user', session('kon_id'))
			->update([
				'jml_benar'=>$jumlah_benar,
				'nilai'=>$nilai_perseratus,
				'status'=>'Y'
			]);

			return redirect()->to(base_url('peserta/ujian'));
		}

	}
}
