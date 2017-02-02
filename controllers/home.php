<?php

class HomeController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Messages');
		$messages = new Messages();

		$messages->validateMessage();

		$data['title'] = 'Home - Gastenboek';
		$data['messages']  = $messages;
		$data['error'] = $messages->error;
		$data['success'] = $messages->success;

		$this->load->View('home', $data);
	}
}