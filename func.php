<?php

error_reporting(0);

// Получение расширение файла
function getExt($file) {
	return substr($file, strpos($file, '.') + 1);
}

// Загрузка файла на сервер
function uploadFile($inputFile, $fileName, $allowedExt = ['json', 'txt']) {
	$uploadDir = 'tests';
	
	// Если файл с таким именем существует
	if(isset($_FILES[$inputFile])) {
		$ext = getExt($_FILES[$inputFile]['name']);
		
		// Если расширение не подходит
		if(!in_array($ext, $allowedExt)) {
			return false;
		}
		
		// Временная директория загружеамого файла
		$sourceFile = $_FILES[$inputFile]['tmp_name'];
		
		// Преобразуем имя загружаемого файла
		$name = "$fileName.$ext";
		
		// Путь для сохранения файла
		$destFile = realpath(__DIR__ . "/$uploadDir").'/'.$name;
		
		// Если файл помещен на $destfile
		if(move_uploaded_file($sourceFile, $destFile)) {
			// Даем список тестов
			$availableTests = scandir('tests');
			return $availableTests;
		}
		else {
			return false;
		}
	}
}

// Создание сертификата
function create_image($name, $surname, $theme, $percent, $percent_color, $eval) {
	$im = ImageCreateTrueColor(800, 600);
	$outline = imagecolorallocate($im, 179, 217, 255);
	$blue = imagecolorallocate($im, 77, 148, 255);
	$rect = imagecolorallocate($im, 251, 251, 251);
	$rect_outline = imagecolorallocate($im, 191, 191, 191);
	
	$text_color = imagecolorallocate($im, 191, 191, 191);
	$colors_for_percent = explode(',', $percent_color); 
	$percent_c = imagecolorallocate($im, $colors_for_percent[0],$colors_for_percent[1],$colors_for_percent[2]);
	
    $font_path = realpath(__DIR__ . '/files/verdana.ttf');
	
	imagefill($im, 0, 0, $outline);
	imagefilledrectangle($im, 20, 20, 780, 580, $rect);
	
	imagerectangle($im, 31, 31, 769, 569, $rect_outline);
	imagettftext($im, 50, 0, 170, 150, $blue, $font_path, "СЕРТИФИКАТ");
	imagettftext($im, 11, 0, 210, 180, $rect_outline, $font_path, "Настоящим сертификатом удостоверяется, что");
	imagettftext($im, 30, 0, 220, 250, $blue, $font_path, ucfirst($name) . ' ' . ucfirst($surname));
	
	imageline($im, 100, 270, imageSX($im) - 100, 270, $rect_outline);
	imagettftext($im, 11, 0, 250, 290, $blue, $font_path, "Прошел тестирование по предмету");
	imagettftext($im, 20, 0, 300, 323, $text_color, $font_path, $theme);
	
	imageline($im, 100, 335, imageSX($im) - 100, 335, $rect_outline);
	imagettftext($im, 11, 0, 305, 357, $blue, $font_path, "Оценка (Результат)");
	
	imagettftext($im, 18, 0, 320, 390, $percent_c, $font_path, "$eval $percent");
	
	imagettftext($im, 11, 0, 50, 550, $text_color, $font_path, "Дата: ");
	imagettftext($im, 11, 0, 100, 550, $blue, $font_path, date('Y-m-d'));
	
	imagettftext($im, 11, 0, 530, 550, $text_color, $font_path, "Проверил: ");
	imagettftext($im, 11, 0, 620, 550, $blue, $font_path, "Махмут Абайулы");
	
	imagepng($im, "files/certificate.png");
	
	imagepng($im);
	
    imagedestroy($im);
    imagedestroy($im);
}

// Чтение из .json файла
function read_json($file_name) {
	$json = file_get_contents($file_name);
	
	$test = json_decode($json, true);
	return $test;
}

// Авторизация для админа
function login($login, $surname, $pass, $login_file = 'files/login.json') {
	$admin = read_json($login_file);
 	if(($admin['Auth']['firstname'] == $login) && ($admin['Auth']['pass'] == $pass)) {
		$_SESSION['login'] = $login;
		$_SESSION['surname'] = $surname;
		$_SESSION['pass'] = $pass;
		return true;
	}
	else {
		set_error('Неверный логин и/или пароль!');
	}
	return false;
}

// Авторизация для гостей
function login_guest($login, $surname) {
	if((strlen($login) <= 3 || strlen($login >=20)) && 
		(strlen($surname) <= 3 || strlen($surname >=20))) {
		set_error('Все поля должны содержать не меньше 3 и не больше 20 букв!');
		return false;
	}
	else {
		$_SESSION['login'] = $login;
		$_SESSION['surname'] = $surname;
		return true;
	}
}

// Установка ошибок
function set_error($msg) {
    $_SESSION['error'] = $msg;
}

// Получение ошибок
function get_error() {
    return isset($_SESSION['error']) ? $_SESSION['error'] : '';
}

// Если авторизован админ
function is_logged_admin() {
	return (!empty($_SESSION['login']) && !empty($_SESSION['surname']) && !empty($_SESSION['pass']));
}

// Если авторизован гость
function is_logged_guest() {
	return (!empty($_SESSION['login']) && !empty($_SESSION['surname']));
}

function get_login_path() {
	return '/u/abaiuly/lesson-2.3/';
}

function clear_error() {
	unset($_SESSION['error']);
	unset($_SESSION['info']);
}

function logout() {
	session_destroy();
	header('Location: ' . get_login_path());
}

?>