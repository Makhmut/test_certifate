<?php
// Удаление тестового файла из директории
session_start();

$testfile = isset($_GET['testfile']) ? $_GET['testfile'] : 'return false';

if(!$testfile) {
	echo "<p>В этой директории такого файла нет!</p>";
	http_responce_code(404);
	die;
}
else {
	unlink('tests/'.$testfile.'.json');
	$_SESSION['info'] = 'Тестовый файл успешно удален!';
	header('Location: list_auth.php');
}

?>