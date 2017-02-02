<?php

class DashboardController extends Controller {

	public function __construct() {
		parent::__construct();

		$this->load->Model('Dashboard');

		$dashboard = new Dashboard();

		$data['title'] 		= 'Dashboard - Admin Panel';
		$data['dashboard'] 	= $dashboard;
		$data['error'] 		= $dashboard->error;
		$data['success']	= $dashboard->success;

		$this->load->View('dashboard', $data);
	}
}