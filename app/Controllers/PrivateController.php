<?php

namespace App\Controllers;

use App\Classes\Request;
use App\Classes\Session;
use App\Classes\Route;

use App\Services\PrivateService;

class PrivateController extends Request {
	
	public function index()
	{
		$sess = new Session();
		
		if( !$sess->input('user_id') )
			return ['uri' => 'signin', 'message' => '', 'input_value' => ''];
		
		$private = new PrivateService();
		
		if( $this->request_method === 'POST' and $private->error() === false )
			$private->update();
		
		if( $this->request_method === 'GET' and isset($_GET['mode']) and $_GET['mode'] === 'logout' )
			$sess->delete();
		
		return ['uri' => 'private', 'message' => $private->message(), 'input_value' => $private->input_value()];
		
	}
}
