<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Ceklogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
	    if (!session('is_login')) {
	    	return redirect()->to(base_url('admin/auth'));
	    }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
