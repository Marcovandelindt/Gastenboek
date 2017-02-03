<?php

class User extends Model {

	public $error = NULL;
	public $success = NULL;

	public function __construct() {
		parent::__construct();
	}

	public function registerUser() {

		if (isset($_POST['register'])) {
			
			// Validate the username input field
			if (empty($this->request->get('username'))) {
				return $this->error = 'Your username cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('username'))) {
				return $this->error = 'Your username cannot contain any JavaScript!';
			}

			if (strlen($this->request->get('username') > 2)) {
				return $this->error = 'Please choose a username that contains 2 characters or more!';
			}

			// Validate the e-mailaddress input field
			if (empty($this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot contain any JavaScript!';
			}

			if (!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL)) {
				return $this->error = 'Please choose a correct e-mailaddress!';
			}

			if (strlen($this->request->get('email') > 3)) {
				return $this->error = 'Please choose a e-mailaddress that contains 3 characters or more!';
			} 

			// Validate the password input field
			if (empty($this->request->get('password'))) {
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password'))) {
				return $this->error = 'Your password cannot contain any JavaScript!';
			}

			if (strlen($this->request->get('password') > 6)) {
				return $this->error = 'For your own safety, please choose a password with 6 characters or more!';
			}

			// Validate the confirm-password input-field
			if ($this->request->get('password') != $this->request->get('password_confirm')) {
				return $this->error = 'Both passwords are not the same!';
			}

			// Check if username already exists in database
			$checkuser = $this->connect->database->prepare('SELECT username FROM users WHERE username = :username');
			$checkuser->execute([
				'username' => $this->request->get('username')
			]);

			if ($checkuser->rowCount() > 0) {
				return $this->error = 'This username already exists!';
			}

			// Check if e-mailaddress already exists in database
			$checkemail = $this->connect->database->prepare('SELECT email FROM users WHERE email = :email');
			$checkemail->execute([
				'email' => $this->request->get('email')
			]);

			if ($checkemail->rowCount() > 0) {
				return $this->error = 'This e-mailaddress already exists!';
			} else {

				// Register the user

				$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

				$password = hash('sha256', $this->request->get('password') . $salt);

				for ($round = 0; $round < 65536; $round++) {
					$password = hash('sha256', $this->request->get('password') . $salt);
				}

				$insert = $this->connect->database->prepare('INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)');
				$insert->execute([
					'username' 	=> $this->request->get('username'),
					'email' 	=> $this->request->get('email'),
					'password' 	=> $password,
					'salt' 		=> $salt
				]);

				if ($insert) {
					return $this->success = 'Hello ' . $this->request->get('username') . '. You\'re successfully registered!';
				} else {
					return $this->error = 'Someting went wrong while registering you. Please try again.';
				}
			}
		}
	}
}