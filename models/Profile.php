<?php

class Profile extends Model {

	public $result;

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

		if (!isset($_SESSION['user'])) {
			header('Location: ../home?Message=NotAllowed');
		}
	}

	public function getUsername() {
		return $this->result['username'];
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

		if (!empty($this->result['last_name'])) {
			echo '<li class="list-group-item"><strong>Last name:</strong><span class="pull-right">' . $this->result['last_name'] . '</span></li>';
		} else {
			if ($this->result['last_name'] <= 25) {
				echo '<li class="list-group-item"><strong>Last name:</strong><br>' . $this->result['last_name'] . '</li>';
			}
		}

		if (!empty($this->result['nickname'])) {
			echo '<li class="list-group-item"><strong>Nickname:</strong><span class="pull-right">' . $this->result['nickname'] . '</span></li>';
		} else {
			if ($this->result['nickname'] <= 25) {
				echo '<li class="list-group-item"><strong>Nickname:</strong><br>' . $this->result['nickname'] . '</li>';
			}
		}

		if (!empty($this->result['country'])) {
			echo '<li class="list-group-item"><strong>Country:</strong><span class="pull-right">' . $this->result['country'] . '</span></li>';
		} else {
			if ($this->result['counrty'] <= 25) {
				echo '<li class="list-group-item"><strong>Country:</strong><br>' . $this->result['country'] . '</li>';
			}
		}

		if (!empty($this->result['bio'])) {
			echo '<li class="list-group-item"><strong>Bio:</strong>&nbsp;' . $this->result['bio'] . '</li>';
		}

		if ($this->result['status'] == 'Online') {
			echo '<li class="list-group-item"><strong>Status:</strong><span class="pull-right"><i class="fa fa-circle-thin online-icon"></i>&nbsp;' . $this->result['status'] . '</span></li>';
		} else {
			if ($this->result['status'] == "Offline") {
				echo '<li class="list-group-item"><strong>Status:</strong><span class="pull-right"><i class="fa fa-circle-thin offline-icon"></i>&nbsp;' . $this->result['status'] . '</span></li>';
				echo '<li class="list-group-item"><strong>Last login:</strong><span class="pull-right">' . date('H:i, d-m-Y', strtotime($this->result['last_online'])) . '</span></li>';
			}
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

	public function showEditPanel() {

		if ($_SESSION['user']['username'] == $this->result['username']) {
			echo '
				<div class="edit-panel">
					<ul class="list-group">
						<li class="list-group-item active"><strong>Edit my Profile</strong></li>
						<a href="' . $this->result['username'] . '&edit"><li class="list-group-item">Personal Information</li></a>
					</ul>
				</div>
			';
		}
	}
}