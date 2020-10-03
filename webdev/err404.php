<?php
header('Expires: Wed, 18 Oct 2017 23:17:32 GMT');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
define('IN_CATALOG', true);
@require_once('allscript.php');
// Заголовок
$title = 'Ошибка 404';
// Описание страницы
$description = '';
// Ключевые слова для поисковых машин
$keywords = '';
// Выводим верхний колонтитул
header_all($title, $description, $keywords, 2);
?>
<h1>Ошибка 404</h1><br>
<?php
@$file = fopen('err404.txt', 'a+') or die('Ошибка');
flock($file, 2);
$date = date('H:i:s d-m-Y');
if (isset($_SERVER['REMOTE_USER'])) {
   $remoteUser = $_SERVER['REMOTE_USER'];
}
else $remoteUser = '';
if (isset($_SERVER['REQUEST_URI'])) {
   $requestUri = $_SERVER['REQUEST_URI'];
}
else $requestUri = '';
if (isset($_SERVER['HTTP_REFERER'])) {
   $httpReferer = $_SERVER['HTTP_REFERER'];
}
else $httpReferer = '';
if (isset($_SERVER['HTTP_USER_AGENT'])) {
   $userAgent = $_SERVER['HTTP_USER_AGENT'];
}
else $userAgent = '';
if (isset($_SERVER['REMOTE_ADDR'])) {
   $remoteAddr = $_SERVER['REMOTE_ADDR'];
}
else $remoteAddr = '';
$msg = "$date\nПользователь: " . $remoteUser. "\n";
$msg .= 'Текст ошибки: ' . $requestUri. "\n";
$msg .= 'Ссылка: ' . $httpReferer . "\n";
$msg .= 'Браузер: ' . $userAgent . "\n";
$msg .= 'IP-адрес: ' . $remoteAddr . "\n\n";
fwrite($file, $msg);
flock($file, 3);
fclose($file);
footer_user(); // Выводим нижний колонтитул
?>
