<?php
require_once "func.php";
session_start();

// Если не авторизован никто то делаем редирект на форму авторизации со статусом 403
if(!is_logged_guest()) {
	header('Location: ' . get_login_path(), 403);
	die;
}

header('Content-Type: image/png');

// Принимаем значении в сессии
$name = $_SESSION['login']; // Имя
$surname = $_SESSION['surname']; // Фамилия
$percent = $_SESSION['percent']; // Процент
$theme = "Основы " . $_SESSION['testfile']; // Тема или предмет

// Балл в процентах
$percent_val = substr($percent, 1, -3);

// Получение оценки $eval через $percent_val
if($percent_val >= 90) {
	$eval = 5;
	$percent_color = "0,255,0";
}
elseif($percent_val < 90 && $percent_val >= 75) {
	$percent_color = "0,0,255";
	$eval = 4;
}
elseif($percent_val < 75 && $percent_val >= 50) {
	$eval = 3;
	$percent_color = "255,0,0";
}
else {
	$eval = 2;
	$percent_color = "255,0,0";
}

// Создание сертификата в соответствии с результатом
create_image($name, $surname, $theme, $percent, $percent_color, $eval);

?>