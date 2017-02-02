<?php

class Messages extends Model {

	public $error = NULL;
	public $success = NULL;

	public function __construct() {
		parent::__construct();
	}
}