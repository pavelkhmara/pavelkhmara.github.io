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
?>
<h1>Сайты на модерации</h1><br>
<table align="center" width="600" bgcolor="#FFFFFF" border="0" cellpadding="2" cellspacing="0">
<tr><td align="left" bgcolor="#FFFFFF">
<?php
// Удаляем сайт
if (isset($_GET['del_msg']) && isset($_GET['check']) &&
    is_array($_GET['check'])) {
   foreach ($_GET['check'] as $value) {
      if (preg_match('/^[0-9]+$/s', $value)) {
         $del = "DELETE FROM `site` WHERE `id_site`=$value LIMIT 1";
         @mysqli_query($db, $del);
      }
   }
}
// Ставим галочку "Одобрено"
if (isset($_GET['upd_msg']) && isset($_GET['check']) &&
    is_array($_GET['check'])) {
   foreach ($_GET['check'] as $value) {
      if (preg_match('/^[0-9]+$/s', $value)) {
         $upd = "UPDATE `site` SET `status_site`='y' ";
         $upd .= "WHERE `id_site`=$value LIMIT 1";
         @mysqli_query($db, $upd);
      }
   }
}
if (isset($_GET['page'])) $page = (int)$_GET['page'];
else $page = 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * COUNT_POS_PAGE;
$err_text = '';
if (isset($_GET['search'])) {
   // Выводим поисковый запрос
   $text = $_GET['search'];
   $text = str_replace("\n", ' ', $text);
   $text = str_replace("\t", ' ', $text);
   $text = str_replace("\r", '', $text);
   $text = trim($text);
   if ($text == '') {
      $err_text = 'Не задана строка поиска<br>';
   }
   else {
      $text2 = mysqli_real_escape_string($db, $text);
      $query = 'SELECT SQL_CALC_FOUND_ROWS `site`.`id_site`, ';
      $query .= '`site`.`url_site`, `site`.`title`, `site`.`descr`, ';
      $query .= '`rubr`.`name_rubr` FROM `site`, `rubr` ';
      $query .= 'WHERE `site`.`id_rubr`=`rubr`.`id_rubr` AND ';
      $query .= "`url_site` LIKE '%$text2%' ";
      $query .= 'LIMIT ' . $start . ', ' . COUNT_POS_PAGE;
   }
}
if (!isset($query)) {
   // Выводим сайты для модерации
   $query = 'SELECT SQL_CALC_FOUND_ROWS `site`.`id_site`, ';
   $query .= '`site`.`url_site`, `site`.`title`, `site`.`descr`, ';
   $query .= '`rubr`.`name_rubr` FROM `site`, `rubr` ';
   $query .= 'WHERE `site`.`id_rubr`=`rubr`.`id_rubr` AND ';
   $query .= "`site`.`status_site`='n' ORDER BY `id_site` ";
   $query .= 'LIMIT ' . $start . ', ' . COUNT_POS_PAGE;
}
$res = @mysqli_query($db, $query) or die('Ошибка ' . mysqli_errno($db));
$count = mysqli_num_rows($res);
$r = @mysqli_query($db, 'SELECT FOUND_ROWS()') or die('Ошибка');
$allcount = mysqli_fetch_row($r)[0];
mysqli_free_result($r);
if ($count > 0) {
   echo '<form action="moder.php">';
   echo '<div align="center">';
   if (isset($text)) {
      echo '<input type="hidden" name="search" value="' . hsc($text) . '">';
   }
   echo '<input type="hidden" name="page" value="' . $page . '">';
   echo '<input type="submit" name="upd_msg" value="Одобрить">';
   echo '<input type="submit" name="del_msg" value="Удалить">';
   echo "</div><br>\n\n";
   while ($p = mysqli_fetch_row($res)) {
      // Выводим описание
      echo '<table width="100%" align="center" border="0" cellspacing="0">';
      echo '<tr><td class="color_table">';
      echo '<input type="checkbox" name="check[]" ';
      echo 'value="' . $p[0] . '"> ';
      echo '<a href="catalog.php?id=' . $p[0] . '" ';
      echo 'target="_blank">' . hsc($p[2]) . '</a></td></tr>';
      echo '<tr><td>' . hsc($p[3]) . '<br>';
      echo '<span class="bold">Рубрика: ' . hsc($p[4]) . '<br>';
      echo 'URL сайта: </span><a href="' . $p[1] . '" ';
      echo 'target="_blank">' . $p[1] . '</a>';
      echo "</td></tr></table><br>\n\n";
   }
   echo "</form>\n";
}
elseif ($allcount == 0) {
   echo '<div align="center" class="bold">';
   echo 'По вашему запросу ничего не найдено</div><br>';
}
else {
   echo '<div align="center">';
   if (isset($text)) {
      echo '<a href="?page=1&amp;search=' . urlencode($text) . '">';
   }
   else {
      echo '<a href="?page=1">';
   }   
   echo 'На первую страницу результатов</a></div>';
   $allcount = 0;
}
mysqli_free_result($res);
if ($allcount > COUNT_POS_PAGE) {
   if (isset($text)) {
      $src = '&amp;search=' . urlencode($text);
   }
   else {
      $src = '';
   }
   echo '<div align="center">';
   if (($start - COUNT_POS_PAGE) >= 0) {
      echo '<a href="?page=' . ($page - 1) . $src;
      echo '">&lt;&lt; Предыдущая страница</a> ';
   }
   if (($start + COUNT_POS_PAGE) < $allcount) {
      echo '<a href="?page=' . ($page + 1) . $src;
      echo '">Следующая страница &gt;&gt;</a> ';
   }
   echo '</div>';
}
if ($err_text != '') {
   echo '<div class="err">' . $err_text . '</div><br>';
}
?>
</td></tr>
</table>
<?php
// Закрываем соединение с базой данных
mysqli_close($db);
footer_admin(); // Выводим нижний колонтитул
?>
