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
	<title>Тесты</title>
	<style>
		table {
			margin: 0 auto;
			width: 320px;
		}
		a {
			color: Blue;
			font-weight: bold;
			text-decoration: underline;
		}
		a:hover {
			text-decoration: none;
			color: Tomato;
		}
	</style>
</head>
<body>

<?php

$dir = scandir('tests');

// Если папка с тестовым файлом пуста(вернее меньше трех, потому что у скандира есть по умолчанию два элемента), 
// то загружаем файлы на сервер
if(count($dir) < 3) {
	echo '<h3 align="center">В папке нет файлы для тестирование, загрузите файл по ссылку:</h3>';
	echo '<a href="admin.php" style="text-align: center; display: block">Загрузить тестовых файлов на сервер</a>';
}
// Иначе показываем список доступных тестов
else {
	echo "<h2 align='center'>Здравствуйте, администратор - <span style='color: CadetBlue;'>" . ucfirst($_SESSION['login']) . "!</span></h2>";
	echo '<h3 align="center">Список доступных тестов на данный момент</h3>';
	$testFiles = scandir('tests');
	
	if(strpos($_SERVER['HTTP_REFERER'], 'admin.php')) {
		$testFiles = uploadFile('testfile', substr($_FILES['testfile']['name'],0, -5));

		if(!$testFiles) {
			echo '<p style="text-align: center;">При загрузке файла произошла ошибка или не выбран файл!</p>';
			if(is_logged_admin()) {
				echo "<p style='text-align: center;'><a href='list_auth.php'>Перейти к списку</a></p>";
			}
			else {
				echo "<p style='text-align: center;'><a href='list.php'>Перейти к списку</a></p>";
			}
		}
	}
}

?>

<p style='text-align: center; color: red;'><b><?php echo $info = isset($_SESSION['info']) ? $_SESSION['info'] : '';?></b></p>

<table>

<?php for($i = 2; $i < count($testFiles); $i++) : ?>
	<tr>
		<td>&bull; <?php echo 'Тест по '.$testfile=substr($testFiles[$i],0,-5);?></td>
		<td><a href="<?php echo "test.php?testfile=$testfile";?>">Начать тест</a></td>
		<td><a href="<?php echo "delete.php?testfile=$testfile";?>">Удалить тест</a></td>
	</tr>
<?php endfor; ?>

</table>

<p style='text-align: center;'><a href="admin.php">Загрузить новый тестовый файл</a></p>
<p style='text-align: center;'><a href="logout.php">Выйти из админки</a></p>

<?php clear_error(); ?>
</body>
</html>