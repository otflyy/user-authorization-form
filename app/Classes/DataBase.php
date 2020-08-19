<?php

namespace App\Classes;

use PDO;

class DataBase {
	
	private $host;
	private $db_name;
	private $charset;
	private $db_user;
	private $db_passwd;
	private $connect_str;
	private $options;
	
	public $db;
	
	public function __construct()
	{
		/* data base config */
		
		$this->host = "127.0.0.1";
		$this->db_name = "";
		$this->charset = 'utf8';
		$this->db_user = "root";
		$this->db_passwd = "";
		
		$this->connect_str = "mysql:host=$this->host;dbname=$this->db_name;charset=$this->charset";
		
		$this->options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
		];
		
		$this->db = $this->connect();
	}
	
	private function connect(): object
	{
		try {
			return new PDO($this->connect_str, $this->db_user, $this->db_passwd, $this->options);
		} 
		catch (PDOException $e) {
			die("Can't connect: " . $e->getMessage());
		} 
	}
} 