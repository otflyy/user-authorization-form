<?php

namespace App\Services;

use PDO;
use App\Classes\Request;
use App\Classes\Session;
use App\Classes\DataBase;

class PrivateService extends Request {
	
	private $message_error;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->message_error = [];
		
		$this->checkFields();
	}
	
	public function update()
	{
		try
		{
			$sess = new Session();
			
			$query = "
				UPDATE users SET 
				fio = '" .htmlspecialchars( trim($this->input['fio']) ) ."',
				password = '". crypt(trim($this->input['password']), trim($this->input['password'])) ."'
				WHERE id = ".$sess->input('user_id');
			
			$pdo = new DataBase();
		
			$stmt = $pdo->db->prepare( $query );
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
		}
		
		$this->setMessageError(false, ['form' => 'Учетная запись изменена!'], []);
	}
	
	public function error()
	{
		return $this->message_error['error'];
	}
	
	public function message()
	{
		return $this->message_error['message'] !== [] ? array_key_first($this->message_error['message']). '='.$this->message_error['message'][array_key_first($this->message_error['message'])] : '';
	}
	
	public function input_value()
	{
		$result_string = '';
		
		if ( !empty($this->message_error['input_value']) ) {
			foreach($this->message_error['input_value'] as $key => $row)
				$result_string .= $result_string === '' ? $key .'='. $row : '&'. $key .'='. $row;
		}
		
		return $result_string;
	}
	
	private function checkFields()
	{
		if( trim($this->input['password']) === '' or trim($this->input['confirm_password']) === '' or trim($this->input['password']) !== trim($this->input['confirm_password']) )
			return $this->setMessageError(true, ['password' => 'Некорректный пароль!'], ['input_fio' => trim($this->input['fio'])]);
		
		return $this->setMessageError();
	}
	
	private function setMessageError(bool $error = false, array $message = [], array $input_value = [])
	{
		$this->message_error = ['error' => $error, 'message' => $message, 'input_value' => $input_value];
	}
}
