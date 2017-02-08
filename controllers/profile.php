<?php

class ProfileController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Profile');

		$profile = new Profile();

		$profile->getProfile();
		$profile->editAccount();

		$this->load->Model('Messages');

		$messages = new Messages();

		$messages->deleteMessage();

		$data = [
			'title' => 'Profile',
			'profile' => $profile,
			'messages' => $messages,
			'username' => $profile->result['username']
		];


		$this->load->View('profile', $data);
	}
}