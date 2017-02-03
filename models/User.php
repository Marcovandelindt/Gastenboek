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

			if (!filter_var($this->request->get('email'), FILTER_VLIDATE_EMAIL))
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
					return $this->success = 'User has successfully benn created. You can now login!';
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
		$get = $this->connect->database->prepare('SELECT * FROM users WHERE id = ' . $_SESSION['user']['id'] . '');
		$get->execute();

		$item = $get->fetch(PDO::FETCH_ASSOC);

		// Check if the username exists
		if (!empty($item['username']))
		{
			echo '<li class="list-group-item"><strong>Username: </strong>' . $item['username'] . '</li>';
		}

		// Chech if the first name exists
		if (!empty($item['first_name']))
		{
			echo '<li class="list-group-item"><strong>Firstname: </strong>' . $item['first_name'] . '</li>';
		}

		// Check if the last name exists
		if (!empty($item['last_name']))
		{
			echo '<li class="list-group-item"><strong>Lastname: </strong>' . $item['last_name'] . '</li>';
		}

		// Check if the nickname exists
		if (!empty($item['nickname']))
		{
			echo '<li class="list-group-item"><strong>Nickname: </strong>' . $item['nickname'] . '</li>';
		}

		// Check if the Date of birth exists
		if (!empty('birth_date'))
		{
			echo '<li class="list-group-item"><strong>Date of Birth: </strong>' . $item['birth_date'] . '</li>';
		}

		// Check if the Bio exists
		if (!empty($item['bio']))
		{
			echo '<li class="list-group-item"><strong>Bio: </strong>' . $item['bio'] . '</li>';
		}

		// Check all the logins
		if (!empty($item['logins']))
		{
			echo '<li class="list-group-item"><strong>Logins: </strong>' . $item['logins'] . '</li>';
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
}

# End of File