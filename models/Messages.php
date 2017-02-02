<?php

class Messages extends Model {

	public $error = NULL;
	public $success = NULL;

	public function __construct() {
		parent::__construct();
	}

	public function validateMessage() {

		if (isset($_POST['sendMessage'])) {
			if (empty($this->request->get('name'))) {
				return $this->error = 'You did not choose a name yet!';
			}

			if (empty($this->request->get('message'))) {
				return $this->error = 'You didn\'t write a message yet!';
			} else {

				$insert = $this->connect->database->prepare('INSERT INTO messages (name, image, message) VALUES (:name, :image, :message)');
				$insert->execute([
					'name' 		=> $this->request->get('name'),
					'image' 	=> $this->request->get('image'),
					'message' 	=> $this->request->get('message')
				]);

				if ($insert) {
					return $this->success = 'Dear ' . $this->request->get('name') . '. Thanx for your message!';
				} else {
					return $this->error = 'It looks like something went wrong. Please try again later...';
				}
			}
		}
	}

	public function showMessages() {
		$get = $this->connect->database->prepare('SELECT * FROM messages ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item) {
			echo '
				<div class="col-sm-12">
					<div class="message">
						<div class="row">
							<div class="col-sm-2">
								<div class="image">
									<img src="assets/images/' . $item['image'] . '">
								</div>
							</div>
							<div class="col-sm-10">
								<div class="col-sm-12">
									<label for="postedby" class="label-control">Posted by&nbsp;' . $item['name'] . ':</label>
									<div class="text">' . $item['message'] . '</div>
									<label for="posteddate" class="label-control posteddate">' . date('d-m-Y, H:i', strtotime($item['date'])) . '</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}
	}

	public function showRecentMessages() {
		$get = $this->connect->database->prepare('SELECT name, date FROM messages ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item) {
			echo '<li><strong>' . $item['name'] . '</strong><i class="pull-right date_time">' . date('d-m-Y, H:i', strtotime($item['date'])) . '</i></li>';
		}
	}
}