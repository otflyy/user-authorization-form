<?php

namespace App\Controllers;

use App\Classes\Request;
use App\Classes\Session;

use App\Services\SigninService;

class SigninController extends Request {
	
	public function index()
	{
		$sess = new Session();
		
		if( $sess->input('user_id') )
			return ['uri' => 'private', 'message' => '', 'input_value' => ''];

		if( $this->request_method === 'POST' ) {
			
			$signin = new SigninService();
			
			if( $signin->error() === true )
				return ['uri' => 'signin', 'message' => $signin->message(), 'input_value' => $signin->input_value()];
			else
				$signin->login();
			
			return ['uri' => $signin->error() === false ? 'private' : 'signin', 'message' => $signin->message(), 'input_value' => $signin->input_value()];
		}
		
		if( $this->request_method === 'GET' ) {
			return ['uri' => 'signin', 'message' => '', 'input_value' => ''];
		}
	}
}
