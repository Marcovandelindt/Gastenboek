<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" description="Marco van de Lindt">
		<meta name="description" content="Simpel gastenboekje">
		<title><?php echo $title; ?></title>

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
							<li class="active"><a href="home">Homepage</a></li>
							<li><a href="">My Friends</a></li>
							<li><a href="profile">My Profile</a></li>
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

			<!-- Continuing with the rest of the page -->
			<div class="section">
				<div class="row">
					<!-- Left side of the page -->
					<div class="col-md-3">
						<div class="inner-wrapper top-wrapper">
							<div class="panel panel-default recent-members default-panel">
								<div class="panel-heading">
									<p>Newest Members</p>
								</div>
								<div class="panel-body">
									<ul class="list-group">
										<?php $user->getAllUsers(); ?>
									</ul>
								</div>
							</div>

							<div class="panel panel-default recent-posts default-panel">
								<div class="panel-heading">
									<p>Recent Posts</p>
								</div>
								<div class="panel-body">
									<?php echo $messages->showRecentMessages(); ?>
								</div>
							</div>

							<div class="panel panel-default online-users default-panel">
								<div class="panel-heading">
									<p>Online Users</p>
								</div>
								<div class="panel-body">
									<ul class="list-group">
										<?php echo $user->getOnlineUsers(); ?>
									</ul>
								</div>
							</div>
							<div class="panel panel-default offline-users default-panel">
								<div class="panel-heading">
									<p>Offline Users</p>
								</div>
								<div class="panel-body">
									<ul class="list-group">
										<?php echo $user->getOfflineUsers(); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- End of the left side of the page -->

					<!-- Middle of the page -->
					<div class="col-md-6">
						<div class="top-wrapper">
							<div id="carousel" class="carousel slide" data-ride="carousel">
								<ol class="carousel-indicators">
									<li data-target="#carousel" data-slide-to="0" class="active"></li>
									<li data-target="#caoursel" data-slide-to="1"></li>
								</ol>
								<div class="carousel-inner" role="listbox">
									<div class="item active">
										<img class="d-block img-fluid carousel-img" src="http://buddywp.wpengine.com/wp-content/uploads/photodune-3382849-mountain3-s1-1003x480.jpg">
										<div class="carousel-caption d-none d-md-block">
											<h3>Welcome to Guestbook</h3>
										</div>
									</div>

									<div class="item">
										<img class="d-block img-fluid carousel-img" src="assets/images/carousel.png">
										<div class="carousel-caption d-none d-md-block">
											<h3>Share your story</h3>
										</div>
									</div>
								</div>
							</div>

							<div class="top-wrapper" style="margin-top: 15px;">
								<div class="panel panel-default messages">
									<div class="panel-heading">
										<p>Recent Messages</p>
									</div>
									<div class="panel-body">
										<?php if (isset($_SESSION['user'])) {
									?>
									<form class="form" method="POST">
										<label for="welcome-text">Welcome back, <?php echo $_SESSION['user']['username']; ?>. What would you like to share?</label>
										<?php
											if (isset($error)) {
												echo '
													<div class="alert alert-danger">
														<i class="fa fa-exclamation"></i>&nbsp;' . $error . '<i class="fa fa-window-close pull-right remover"></i>
													</div>
												';
											}
										?>
										<div class="media">
											<div class="media-left">
												<img src="assets/images/placeholdermale.jpg">
											</div>
											<div class="media-body">
												<div class="row">
													<div class="col-sm-9">
														<div class="form-group">
															<textarea class="form-control" name="message" placeholder="Share something with the world!"></textarea>
														</div>
													</div>
													<div class="col-xs-3">
														<div class="form-group">
															<button type="submit" class="btn btn-success post-message" name="sendMessage">Post it!</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<hr>
									<?php echo $messages->showMessages(); ?>
									<?php
										} else {
											echo $messages->showMessages();
									?>

									<?php
										}
									?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End of the middle of the page -->

					<!-- Right side of the page -->
					<div class="col-md-3">
					<?php
						if (isset($_SESSION['user'])) {

					?>

					<?php
						} else {
					?>
						<div class="panel panel-default login-panel default-panel">
							<div class="panel-heading">
								<p>Log me in!</p>
							</div>
							<div class="panel-body">
								<form class="form" method="POST">
									<div class="form-group">
										<label for="email" class="label-control">E-mail address:</label>
										<input type="text" name="email" class="form-control" placeholder="E-mailaddress">
									</div>
									<div class="form-group">
										<label for="password" class="label-control">Password:</label>
										<input type="password" name="password" class="form-control" placeholder="Password">
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary" name="login">Login</button>
									</div>
								</form>
							</div>
						</div>
					<?php
						}
					?>
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
						<div class="panel panel-default search-panel default-panel">
							<div class="panel-body">
								<input type="text" name="search" class="form-control" placeholder="Search the Guestbook">
							</div>
						</div>

						<div class="ad">
							<img src="assets/images/ad.gif" class="ad-image">
						</div>

						<div class="panel panel-default online-friends default-panel">
							<div class="panel-heading">
								<p>Online Friends</p>
							</div>
							<div class="panel-body">
								<i style="color: grey">You currently have no friends...</i>
							</div>
						</div>
					</div>
					<!-- End of the right side of the page -->
				</div>
			</div>
		</div>

		<?php require 'requirements/footer.html'; ?>

		<script type="text/javascript">
			$('.fa-heart').on('click', function() {
				$('.badge').hide();
			});
		</script>

		<script type="text/javascript">
			function Blink(selector) {
				$(selector).fadeOut('slow', function() {
					$(this).fadeIn('slow', function() {
						Blink(this);
					});
				});
			}

			Blink('.fa-bell');
			Blink('.online-icon');
		</script>

		<script>
			$('.remover').on('click', function() {
				$(this).parent().hide(500);
			});
		</script>
	</body>
</html>