<?php

namespace App\Services;

use App\Controllers\SigninController;
use App\Controllers\SignupController;
use App\Controllers\SignoutController;
use App\Controllers\PrivateController;

class RouteService {
	
	private $array_uri;
	private $home_uri;
	private $cur_uri;
	private $routes;
	private $message;
	private $input_value;
	
	public function __construct()
	{
		$this->message = '';
		$this->input_value = '';
		$this->array_uri = explode('/', $_SERVER['REQUEST_URI']);
		$this->home_uri = $this->array_uri[1];
		$this->cur_uri = $this->array_uri[2];
		
		$this->routes = [ /* config routes */
			'signin' => new SigninController,
			'signup' => new SignupController,
			'private' => new PrivateController,
		];
	}
	
	public function redirect()
	{
		$this->getRouting();
		
		header('Location: /'.$this->home_uri.'/public/'.$this->cur_uri.'.php' . $this->message . $this->input_value);
		
		exit;
	}
	
	private function getRouting()
	{
		if(!array_key_exists($this->cur_uri, $this->routes))
			$this->cur_uri = 'signin';
		
		$sing_result = $this->routes[$this->cur_uri]->index();
		$this->cur_uri = $sing_result['uri'];
		$this->message = $sing_result['message'] === '' ? '' : '?' .$sing_result['message'];
		
		if($sing_result['input_value'] !== '')
			$this->input_value = $this->message === '' ? '?'. $sing_result['input_value'] : '&'. $sing_result['input_value'];
	}
}
