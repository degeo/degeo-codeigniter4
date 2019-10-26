<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use \DeGeo\Libraries\Hosts;
use \DeGeo\Objects\Document;
use \DeGeo\Libraries\Metatags_queue;
use \DeGeo\Libraries\Messages_queue;
use \DeGeo\Libraries\Breadcrumbs_queue;
use \DeGeo\Libraries\Codeigniter4_layout;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Auto Render
	 * Flag for Rendering on __destruct
	 *
	 * @var boolean
	 */
	protected $auto_render = false;

	/**
	 * Application
	 * Application Config containing name, description and keywords.
	 *
	 * @var object
	 */
	protected $application;

	/**
	 * Hosts
	 * Hosts list for loading resources.
	 *
	 * @var object
	 */
	protected $hosts;

	/**
	 * Document
	 * Document information containing page title, description, etc.
	 *
	 * @var object
	 */
	protected $document;

	/**
	 * Meta Tags
	 * Meta Tags to render in the HTML `head` tag.
	 *
	 * @var object
	 */
	protected $metatags;

	/**
	 * Messages
	 * Messages Queue
	 *
	 * @var object
	 */
	protected $messages;

	/**
	 * Breadcrumbs
	 * Breadcrumb trail for the application.
	 *
	 * @var object
	 */
	protected $breadcrumbs;

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

		// Application Config
		$this->application = config( 'Application' );

		// DeGeo Hosts Library
		$this->hosts = new \DeGeo\Libraries\Hosts();

		// DeGeo Document Library
		$this->document = new \DeGeo\Objects\Document();

		// Set Default Document Title to Application Name
		$this->document->title( $this->application->name );

		// Set Default Document Description to Application Descrpition
		$this->document->description( $this->application->description );

		// Set Default Document Attributes
		// $this->document->attributes( (array) );

		// DeGeo Metatags Queue Library
		$this->metatags = new \DeGeo\Libraries\Metatags_queue();

		// Set Default Meta Tag Description
		$this->metatags->add( '<meta name="description" content="' . $this->application->description . '"/>', 1 );

		// Set Default Meta Tag Keywords
		$this->metatags->add( '<meta name="keywords" content="' . $this->application->keywords . '"/>', 2 );

		// DeGeo Messages Queue Library
		$this->messages = new \DeGeo\Libraries\Messages_queue();

		// DeGeo Breadcrumbs Queue Library
		$this->breadcrumbs = new \DeGeo\Libraries\Breadcrumbs_queue();

		// Add First Breadcrumb
		$this->breadcrumbs->add( '', $this->application->name, 1 );

		// DeGeo CodeIgniter 4 Layout Library
		$this->layout = new \DeGeo\Libraries\Codeigniter4_layout();
	} // function

	/**
	 * Build Data
	 * Builds data array to be passed to Layout render method.
	 *
	 * @param  array $additional_data
	 * @return array
	 */
	protected function build_data($additional_data = [])
	{
		$data = [
			'application' => $this->application,
			'hosts'       => $this->hosts,
			'document'    => $this->document,
			'metatags'    => $this->metatags,
			'messages'    => $this->messages,
			'breadcrumbs' => $this->breadcrumbs->get_queue(),
		];

		if (! is_array( $additional_data ) && ! empty( $additional_data ))
		{
			$data = array_merge( $data, $additional_data );
		}

		return $data;
	} // function

	/**
	 * Render
	 * Builds data array to be passed to Layout render method and renders the layout.
	 *
	 * @return string
	 */
	protected function render()
	{
		// Add Data for Rendering
		$data = $this->build_data();

		// Render Layout
		return $this->layout->render( $data );
	} // function

	/**
	 * Destruct
	 * Automatically renders the Layout.
	 */
	public function __destruct()
	{
		if ($this->auto_render === true)
		{
			echo $this->render();
		}

		return;
	} // function

} // class
