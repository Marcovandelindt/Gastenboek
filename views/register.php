<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta name="author" content="Marco van de Lindt">
		<meta name="description" content="Klein gastenboek in opdracht van Loyals">
		<title><?php echo $title; ?></title>

		<?php require 'requirements/header.html'; ?>
	</head>
	<body>
		<div class="container-fluid">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default register-panel">
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="form-heading text-center">
								<p>Register!</p>
							</div>
						</div>
						<form class="form" method="POST">
							<?php
								if (isset($error)) {
									echo '
										<div class="col-sm-12 add-padding">
											<div class="alert alert-danger">
												<i class="glyphicon glyphicon-remove-circle"></i>&nbsp;' . $error . '
												<i class="glyphicon glyphicon-remove pull-right remover"></i>
											</div>
										</div>
									';
								}

								if (isset($success)) {
									echo '
										<div class="col-sm-12 add-padding">
											<div class="alert alert-success">
												<i class="glyphicon glyphicon-ok-circle"></i>&nbsp;' . $success . '
												<i class="glyphicon glyphicon-remove pull-right remover"></i>
											</div>
										</div>
									';
								}
							?>
							<div class="input-group add-padding">
								<input type="text" name="username" class="form-control" placeholder="Choose a username" aria-describedby="basic-addon1">
								<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
							</div>
							<div class="input-group add-padding">
								<input type="email" name="email" class="form-control" placeholder="Choose your e-mailaddress" aria-describedby="basic-addon2">
								<span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-envelope"></span></span>
							</div>
							<div class="input-group add-padding">
								<input type="password" name="password" class="form-control" placeholder="Choose your password" aria-describedby="basic-addon3">
								<span class="input-group-addon" id="basic-addon3"><span class="fa fa-user-secret"></span></span>
							</div>
							<div class="input-group add-padding">
								<input type="password" name="password_confirm" class="form-control" placeholder="Confirm your password" aria-describedby="basic-addon4">
								<span class="input-group-addon" id="basic-addon4"><span class="fa fa-user-secret"></span></span>
							</div>
							<div class="form-group add-padding">
								<button type="submit" class="btn btn-success register-button" name="register">Register</button>
								<button type="reset" class="btn btn-warning" name="reset">Reset</button>
							</div>
							<div class="form-group add-padding">
								<a href="login">I already have an account, log me in!</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php require 'requirements/footer.html'; ?>
	</body>
</html>