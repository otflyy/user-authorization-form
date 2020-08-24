<?php

namespace App\Controllers;

use App\Classes\Request;
use App\Classes\Session;

use App\Services\SigninService;

class SigninController extends Request {
	
	private $rout_options;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->rout_options = ['uri' => 'signin', 'message' => '', 'input_value' => ''];
	}
	
	public function index()
	{
		$sess = new Session();
		
		if( $sess->input('user_id') )
			$this->rout_options['uri'] = 'private';

		$signin = new SigninService();
			
		if( $this->request_method === 'POST' ) {
			
			if( $signin->error() === false ) {
				$signin->login();
				$this->rout_options['uri'] = 'private';
			}
			
			$this->rout_options['message'] = $signin->message();
			$this->rout_options['input_value'] = $signin->input_value();
		}
		
		return $this->rout_options;
	}
}
