<?php

class Profile extends Model {

	private $result;

	public function getProfile() {
		
		$get = $this->connect->database->prepare('SELECT * FROM users WHERE username = :username');
		$get->execute([
			'username' => $_GET['value']
		]);

		if ($get->rowCount() < 1) {
			header('Location: ../errors/notfound.php');
			return false;
		}

		$this->result = $get->fetch(PDO::FETCH_ASSOC);
	}

	public function getUsername() {
		echo $this->result['username'];
	}

	public function getUserDetails() {

		if (!empty($this->result['username'])) {
			echo '<li class="list-group-item"><strong>Username:</strong><span class="pull-right">@' . $this->result['username'] . '</span></li>';
		}

		if (!empty($this->result['email'])) {
			echo '<li class="list-group-item"><strong>E-mail:</strong><span class="pull-right">' . $this->result['email'] . '</span></li>';
		} else {
			if (strlen($this->result['email']) <= 25) {
				echo '<li class="list-group-item"><strong>E-mail:</strong><br>' . $this->result['email'] . '</li>';
			}
		}

		if (!empty($this->result['birth_date'])) {
			echo '<li class="list-group-item"><strong>Date of Birth:</strong><span class="pull-right">' . $this->result['birth_date'] . '</span></li>';
		}

		if (!empty($this->result['first_name'])) {
			echo '<li class="list-group-item"><strong>First name:</strong><span class="pull-right">' . $this->result['first_name'] . '</span></li>';
		}
	}

	public function getUsersMessages() {
		$get = $this->connect->database->prepare('SELECT * FROM messages WHERE username = :username ORDER BY date DESC');
		$get->execute([
			'username' => $this->result['username']
		]);

		if ($_SESSION['user']['username'] == $this->result['username']) {
			foreach ($get as $item) {
				echo '
					<form class="form" method="POST">
						<div class="user-message">
							<div class="media">
								<div class="media-left">
									<img src="../assets/images/' . $item['image'] . '">
								</div>
								<div class="media-body">
									<p class="message-username">' . $item['username'] . '</p>
									<p class="message-text">' . $item['message'] . '</p>
									<p class="date">' . $item['date'] . '</p>
									<div class="form-group">
										<input type="hidden" name="hidden" value="' . $item['id'] . '">
										<button type="submit" class="btn btn-danger" id="' . $item['id'] . '" name="delete">Delete</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<hr>
				';
			}
		} else {
			foreach ($get as $item) {
				echo '
					<form class="form" method="POST">
						<div class="user-message">
							<div class="media">
								<div class="media-left">
									<img src="../assets/images/' . $item['image'] . '">
								</div>
								<div class="media-body">
									<p class="message-username">' . $item['username'] . '</p>
									<p class="message-text">' . $item['message'] . '</p>
									<p class="date">' . $item['date'] . '</p>
								</div>
							</div>
						</div>
					</form>
					<hr>
				';
			}
		}
	}

	public function getUsersMessagesWithoutButton() {
		$get = $this->connect->database->prepare('SELECT * FROM messages WHERE username = :username ORDER BY date DESC');
		$get->execute([
			'username' => $this->result['username']
		]);

		foreach ($get as $item) {
			echo '
				<form class="form" method="POST">
					<div class="user-message">
						<div class="media">
							<div class="media-left">
								<img src="../assets/images/' . $item['image'] . '">
							</div>
							<div class="media-body">
								<p class="message-username">' . $item['username'] . '</p>
								<p class="message-text">' . $item['message'] . '</p>
								<p class="date">' . $item['date'] . '</p>
							</div>
						</div>
					</div>
				</form>
				<hr>
			';
		}
	}

	public function checkSessionAndUser() {

	}
}