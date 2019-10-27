<?php
namespace App\Controllers;

use App\Controllers\ApplicationController;
use \App\Models\Users;

class AclController extends ApplicationController
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend AclController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Forces Authentication
	 * Set this property in extending Controllers to true to require login.
	 *
	 * @var boolean
	 */
	protected $login_required = false;

	/**
	 * Account Creation Enabled
	 * Determines whether or not users can create their own accounts.
	 *
	 * @var boolean
	 */
	protected $account_creation_enabled = false;

	/**
	 * Session Library
	 *
	 * @var null
	 */
	protected $session = null;

	protected $users_model;

	protected $current_user_id = null;
	protected $current_user    = [];

	protected $request;

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		$this->request = $request;

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
		if($this->user_is_logged_in() === false):
			return $this->_show_login();
		endif;

		return true;
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
		if($this->account_creation_enabled === false):
			return $this->_show_login();
		endif;

		helper('form');

		if($this->request->getMethod() === 'post' && set_value( 'form_name' ) === 'acl.account.create'):
			$email    = set_value( 'user_email' );
			$password = set_value( 'user_password' );

			$created_user = $this->_create( $email, $password );

			if(! is_null( $created_user )):
				return $this->_show_login();
			endif;
		endif;

		// Set Document Title
		$this->document->title( 'Create an Account for ' . $this->application->name );

		// Add Second Breadcrumb
		$this->breadcrumbs->add( '', $this->document->title(), ' Account Creation', 2 );

		$this->layout->add( 'html/bootstrap/forms/user-create', 20 );

		echo $this->render();

		exit();
	} // function

	protected function _create($email, $password)
	{
		if ($this->account_creation_enabled === false)
		{
			return false;
		}

		$data = [
			'user_email'    => $email,
			'user_password' => $this->_hash_password( $password ),
		];

		$user_id = $this->users_model->insert( $data );

		if ($user_id === false)
		{
			return false;
		}

		return $user_id;
	} // function

	// @TODO - show login form
	protected function _show_login()
	{
		helper('form');

		if($this->request->getMethod() === 'post' && set_value( 'form_name' ) === 'acl.account.login'):
			$email    = set_value( 'user_email' );
			$password = set_value( 'user_password' );

			$logged_in_user = $this->_login( $email, $password );

			if(! is_null( $logged_in_user )):
				return redirect()->to( site_url( uri_string() ) );
			endif;
		endif;

		// Set Document Title
		$this->document->title( 'Login to ' . $this->application->name );

		// Add Second Breadcrumb
		$this->breadcrumbs->add( '', $this->document->title() . ' Login', 2 );

		$this->layout->add( 'html/bootstrap/forms/user-login', 20 );

		echo $this->render();

		exit();
	} // function

	protected function _login($email, $password)
	{
		if (empty( $email ) || empty( $password ))
		{
			throw new \Exception( 'Missing required parameters in ' . __METHOD__ );
		}

		$where = [
			'user_email' => $email,
		];

		$user = $this->users_model->where( $where )->first();

		if (empty( $user ))
		{
			return false;
		}

		if(password_verify( $password, $user['user_password'] ) === false):
			return false;
		endif;

		if (array_key_exists( $this->users_model->primaryKey, $user ) && ! empty( $user[$this->users_model->primaryKey] ))
		{
			$this->current_user_id = $user[$this->users_model->primaryKey];
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

	protected function _hash_password($password)
	{
		if (empty( $password ))
		{
			throw new \Exception( 'Missing parameter for ' . __METHOD__ );
		}

		$password = password_hash( $password, PASSWORD_BCRYPT );

		return $password;
	} // function

} // class
