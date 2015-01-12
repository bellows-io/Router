<?php

namespace Router;

class Router {

	protected $routes = array();

	public function __construct() {

	}

	public function register($pattern) {
		$this->routes[] = new Route($pattern);
	}

	public function route($uri) {
		foreach ($this->routes as $route) {
			if ($routeData = $route->match($uri)) {
				return $routeData;
			}
		}
		return null;
	}

	public function build($arguments) {
		$tokens = array_keys($arguments);
		foreach ($this->routes as $route) {
			if ($route->hasTokens($tokens)) {
				return $route->build($arguments);
			}
		}
		return null;
	}


}