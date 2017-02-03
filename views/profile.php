<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Marco van de Lindt">
		<meta name="description" content="Gastenboek">
		<title><?php echo $title; ?></title>

		<?php require 'requirements/header.html'; ?>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top">
			<div class="container-fluid">

			</div>
		</nav>
		<?php
			if (!isset($_GET['details'])) {
		?>
		<p>Dit is niet de eerste keer dat je inlogd!</p>
		<?php
			} else {
		?>

		<?php
			}
		?>

		<?php require 'requirements/footer.html'; ?>
	</body>
</html>