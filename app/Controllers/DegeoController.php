<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use \DeGeo\Libraries\Hosts;
use \DeGeo\Objects\Document;
use \DeGeo\Libraries\Metatags_queue;
use \DeGeo\Libraries\Messages_queue;
use \DeGeo\Libraries\Breadcrumbs_queue;
use \DeGeo\Libraries\Codeigniter4_layout;
use Config\Services;

class DegeoController extends BaseController
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend DegeoController.
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
	 * Resources
	 * Resource tags to render in the head and footer of a document.
	 *
	 * @var object
	 */
	protected $resources;

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
	 * Layout
	 * Layout Library for queuing layouts.
	 *
	 * @var object
	 */
	protected $layout;

	/**
	 * Renderer
	 * Renderer service for setting data.
	 *
	 * @var object
	 */
	protected $renderer;

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

		// Create Document Object for Page Information
		$this->document = new \DeGeo\Objects\Document( $this->application->name, $this->application->description );

		// Create Metatags Queue and Set Defaults
		$this->metatags = $this->create_default_metatags();

		// Create Resources Queue and Set Defaults
		$this->resources = $this->create_default_resources();

		// DeGeo Messages Queue Library
		$this->messages = new \DeGeo\Libraries\Messages_queue();

		// Create Breadcrumbs Queue and Set Defaults
		$this->breadcrumbs = $this->create_default_breadcrumbs();

		// Create Layout Queue and Set Defaults
		$this->layout = $this->create_default_layout();

		// Load CodeIgniter 4 Renderer Service
		$this->renderer = Services::renderer();
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
		// Array keys are available as variables in all views.
		$data = [
			'application' => $this->application,
			'hosts'       => $this->hosts,
			'document'    => $this->document,
			'metatags'    => $this->metatags,
			'resources'   => $this->resources,
			'messages'    => $this->messages,
			'breadcrumbs' => $this->breadcrumbs,
			'layout'      => $this->layout,
		];

		if (! is_array( $additional_data ) && ! empty( $additional_data ))
		{
			$data = array_merge( $data, $additional_data );
		}

		return $data;
	} // function

	protected function create_default_metatags()
	{
		// DeGeo Metatags Queue Library
		$metatags = new \DeGeo\Libraries\Metatags_queue();

		// Set Default Meta Tag Charset
		$metatags->add( '<meta charset="UTF-8">', 1 );

		// Set Default Meta Tag Viewport
		$metatags->add( '<meta name="viewport" content="width=device-width, initial-scale=1">', 2 );

		// Set Default Meta Tag Description
		$metatags->add( '<meta name="description" content="' . $this->application->description . '"/>', 3 );

		// Set Default Meta Tag Keywords
		$metatags->add( '<meta name="keywords" content="' . $this->application->keywords . '"/>', 4 );

		return $metatags;
	} // function

	protected function create_default_resources()
	{
		// DeGeo Resources Queue Library
		$resources = new \DeGeo\Libraries\Resources_queue();

		// Queue jQuery Resources
		$resources->add( 'header', '<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>', 100 );

		// Queue Bootstrap Resources
		$resources->add( 'header', '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>', 1 );
		$resources->add( 'header', '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>', 101 );

		return $resources;
	} // function

	protected function create_default_breadcrumbs()
	{
		// DeGeo Breadcrumbs Queue Library
		$breadcrumbs = new \DeGeo\Libraries\Breadcrumbs_queue();

		// Add First Breadcrumb
		$breadcrumbs->add( '', $this->application->name, 1 );

		return $breadcrumbs;
	} // function

	protected function create_default_layout()
	{
		// DeGeo CodeIgniter 4 Layout Library
		$layout = new \DeGeo\Libraries\Codeigniter4_layout();

		// Add HTML Layouts
		$layout->add( 'html/bootstrap/header', 1 );
		$layout->add( 'html/bootstrap/footer', 999999 );

		return $layout;
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
