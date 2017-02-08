<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Marco van de Lindt">
	<meta name="description" content="Profilepage from the Guestbook">
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
					<a href="../home" class="navbar-brand">Guestbook</a><
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav" aria-expanded="false">
						<span class="sr-only">Toggle Navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="nav">
					<ul class="nav navbar-nav navbar-right">
						<!-- Check if the user is logged in and display the navigation based on that (start of the if-statement )-->
						<?php if (isset($_SESSION['user'])) { ?>
						<li><a href="../home">Homepage</a>
						</li>
						<li><a href="#">My Friends</a>
						</li>
						<li><a href="../profile/<?php echo $_SESSION['user']['username']; ?>">My Profile</a>
						</li>
						<li><a href="../logout">Logout</a>
						</li>
						<!-- If the user is not logged in, display the following navigation -->
						<?php } else { ?>
						<li><a href="../home">Homepage</a>
						</li>
						<li><a href="../login">Login</a>
						</li>
						<li><a href="../register">Register</a>
						</li>
						<!-- End of the if-statement -->
						<?php } ?>
					</ul>
				</div>
			</div>
		</nav>

		<div class="section">
			<div class="row">
			<!-- Check if the Session of the current user is the same as the users profile -->
			<?php if ($_SESSION['user']['username'] == $username) { ?>
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
				<!-- Check if the URL contains nothing -->
				<?php if (!isset($_GET['edit']) && !isset($_GET['account'])) { ?>
				<div class="col-md-6">
					<div class="panel panel-default user-messages-panel default-panel">
						<div class="panel-heading">
							<p>Messages from: <strong>@<?php echo $username; ?></strong></p>
						</div>
						<div class="panel-body">
							<form class="form" method="POST">
								<label for="welcome-message" class="label-control">Hello, <?php echo $username; ?>. Would you like to share something?</label>
								<br><br>
								<div class="row">
									<div class="col-sm-9">
										<textarea class="form-control" name="message" placeholder="What would you like to say?"></textarea>
									</div>
									<div class="col-sm-3">
										<button type="button" class="btn btn-success" name="sendMessage">Post It!</button>
									</div>
								</div>
							</form>
							<hr>
							<?php echo $profile->getUsersMessages(); ?>
						</div>
					</div>
				</div>
				<!-- If the URL contains &edit -->
				<?php } else if (isset($_GET['edit']) && !isset($_GET['account'])) { ?>
				<div class="col-md-6">
					<div class="alert alert-info">From this page, you can edit your personal information!</div>
					<div class="panel panel-default edit-profile default-panel">
						<div class="panel-heading">
							<p>Change my personal information!</p>
						</div>
						<div class="panel-body">
							<form class="form" method="POST">
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="firstname" class="col-2 col-form-label">First name:</label>
										<div class="col-10">
											<input type="text" name="firstname" class="form-control" value="<?php echo $_SESSION['user']['username']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="lastname" class="col-2 col-form-label">Last name:</label>
										<div class="col-10">
											<input type="text" class="form-control" name="lastname" value="<?php echo $_SESSION['user']['last_name']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="birthdate" class="col-2 col-form-label">Date of Birth:</label>
										<div class="col-10">
											<input type="text" class="form-control" name="birthdate" value="<?php echo $_SESSION['user']['birth_date']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="nickname" class="col-2 col-form-label">Nickname:</label>
										<div class="col-10">
											<input type="text" class="form-control" name="nickname" value="<?php echo $_SESSION['user']['nickname']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="country" class="col-2 col-form-label">Country:</label>
										<div class="col-10">
											<input type="text" class="form-control" name="country" value="<?php echo $_SESSION['user']['country']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="email" class="col-2 col-form-label">E-mailaddress:</label>
										<div class="col-10">
											<input type="text" class="form-control" name="email" value="<?php echo $_SESSION['user']['email']; ?>">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<label for="bio" class="col-2 col-form-label">Bio:</label>
										<div class="col-10">
											<textarea class="form-control" name="bio"><?php echo $_SESSION['user']['bio']; ?></textarea>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12 add-padding">
										<button type="submit" class="btn btn-info" name="editProfile">Edit</button>
										<button type="reset" class="btn btn-warning" name="reset">Reset</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- If the URL contains &account: -->
				<?php } else if (!isset($_GET['edit']) && isset($_GET['account'])) { ?>
				<div class="col-md-6">
					<div class="alert alert-warning">From this page you can edit your account settings!</div>
					<div class="panel panel-default default-panel">
						<div class="panel-heading">
							<p>Change my account information!</p>
						</div>
						<div class="panel-body">
							<div class="form-group row">
								<label for="username" class="col-2 col-form-label">Username:</label>
								<div class="col-10">
									<input type="text" name="username" class="form-control" value="<?php echo $_SESSION['user']['username']; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End the if statement which checks if the URL contains &edit or &account -->
				<?php }  ?>

				<div class="col-md-3">

				</div>
			<!-- If the Session of the current user isn't the same as the users profile, show the following -->
			<?php } else { ?>
				<div class="col-md-3">

				</div>
				<div class="col-md-6">

				</div>
				<div class="col-md-3">

				</div>
			<?php }  ?>
			</div>
		</div>
	</div>
</body>
</html>