<?php

class Messages extends Model
{
	public $error = NULL;
	public $success = NULL;

	public function __construct()
	{
		parent::__construct();
	}

	public function validateMessage()
	{
		if (isset($_POST['sendMessage']))
		{
			if (empty($this->request->get('name')))
			{
				return $this->error = 'You did not choose a name yet!';
			}

			if (empty($this->request->get('image')))
			{
				return $this->error = 'You did not choose an image yet!';
			}

			if (empty($this->request->get('message')))
			{
				return $this->error = 'You did not write your message yet!';
			} 
			else
			{
				$insert = $this->connect->database->prepare('INSERT INTO messages (name, image, message) VALUES (:name, :image, :message)');
				$insert->execute([
					'name' => $this->request->get('name'),
					'image' => $this->request->get('image'),
					'message' => $this->request->get('message')
				]);

				if ($insert)
				{
					return $this->success = 'Dear ' . $this->request->get('name') . '. Thanx for your message!';
				}
				else
				{
					return $this->error = 'Something went wrong while posting your message. Please try again later!';
				}
			}
		}
	}

	public function showMessages()
	{
		$get = $this->connect->database->prepare('SELECT * FROM messages ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item)
		{
			echo '
				<div class="media">
					<div class="media-left">
						<img class="media-object" src="assets/images/' . $item['image'] . '">
					</div>
					<div class="media-body">
						<p class="name">' . $item['name'] . '</p>
						<p class="message">' . $item['message'] . '</p>
						<p class="date"><i>' . $item['date'] . '</i></p>
					</div>
				</div>
			';
		}
	}

	public function showRecentMessages()
	{
		$get = $this->connect->database->prepare('SELECT name, image, date FROM messages ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item)
		{
			echo '
				<div class="media">
					<div class="media-left">
						<img class="media-object" src="assets/images/' . $item['image'] . '">
					</div>
					<div class="media-body">
						<p class="name">' . $item['name'] . '</p>
						<p class="date"><i>' . $item['date'] . '</i></p>
					</div>
				</div>
			';
		}
	}

	public function countMessages() {
		$count = $this->connect->database->prepare('SELECT * FROM messages');
		$count->execute();

		$rows = $count->rowCount();

		echo $rows;
	}
}

# End of File