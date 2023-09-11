<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__ . "/../vendor/autoload.php";

?>

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Forgot Password | eSupport Supervision</title>
	<link rel="icon" href="../favicon.ico">


	<?php include('./header.php'); ?>
	<!-- <?php
			// if (isset($_SESSION['login_id']))
			// header("location:index.php?page=home");

			?> -->

</head>
<style>
	body {
		width: 100%;
		height: calc(100%);
		position: fixed;
		top: 0;
		left: 0
			/*background: #007bff;*/
	}

	main#main {
		width: 100%;
		height: calc(100%);
		display: flex;
	}
</style>

<body class="bg-dark">


	<main id="main">

		<div class="align-self-top w-100">
			<marquee behavior="alternate" direction="right">
				<img src="assets/img/ess_logo.png" alt="">
			</marquee>
			<div id="login-center" class="bg-dark row justify-content-center">
				<div class="card col-md-4">
					<div class="card-body">
						<form id="login-form" action="login" method="POST">
							<div class="form-group">
								<label for="email" class="control-label text-dark">Email</label>
								<input type="text" id="email" name="email" class="form-control form-control-sm" value="<?php echo $email ?? '' ?>" required>
							</div>
							<center><input class="btn-sm btn-block btn-wave col-md-4 btn-primary" name="submit" type="submit" value="Submit"></center>
						</form>

					</div>
				</div>
			</div>
		</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

	

</body>

</html>