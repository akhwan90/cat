<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
// $routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Landing::index');

$routes->get('/admin/auth', 'Auth::index');
$routes->post('/admin/auth/login', 'Auth::login');
$routes->get('/admin/logout', 'Auth::logout');

$routes->group('/admin', ['filter'=>'cek_login'], function($routes) {

    $routes->get('', 'Admin\Dashboard::index');
    $routes->get('ubah_password', 'Admin\Dashboard::ubah_password');
    $routes->post('ubah_password', 'Admin\Dashboard::ubah_password_ok');

    $routes->group('siswa', function($routes) {	
	    $routes->get('', 'Admin\Siswa::index');
	    $routes->post('datatabel', 'Admin\Siswa::datatabel');
	    $routes->post('detil', 'Admin\Siswa::detil');
	    $routes->post('simpan', 'Admin\Siswa::simpan');
	    $routes->post('hapus', 'Admin\Siswa::hapus');
	    $routes->get('aktifkan_user', 'Admin\Siswa::aktifkan_user');
	    $routes->get('aktifkan_user_satu/(:num)', 'Admin\Siswa::aktifkan_user_satu/$1');
	    $routes->post('reset_password', 'Admin\Siswa::reset_password');
	    $routes->get('form_import', 'Admin\Siswa::form_import');
	    $routes->post('import_ok', 'Admin\Siswa::import_ok');
    });

    $routes->group('guru', function($routes) {	
	    $routes->get('', 'Admin\Guru::index');
	    $routes->post('datatabel', 'Admin\Guru::datatabel');
	    $routes->post('detil', 'Admin\Guru::detil');
	    $routes->post('simpan', 'Admin\Guru::simpan');
	    $routes->post('hapus', 'Admin\Guru::hapus');
	    $routes->get('aktifkan_user', 'Admin\Guru::aktifkan_user');
	    $routes->get('aktifkan_user_satu/(:num)', 'Admin\Guru::aktifkan_user_satu/$1');
	    $routes->post('reset_password', 'Admin\Guru::reset_password');
	    $routes->get('form_import', 'Admin\Guru::form_import');
	    $routes->post('import_ok', 'Admin\Guru::import_ok');
    });

    $routes->group('mapel', function($routes) {	
	    $routes->get('', 'Admin\Mapel::index');
	    $routes->post('datatabel', 'Admin\Mapel::datatabel');
	    $routes->post('detil', 'Admin\Mapel::detil');
	    $routes->post('simpan', 'Admin\Mapel::simpan');
	    $routes->post('hapus', 'Admin\Mapel::hapus');
	    $routes->get('form_import', 'Admin\Mapel::form_import');
	    $routes->post('import_ok', 'Admin\Mapel::import_ok');
    });

    $routes->group('ujian', function($routes) {	
	    $routes->get('', 'Admin\Ujian::index');
	    $routes->post('datatabel', 'Admin\Ujian::datatabel');
	    $routes->post('detil', 'Admin\Ujian::detil');
	    $routes->post('simpan', 'Admin\Ujian::simpan');
	    $routes->post('hapus', 'Admin\Ujian::hapus');
	    $routes->get('form_import', 'Admin\Ujian::form_import');
	    $routes->post('import_ok', 'Admin\Ujian::import_ok');
	    $routes->get('refresh_token/(:num)', 'Admin\Ujian::refresh_token/$1');

	    // setting soal
	    $routes->get('setting/(:num)', 'Admin\Ujian_setting::index/$1');
	    $routes->get('setting/(:num)/get_soal', 'Admin\Ujian_setting::get_soal/$1');
	    $routes->get('setting/(:num)/detil_soal', 'Admin\Ujian_setting::detil_soal/$1');
	    $routes->get('setting/(:num)/hapus/(:num)', 'Admin\Ujian_setting::hapus/$1/$2');
	    $routes->get('setting/(:num)/up_soal/(:num)', 'Admin\Ujian_setting::up_soal/$1/$2');
	    $routes->get('setting/(:num)/down_soal/(:num)', 'Admin\Ujian_setting::down_soal/$1/$2');
	    $routes->post('setting/(:num)/simpan_ujian_soal', 'Admin\Ujian_setting::simpan_ujian_soal/$1');
	    // setting peserta
	    $routes->get('setting/(:num)/get_peserta', 'Admin\Ujian_setting::get_peserta/$1');
	    $routes->get('setting/(:num)/detil_peserta', 'Admin\Ujian_setting::detil_peserta/$1');
	    $routes->get('setting/(:num)/hapus_peserta/(:num)', 'Admin\Ujian_setting::hapus_peserta/$1/$2');
	    $routes->post('setting/(:num)/simpan_ujian_peserta', 'Admin\Ujian_setting::simpan_ujian_peserta/$1');
	    $routes->get('setting/(:num)/hasil_peserta/(:num)', 'Admin\Ujian_setting::hasil_peserta/$1/$2');
	    $routes->get('setting/(:num)/cetak_hasil', 'Admin\Ujian_setting::cetak_hasil/$1');
	    // $routes->get('get_detil_soal/(:num)', 'Admin\Ujian_setting::detil_soal/$1');
    });

    $routes->group('soal', function($routes) {	
	    $routes->get('', 'Admin\Soal::index');
	    $routes->post('datatabel', 'Admin\Soal::datatabel');
	    $routes->get('edit/(:num)', 'Admin\Soal::edit/$1');
	    $routes->post('save', 'Admin\Soal::save');
	    $routes->get('import', 'Admin\Soal::import');
	    $routes->post('import_ok', 'Admin\Soal::import_ok');
	    

	    $routes->post('hapus', 'Admin\Soal::hapus');
    });

    $routes->group('instansi', function($routes) {	
	    $routes->get('', 'Admin\Instansi::index');
	    $routes->get('edit', 'Admin\Instansi::edit');
	    $routes->post('save', 'Admin\Instansi::save');
    });


});

$routes->group('/peserta', ['filter'=>'cek_login'], function($routes) {
    $routes->group('ujian', function($routes) {	
	    $routes->get('', 'Peserta\Ujian::index');
	    $routes->post('datatabel', 'Peserta\Ujian::datatabel');
	    $routes->post('detil', 'Peserta\Ujian::detil');
	    $routes->post('simpan', 'Peserta\Ujian::simpan');
	    $routes->post('hapus', 'Peserta\Ujian::hapus');
	    $routes->get('lihat_hasil/(:num)', 'Peserta\Ujian::lihat_hasil/$1');
	    $routes->get('cetak_1/(:num)', 'Peserta\Ujian::cetak_1/$1');
    });

    $routes->group('ikuti_ujian', function($routes) {	
	    $routes->get('ok/(:num)', 'Peserta\Ikuti_ujian::ok/$1');
	    $routes->get('kerjakan/(:num)', 'Peserta\Ikuti_ujian::kerjakan/$1');
    });

    $routes->group('hitung_hasil', function($routes) {	
	    $routes->post('hitung_satu', 'Peserta\Hitung_hasil_ujian::hitung_satu');
	    $routes->get('selesai/(:num)', 'Peserta\Hitung_hasil_ujian::selesai/$1');
    });
});

// $routes->get('/dashboard', 'Admin::dashboard', ['filter'=>'cek_login']);



/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
