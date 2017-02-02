<?php

class Request {

	public function get($key) {
		
		if(array_key_exists($key, $_REQUEST)) {
			return $_REQUEST[$key];
		}
	}

	public function isPost() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public function isGet() {
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}
}

# End of File