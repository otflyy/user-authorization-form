<?php

namespace App\Classes;

class Request {
	
	private $methods_array;
	protected $request_method;
	public $input;
	
	public function __construct()
	{
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->methods_array = ['GET' => $_GET, 'POST' => $_POST];
		$this->input = $this->getRequest();
	}
	
	private function getRequest(): array
	{
		return !in_array($this->request_method, ['GET', 'POST']) ? $this->methods_array['GET'] : $this->methods_array[$this->request_method];
	}
}
