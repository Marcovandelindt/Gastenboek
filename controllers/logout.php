<?php

class logoutController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('User');

		$user = new User();

		$user->logoutUser();
	}
}