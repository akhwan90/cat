<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Autologin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
	    if (session('is_login')) {
	    	return redirect()->to('admin/dashboard');
	    }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
