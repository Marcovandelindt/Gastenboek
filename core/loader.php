<?php

class Loader {

	public function View($name, $param = NULL) {
		
		if (isset($param)) {
			extract($param);
		}

		if (file_exists(BASE_PATH . '/views/' . $name . '.php')) {
			require BASE_PATH . '/views/' . $name . '.php';
		} else {
			require BASE_PATH . '/errors/view.php';
			exit;
		}
	}

	public function Model($name, $param = NULL) {

		if (isset($param)) {
			extract($param);
		}

		if(file_exists(BASE_PATH . '/models/' . $name . '.php')) {
			require BASE_PATH . '/models/' . $name . '.php';

			$name = new $name;
		} else {
			require BASE_PATH . '/errors/model.php';
			exit;
		}
	}
}

# End of File