<?php

namespace App\Controllers;

use App\Classes\Request;
use App\Classes\DataBase;
use App\Classes\Session;

use App\Services\SignupService;

class SignupController extends Request {
	
	public function index()
	{
		$sess = new Session();
		
		if( $sess->input('user_id') )
			return ['uri' => 'private', 'message' => '', 'input_value' => ''];
		
		if( $this->request_method === 'POST' ) {
			
			$signup = new SignupService();
			
			if( $signup->error() === true )
				return ['uri' => 'signup', 'message' => $signup->message(), 'input_value' => $signup->input_value()];
			else
				$signup->insert();
			
			return ['uri' => $signup->error() === false ? 'signin' : 'signup', 'message' => $signup->message(), 'input_value' => $signup->input_value()];
		}
		
		if( $this->request_method === 'GET' ) {
			return ['uri' => 'signup', 'message' => 'form=Заполните форму', 'input_value' => ''];
		}
	}
}
