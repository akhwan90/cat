<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Ceklogin_peserta implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
	    if (!session('is_login_peserta')) {
	    	return redirect()->to(base_url('peserta/auth'));
	    }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
