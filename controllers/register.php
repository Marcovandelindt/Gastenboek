<?php

class RegisterController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('User');

		$user = new User();

		$user->registerUser();

		$data['title'] 		= 'Registreren - Gastenboek';
		$data['user'] 		= $user;
		$data['error'] 		= $user->error;
		$data['success'] 	= $user->success;

		$this->load->View('register', $data);
	}
}