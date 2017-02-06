<?php

class User extends Model
{
	public $error 	= NULL;
	public $success = NULL;
	public $warning = NULL;

	public function __construct()
	{
		parent::__construct();
	}

	public function registerUser()
	{
		if (isset($_POST['register']))
		{
			// Validate the username input field
			if (empty($this->request->get('username')))
			{
				return $this->error = 'Your username cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('username')))
			{
				return $this->error = 'Your username cannot contain any JavaScript!';
			}

			// Validate the e-mailaddress input field
			if (empty($this->request->get('email')))
			{
				return $this->error = 'Your e-mailaddress cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email')))
			{
				return $this->error = 'Your e-mailaddress cannot contain any JavaScript!';
			}

			if (!filter_var($this->request->get('email'), FILTER_VALIDATE_EMAIL))
			{
				return $this->error = 'Please insert a correct e-mailaddress!';
			}

			// Validate the password input field
			if (empty($this->request->get('password')))
			{
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password'))) {
				return $this->error = 'Your password cannot contain any JavaScript';
			}

			// Validate the password_confirm input field
			if (empty($this->request->get('password_confirm')))
			{
				return $this->error = 'Please confirm your password!';
			}

			// Check if the password and confirmed password are both the same
			if ($this->request->get('password') != $this->request->get('password_confirm'))
			{
				return $this->error = 'Both the passwords do not match!';
			}

			// Check if user is already in the database
			$checkuser = $this->connect->database->prepare('SELECT username FROM users WHERE username = :username');
			$checkuser->execute([
				'username' => $this->request->get('username')
			]);

			if ($checkuser->rowCount() > 0)
			{
				return $this->error = 'This username already exists!';
			}

			// Check if email already exists in the database
			$checkemail = $this->connect->database->prepare('SELECT email FROM users WHERE email = :email');
			$checkemail->execute([
				'email' => $this->request->get('email')
			]);

			if ($checkemail->rowCount() > 0) {
				return $this->error = 'This e-mailaddress already exists!';
			} else
			{
				// Insert the data in the database
				$salt 		= dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

				$password 	= hash('sha256', $this->request->get('password') . $salt);

				for ($round = 0; $round < 65536; $round++)
				{
					$password = hash('sha256', $this->request->get('password') . $salt);
				}

				$insert = $this->connect->database->prepare('INSERT INTO users (username, email, password, salt) VALUES (:username, :email, :password, :salt)');
				$insert->execute([
					'username' 	=> $this->request->get('username'),
					'email' 	=> $this->request->get('email'),
					'password' 	=> $password,
					'salt' 		=> $salt
				]);

				if ($insert)
				{
					return $this->success = 'User has successfully been created. You can now login!';
				}
				else
				{
					return $this->error = 'Something went wrong while registering. Please try again later!';
				}
			}
		}
	}

	public function loginUser()
	{
		if (isset($_POST['login']))
		{
			// Validate the email
			if (empty($this->request->get('email')))
			{
				return $this->error = 'Your e-mailaddress cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('email')))
			{
				return $this->error = 'Your e-mailaddress cannot contain any JavaScript!';
			}

			// Validating the password
			if (empty($this->request->get('password')))
			{
				return $this->error = 'Your password cannot be empty!';
			}

			if (preg_match("#<script(.*?)>(.*?)</script>#is", $this->request->get('password')))
			{
				return $this->error = 'Your password cannot contain any JavaScript!';
			}
			else
			{
				// Check the data
				$select = $this->connect->database->prepare('SELECT * FROM users WHERE email = :email');
				$select->execute([
					'email' => $this->request->get('email')
				]);

				$loggedin = FALSE;

				$row = $select->fetch(PDO::FETCH_ASSOC);

				if ($row)
				{
					$check_password = hash('sha256', $this->request->get('password') . $row['salt']);

					for ($round = 0; $round < 65536; $round++)
					{
						$check_password = hash('sha256', $this->request->get('password') . $row['salt']);
					}

					if ($check_password === $row['password'])
					{
						$loggedin = TRUE;
					}
					else
					{
						return $this->error = 'The password and emal combination did not match. Please try again!';
					}
				}

				if ($loggedin)
				{
					// Update the login count
					$update = $this->connect->database->prepare('UPDATE users SET logins = logins+1 WHERE email = :email');
					$update->execute([
						'email' => $this->request->get('email')
					]);

					// Retrieve the IP Address and User Agent
					$insert = $this->connect->database->prepare('UPDATE users SET ip_address = :ip_address, user_agent = :user_agent');
					$insert->execute([
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'user_agent' => $_SERVER['HTTP_USER_AGENT']
					]);

					// Unset the password and the salt before the session
					unset($row['password']);
					unset($row['salt']);

					// Set the session name and the session
					session_name('userdata');
					$_SESSION['user'] = $row;

					// Update status to Online
					$update = $this->connect->database->prepare('UPDATE users SET status = "Online" WHERE id = ' . $_SESSION['user']['id'] . '');
					$update->execute();

					// Check if the user logs in for the first time
					header('Location: profile');
				}
				else
				{
					return $this->error = 'You could not be logged in. Please try again later!';
				}
			}
		}
	}

	public function isLoggedIn()
	{
		if (isset($_SESSION['user']) && $_SESSION['user'] == TRUE)
		{
			if ($_SESSION['user']['logins'] == 0)
			{
				header('Location: profile&details');
			}
			else
			{
				header('Location: profile');
			}
		}
		else
		{
			header('Location: login');
		}
	}

	public function getWelcomeMessage()
	{
		echo'
			<div class="alert alert-info">
				<p>Dear ' . $_SESSION['user']['username'] . ',<p>
				<p>It looks like this is your first time on the Guestbook. We are very happy that you signed up for us!</p>
				<p>We really try to get to know our members better, so we would like to know a litlle bit more about you. Before you continue, can you please tell us something about yourself below?</p>
			</div>
		';
	}

	public function getUserInformation()
	{
		$get = $this->connect->database->prepare('SELECT * FROM users WHERE id = :id');

		$get->setFetchMode(PDO::FETCH_CLASS, 'User');

		$get->execute([
			'id' => $_SESSION['user']['id']
		]);

		$oUser = $get->fetch(PDO::FETCH_CLASS);

		if (!empty($oUser->username)) {
			echo '<li class="list-group-item"><strong>Username:</strong><span class="pull-right">@' . $oUser->username . '</span</li>';
		}

		if (!empty($oUser->first_name)) {
			echo '<li class="list-group-item"><strong>First name:</strong><span class="pull-right">' . $oUser->first_name . '</span></li>';
		}

		if (!empty($oUser->last_name)) {
			echo '<li class="list-group-item"><strong>Last name:</strong><span class="pull-right">' . $oUser->last_name . '</span></li>';
		}

		if (!empty($oUser->birth_date)) {
			echo '<li class="list-group-item"><strong>Date of Birth:</strong><span class="pull-right">' . $oUser->birth_date . '</span></li>';
		}

		if (!empty($oUser->country)) {
			echo '<li class="list-group-item"><strong>Country:</strong><span class="pull-right">' . $oUser->country . '</span></li>';
		}

		if (!empty($oUser->bio)) {
			echo '<li class="list-group-item"><strong>Bio:</strong>&nbsp;' . $oUser->bio . '</li>';
		}

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