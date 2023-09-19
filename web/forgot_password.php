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

	.loader {
		width: 48px;
		height: 48px;
		border: 2px solid #006699;
		border-radius: 50%;
		display: inline-block;
		position: relative;
		box-sizing: border-box;
		animation: rotation 1s linear infinite;
	}

	.loader::after,
	.loader::before {
		content: '';
		box-sizing: border-box;
		position: absolute;
		left: 0;
		top: 0;
		background: #FF3D00;
		width: 6px;
		height: 6px;
		transform: translate(150%, 150%);
		border-radius: 50%;
	}

	.loader::before {
		left: auto;
		top: auto;
		right: 0;
		bottom: 0;
		transform: translate(-150%, -150%);
	}

	@keyframes rotation {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	#spanRefresh {
		cursor: pointer;
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
						<form id="formForgotPassword" action="login" method="POST">
							<div class="form-group">
								<label for="email" class="control-label text-dark">Email</label>
								<input type="text" id="inputEmail" name="email" class="form-control form-control-sm" value="<?php echo $email ?? '' ?>" required placeholder="Email">
							</div>
							<center><input id="btnSubmit" class="btn-sm btn-block btn-wave col-md-4 btn-primary" name="submit" type="submit" value="Request Reset"></center>
						</form>

						<div id="divResend" class="d-none">
							<p class="text-dark">A password reset link has been sent to email <span id="spanEmail" class="text-info"> kimjose693@gmail.com</span>. Didn't get the link? Resend in <span id='spanTimer' class='text-info'> 60 seconds.</span>. If that is not your email click <span id="spanRefresh" class="text-primary"> here</span> </p>
							<button id="btnResend" class="btn btn-primary d-none">Resend</button>
						</div>

						<div class="loaderHolder" style=" width: 100%; z-index: 10px; text-align: center ">
							<span class="loader d-none"></span>
						</div>
					</div>

				</div>

			</div>


		</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

	<!-- Toastr -->
	<script src="assets/plugins/toastr/toastr.min.js"></script>

	<script>
		const formForgotPassword = document.getElementById('formForgotPassword')
		const inputEmail = document.getElementById('inputEmail')
		const btnSubmit = document.getElementById('btnSubmit')
		const spanEmail = document.getElementById('spanEmail')
		const spanTimer = document.getElementById('spanTimer')
		const spanRefresh = document.getElementById('spanRefresh')
		const btnResend = document.getElementById('btnResend')
		const divResend = document.getElementById('divResend')
		const loader = document.querySelector('.loader')

		let sentToAddress = ''

		/**
		 * @param {Number} target  
		 */
		const countDown = (target) => {
			if (!btnResend.classList.contains('d-none')) btnResend.classList.add('d-none')
			let interval = setInterval(() => {
				let now = new Date().getTime();
				if (now >= target) {
					if (btnResend.classList.contains('d-none')) btnResend.classList.remove('d-none')
					spanTimer.innerText = `0 seconds `
					clearInterval(interval)
				} else {
					let diff = Math.floor((target - now) / 1000)
					spanTimer.innerText = `${diff} seconds `
				}
			}, 1000, target)
		}

		spanRefresh.addEventListener('click', () => location.reload())

		formForgotPassword.addEventListener('submit', e => {
			e.preventDefault()
			let email = inputEmail.value.trim()

			loader.classList.remove('d-none')
			btnSubmit.classList.add('d-none')
			fetch("../api/user-request-reset", {
					method: 'POST',
					headers: {
						"content-type": "application/x-www-form-urlencoded"
					},
					body: JSON.stringify({
						'email': email
					})
				})
				.then(response => {
					return response.json()
				})
				.then(response => {
					let code = response.code
					if (code === 200) {
						toastr.success(response.message)
						if (divResend.classList.contains('d-none')) divResend.classList.remove('d-none')
						let target = new Date().getTime() + (60 * 1000)
						if (!loader.classList.contains('d-none')) loader.classList.add('d-none')
						btnSubmit.classList.add('d-none')
						sentToAddress = email
						countDown(target)
					} else {
						throw new Error(response.message)
					}
				})
				.catch(err => {
					toastr.error(err.message)
					if (!loader.classList.contains('d-none')) loader.classList.add('d-none')
				})

		})

		btnResend.addEventListener('click', () => {
			loader.classList.remove('d-none')
			btnResend.classList.add('d-none')
			fetch("../api/user-request-reset", {
					method: 'POST',
					headers: {
						"content-type": "application/x-www-form-urlencoded"
					},
					body: JSON.stringify({
						'email': sentToAddress
					})
				})
				.then(response => {
					return response.json()
				})
				.then(response => {
					let code = response.code
					if (code === 200) {
						toastr.success(response.message)
						if (divResend.classList.contains('d-none')) divResend.classList.remove('d-none')
						let target = new Date().getTime() + (60 * 1000)
						if (!loader.classList.contains('d-none')) loader.classList.add('d-none')
						countDown(target)
					} else {
						toastr.error(response.message)
					}
				})
		})
	</script>

</body>

</html>