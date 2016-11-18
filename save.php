<?php
// Сохранение сертификата на локальный компьютер
// Сохранение возможен только после просмотра сертификата поскольку функция вызвана именно там

header('Content-type: image/png');
header('Content-Disposition: attachment; filename="certificate.png"');
readfile("files/certificate.png");
unlink("files/certificate.png");

?>