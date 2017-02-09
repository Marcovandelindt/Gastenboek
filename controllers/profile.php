<?php

class ProfileController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Profile');

		$profile = new Profile();

		$profile->getProfile();
		$profile->editInformation();
		$profile->editAccount();

		$this->load->Model('Messages');

		$messages = new Messages();
		
		$messages->validateMessage(); 
		$messages->deleteMessage();

		$data = [
			'title' 	=> 'Profile',
			'profile' 	=> $profile,
			'messages' 	=> $messages,
			'username' 	=> $profile->result['username'],
			'error' 	=> $profile->error,
			'success' 	=> $profile->success
		];


		$this->load->View('profile', $data);
	}
}