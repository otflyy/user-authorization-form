<?php

namespace App\Services;

use PDO;

use App\Classes\Request;
use App\Classes\DataBase;

class SignupService extends Request {
	
	private $message_error;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->message_error = [];
		
		$this->checkFields();
	}
	
	public function insert()
	{
		try
		{
			$pdo = new DataBase();
		
			$query = "
				INSERT INTO users (email, login, fio, password) 
				VALUES (
				'". htmlspecialchars( trim($this->input['email']) ) ."',
				'". htmlspecialchars( trim($this->input['login']) ) ."',
				'". htmlspecialchars( trim($this->input['fio']) ) ."',
				'". crypt(trim($this->input['password']), trim($this->input['password'])) ."'
				)";
			$stmt = $pdo->db->prepare( $query );
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
		}
		
		$this->setMessageError(false, ['form' => 'Учетная запись создана, теперь вы можете авторизоваться!'], []);
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
		$input_array = [		
			'input_email' => trim($this->input['email']),
			'input_fio' => trim($this->input['fio']),
			'input_login' => trim($this->input['login'])
		];
		
		if( trim($this->input['email']) === '' )
			return $this->setMessageError( true, ['email' => 'Поле Email не может быть пустым!'], $input_array );
		
		if( strpos(explode('@', trim($this->input['email']))[1], '.') === false )
			return $this->setMessageError( true, ['email' => 'Некорректный Email адрес!'], $input_array );
		
		if( $this->checkEmail() > 0 )
			return $this->setMessageError( true, ['email' => 'Пользователь с таким Email адресом уже существует!'], $input_array );
		
		if( trim($this->input['login']) === '' )
			return $this->setMessageError( true, ['login' => 'Поле Login не может быть пустым!'], $input_array );
		
		if( trim($this->input['password']) === '' or trim($this->input['confirm_password']) === '' or trim($this->input['password']) !== trim($this->input['confirm_password']) )
			return $this->setMessageError( true, ['password' => 'Некорректный пароль!'], $input_array );
		
		return $this->setMessageError();
	}
	
	private function checkEmail(): int
	{
		try
		{
			$pdo = new DataBase();
		
			$query = "
				SELECT COUNT(*) as count
				FROM users
				WHERE email = '" . trim($this->input['email']) . "'";
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
