<?php
require_once "func.php";
session_start();

if(!is_logged_admin()) {
	header('Location: ' . get_login_path(), 403);
	die;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Обработка форм</title>
	<style>
		form {
			margin: 0 auto;
			width: 350px;
		}
	</style>
</head>
<body>

<h3 align="center">Загрузка тестовых файлов на сервер (расширение *.json)</h3>

<form action="list_auth.php" enctype="multipart/form-data" method="post">
	<input type="file" name="testfile" />
	<button type="submit">Загрузить</button>
</form>


</body>
</html>