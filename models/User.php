<?php

class User extends Model {

	public $error = NULL;
	public $success = NULL;
	public $warning = NULL;

	public function __construct() {
		parent::__construct();
	}

	public function registerUser() {

		if (isset($_POST['register'])) {
			
			// Validate the username input field
			if (empty($this->request->get('username'))) {
				return $this->error = 'Your username cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('username'))) {
				return $this->error = 'Your username cannot contain any JavaScript!';
			}

			if (strlen($this->request->get('username') > 2)) {
				return $this->error = 'Please choose a username that contains 2 characters or more!';
			}

			// Validate the e-mailaddress input field
			if (empty($this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot contain any JavaScript!';
			}

			if (!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL)) {
				return $this->error = 'Please choose a correct e-mailaddress!';
			}

			if (strlen($this->request->get('email') > 3)) {
				return $this->error = 'Please choose a e-mailaddress that contains 3 characters or more!';
			} 

			// Validate the password input field
			if (empty($this->request->get('password'))) {
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password'))) {
				return $this->error = 'Your password cannot contain any JavaScript!';
			}

			if (strlen($this->request->get('password') > 6)) {
				return $this->error = 'For your own safety, please choose a password with 6 characters or more!';
			}

			// Validate the confirm-password input-field
			if ($this->request->get('password') != $this->request->get('password_confirm')) {
				return $this->error = 'Both passwords are not the same!';
			}

			// Check if username already exists in database
			$checkuser = $this->connect->database->prepare('SELECT username FROM users WHERE username = :username');
			$checkuser->execute([
				'username' => $this->request->get('username')
			]);

			if ($checkuser->rowCount() > 0) {
				return $this->error = 'This username already exists!';
			}

			// Check if e-mailaddress already exists in database
			$checkemail = $this->connect->database->prepare('SELECT email FROM users WHERE email = :email');
			$checkemail->execute([
				'email' => $this->request->get('email')
			]);

			if ($checkemail->rowCount() > 0) {
				return $this->error = 'This e-mailaddress already exists!';
			} else {

				// Register the user

				$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

				$password = hash('sha256', $this->request->get('password') . $salt);

				for ($round = 0; $round < 65536; $round++) {
					$password = hash('sha256', $this->request->get('password') . $salt);
				}

				$insert = $this->connect->database->prepare('INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)');
				$insert->execute([
					'username' 	=> $this->request->get('username'),
					'email' 	=> $this->request->get('email'),
					'password' 	=> $password,
					'salt' 		=> $salt
				]);

				if ($insert) {
					return $this->success = 'Hello ' . $this->request->get('username') . '. You\'re successfully registered!';
				} else {
					return $this->error = 'Someting went wrong while registering you. Please try again.';
				}
			}
		}
	}

	public function loginUser() {

		if (isset($_POST['login'])) {

			// Validate the e-mailaddress input field
			if (empty($this->request->get('email'))) {
				return $this->error = 'Your e-mailaddress cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email'))) {
				return $this->error = 'You cannot use any JavaScript in the e-mailaddress!';
			}

			// Validate the Password input field
			if (empty($this->request->get('password'))) {
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password'))) {
				return $this->error = 'You cannot use any JavaScript in your password!';
			} else {

				// Loggin in the User

				$select = $this->connect->database->prepare('SELECT * FROM users WHERE email = :email');
				$select->execute([
					'email' => $this->request->get('email')
				]);

				$loggedin = FALSE;

				$row = $select->fetch(PDO::FETCH_ASSOC);

				// Check if passwords exists in database

				if ($row) {
					$check_password = hash('sha256', $this->request->get('password') . $row['salt']);

					for ($round = 0; $round < 65536; $round++) {
						$check_password = hash('sha256', $this->request->get('password') . $row['salt']);
					}

					if ($check_password === $row['password']) {
						$loggedin = TRUE;
					} else {
						return $this->error = 'This password dit not match the e-mail adress. Please try again.';
					}
				}

				if ($loggedin) {

					$update = $this->connect->database->prepare('UPDATE users SET logins = logins+1 WHERE email = :email');
					$update->execute([
						'email' => $this->request->get('email'),
					]);

					// Keep in mind that when you're using localhost, the ip will be ::1

					$insert = $this->connect->database->prepare('UPDATE users SET ip_address = :ip_address, user_agent = :user_agent');
					$insert->execute([
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'user_agent' => $_SERVER['HTTP_USER_AGENT']
					]);

					unset($row['salt']);
					unset($row['password']);

					session_name('userdata');
					$_SESSION['user'] = $row;

					if ($_SESSION['user']['logins'] == 0) {
						header('Location: profile&details');
					} else {
						header('Location: profile');
					}
				} else {
					return $this->error = 'You could not be logged in. Please try again later.';
				}
			}
		}
	}

	public function isLoggedIn() {
		if (isset($_SESSION['user']) && $_SESSION['user'] == TRUE) {
			if ($_SESSION['user']['logins'] == 0) {
				header('Location: profile&details');
				exit;
			} else {
				header('Location: profile');
			}
		} else {
			header('Location: login');
		}
	}

	public function getWelcomeMessage() {

		echo '
			<div class="alert alert-info">
				<p>Dear ' . $_SESSION['user']['username'] . ',<p>
				<p>It looks like this is your first time on the Guestbook. We are very happy that you signed up for us!</p>
				<p>We really try to get to know our members better, so we would like to know a litlle bit more about you. Before you continue, can you please tell us something about yourself below?</p>
			</div>
		';
	}

	public function getUserInformation() {

		$get = $this->connect->database->prepare('SELECT * FROM users WHERE id = ' . $_SESSION['user']['id'] . '');
		$get->execute();

		$item = $get->fetch(PDO::FETCH_ASSOC);

		if (!empty($item['username'])) {
			echo '<li class="list-group-item"><strong>Username:</strong> @' . $item['username'] . '</li>';
		}

		if (!empty($item['first_name'])) {
			echo '<li class="list-group-item"><strong>First name:</strong> ' . $item['first_name'] . '</li>';
		}

		if (!empty($item['last_name'])) {
			echo '<li class="list-group-item"><strong>Last name:</strong> ' . $item['last_name'] . '</li>';
		}

		if (!empty($item['nickname'])) {
			echo '<li class="list-group-item"><strong>Nickname: </strong>' . $item['nickname'] . '</li>';
		}

		if (!empty($item['birth_date'])) {
			echo '<li class="list-group-item"><strong>Date of Birth: </strong>' . $item['birth_date'] . '</li>';
		}

		if (!empty($item['country'])) {
			echo '<li class="list-group-item"><strong>Lives in: </strong>' . $item['country'] . '</li>';
		}

		if (!empty($item['bio'])) {
			echo '<li class="list-group-item"><strong>Bio: </strong>' . $item['bio'] . '</li>';
		}

		if (!empty($item['logins'])) {
			echo '<li class="list-group-item"><strong>Logins: </strong>' . $item['logins'] . '</li>';
		}
	}

	public function logoutUser() {
		session_destroy();
		header('location: home');
	}

	public function checkSession() {

		if ($_SESSION['user'] == FALSE) {

			header('Location: errors/notloggedin.php');
			return false;
		}
	}
}

# End of File