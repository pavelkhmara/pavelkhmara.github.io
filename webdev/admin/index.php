<?php
header('Expires: Wed, 18 Oct 2017 23:17:32 GMT');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
define('IN_CATALOG', true);
@require_once('allscript.php');
// Подключаемся к базе данных
$db = db_connect();
// Заголовок
$title = 'Администрирование';
// Выводим верхний колонтитул
header_all($title, '', '', 2);

if (isset($_GET['go401'])) { // Очищаем файл err401.txt
   file_put_contents('../err401.txt', ' ');
}
if (isset($_GET['go403'])) { // Очищаем файл err403.txt
   file_put_contents('../err403.txt', ' ');
}
if (isset($_GET['go404'])) { // Очищаем файл err404.txt
   file_put_contents('../err404.txt', ' ');
}
$arr['s'] = 0;
$arr['y'] = 0;
$arr['n'] = 0;
$count_site = $msg_new = $count_msg = 0;
$res = @mysqli_query($db, 'SELECT COUNT(`id_site`) FROM `site`') or die(
                     'Ошибка ' . mysqli_errno($db));
if (mysqli_num_rows($res) === 1) {
   $count_site = mysqli_fetch_row($res)[0];
}
mysqli_free_result($res);
$query = 'SELECT `status_site`, COUNT(`id_site`) ';
$query .= 'FROM `site` GROUP BY `status_site`';
$res = @mysqli_query($db, $query) or die('Ошибка ' . mysqli_errno($db));
while ($row = mysqli_fetch_row($res)) {
   $arr[$row[0]] = $row[1];
}
mysqli_free_result($res);
$res = @mysqli_query($db, 'SELECT COUNT(`id_msg`) FROM `gbook`') or die(
                     'Ошибка ' . mysqli_errno($db));
if (mysqli_num_rows($res) === 1) {
   $count_msg = mysqli_fetch_row($res)[0];
}
mysqli_free_result($res);
$q = "SELECT COUNT(`id_msg`) FROM `gbook` WHERE `msg_new`='n'";
$res = @mysqli_query($db, $q) or die('Ошибка ' . mysqli_errno($db));
if (mysqli_num_rows($res) === 1) {
   $msg_new = mysqli_fetch_row($res)[0];
}
mysqli_free_result($res);
?>
<h1>Статистика</h1><br>
<br><div align="center" class="bold">
Количество сайтов в каталоге - <?php echo $count_site; ?><br>
- активных - <?php echo $arr['y']; ?><br>
<a href="moder.php">- на модерации - <?php echo $arr['n']; ?></a><br>
- нерабочие - <?php echo $arr['s']; ?><br></div>
<br><div align="center" class="bold">
Количество сообщений в гостевой книге - <?php echo $count_msg; ?><br>
<a href="gbook.php">- не прочитано - <?php echo $msg_new; ?></a>
</div>
<br><div align="center" class="bold">Ошибка 404<br>
<textarea name="msg404" id="msg404" cols="15" rows="20" class="textarea_frm" 
style="width: 500px; height: 150px">
<?php
if (file_exists('../err404.txt')) {
   echo hsc(file_get_contents('../err404.txt'));
}
?>
</textarea><br>
<form action="index.php"><input type="submit" name="go404" value="Очистить">
</form></div>
<br><div align="center" class="bold">Ошибка 401<br>
<textarea name="msg401" id="msg401" cols="15" rows="20" class="textarea_frm" 
style="width: 500px; height: 150px">
<?php
if (file_exists('../err401.txt')) {
   echo hsc(file_get_contents('../err401.txt'));
}
?>
</textarea><br>
<form action="index.php"><input type="submit" name="go401" value="Очистить">
</form></div>
<br><div align="center" class="bold">Ошибка 403<br>
<textarea name="msg403" id="msg403" cols="15" rows="20" class="textarea_frm" 
style="width: 500px; height: 150px">
<?php
if (file_exists('../err403.txt')) {
   echo hsc(file_get_contents('../err403.txt'));
}
?>
</textarea><br>
<form action="index.php"><input type="submit" name="go403" value="Очистить">
</form></div>
<?php
// Закрываем соединение с базой данных
mysqli_close($db);
footer_admin(); // Выводим нижний колонтитул
?>
