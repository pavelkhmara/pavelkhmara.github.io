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
<h1>Гостевая книга</h1><br>
<table align="center" width="600" bgcolor="#FFFFFF" 
border="0" cellpadding="2" cellspacing="0">
<tr><td align="left" bgcolor="#FFFFFF">

<?php
if (isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
// Удаляем сообщение
if (isset($_GET['del_msg']) && isset($_GET['check']) && 
    is_array($_GET['check'])) {
   foreach ($_GET['check'] as $value) {
      if (preg_match('/^[0-9]+$/s', $value)) {
         $del = "DELETE FROM `gbook` WHERE `id_msg`=$value LIMIT 1";
         @mysqli_query($db, $del);
      }
   }
}
// Ставим галочку "Прочитано"
if (isset($_GET['upd_msg']) && isset($_GET['check']) &&
    is_array($_GET['check'])) {
   foreach ($_GET['check'] as $value) {
      if (preg_match('/^[0-9]+$/s', $value)) {
         $upd = "UPDATE `gbook` SET `msg_new`='y' ";
         $upd .= "WHERE `id_msg`=$value LIMIT 1";
         @mysqli_query($db, $upd);
      }
   }
}
// Выводим сообщения
$query_count = 'SELECT COUNT(`id_msg`) FROM `gbook`';
$res_count = @mysqli_query($db, $query_count) or die('Ошибка ' . 
                                                     mysqli_errno($db));
if (mysqli_num_rows($res_count) === 1) {
   $count = mysqli_fetch_row($res_count)[0];
   if (preg_match('/^[0-9]+/s', $count) && $count > 0) {
      if (!isset($page) || $page < 1) $page = 1;
      if (!preg_match('/^[0-9]+$/s', $page)) $page = 1;
      $count_page = ceil($count / COUNT_POS_PAGE_GBOOK);
      if ($page > $count_page) $page = $count_page;
      $pos_start = ($page - 1) * COUNT_POS_PAGE_GBOOK;
      $query = 'SELECT `id_msg`, `author`, `msg`, msg_new, ';
      $query .= "DATE_FORMAT(`msg_date`, '%d.%m.%Y %H:%i') AS `d` ";
      $query .= 'FROM `gbook` ORDER BY `id_msg` DESC ';
      $query .= 'LIMIT ' . $pos_start . ', ' . COUNT_POS_PAGE_GBOOK;
      $res = @mysqli_query($db, $query) or die('Ошибка ' .
                                                mysqli_errno($db));
      echo '<form action="gbook.php">';
      echo '<div align="center"><input type="hidden" name="page" value="' .
           $page . '">';
      echo '<input type="submit" name="upd_msg" value="Прочитано">';
      echo '<input type="submit" name="del_msg" value="Удалить"></div><br>';
      while ($r = mysqli_fetch_assoc($res)) {
         $r['author'] = hsc($r['author']);
         $r['msg'] = hsc($r['msg']);
         $r['msg'] = str_replace("\n", '<br>', $r['msg']);
         echo '<table width="100%" align="center" border="0" cellspacing="0">';
         echo '<tr><td class="color_table">';
         echo '<input type="checkbox" name="check[]" value="';
         echo $r['id_msg'] . '"> <b>' . $r['d'] . ' ' . $r['author'];
         echo '</b></td></tr><tr><td>';
         if ($r['msg_new']=='n') {
            echo '<span class="err">NEW !!! </span> ';
         }
         echo $r['msg'] . "</td></tr></table><br>\n\n";
      }
      mysqli_free_result($res);
      if ($count > COUNT_POS_PAGE_GBOOK) { // Выводим количество страниц
         table_page_start(); // Таблица для количества страниц Начало
         echo '<span class="bold">Страницы:</span> ';
         for ($j = 1; $j < $count_page + 1; $j++) {
            if ($j == $page) echo '[' . $j . "] \n";
            else echo "<a href=\"?page=$j\">$j</a> \n";
         }
         table_page_end(); // Таблица для количества страниц Конец
      }
      echo '</form>';
   }
   else {
      echo '<div align="center" class="bold">Сообщений нет</div><br>';
   }
}
else echo '<div class="err">Ошибка</div><br>';
mysqli_free_result($res_count);
mysqli_close($db);
?>
</td></tr>
</table>
<?php
footer_admin(); // Выводим нижний колонтитул
?>
