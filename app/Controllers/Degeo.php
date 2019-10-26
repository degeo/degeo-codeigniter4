<?php namespace App\Controllers;

class Degeo extends BaseController
{

	public function index()
	{
		// Set Document Title
		$this->document->title( 'Welcome to the DeGeo CodeIgniter 4 Starter Application' );

		// Add Second Breadcrumb
		$this->breadcrumbs->add( 'home/degeo', $this->document->title(), 2 );

		// Data Processing (Model calls)
		//

		// Add Info Message
		$this->messages->add( 'info', 'This is how you add an info message.', 1 );

		// Add Layout
		$this->layout->add( 'degeo_welcome_message', 1 );

		// Render Layout
		return $this->render();
	} // function

	//--------------------------------------------------------------------

}
