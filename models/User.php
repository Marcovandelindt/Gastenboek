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

			if (strlen($this->request->get('username') < 2)) {
				return $this->error = 'Please choose a username that contains 2 characters or more!';
			}

			// Validate the e-mailaddress input field
			if (empty($this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress caanot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot contain any JavaScript!';
			}

			if (!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL)) {
				return $this->error = 'Please choose a correct e-mailaddress!';
			}

			if (strlen($this->request->get('email') < 3)) {
				return $this->error = 'Please choose a e-mailaddress that contains 3 characters or more!';
			} 

			// Validate the password input field
			if (empty($this->request->get('password'))) {
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password'))) {
				return $this->error = 'Your password cannot contain any JavaScript!';
			}

			if (strlen($this->request->get('password') < 6)) {
				return $this->error = 'For your own safety, please choose a password with 6 characters or more!';
			}
		}
	}
}