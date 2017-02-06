<?php

class ProfileController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('User');

		$user = new User();

		$user->checkSession();

		$this->load->Model('Messages');

		$messages = new Messages();

		$messages->deleteMessage(); 

		$data['title'] = 'My Profile - Gastenboek';
		$data['user'] = $user;
		$data['error'] = $user->error;
		$data['success'] = $user->success;
		$data['warning'] = $user->warning;
		$data['messages'] = $messages;

		$this->load->View('profile', $data);
	}
}