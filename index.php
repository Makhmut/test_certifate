<?php
require_once "func.php";
session_start();

$guest_base_url = '/u/abaiuly/lesson-2.3/list.php';
$admin_base_url = '/u/abaiuly/lesson-2.3/list_auth.php';

if(isset($_POST['Auth'])) {
	// Если пуста элемент pass то авторизуем гость и делаем редирект на гостевую
	if(empty($_POST['Auth']['pass'])) {
		$login = $_POST['Auth']['firstname'];
		$surname = $_POST['Auth']['lastname'];
		
		$is_logged = login_guest($login, $surname);
		
		if($is_logged) {
			header('Location: ' . $guest_base_url);
			die;
		}
		else {
			header('Location: ' . get_login_path());
			die;
		}
	}
	// Иначе авторизуем админа и делаем редирект на админскую зону
	else {
		$login = $_POST['Auth']['firstname'];
		$surname = $_POST['Auth']['lastname'];
		$pass = $_POST['Auth']['pass'];
		
		$is_logged = login($login, $surname, $pass);
		
		if($is_logged) {
			header('Location: ' . $admin_base_url);
		}
		else {
			header('Location: ' . get_login_path());
			die;
		}
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Тестирование на web технологии</title>
	<style>
		* {
			transition: all ease-in-out .4s;
			-webkit-transition: all ease-in-out .4s;
			-moz-transition: all ease-in-out .4s;
		}
		#loginform input {
			padding: 5px 10px;
			margin-bottom: 15px;
			width: 150px;
			border-radius: 5px;
			outline: none;
		}
		#loginform button {
			padding: 7px 10px;
			width: 180px;
			background: #f2f2f2;
			font-size: 15px;
			color: #333;
			border: none;
			border-radius: 5px;
			margin-bottom: 15px;
			margin-left: 80px;
			display: block;
		}
		#loginform button:hover {
			background: #009899;
			color: #fff;
			cursor: pointer;
			transition: ease-in-out .5s;
			-webkit-transition: ease-in-out .3s;
			-moz-transition: ease-in-out .5s;
		}
		a {
			padding: 10px 20px;
			background-color: #008098;
			color: #fff;
			margin-top: 10px;
			display: inline-block;
			border-radius: 5px;
		}
		a:hover {
			background-color: #f2f2f2;
			color: #333;
			text-decoration: none;
		}
	</style>
	<!-- Скрипт для скрытие/показа поле ввода паролья События в onClick -->
	<script type="text/javascript">
		function AuthFunc() {
			var elem = document.getElementById("pass");
			elem.style.display = (elem.style.display == 'block') ? 'none' : 'block';
			var a = document.getElementById("link");
			a.innerHTML = (a.innerHTML == 'Я не администратор') ? 'Авторизоваться' : 'Я не администратор';
			var h = document.getElementById("h3");
			h.innerHTML = (h.innerHTML == 'Войти как администратор:') ? 'Войти как гость:' : 'Войти как администратор:';
		}
	</script>
</head>
<body>

<!-- Если авторизован админ или гость -->
<?php if(is_logged_admin()) : ?>
		<h2 align="center">Здравствуйте, <span style='color: CadetBlue;'><?php echo $_SESSION['login'] .' '. $_SESSION['surname'];?>!</span></h2>
		<p style='text-align: center;'><a href='list_auth.php'>Список тестов</a></p>
		<p style='text-align: center;'><a href='logout.php'>Выйти</a></p>
<!-- Если авторизован гость -->
<?php elseif(is_logged_guest()) : ?>
		<h2 align="center">Здравствуйте, <span style='color: CadetBlue;'><?php echo $_SESSION['login'] .' '. $_SESSION['surname'];?>!</span></h2>
		<p style='text-align: center;'><a href='list.php'>Список тестов</a></p>
		<p style='text-align: center;'><a href='logout.php'>Выйти</a></p>
<!-- Если не авторизован админ или гость -->
<?php else : ?>
		<div style='margin: 0 auto; width: 450px;'>
			<h2>Тестирование на web технологии</h2>
			<h3 id="h3">Войти как гость:</h3>
			<p style='color: red;'><b><?php echo get_error();?></b></p>
			<form action="" method="post" id="loginform">
				<input type="text" name="Auth[firstname]" placeholder="Имя" required />
				<input type="text" name="Auth[lastname]" placeholder="Фамилия" required />
				<input style="display: none; width: 330px;" id="pass" type="password" name="Auth[pass]" placeholder="Пароль" />
				<button type="submit" name="login">Войти</button>
			</form>
			<p style='margin-left: 85px;'><a href="#" id="link" onClick="AuthFunc()">Авторизоваться</a></p>
		</div>
<?php endif; ?>
<!-- Очишаем ошибки -->
<?php clear_error(); ?>
</body>
</html>