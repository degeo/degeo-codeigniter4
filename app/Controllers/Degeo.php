<?php namespace App\Controllers;

class Degeo extends ApplicationController
{

	public function index()
	{
		// Set Document Title
		$this->document->title( 'Welcome to the DeGeo CodeIgniter 4 Starter Application' );

		// Add Second Breadcrumb
		$this->breadcrumbs->add( 'home/degeo', $this->document->title(), 2 );

		// Data Processing (Model calls)
		//
		// $model = new \App\Models\Modelname();
		// $records = $model->findAll();
		//

		// Add Data for the Renderer
		$this->renderer->setVar( 'test', 'test successful' );

		// Add Several Pieces of Data for the Renderer
		// $this->renderer->setData( $data = [] );

		// Remove all Data from the Renderer
		// $this->renderer->resetData();

		// Add Info Message
		$this->messages->add( 'info', 'This is how you add an info message.', 1 );

		// Add Layout
		$this->layout->add( 'degeo_welcome_message', 100 );

		// Render Layout
		return $this->render();
	} // function

	//--------------------------------------------------------------------

}
