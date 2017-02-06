<?php

class User extends Model {

	public $error 	= NULL;
	public $success = NULL;
	public $scripts = "#<script(.*?)>(.*?)</script>#is";

	public function __construct() {
		parent::__construct();
	}

	public function registerUser() {

		$scripts = "#<script(.*?)>(.*?)</script>#is";

		if (isset($_POST['register'])) {

			// Validate the Username input-field
			if (empty($this->request->get('username'))) {
				return $this->error = 'You have not chosen a username yet!';
			}

			if (preg_match($scripts, $this->request->get('username'))) {
				return $this->error = 'You are not allowed to use JavaScript in your username!';
			}

			// Validate the E-mailaddress input field
			if (empty($this->request->get('email'))) {
				return $this->error = 'You have not chosen a e-mailaddress yet!';
			}

			if (!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL)) {
				return $this->error = 'Please choose a correct e-mailaddress!';
			}

			if (preg_match($scripts, $this->request->get('email'))) {
				return $this->error = 'You are not allowed to use JavaScript in your e-mailaddress';
			}

			// Validate the Password input-field
			if (empty($this->request->get('password'))) {
				return $this->error = 'You have not chosen your password yet!';
			}

			if (preg_match($scripts, $this->request->get('password'))) {
				return $this->error = 'You are not allowed to use JavaScript in your password!';
			}

			// Validate the password_confirm input-field
			if (empty($this->request->get('password_confirm'))) {
				return $this->error = 'Please confirm your password!';
			}

			// Check if both passwords are the same
			if ($this->request->get('password') != $this->request->get('password_confirm')) {
				return $this->error = 'Both passwords do not match!';
			}

			// Check if the username already exists in the database
			$checkuser = $this->connect->database->prepare('SELECT username FROM users WHERE username = :username');

			$checkuser->execute([
				'username' => $this->request->get('username')
			]);

			if ($checkuser->rowCount() > 0) {
				return $this->error = 'This username already exists!';
			}

			// Check if e-mailaddress exists in Database
			$checkemail = $this->connect->database->prepare('SELECT email FROM users WHERE email = :email');

			$checkemail->execute([
				'email' => $this->request->get('email')
			]);

			if ($checkemail->rowCount() > 0) {
				return $this->error = 'This e-mailaddress already exists!';
			} else {

				// Generate a password
				$salt 		= dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
				$password 	= hash('sha256', $this->request->get('password') . $salt);

				for ($round = 0; $round < 65536; $round++) {
					$password = hash('sha256', $this->request->get('password') . $salt);
				}

				// Insert into the Database
				$insert = $this->connect->database->prepare('INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)');
				$insert->execute([
					'username' 	=> $this->request->get('username'),
					'email' 	=> $this->request->get('email'),
					'password' 	=> $password,
					'salt' 		=> $salt
				]);

				if ($insert) {
					return $this->success = 'You have successfully been registered. You can login now!';
				} else {
					return $this->error = 'Something went wrong. Please try again later.';
				}
			}
		}
	}

	public function loginUser() {
		
		$scripts = "#<script(.*?)>(.*?)</script>#is";

		if (isset($_POST['login'])) {
			// Validate the E-mailaddress
			if (empty($this->request->get('email'))) {
				return $this->error = 'Please fill in your e-mailaddress!';
			}

			if (preg_match($scripts, $this->request->get('email'))) {
				return $this->error = 'You are not allowed to use JavaScript in your e-mailaddress!';
			}

			// Validate the Password
			if (empty($this->request->get('password'))) {
				return $this->error = 'Please fill in your password!';
			}

			if (preg_match($scripts, $this->request->get('passwowrd'))) {
				return $this->error = 'You are not allowed to use JavaScript in your password!';
			} else {

				// Decode the password
				$select = $this->connect->database->prepare('SELECT * FROM users WHERE email = :email');
				$select->execute([
					'email' => $this->request->get('email')
				]);	

				$loggedin = FALSE;

				$row = $select->fetch(PDO::FETCH_ASSOC);

				if ($row) {
					$check_password = hash('sha256', $this->request->get('password') . $row['salt']);

					for ($rount = 0; $round < 65536; $round++) {
						$check_password = hash('sha256', $this->request->get('password') . $row['salt']);
					}

					if ($check_password === $row['password']) {
						$loggedin = TRUE;
					} else {
						return $this->error = 'The password and e-mail did not match. Please try again!';
					}
				}

				if ($loggedin) {
					
					// Update the login count
					$update = $this->connect->database->prepare('UPDATE users SET ip_address = :ip_address, user_agent = :user_agent');
					$update->execute([
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'user_agent' => $_SERVER['HTTP_USER_AGENT']
					]);

					// Unset password and salt before creating a Session
					unset($row['password']);
					unset($row['salt']);

					// Create the Session
					session_name('userdata');
					$_SESSION['user'] = $row;

					// Set status to online
					$update = $this->connect->database->prepare('UPDATE users SET status = "Online" WHERE id = :id');
					$update->execute([
						'id' => $_SESSION['user']['id']
					]);

					header('Location: profile');
				} else {
					return $this->error = 'You could not be logged in. Please try again later!';
				}
			}
		}
	}

	public function isLoggedIn() {
		
		if (isset($_SESSION['user']) && $_SESSION['user'] == TRUE) {
			header('Location: profile');
		} else {
			header('Location: login');
		}
	}

	public function getUserInformation() {

		$get = $this->connect->database->prepare('SELECT * FROM users WHERE id = :id');

		$get->setFetchMode(PDO::FETCH_CLASS, 'User');

		$get->execute([
			'id' => $_SESSION['user']['id']
		]);

		$oUser = $get->fetch(PDO::FETCH_CLASS);

		// Check if the user has a username or not
		if (!empty($oUser->username)) {
			echo '<li class="list-group-item"><strong>Username:</strong><span class="pull-right">@' . $oUser->username . '</span</li>';
		}

		// Check if the user has a first name or not
		if (!empty($oUser->first_name)) {
			echo '<li class="list-group-item"><strong>First name:</strong><span class="pull-right">' . $oUser->first_name . '</span></li>';
		} else {
			echo '<li class="list-group-item"><i style="color: grey">' . $oUser->username . '\'s first name is private or not filled in yet...</i>';
		}

		// Check if the user has a last name or not
		if (!empty($oUser->last_name)) {
			echo '<li class="list-group-item"><strong>Last name:</strong><span class="pull-right">' . $oUser->last_name . '</span></li>';
		} else {
			echo '<li class="list-group-item"><i style="color: grey">' . $oUser->username . '\'s last name is private or not filled in yet...</i>';
		}

		// Check if the user has a birth date or not
		if (!empty($oUser->birth_date)) {
			echo '<li class="list-group-item"><strong>Date of Birth:</strong><span class="pull-right">' . $oUser->birth_date . '</span></li>';
		} else {
			echo '<li class="list-group-item"><i style="color: grey">' . $oUser->username . '\'s date of birth is private or not filled in yet...</i>';

		}

		// Check if the user has a country or not
		if (!empty($oUser->country)) {
			echo '<li class="list-group-item"><strong>Country:</strong><span class="pull-right">' . $oUser->country . '</span></li>';
		} else {
			echo '<li class="list-group-item"><i style="color: grey">' . $oUser->username . '\'s country is private or not filled in yet...</i>';

		}

		// Check if the user has a bio or not
		if (!empty($oUser->bio)) {
			echo '<li class="list-group-item"><strong>Bio:</strong>&nbsp;' . $oUser->bio . '</li>';
		} else {
			echo '<li class="list-group-item"><i style="color: grey">' . $oUser->username . '\'s bio is private or not filled in yet...</i>';
		}

		// Check if the user is online or offline
		if ($oUser->status == "Online") {
			echo '
			<li class="list-group-item">
				<strong>Status:</strong>
				<span class="pull-right"><i class="fa fa-circle-thin online-icon"></i>&nbsp;' . $oUser->status . '</span>
			</li>
			';	
		}

		if ($oUser->status == "Offline") {
			echo '
			<li class="list-group-item">
				<strong>Status:</strong>
				<span class="pull-right"><i class="fa fa-circle-thin offline-icon"></i>&nbsp;' . $oUser->status . '</span>
			</li>
			';
		}
	}		

	// Hidden input meegeven

	public function getUsersMessages() {
		$get = $this->connect->database->prepare('SELECT * FROM messages WHERE username = :username');
		$get->execute([
			'username' => $_SESSION['user']['username']
		]);

		foreach ($get as $item) {
			echo '
				<form class="form" method="POST">
				<div class="user-message">
					<div class="media">
						<div class="media-left">
							<img src="assets/images/' . $item['image'] . '">
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
	}

	public function logoutUser()
	{
		$update = $this->connect->database->prepare('UPDATE users SET status = "Offline", last_online = "' . date('Y-m-d H:i') . '" WHERE id = ' . $_SESSION['user']['id'] . '');
		$update->execute();


		session_destroy();
		header('Location: home');
	}

	public function checkSession()
	{
		if ($_SESSION['user'] == FALSE)
		{
			header('Location: login');
		}
	}

	public function countOnlineUsers()
	{
		$get = $this->connect->database->prepare('SELECT * FROM users WHERE status = "Online" LIMIT 5');
		$get->execute();

		$count = $get->rowCount();

		if ($count == 0) 
		{
			echo $count;
		}
		else
		{
			echo $count;
		}
	}

	public function getOnlineUsers()
	{
		$get = $this->connect->database->prepare('SELECT username, last_online FROM users WHERE status = "Online"');
		$get->execute();

		$count = $get->rowCount(); 

		if ($count == 0) {
			echo '<i style="color: grey">Whoops, looks like no one is online...</i>';
		} else {

			foreach ($get as $item) {
			echo '<li class="list-group-item"><i class="fa fa-circle-thin online-icon"></i>&nbsp;' . $item['username'] . '&nbsp;(<i>' . date('H:i', strtotime($item['last_online'])) . '</i>)</li>';
			}
		}
	}

	public function getOfflineUsers()
	{
		$get = $this->connect->database->prepare('SELECT * FROM users WHERE status = "Offline" LIMIT 5');
		$get->execute();

		$count = $get->rowCount();

		if ($count == 0)
		{
			echo 'Wuuut, eveyone is online!';
		}
		else
		{
			foreach ($get as $item)
			{
				echo '<li class="list-group-item"><i class="fa fa-circle-thin offline-icon"></i>&nbsp;' . $item['username'] . '&nbsp;(<i>' . date('d-m-Y H:i', strtotime($item['last_online'])) . '</i>)</li>';
			}
		}
	}

	public function countOfflineUsers()
	{
		$count = $this->connect->database->prepare('SELECT * FROM users WHERE status = "Offline"');
		$count->execute();

		$row = $count->rowCount();

		echo $row;
	}

	public function getAllUsers() {
		$get = $this->connect->database->prepare('SELECT username, email, joined_date FROM users ORDER BY joined_date DESC LIMIT 5');
		$get->execute();

		foreach ($get as $item) {
			echo '
				<li class="list-group-item">
					<p>' . $item['username'] . '&nbsp;(<i style="color: grey">@' . $item['username'] . '</i>)</p>
					<span class="label label-success">' . date('d-m-Y H:i', strtotime($item['joined_date'])) . '</span>
				</li>
			';
		}
	}

	public function countUsers() {
		$count = $this->connect->database->prepare('SELECT * FROM users');
		$count->execute();

		$rows = $count->rowCount();

		echo $rows;
	}

	// Make later 
	public function getOnlineFriends() {
		$get = $this->connect->database->prepare();
	}
}

# End of File