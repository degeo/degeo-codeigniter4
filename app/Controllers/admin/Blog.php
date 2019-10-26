<?php namespace App\Controllers\Admin;

use \App\Controllers\AclController;

class Blog extends AclController
{

	protected $login_required = false;

	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		parent::initController( $request, $response, $logger );

		// Validate the current User
		if($this->validate_user() === false):
			echo 'logged out';
		else:
			echo 'logged in';
		endif;
		var_dump( $this->validate_user() );
	} // function

	public function index()
	{
		print_r($this->current_user);
	} // function

	//--------------------------------------------------------------------

}
