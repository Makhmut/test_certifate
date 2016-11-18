<?php 
	require_once "func.php"; 
	session_start();
	
	if(!is_logged_guest()) {
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
		a.logout {
			text-align: center;
			display: block;
			margin-top: 15px;
			margin-right: 35px;
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
	echo "<h2 align='center'>Здравствуйте, <span style='color: CadetBlue;'>" . $_SESSION['login'] . ' ' . $_SESSION['surname'] . "!</span></h2>";
	echo '<h3 align="center">Список доступных тестов на данный момент</h3>';
	$testFiles = scandir('tests');
}

?>

<table>

<?php for($i = 2; $i < count($testFiles); $i++) : ?>
	<tr>
		<td>&bull; <?php echo 'Тест по '.$testfile=substr($testFiles[$i],0,-5);?></td>
		<td><a href="<?php echo "test.php?testfile=$testfile";?>">Начать тест</a></td>
	</tr>
<?php endfor; ?>
	<tr>
		<td colspan="2"><a href="logout.php" class="logout">Выйти</a></td>
	</tr>
</table>

</body>
</html>