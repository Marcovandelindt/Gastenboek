<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Marco van de Lindt">
		<meta name="description" content="Gastenboek">
		<title><?php echo $title; ?></title>

		<link rel="stylesheet" type="text/css" href="../assets/css/style.css" media="screen">

		<!-- Requiring the Bootstrap CSS CDN -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

		<!-- Requiring the Font Awesome CDN -->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<nav class="navbar navbar-default navbar-static-top top-nav">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="../home">Guestbook</a>
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav" aria-expanded="false">
							<span class="sr-only">Toggle Navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>

					<div class="collpase navbar-collapse" id="nav">
						<ul class="nav navbar-nav navbar-right">
							<?php
								if (isset($_SESSION['user'])) {
							?>
							<li><a href="../home">Homepage</a></li>
							<li><a href="../home">My Friends</a></li>
							<li><a href="../profile/<?php echo $_SESSION['user']['username']; ?>">My Profile</a></li>
							<li><a href="../logout">Logout</a></li>
							<?php
								} else {
							?>
							<li><a href="../home">Homepage</a></li>
							<li><a href="../login">Login</a></li>
							<li><a href="../register">Register</a></li>
							<?php
								}
							?>
						</ul>
					</div>
				</div>
			</nav>

			<div class="section">
				<div class="row">
					<div class="col-md-3">
						<div class="profile">
							<div class="thumbnail">
								<img src="../assets/images/placeholdermale.jpg">
								<div class="caption">
									<ul class="list-group">
										<li class="list-group-item active">Personal Information</li>
										<?php echo $profile->getUserDetails(); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>		
					<div class="col-md-6">
						<div class="pane panel-default users-messages default-panel">
							<div class="panel-heading">
								<p>All messages from: <strong>@<?php echo $profile->getUsername(); ?></strong></p>
							</div>
							<div class="panel-body">
								<?php echo $profile->getUsersMessages(); ?>
							</div>
						</div>
					</div>
					<div class="col-md-3">

					</div>
				</div>	
			</div>
		</div>

		<?php require 'requirements/footer.html'; ?>
	</body>
</html>