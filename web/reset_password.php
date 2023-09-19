<!DOCTYPE html>
<html lang="en">
<?php

use Umb\Mentorship\Models\PasswordReset;
use Umb\Mentorship\Models\User;

require_once __DIR__ . "/../vendor/autoload.php";

$token = $_GET['t'];
$allow = true;
if ($token == null || $token == '') $allow = false;
else {
	$passwordReset = PasswordReset::where('token', $token)->where('is_used', 0)->where('expires_at', '>', date('Y-m-d G:i:s'))->first();
	if ($passwordReset == null) $allow = false;
	else {
		$user = User::find($passwordReset->user_id);
		if ($user == null) $allow = false;
	}
}

?>

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Login | eSupport Supervision</title>
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

	<?php if ($allow) : ?>
		<main id="main">

			<div class="align-self-top w-100">
				<marquee behavior="alternate" direction="right">
					<img src="assets/img/ess_logo.png" alt="">
				</marquee>
				<div id="login-center" class="bg-dark row justify-content-center">
					<div class="card col-md-4">
						<div class="card-body">
							<h4 class="text-dark">Hi, <?php echo $user->first_name ?>. Set your new password</h4>
							<form id="formResetPassword" action="" method="POST">

								<div class="form-group">
									<label for="password" class="control-label text-dark">Password</label>
									<input type="password" id="inputPassword" name="password" class="form-control form-control-sm" required>
								</div>
								<div class="form-group">
									<label for="password" class="control-label text-dark">Password Confirm</label>
									<input type="password" id="inputPasswordConfirm" name="password-confirm" class="form-control form-control-sm" required>
									<small id="pass_match" data-status=''></small>
								</div>
								<center><input class="btn-sm btn-block btn-wave col-md-4 btn-primary" name="submit" type="submit" value="Reset Password"></center>
							</form>
							<hr>

						</div>
					</div>
				</div>
			</div>
		</main>

		<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
	<!-- Toastr -->
	<script src="assets/plugins/toastr/toastr.min.js"></script>

		<script>
			const token = '<?php echo $token; ?>'
			const inputPassword = document.getElementById('inputPassword')
			const inputPasswordConfirm = document.getElementById('inputPasswordConfirm')
			const formResetPassword = document.getElementById('formResetPassword')

			$(function() {

				$('[name="password"],[name="password-confirm"]').keyup(function() {
					var pass = $('[name="password"]').val()
					var cpass = $('[name="password-confirm"]').val()
					if (cpass == '' || pass == '') {
						$('#pass_match').attr('data-status', '')
					} else {
						if (cpass == pass) {
							$('#pass_match').attr('data-status', '1').html('<i class="text-success">Password Matched.</i>')
						} else {
							$('#pass_match').attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>')
						}
					}
				})


				formResetPassword.addEventListener('submit', e => {
					e.preventDefault()
					let password = inputPassword.value.trim()
					let passwordConfirm = inputPasswordConfirm.value.trim()
					if (password !== passwordConfirm) {
						toastr.error('Passwords do not match...')
						return;
					}
					if (password.length < 6) {
						toastr.error('Password too short')
						return
					}

					///api/reset-password
					fetch("../api/reset-password", {
							method: 'POST',
							headers: {
								"content-type": "application/x-www-form-urlencoded"
							},
							body: JSON.stringify({
								token: token,
								password: password
							})
						})
						.then(response => {
							return response.json()
						})
						.then(response => {
							let code = response.code
							if (code === 200) {
								toastr.success(response.message)
								setTimeout(() => {
									location.replace('login')
								}, 2000)
							} else {
								throw new Error(response.message)
							}
						})
						.catch(err => {
							toastr.error(err.message)
						})
				})


			})
		</script>

	<?php else : ?>
		<h2 class="text-danger">Operation not allowed. The link is likely to have expired. Request a new link...</h2>
	<?php endif; ?>
</body>

</html>