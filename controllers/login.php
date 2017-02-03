<?php

class LoginController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('User');

		$user = new User();

		$user->loginUser();

		$data['title'] 		= 'Inloggen - Gastenboek';
		$data['user'] 		= $user;
		$data['error'] 		= $user->error;
		$data['success'] 	= $user->success;

		$this->load->View('login', $data);
	}
}