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
			Route::index();
		
		if( $this->request_method === 'POST' ) {
			
			$private = new PrivateService();
			
			if( $private->error() === true )
				return ['uri' => 'private', 'message' => $private->message(), 'input_value' => $private->input_value()];
			else
				$private->update();
			
			return ['uri' => 'private', 'message' => $private->message(), 'input_value' => $private->input_value()];
		}
		
		if( $this->request_method === 'GET' and isset($_GET['mode']) and $_GET['mode'] === 'logout' ) {
			$sess->delete();
			Route::index();
		}
	}
}
