<?php

namespace Router;

class Redirector {

	protected $redirects = array();

	public function redirect($pattern, $callback, $code = 301) {
		$this->redirects[] = [ $pattern, $callback, $code ];
		return $this;
	}

	public function route($uri, &$code = null) {
		foreach ($this->redirects as $redirect) {
			list ( $pattern, $callback, $code ) = $redirect;
			if ( preg_match( $pattern, $uri, $matches )) {
				if ( is_callable( $callback )) {
					return call_user_func( $callback, $matches );
				}

				return $callback;
			}
		}
		return null;
	}
}
