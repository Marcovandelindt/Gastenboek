<?php

class Messages extends Model
{
	public $error = NULL;
	public $success = NULL;

	public function __construct()
	{
		parent::__construct();
	}

	public function validateMessage() {

		if (isset($_POST['sendMessage'])) {
			if (empty($this->request->get('message'))) {
				return $this->error = 'You did not fill in a message!';
			} else {

				// Insert into database
				$insert = $this->connect->database->prepare('INSERT INTO messages (username, image, message) VALUES (:username, :image, :message)');
				$insert->execute([
					':username' => $_SESSION['user']['username'],
					':message' => $this->request->get('message'),
					':image' => 'placeholdermale.jpg'
				]);
			}
		}
	}

	public function showMessages()
	{
		$get = $this->connect->database->prepare('SELECT * FROM messages  ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item)
		{
			echo '
				<div class="media message-wrapper message' . $item['id'] . '">
					<div class="media-left">
						<img class="media-object" src="assets/images/' . $item['image'] . '">
					</div>
					<div class="media-body">
						<p class="name">' . $item['username'] . '</p>
						<p class="message">' . $item['message'] . '</p>
						<p class="date"><i>' . $item['date'] . '</i></p>
					</div>
				</div>
			';
		}
	}

	public function showRecentMessages()
	{
		$get = $this->connect->database->prepare('SELECT username, image, date FROM messages ORDER BY date DESC');
		$get->execute();

		foreach ($get as $item)
		{
			echo '
				<div class="media">
					<div class="media-left">
						<img class="media-object" src="assets/images/' . $item['image'] . '">
					</div>
					<div class="media-body">
						<p class="name">' . $item['username'] . '</p>
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

	public function likeMessage() {
		if (isset($_POST['like'])) {
			$update = $this->connect->database->prepare('UPDATE messages SET likes = likes+1');
			$update->execute();
		}
	}

	public function showReplies() {
		$get = $this->connect->database->prepare('SELECT * FROM comments WHERE id = :id');
		$get->execute([
			'id' => $this->request->get('id')
		]);

		foreach ($get as $item) {
			echo $item['message'];
		}
	}

	
	public function deleteMessage() {
		
		if (isset($_POST['delete'])) {
			$delete = $this->connect->database->prepare('DELETE FROM messages WHERE id = :id');
			$delete->execute([
				'id' => $this->request->get('hidden')
			]);
		}
	}
}

# End of File