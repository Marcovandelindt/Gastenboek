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
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-default register-panel">
					<div class="panel-body">
						<div class="col-sm-12">
							<div class="form-heading text-center">
								<p>Login!</p>
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
								<input type="email" name="email" class="form-control" placeholder="What's your e-mailaddress?" aria-describedby="basic-addon2">
								<span class="input-group-addon" id="basic-addon2"><span class="glyphicon glyphicon-envelope"></span></span>
							</div>
							<div class="input-group add-padding">
								<input type="password" name="password" class="form-control" placeholder="What's your password?" aria-describedby="basic-addon3">
								<span class="input-group-addon" id="basic-addon3"><span class="fa fa-user-secret"></span></span>
							</div>
							<div class="form-group add-padding">
								<button type="submit" class="btn btn-success login-button" name="login">Login</button>
								<button type="reset" class="btn btn-warning" name="reset">Reset</button>
							</div>
							<div class="form-group add-padding">
								<a href="register">I don't have an account yet, let's make one!</a><br>
								<a href="home">Take me back to the homepage, please.</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php require 'requirements/footer.html'; ?>

		<script type="text/javascript">
			$('.remover').on('click', function() {
				$(this).parent().remove();
			});
		</script>
	</body>
</html>