<?php
	require dirname(__DIR__, 1).'/vendor/autoload.php';
	header('Content-Type: text/html; charset=utf-8');
	
	use App\Classes\Session;
	use App\Classes\Route;
	use App\Classes\DataBase;
	
	use App\Controllers\PrivateController;
	
	if( $_SERVER['REQUEST_METHOD'] === 'GET' and isset($_GET['mode']) and $_GET['mode'] === 'logout' ) {
		$controller = new PrivateController();
		$controller->index();
	}

	$sess = new Session;

	if( !$sess->input('user_id') )
		Route::index();
	
	try
	{
		$pdo = new DataBase();
	
		$query = "
			SELECT id, fio, login, email
			FROM users
			WHERE id = ". $sess->input('user_id');
		
		$stmt = $pdo->db->prepare( $query );
		$stmt->execute();
	}
	catch (PDOException $e)
	{
		die("Error in :".__FILE__." file, at ".__LINE__." line. Can't get data : " . $e->getMessage(). " Query : $query");
	}
	
	$row = $stmt->fetch( PDO::FETCH_OBJ );
	
	
?>
<!doctype html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
		<title>Личный кабинет</title>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-4 offset-4 mt-5 text-center">
					<h4 class="text-center">Личный кабинет</h4>
					<?= isset($_GET['private']) ? '<div class="valid-feedback d-block">'.$_GET['private'].'</div>' : '' ?>
					<?= isset($_GET['form']) ? '<div class="valid-feedback d-block">'.$_GET['form'].'</div>' : '' ?>
					<form action="/private" method="post">
						<div class="form-group row">
							<label for="email" class="col-sm-3 col-form-label">Email</label>
							<div class="col-sm-9">
								<input type="email" name="email" id="email" class="form-control" value="<?= $row->email?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label for="fio" class="col-sm-3 col-form-label">ФИО</label>
							<div class="col-sm-9">
								<input type="text" name="fio" id="fio" class="form-control" value="<?= $_GET['input_fio'] ?? $row->fio?>">
								<?= isset($_GET['fio']) ? '<div class="invalid-feedback d-block">'.$_GET['fio'].'</div>' : '' ?>
							</div>
						</div>
						<div class="form-group row">
							<label for="login" class="col-sm-3 col-form-label">Login</label>
							<div class="col-sm-9">
								<input type="text" name="login" id="login" class="form-control" value="<?= $row->login?>" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label for="password" class="col-sm-3 col-form-label">Пароль</label>
							<div class="col-sm-9">
								<input type="password" name="password" id="password" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="confirm_password" class="col-sm-3 col-form-label">Пароль подтвердить</label>
							<div class="col-sm-9">
								<input type="password" name="confirm_password" id="confirm_password" class="form-control">
								<?= isset($_GET['password']) ? '<div class="invalid-feedback d-block">'.$_GET['password'].'</div>' : '' ?>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-9 offset-3">
								<button type="submit" class="btn btn-primary w-100">Изменить</button>
							</div>
						</div>
					</form>
					<div class="form-group row">
						<div class="col-sm-9 offset-3">
							<a class="text-primary" href="/public/private.php?mode=logout">Выйти</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>