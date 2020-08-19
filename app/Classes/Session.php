<?php

namespace App\Classes;

class Session {
	
	private $session;
	
	public function __construct ()
	{
		$this->dir(dirname(__DIR__, 2).'/tmp');
		session_start();
		$this->session = &$_SESSION;
	}
	
	private function dir($dir = null): string
	{
		if($dir and file_exists($dir) and is_dir($dir))
			return session_save_path($dir);
		else
			return session_save_path();
	}
	
	public function set(string $key = '', $value = null): bool
	{
		if(trim($key) === '' or !$value)
			return false;
		
		$this->session[trim($key)] = $value;
		
		return true;
	}
	
	public function input(string $name = '')
	{
		return $this->session[trim($name)] ?? null;
	}
	
	public function delete(array $keys = []): bool
	{
		if(empty($keys))
			$this->session = [];
		else
			foreach($keys as $row)
				if(isset($this->session[$row]))
					unset($this->session[$row]);
		
		return true;
	}
}
