<?php

class ProfileController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Profile');

		$profile = new Profile();

		$profile->getProfile();

		$data['title'] = 'Profile';
		$data['profile'] = $profile;

		$this->load->View('profile', $data);
	}
}