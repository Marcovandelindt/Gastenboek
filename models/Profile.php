<?php

class Profile extends Model {

	public $result;
	public $error 	= NULL;
	public $success = NULL;

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

		if (!empty($this->result['birth_date'])) {
			echo '<li class="list-group-item"><strong>Date of Birth:</strong><span class="pull-right">' . $this->result['birth_date'] . '</span></li>';
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
			if ($this->result['country'] <= 25) {
				echo '<li class="list-group-item"><strong>Country:</strong><br>' . $this->result['country'] . '</li>';
			}
		}

		if (!empty($this->result['email'])) {
			echo '<li class="list-group-item"><strong>E-mail:</strong><span class="pull-right">' . $this->result['email'] . '</span></li>';
		} else {
			if (strlen($this->result['email']) <= 25) {
				echo '<li class="list-group-item"><strong>E-mail:</strong><br>' . $this->result['email'] . '</li>';
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
									<img src="../' . $item['image'] . '">
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
					<div class="col-sm-12">
						<div class="user-message">
						<div class="media">
							<div class="media-left">
								<img src="../' . $item['image'] . '">
							</div>
							<div class="media-body">
								<p class="message-username">' . $item['username'] . '</p>
								<p class="message-text">' . $item['message'] . '</p>
								<p class="date">' . $item['date'] . '</p>
							</div>
						</div>
					</div>
					</div>
				';
			}
		}
	}

	public function deleteMessage() {
		
		if (isset($_POST['delete'])) {
			$delete = $this->connect->database->prepare('DELETE FROM messages WHERE id = :id');
			$delete->execute([
				'id' => $this->request->get('hidden')
			]);

			return $this->success = 'You have successully deleted your message!';
		}
	}

	public function showEditPanel() {

		if ($_SESSION['user']['username'] == $this->result['username']) {
			echo '
				<div class="edit-panel">
					<ul class="list-group">
						<li class="list-group-item active"><strong>Edit my Profile</strong></li>
						<a href="' . $this->result['username'] . '&edit"><li class="list-group-item">Personal Information</li></a>
						<a href="' . $this->result['username'] . '&account"><li class="list-group-item">Account</li></a>
					</ul>
				</div>
			';
		}
	}

	public function editInformation() {

		$scripts = "#<script(.*?)>(.*?)</script>#is";

		if (isset($_POST['editInformation'])) {

			// Validate all the fields on JavaScript
			if (preg_match($scripts, $this->request->get('firstname'))) {
				return $this->error = 'Your firstname cannot contain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('lastname'))) {
				return $this->error = 'Your lastname cannot contain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('birth_date'))) {
				return $this->error = 'Your birth date cannot contain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('nickname'))) {
				return $this->error = 'Your nickname cannot contain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('country'))) {
				return $this->error = 'You country cannot cointain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('email'))) {
				return $this->error = 'You e-mail address cannot contain any JavaScript!';
			}

			if (preg_match($scripts, $this->request->get('bio'))) {
				return $this->errror = 'Your bio cannot contain any JavaScript!';
			} else {

				// Update the users information
				$update = $this->connect->database->prepare('UPDATE users SET first_name = :firstname, last_name = :lastname, birth_date = :birthdate, nickname = :nickname, country = :country, email = :email, bio = :bio WHERE id = :id');
				$update->execute([
					'firstname' => $this->request->get('firstname'),
					'lastname' 	=> $this->request->get('lastname'),
					'birthdate' => $this->request->get('birthdate'),
					'nickname' 	=> $this->request->get('nickname'),
					'country' 	=> $this->request->get('country'),
					'email' 	=> $this->request->get('email'),
					'bio' 		=> $this->request->get('bio'),
					'id' 		=> $_SESSION['user']['id']
				]);

				if ($update) {
					header('Location: ../logout?logout=True');
				} else {
					return $this->error = 'Something went wrong. Please try again later.';
				}
			}
		}
	}

	public function editAccount() {
		
		if (isset($_POST['editAccount'])) {

			if (is_uploaded_file($_FILES['image']['tmp_name'])) {
				$folder = 'assets/images/';
				$file = basename($_FILES['image']['name']);
				$fullpath = $folder . $file;

				if (getimagesize($file > 2097152)) {
					return $this->error = 'Please, choose an image which is not greater than 2MB!';
				}

				if (move_uploaded_file($_FILES['image']['tmp_name'], $fullpath)) {
					echo 'Thank You!';
				}
			}

			$update = $this->connect->database->prepare('UPDATE users SET username = "' . $this->request->get('username') . '", image = "' . $fullpath . '" WHERE id = ' . $_SESSION['user']['id'] . '');
			$update->execute();

			session_destroy();

			header('Location: ../logout');
		}
	}
}