<?php

namespace Router;

class Router {

	protected $routes = array();

	public function register($name, $pattern, callable $callback) {
		$this->routes[$name] = new Route($pattern, $callback);
	}

	public function registerMultiple($name, array $patterns, callable $callback) {
		$this->routes[$name] = [];
		foreach ($patterns as $pattern) {
			$this->routes[$name][] = new Route($pattern, $callback);
		}
	}

	public function route($uri, &$routeName = null) {

		foreach ($this->routes as $name => $route) {
			if (is_array($route)) {
				foreach ($route as $r) {
					if ($routeData = $r->match($uri)) {
						$routeName = $name;
						return $routeData;
					}
				}
			} else if ($routeData = $route->match($uri)) {
				$routeName = $name;
				return $routeData;
			}
		}
		return null;
	}

	public function build($name, array $arguments) {
		$tokens = array_keys($arguments);
		if (! array_key_exists($name, $this->routes)) {
			return null;
		}
		if (is_array($this->routes[$name])) {
			foreach ($this->routes[$name] as $route) {
				if ($route->hasTokens($tokens)) {
					return $route->build($arguments);
				}
			}
			return null;
		}
		return $this->routes[$name]->build($arguments);
	}


}
