<?php
namespace App\Controllers;

use App\Controllers\ApplicationController;
use \App\Models\Users;

class AclController extends ApplicationController
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend ApplicationController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	protected $login_required = false;

	protected $session = null;

	protected $users_model;

	protected $current_user_id = null;
	protected $current_user    = [];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();

		$this->session = \Config\Services::session();

		$this->users_model = new \App\Models\Users();

		if (! empty( $this->session->current_user_id ))
		{
			$this->current_user_id = $this->session->current_user_id;
		}

		if (! empty( $this->current_user_id ))
		{
			$this->current_user = $this->read_user( $this->current_user_id );
		}

		// Login Required Flag.
		if($this->login_required === true && ( empty( $this->current_user ) || empty( $this->current_user_id ) )):
			return $this->validate_user();
		endif;
	} // function

	protected function read_user($user_id)
	{
		$user = $this->users_model->find( $user_id );

		if (! empty( $user ))
		{
			return $user;
		}

		return false;
	} // function

	protected function validate_user()
	{
		if ($this->user_is_logged_in() === true)
		{
			return true;
		}

		$this->_show_login();

		return false;
	} // function

	protected function user_is_logged_in()
	{
		if (empty( $this->current_user_id ))
		{
			return false;
		}

		if (empty( $this->current_user ))
		{
			return false;
		}

		$this->current_user = $this->read_user( $this->current_user_id );

		return true;
	} // function

	protected function _show_create()
	{
		// @TODO - show create account form
		echo 'creat form';
	} // function

	protected function _create($email, $password)
	{
		$data = [
			'user_email'    => $email,
			'user_password' => $password,
		];

		$user_id = $this->users_model->insert( $data );
	} // function

	protected function _show_login()
	{
		// @TODO - show login form
		echo 'login form';

		return $this->render();
	} // function

	protected function _login($email, $password)
	{
		$where = [
			'user_email'    => $email,
			'user_password' => $password,
		];

		$user = $this->users_model->where( $where )->first();

		if (empty( $user ))
		{
			return false;
		}

		if (array_key_exists( 'user_id', $user ) && ! empty( $user['user_id'] ))
		{
			$this->current_user_id = $user_id;
		}

		if (empty( $this->current_user_id ))
		{
			return false;
		}

		$this->session->current_user_id = $this->current_user_id;
		$this->current_user             = $user;

		return $user;
	} // function

	protected function _logout()
	{
		return $this->session->destroy();
	} // function

	protected function current_user_id()
	{
		if (empty( $this->current_user_id ))
		{
			return false;
		}

		return $this->current_user_id;
	} // function

	protected function current_user()
	{
		if (empty( $this->current_user ))
		{
			return false;
		}

		return $this->current_user;
	} // function

} // class
