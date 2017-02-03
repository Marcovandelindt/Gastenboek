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
				<div class="navbar-header">
					<a class="navbar-brand" href="home">Guestbook</a>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav" aria-expanded="false">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse" id="nav">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="home"><i class="fa fa-home"></i>&nbsp;Homepage</a></li>
						<li class="active"><a href="profile"><i class="fa fa-user-circle"></i>&nbsp;Profile</a></li>
						<li><a href="logout"><i class="fa fa-user"></i>Logout</a></li>
					</ul>
				</div>		
			</div>
		</nav>

		<?php
			if (!isset($_GET['details'])) {
		?>
		<div class="section">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4">
						<div class="profile">
							<div class="thumbnail">
								<img src="assets/images/generalplaceholder.png" class="profile-image">
								<div class="caption">
									<ul class="list-group">
										<li class="list-group-item active">Personal Information</li>
										<?php echo $user->getUserInformation(); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">

					</div>
				</div>
			</div>
		</div>
		<?php
			} else {
		?>
		<div class="section">
			<div class="container-fluid">
				<?php echo $user->getWelcomeMessage(); ?>
				<div class="col-md-4 col-md-offset-4">
					<div class="panel panel-default info-panel">
						<div class="panel-body">
							<div class="form-heading">Tell us a bit about yourself!</div>
							<form class="form" method="POST">
								<div class="form-group add-padding">
									<label for="firstname" class="label-control">First name:</label>
									<input type="text" name="firstname" class="form-control" placeholder="What is your firstname?">
								</div>
								<div class="form-group">
									<label for="lastname" class="label-control">Last name:</label>
									<input type="text" name="lastname" class="form-control" placeholder="What is your last name?">
								</div>
								<div class="form-group">
									<label for="birth_date" class="label-control">Date fo birth:</label>
									<input type="text" name="birth_date" class="form-control" placeholder="When were you born?">
								</div>
								<div class="form-group">
									<label for="country" class="label-control">Where are you from:</label>
									<input type="text" name="country" class="form-control" placeholder="For example: England, Netherlands">
								</div>
								<div class="form-group">
									<label for="nickname" class="label-control">Nickname:</label>
									<input type="text" name="nickname" class="form-control" placeholder="Do you have a nickname?">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-success" name="send">Send it!</button>
									<button type="reset" class="btn btn-warning" name="reset">Reset!</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			}
		?>

		<?php require 'requirements/footer.html'; ?>
	</body>
</html>