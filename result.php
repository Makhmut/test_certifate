<?php
require_once "func.php";
session_start();

if(!isset($_GET['testfile'])) {
	header('refresh:3;url='.get_login_path());
	die('Не задан параметр для определения имени тестового файла! Перенаправление на главную!');
}
else {
	$testfile = $_GET['testfile'];
	$_SESSION['testfile'] = $testfile;
}

if(isset($_POST['result'])) {
	$answers_not_empty = array_filter($_POST['Answer'], function($item) {return !empty($item);});
	if(count($answers_not_empty) < count(read_json('tests/'.$testfile.'.json'))) {
        if(is_logged_admin()) {
			header('refresh:3;url=list_auth.php');
		}
		else {
			header('refresh:3;url=list.php');
		}
        echo '<p>Все вопросы требуют ответа! Перенаправление на список тестов.</p>';
        die();
	}
	
	// Правильный ответ
	$correct = 0;
	
	// Неправильный ответ
	$wrong = 0;
	
	// Чтение тестового файла
	$test = read_json('tests/'.$testfile.'.json');
	
	// Пустой массив который будет содержать правильные вариантов ответа
	$correct_answers = array();
	
	// Пройдемся по циклу по .json файла по имени $testfile, которой передаем через $_GET
	for($i = 0; $i < count($test); $i++) {
		// Список ответов из .json файла
		$answers = $test[$i]['Answers'];
		// Ответы пользователя
		$user_answers = $_POST['Answer'][$i];
		
		// Цикл для проверки правильности ответов
		foreach($answers as $item) {
			// Добавим в конец массива $correct_answers корректный вариант ответа,
			// для того чтобы пользователью показать какой ответ правильный
			$correct_answer = $item['Correct_answer'];
			array_push($correct_answers, $correct_answer);
			
			// Если ответ верный увеличиваем на единицу $correct, иначе увеличиваем $wrong
			if($user_answers == $item['Correct_answer']) {
				$correct++;
			}
			else {
				$wrong++;
			}
		}
		
	}
}
else {
	die('Не нажата кнопка завершить и проверить');
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Результат тестов</title>
	<style>
		* {
			box-sizing: border-box;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			-o-box-sizing: border-box;
			margin: 0;
			padding: 0;
			border-radius: 5px;
		}
		h3 {
			margin: 15px 0;
			text-align: center;
		}
		p {
			margin: 15px 0;
		}
		.container {
			margin: 0 auto; 
			width: 800px; 
			padding: 25px 0;
		}
		.razbor {
			background-color: #f2f2f2;
			padding: 15px;
			margin-bottom: 25px;
		}
		.razbor:hover {
			background-color: #ccc;
			-webkit-transition: ease all .5s;
		}
		.razbor .question {
			color: blue;
			font-size: 18px;
		}
		.razbor .correct {
			padding: 10px;
			background-color: #fff;
			color: green;
		}
		.razbor .wrong {
			padding: 10px;
			background-color: #fff;
			color: red;
		}
		a.link {
			padding: 10px 20px;
			background-color: #008098;
			color: #fff;
		}
		a.link:hover {
			background-color: #f2f2f2;
			color: #333;
		}
	</style>
</head>
<body>
	<div class="container">
		<h2 align="center">Здравствуйте, <span style='color: CadetBlue;'><?php echo ucfirst($_SESSION['login']) . ' ' . ucfirst($_SESSION['surname']);?>!</span></h2>
		<h3 style="<?php if($correct > 3) echo 'color: green;'; else echo 'color: red;';?>">
			Ваш результат: <b><?php echo $correct;?></b> из <?php echo count($test);?><br />
			<?php 
				echo $percent = '('.($correct * 100) / count($test).' %)';
				$_SESSION['percent'] = $percent;
			?>
		</h3>
		<h3>Разбор каждого вопроса:</h3>
		<?php for($i = 0; $i < count($test); $i++) : ?>
			<div class="razbor">
				<p class="question"><b><?php echo ($i+1).'. '.htmlspecialchars($test[$i]['Question']);?></b></p>
				<p class="<?php if($_POST['Answer'][$i] == $correct_answers[$i]) echo 'correct'; else echo 'wrong';?>">Вы ответили: <b><?php echo htmlspecialchars($_POST['Answer'][$i]);?></b></p>
				<p class="correct">Правильный вариант: <b><?php echo htmlspecialchars($correct_answers[$i]);?></b></p>
			</div>
		<?php endfor; ?>
		<p style='text-align: center; display: inline-block;'><a href='<?php if(is_logged_admin()) {echo "list_auth.php";} else echo "list.php";?>' class="link">Вернуться к списку тестов</a></p>
		<p style='text-align: center; display: inline-block;'><a href='<?php echo "certificate.php";?>' target='_blank' class="link">Просмотреть сертификат</a></p>
		<p style='text-align: center; display: inline-block;'><a href='<?php echo "save.php";?>' class="link">Сохранить сертификат</a> (Сохранение возможно только после просмотра!)</p>
	</div>
</body>
</html>