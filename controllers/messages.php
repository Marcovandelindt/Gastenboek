<?php

class MessagesController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Messages');

		$messages = new Messages();

		$data['title'] 		= 'Berichten - Gastenboek';
		$data['messages'] 	= $messages;
		$data['error'] 		= $messages->error;
		$data['success'] 	= $messages->success;

		$this->load->View('messages', $data);
	}
}