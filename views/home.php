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
			<div class="header">
				<div class="col-md-4 heading">
					<h2>Gastenboek!</h2>
					<p>Laat je ook een berichtje achter?</p>
				</div>
			</div>

			<div class="section">
				<div class="container-fluid">
					<div class="content-wrapper">
					<div class="row">
						<!-- Left side of the Page -->
						<div class="col-md-8 left-side">
							<div class="col-md-12 messages-wrapper">
								<div class="col-sm-12">
									<div class="send-message">
										<?php
											if (isset($error)) {
												echo '
													<div class="alert alert-danger">
														<i class="glyphicon glyphicon-remove-circle"></i>&nbsp;
														' . $error . '
														<i class="glyphicon glyphicon-remove pull-right remover"></i>
													</div>
												';
											}

											if (isset($success)) {
												echo '
													<div class="alert alert-success">
														<i class="glyphicon glyphicon-ok-circle"></i>&nbsp;
														' . $success . '
														<i class="glyphicon glyphicon-remove pull-right remover"></i>
													</div>
												';
											}
										?>
										<div class="row">
											<div class="col-sm-2">
												<div class="image">
													<img src="assets/images/generalplaceholder.png">
												</div>
											</div>
											<?php
												if (isset($_SESSION['user']) == TRUE) {
											?>
											<div class="col-sm-10">
												<form class="post-message" method="POST">
													<label for="welcome-message" class="label-control">
														Welcome <?php echo $_SESSION['user']['username']; ?>. What would you like to share?
													</label>
													<div class="row">
														<div class="col-sm-8">
															<div class="form-group">
																<input type="text" name="name" class="form-control" value="<?php echo $_SESSION['user']['username']; ?>"> 
															</div>
														</div>
														<div class="col-sm-3">
															<div class="form-group">
																<select class="form-control" name="image" placeholder="Choose an image">
																	<option disabled>Choose your image</option>
																	<option>generalplaceholder.png</option>
																	<option>placeholder.png</option>
																	<option>placeholdermale.jpg</option>
																	<option>placeholderfemale.png</option>
																	<option>placeholderdog.jpg</option>
																	<option>placeholdercat.jpg</option>
																</select>
															</div>
														</div>
														<div class="col-sm-1">
															<div class="form-group">
																<span class="glyphicon glyphicon-info-sign image-info" data-toggle="modal" data-target="#imageModal"></span>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-sm-10">
															<div class="form-group">
																<textarea class="form-control" name="message" placeholder="<?php echo $_SESSION['user']['username']; ?>, what would you like to say?"></textarea>
															</div>
														</div>
														<div class="col-sm-2">
															<button type="submit" class="btn btn-success sender" name="sendMessage">
																Send it!
															</button>
														</div>
													</div>
												</form>
											</div>
											<?php
												} else {
											?>
											<div class="col-sm-10">
												<form class="form post-message" method="POST">
													<label for="welcome-message" class="label-control">
														Hello there! What's up?
													</label>
													<div class="row">
														<div class="col-sm-8">
															<div class="form-group">
																<input type="text" name="name" class="form-control" placeholder="What's your name?">
															</div>
														</div>
														<div class="col-sm-3">
															<div class="form-group">
																<select class="form-control" name="image" placeholder="Choose an image">
																	<option disabled>Choose your image</option>
																	<option>generalplaceholder.png</option>
																	<option>placeholder.png</option>
																	<option>placeholdermale.jpg</option>
																	<option>placeholderfemale.png</option>
																	<option>placeholderdog.jpg</option>
																	<option>placeholdercat.jpg</option>
																</select>
															</div>
														</div>
														<div class="col-sm-1">
															<div class="form-group">
																<span class="glyphicon glyphicon-info-sign image-info" data-toggle="modal" data-target="#imageModal"></span>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-sm-10">
															<div class="form-group">
																<textarea class="form-control" name="message" placeholder="What would you like to say?"></textarea>
															</div>
														</div>
														<div class="col-sm-2">
															<button type="submit" class="btn btn-success sender" name="sendMessage">
																Send it!
															</button>
														</div>
													</div>
												</form>
											</div>
											<?php
												}
											?>
										</div>
									</div>
								</div>
								<?php echo $messages->showMessages(); ?>
							</div>
						</div>
						<!-- End of the left side of the Page -->

						<!-- Right side of the Page -->
						<div class="col-md-4 right-side">
							<div class="col-sm-12">
								<div class="search-bar">
									<div class="input-group">
										<input type="text" name="search" class="form-control" placeholder="Search..." aria-describedby="basic-addon1">
										<span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-search"></span></span>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="recent-posts">
									<div class="page-header">
										<p>Latest messages:</p>
									</div>
									<ul>
										<?php $messages->showRecentMessages(); ?>
									</ul>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="recent-posts">
									<div class="page-header">
										<p>Online members:</p>
									</div>
								</div>
							</div>
						</div>
						<!-- End of the right side of the Page -->

						<!-- Here are the Modals stored -->
						<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="imageModalLabel">Information about the image</h5>
									</div>
									<div class="modal-body">
										<p><strong>Right now, you can't upload your own photos... yet!</strong></p>
										<p>We're working hard on making it possible for everyone to upload their own photos, but for now you have to do with the photo's we've selected for you.</p>
										<div class="row">
											<div class="col-md-4">
												<label for="generalplaceholder" class="label-control">General Placeholder:</label>
												<img src="assets/images/generalplaceholder.png" class="modal-image">
											</div>
											<div class="col-md-4">
												<label for="placeholder1" class="label-control">Placeholder 1:</label>
												<img src="assets/images/placeholder1.jpg" class="modal-image">
											</div>
											<div class="col-md-4">
												<label for="placeholdermale" class="label-control">Placeholder Male:</label>
												<img src="assets/images/placeholdermale.jpg" class="modal-image">
											</div>
										</div>
										<div class="row" style="margin-top: 15px;">
											<div class="col-md-4">
												<label for="placeholderfemale" class="label-control">Placeholder Female:</label>
												<img src="assets/images/placeholderfemale.png" class="modal-image">
											</div>
											<div class="col-md-4">
												<label for="placeholderdog" class="label-control">Placeholder Dog:</label>
												<img src="assets/images/placeholderdog.jpg" class="modal-image">
											</div>
											<div class="col-md-4">
												<label for="placeholdercat" class="label-control">Placeholder Cat:</label>
												<img src="assets/images/placeholdercat.jpg" class="modal-image">
											</div>
										</div>
										<p style="margin-top: 15px;"><strong>We're sorry for the inconvienience, but we'll try to have it working as soon as possible!</strong></p>
									</div>
								</div>
							</div>
						</div>
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