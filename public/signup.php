<?php
	require dirname(__DIR__, 1).'/vendor/autoload.php';
	header('Content-Type: text/html; charset=utf-8');

	use App\Classes\Session;
	use App\Classes\Route;

	$sess = new Session;

	if( $sess->input('user_id') )
		Route::index();
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
		<title>Регистрация пользователя</title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-4 offset-4 mt-5 text-center">
					<h4 class="text-center">Регистрация</h4>
					<?= isset($_GET['form']) ? '<div class="valid-feedback d-block">'.$_GET['form'].'</div>' : '' ?>
					<form action="/signup" method="post" class="mb-4">
						<div class="form-group">
							<input type="email" name="email" class="form-control" value="<?= $_GET['input_email'] ?? '' ?>" placeholder="Email">
							<?= isset($_GET['email']) ? '<div class="invalid-feedback d-block">'.$_GET['email'].'</div>' : '' ?>
						</div>
						<div class="form-group">
							<input type="text" name="fio" class="form-control" value="<?= $_GET['input_fio'] ?? '' ?>" placeholder="ФИО">
							<?= isset($_GET['fio']) ? '<div class="invalid-feedback d-block">'.$_GET['fio'].'</div>' : '' ?>
						</div>
						<div class="form-group">
							<input type="text" name="login" class="form-control" value="<?= $_GET['input_login'] ?? '' ?>" placeholder="Login">
							<?= isset($_GET['login']) ? '<div class="invalid-feedback d-block">'.$_GET['login'].'</div>' : '' ?>
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control" placeholder="Пароль">
						</div>
						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control" placeholder="Подтвердить пароль">
							<?= isset($_GET['password']) ? '<div class="invalid-feedback d-block">'.$_GET['password'].'</div>' : '' ?>
						</div>
						<button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
					</form>
					<a class="text-primary" href="/public/signin.php">У меня есть аккаунт</a>
				</div>
			</div>
		</div>
	</body>
</html>