<?php

namespace Router;

class Route {

	const CHARSET_TEXT = 'text';
	const CHARSET_NUMERIC = 'numeric';
	const CHARSET_ANY = 'any';

	protected $path;
	protected $regex;
	protected $template;
	protected $tokens = array();

	public function __construct($path) {
		$this->path = $path;

		if (! preg_match_all('/\\{\\{(?P<name>[a-z]+)(\|(?P<charset>[^}}]+))?\\}\\}/i', $path, $matches)) {
			throw new \Exception("Could not match path `$path`");
		}

		$regex = '/'.str_replace('/', '\\/', $path).'/i';
		$template = $path;
		foreach ($matches[0] as $i => $selector) {
			$name = $matches['name'][$i];
			$charset = $matches['charset'][$i] ?: self::CHARSET_ANY;
			$subRegex = "(?P<$name>";
			if ($charset == self::CHARSET_TEXT) {
				$subRegex .= '[a-z]';
			} elseif ($charset == self::CHARSET_NUMERIC) {
				$subRegex .= '[0-9]';
			} elseif ($charset == self::CHARSET_ANY) {
				$subRegex .= '.';
			} else {
				throw new \Exception("Invalid charset: `$charset`");
			}
			$subRegex .= '+)';
			$this->tokens[$name] = $charset;

			$regex = str_replace($selector, $subRegex, $regex);
			$template = str_replace($selector, '(%'.$name.'%)', $template);
		}
		$this->regex = $regex;
		$this->template = $template;
	}

	public function hasTokens(array $tokens) {
		$myTokens = array_keys($this->tokens);
		return (! array_diff($tokens, $myTokens)) && (! array_diff($myTokens, $tokens));
	}

	public function build($data) {
		$url = $this->template;
		foreach ($data as $key => $value) {
			$url = str_replace('(%'.$key.'%)', $value, $url);
		}
		return $url;
	}

	public function match($path) {
		if (preg_match($this->regex, $path, $matches)) {
			$data = [];
			foreach ($matches as $name => $value) {
				if (! is_numeric($name)) {
					$data[$name] = $value;
				}
			}
			return $data;
		}
		return false;
	}

}