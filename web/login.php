<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__ . "/../vendor/autoload.php";
session_start();

$loginError = '';
$redirect = $_GET['redirect'] ?? '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	try {
		if ($email == '') throw new \Exception("Enter a valid email", -1);
		$user = \Umb\Mentorship\Models\User::where('email', $email)->first();
		if ($user == null) throw new \Exception("Invalid credentials. Try again.");
		if (md5($password) == $user->password) {
			// echo "Here is here.";
			$user->last_login = date("Y:m:d h:i:s", time());
			$user->save();
			session_start();
			$sessionData = [];
			$sessionData['user'] = $user;
			$sessionData['expires_at'] = time() + ($_ENV['SESSION_DURATION'] * 60);
			$_SESSION[$_ENV['SESSION_APP_NAME']] = $sessionData;
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
				$url = "https://";
			else
				$url = "http://";
			// Append the host(domain name, ip) to the URL.
			$url .= $_SERVER['HTTP_HOST'];

			// Append the requested resource location to the URL
			$url .= $_SERVER['REQUEST_URI'];
			$pos = strpos($url, 'login');
			$len = strlen($url);
			$baseUrl = substr($url, 0, $pos);
			header('Location: ' . $baseUrl . $redirect);
		} else throw new \Exception("Invalid credentials. Try again.");
	} catch (\Throwable $th) {
		//        echo "login failed" . $th->getMessage();
		$loginError = $th->getMessage();
	}
	unset($_POST['submit']);
	unset($_POST['email']);
	unset($_POST['password']);
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
							<div class="form-group">
								<label for="password" class="control-label text-dark">Password</label>
								<input type="password" id="password" name="password" class="form-control form-control-sm" required>
							</div>
							<center><input class="btn-sm btn-block btn-wave col-md-4 btn-primary" name="submit" type="submit" value="Login"></center>
						</form>
						<hr>
						<div class="text-center">
							<a class="small" href="forgot_password.php">Forgot Password?</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>

</html>