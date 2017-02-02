<?php

class Database {

	public $database = '';

	public function __construct() {

		try {
			$this->database = new PDO('mysql:host=' . hostname . ';dbname=' . 
			dbname, username, password);
			echo '<div class="connection-status">Connected!</div>';
		} catch(PDOEsception $error) {
			echo 'Could not connect to the database...';		// Error page when finished
			exit();
		}

		return $this->database;
	}
}

# End of File