<?php

class Model
{
	public function __construct()
	{
		$this->load 	= new Loader();
		$this->request 	= new Request();
		$this->connect 	= new Database();
	}
}

# End of File