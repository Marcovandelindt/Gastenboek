<?php

class Autoloader {

	public function __construct() {
		$this->autoloader();
		$this->load 	= new Loader();
		$this->router 	= new Router();
	}

	public function autoloader() {
		require BASE_PATH . '/database/settings.php';
		require BASE_PATH . '/database/connection.php';
		require BASE_PATH . '/core/loader.php';
		require BASE_PATH . '/core/request.php';
		require BASE_PATH . '/core/router.php';
		require BASE_PATH . '/core/model.php';
		require BASE_PATH . '/core/controller.php';
	}
}

# End of File