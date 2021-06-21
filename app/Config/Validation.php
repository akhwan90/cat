<?php namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
		\Myth\Auth\Authentication\Passwords\ValidationRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------
	//
	
	public $aspek = [
		'nama' => [
			'rules' => 'required',
		],
	];

	public $aspek_errors = [
		'nama' => [
			'required' =>'Nama Harus Diisi',
		],
	];

	// KOMPETENSI
	public $kompetensi = [
		'nama' => [
			'rules' => 'required',
		],
	];

	public $kompetensi_errors = [
		'nama' => [
			'required' =>'Nama Harus Diisi',
		],
	];

	// PESERTA
	public $peserta = [
		'nama' => [
			'rules' => 'required',
		],
		'email' => [
			'rules' => 'valid_email|required',
		],
		'nomor' => [
			'rules' => 'required',
		],
		'level_test' => [
			'rules' => 'required',
		],
		'tmp_lahir' => [
			'rules' => 'required',
		],
		'tgl_lahir' => [
			'rules' => 'required',
		],
		'jenis_kelamin' => [
			'rules' => 'required',
		],
		'pendidikan' => [
			'rules' => 'required',
		],
	];

	public $peserta_errors = [
		'nama' => [
			'required' =>'Nama Harus Diisi',
		],
		'nomor' => [
			'required' =>'Nomor Harus Diisi',
		],
		'level_test' => [
			'required' =>'Level Test Harus Diisi',
		],
		'usia' => [
			'required' =>'Usia Harus Diisi',
		],
		'jenis_kelamin' => [
			'required' =>'Jenis Kelamin Harus Diisi',
		],
		'pendidikan' => [
			'required' =>'Pendidikan Harus Diisi',
		],
	];
}
