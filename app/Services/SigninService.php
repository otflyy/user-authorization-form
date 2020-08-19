<?php

namespace App\Services;

use PDO;

use App\Classes\Request;
use App\Classes\DataBase;
use App\Classes\Session;

class SigninService extends Request {
	
	private $message_error;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->message_error = [];
		
		$this->checkFields();
	}
	
	public function login()
	{
		try
		{
			$pdo = new DataBase();
		
			$query = "
				SELECT id, fio
				FROM users
				WHERE 
				( email = '" . htmlspecialchars( trim($this->input['login']) ) . "'
				OR
				login = '" . htmlspecialchars( trim($this->input['login']) ) . "'
				)
				AND
				password = '" . crypt(trim($this->input['password']), trim($this->input['password'])) . "'";
			
			$stmt = $pdo->db->prepare( $query );
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
		}
		
		$row = $stmt->fetch( PDO::FETCH_OBJ );
		
		$sess = new Session();
		$sess_result = $sess->set('user_id', $row->id);
		
		if($sess_result === true)
			$this->setMessageError(false, ['private' => 'Добро пожаловать ' . $row->fio . '!'], []);
		else
			$this->setMessageError(true, ['password' => 'Ошибка авторизации! Попробуйте авторизоваться снова!'], []);
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
		if( trim($this->input['login']) === '' )
			return $this->setMessageError(true, ['login' => 'Поле Email или Login не может быть пустым!']);
		
		if( trim($this->input['password']) === '' )
			return $this->setMessageError(true, ['password' => 'Поле Пароль не может быть пустым!']);
		
		if( $this->checkUser() === 0 )
			return $this->setMessageError(true, ['password' => 'Не верный Пользователь или Пароль!']);
		
		return $this->setMessageError();
	}
	
	private function checkUser(): int
	{
		try
		{
			$pdo = new DataBase();
		
			$query = "
				SELECT COUNT(*) as count
				FROM users
				WHERE 
				( email = '" . htmlspecialchars( trim($this->input['login']) ) . "'
				OR
				login = '" . htmlspecialchars( trim($this->input['login']) ) . "'
				)
				AND
				password = '" . crypt(trim($this->input['password']), trim($this->input['password'])) . "'";
			
			$stmt = $pdo->db->prepare( $query );
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
		}
		
		$row = $stmt->fetch( PDO::FETCH_OBJ );
		
		return (int)$row->count;
	}
	
	private function setMessageError(bool $error = false, array $message = [], array $input_value = [])
	{
		$this->message_error = ['error' => $error, 'message' => $message, 'input_value' => $input_value];
	}
}
