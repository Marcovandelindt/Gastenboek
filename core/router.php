<?php

class Router {

	public function __construct() {
		$this->request = new Request();
		$this->loadController($this->request->get('page'));
	}

	public function loadController($class) {

		if (empty($class)) {
			require BASE_PATH . '/controllers/home.php';

			$home = new HomeController();

			return false;
		}

		if (file_exists(BASE_PATH . '/controllers/' . $class . '.php')) {
			require BASE_PATH . '/controllers/' . $class . '.php';

			$controller = $class . 'Controller';

			$class = new $controller;

			return false;
		}

		if (!file_exists(BASE_PATH . '/controllers/' . $class . '.php')
		&& !empty($class)) {
			require BASE_PATH . '/errors/404.php';
		}
	}
}

# End of File