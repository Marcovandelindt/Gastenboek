<!DOCTYPE html>
<html lang="en">	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<mata name="author" content="Marco van de Lindt">
		<meta name="description" content="Gastenboek">
		<title><?php echo $title;?></title>

		<?php require 'requirements/header.html'; ?>
	</head>
	<body>
		<div class="container">
			<nav class="navbar navbar-default navbar-static-top top-nav">
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

					<?php
						if (isset($_SESSION['user'])) {
					?>
					<div class="collapse navbar-collapse" id="nav">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="home">Homepage</a></li>
							<li><a href="">My Friends</a></li>
							<li class="active"><a href="profile">My Profile</a></li>
							<li><a href="logout">Logout</a></li>
						</ul>
					</div>
					<?php 
						} else {
					?>
					<div class="collapse navbar-collapse" id="nav">
						<ul class="nav navbar-nav navbar-right">
							<li class="active"><a href="home">Homepage</a></li>
							<li><a href="login">Login</a></li>
							<li><a href="register">Register</a></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
			</nav>

			<div class="section">
				<div class="row">
					<!-- Left side of the page -->
					<div class="col-md-3">
						<div class="profile">
							<div class="thumbnail">
								<img src="assets/images/placeholdermale.jpg">
								<div class="caption">
									<ul class="list-group">
										<li class="list-group-item active">Personal Information</li>
										<?php echo $user->getUserInformation(); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- End of the left side of the page -->
					<div class="col-md-6">
						<div class="panel panel-default my-messages default-panel">
							<div class="panel-heading">
								<p>All messages by @<?php echo $_SESSION['user']['username']; ?></p>
							</div>
							<div class="panel-body">
								<?php echo $user->getUsersMessages(); ?>
							</div>
						</div>
					</div>
					<!-- Middle of the page -->
					<div class="col-md-3">
						<div class="panel panel-default friends-panel default-panel">
							<div class="panel-heading">
								<p>All my friends</p>
							</div>
							<div class="panel-body">
								<p><i>You don't have any friends just yet...</i></p>
							</div>
						</div>
						<div class="panel panel-default default-panel statistics">
							<div class="panel-heading">
								<p>Statistics</p>
							</div>
							<div class="panel-body">
								<ul class="list-group">
									<li class="list-group-item">
										<i class="fa fa-pencil"></i>&nbsp;Messages<span class="pull-right"><?php echo $messages->countMessages(); ?>
									</li>
									<li class="list-group-item">
										<i class="fa fa-users"></i>&nbsp;Users<span class="pull-right"><?php echo $user->countUsers(); ?>
									</li>
									<li class="list-group-item">
										<i class="fa fa-bell"></i>&nbsp;Online Users<span class="pull-right"><?php echo $user->countOnlineUsers(); ?>
									</li>
									<li class="list-group-item">
										<i class="fa fa-minus-circle"></i>&nbsp;Offline Users<span class="pull-right"><?php echo $user->countOfflineUsers(); ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- End of the middle of the page -->

					<!-- Right side of the page -->

					<!-- End of the right side of the page -->
				</div>
			</div>
		</div>
		<?php require 'requirements/footer.html'; ?>
	</body>
</html>