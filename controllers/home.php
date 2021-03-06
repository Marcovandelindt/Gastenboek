<?php

class HomeController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Messages');
		$messages = new Messages();

		$messages->validateMessage();
		$messages->deleteMessage(); 

		$this->load->Model('User');
		$user = new User();

		$user->loginUser();
		$user->Search();

		$data['title'] = 'Home - Gastenboek';
		$data['messages']  = $messages;
		$data['error'] = $messages->error;
		$data['success'] = $messages->success;
		$data['user'] = $user;

		$this->load->View('home', $data);
	}
}