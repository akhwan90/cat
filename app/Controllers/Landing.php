<?php 
namespace App\Controllers;
use CodeIgniter\Controller;

class Landing extends BaseController {
	
	public function __construct() {
    }

	public function index() {

		$d['title'] = "Online Test Assesment";
		$d['p'] = "landing";
		
		return view('landing', $d);
	}


}
