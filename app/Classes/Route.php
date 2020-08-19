<?php

namespace App\Classes;

use App\Services\RouteService;
use App\Interfaces\RouteInterface;

class Route implements RouteInterface {
	
	public static function index()
	{
		$service = new RouteService;
		$service->redirect();
	}
}
