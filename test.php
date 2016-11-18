<?php

require_once "func.php";
session_start();

if(!is_logged_guest()) {
	header('Location: ' . get_login_path(), 403);
	die;
}

if(!isset($_GET['testfile'])) {
	die('Параметр не задан!');
}
else {
	$testfile = $_GET['testfile'].'.json';
	$files = scandir('tests');
	
	if(in_array($testfile, $files)) {
		$testfile = $_GET['testfile'];
	}
	else {
		http_response_code('404');
		echo "<p style='text-align: center;'>Такой тестовый файл не найдено!</p>";
		if(is_logged_admin()) {
			echo "<p style='text-align: center;'><a href='list_auth.php'>Вернуться к списку</a></p>";
		}
		else {
			echo "<p style='text-align: center;'><a href='list.php'>Вернуться к списку</a></p>";
		}
		die();
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Тесты</title>
	<style>
		table {
			margin: 0 auto;
			width: 350px;
		}
		input {
			margin: 7px 0;
		}
		.testform button[type="submit"] {
			margin: 20px auto;
			display: block;
			text-align: center;
			padding: 10px 20px;
			background-color: #008098;
			color: #fff;
			border: 0;
			cursor: pointer;
			border-radius: 5px;
		}
		.testform button[type="submit"]:hover {
			background-color: #f2f2f2;
			color: #333;
		}
	</style>
</head>
<body>

<h3 align="center">Тест на знание <?php echo $testfile;?></h3>

<div style='margin: 0 auto; width: 700px;'>
	<p><b>Порядок прохождения теста:</b></p>
	<ol>
		<li>Отвечаете на поставленные вопросы, выбрав <b>единственный</b> правильный вариант.</li>
		<li>По завершению тестирования Вы увидите <b>свой балл</b>, <b>количество ошибок</b>, а также <b>разбор каждого вопроса</b> из теста.</li>
	</ol>
	
	<form action="<?php echo "result.php?testfile=$testfile";?>" method="post" class="testform">
	
<?php

	$test = read_json('tests/'.$testfile.'.json');

	for($i = 0; $i < count($test); $i++) :
	$answers = $test[$i]['Answers'];
?>
		<div>
			<p><b><?php echo ($i+1).'. '.htmlspecialchars($test[$i]['Question']);?></b></p>
			<?php for($k = 0; $k < count($answers, COUNT_RECURSIVE) - 2; $k++) : ?>
				<?php foreach($answers as $item) : ?>
					<label>
						<input name="Answer[<?php echo $i;?>]" type="radio" value="<?php echo $item['Answer'.$k];?>">
						<?php echo htmlspecialchars($item['Answer'.$k]);?><br />
					</label>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
		
<?php endfor; ?>
		
		<button type="submit" name="result">Завершить и проверить</button>
	</form>

</div>

</body>
</html>