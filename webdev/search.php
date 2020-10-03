<?php
header('Expires: Wed, 18 Oct 2017 23:17:32 GMT');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
define('IN_CATALOG', true);
@require_once('allscript.php');
// Подключаемся к базе данных
$db = db_connect();

if (isset($_GET['search'])) $text = $_GET['search'];
else $text = '';
if (isset($_GET['page'])) $page = (int)$_GET['page'];
else $page = 1;
$err_text = '';
$text = str_replace("\n", ' ', $text);
$text = str_replace("\t", ' ', $text);
$text = str_replace("\r", '', $text);
$text = trim($text);
if ($text == '') {
   $err_text = 'Не задана строка поиска<br>';
}
if (strlen($text) < 3) {
   $err_text .= 'В поле допустимо не менее 3 символов<br>';
}
if (strlen($text) > 50) {
   $err_text .= 'В поле допустимо не более 50 символов<br>';
}
// Заголовок
$title = 'Результаты поиска - ' . $text;
// Описание страницы
$description = '';
// Ключевые слова для поисковых машин
$keywords = '';
// Выводим верхний колонтитул
header_all($title, $description, $keywords, 2, $text);
table_2_start(); // Выводим таблицу второго уровня
echo '<h1>Результаты поиска</h1><br>' . "\n";
if ($err_text == '') {
   $t = urlencode($text);
   if (!isset($page) || $page < 1) $page = 1;
   if (!preg_match('/^[0-9]+$/s', $page)) $page = 1;
   $start = ($page - 1) * COUNT_POS_PAGE;
   $text = mysqli_real_escape_string($db, $text);
   $text = addcslashes($text, '_%');
   // Выводим результаты поиска
   $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM `site` ';
   $query .= "WHERE `status_site`='y' AND ";
   $query .= "(`title` LIKE '%" . $text . "%' ";
   $query .= "OR `descr` LIKE '%" . $text . "%' ";
   $query .= "OR `url_site` LIKE '%" . $text . "%') ";
   $query .= 'ORDER BY `iq_site` DESC ';
   $query .= 'LIMIT ' . $start . ', ' . COUNT_POS_PAGE;
   $res = @mysqli_query($db, $query) or die('Ошибка');
   if ($res) {
      $count = mysqli_num_rows($res);
      $r = @mysqli_query($db, 'SELECT FOUND_ROWS()') or die('Ошибка');
      $allcount = mysqli_fetch_row($r)[0];
      mysqli_free_result($r);
      if ($count > 0) {
         while ($p = mysqli_fetch_assoc($res)) {
            // Выводим описание
            table_site($p['url_site'], $p['title'], $p['descr']);
         }
      }
      elseif ($allcount == 0) {
         echo '<div align="center" class="bold">';
         echo 'По вашему запросу ничего не найдено</div><br>';
      }
      else {
         echo '<div align="center">';
         echo '<a href="?search=' . $t . '&amp;page=1">';
         echo 'На первую страницу результатов</a></div>';
         $allcount = 0;
      }
      if ($allcount > COUNT_POS_PAGE) {
         echo '<div align="center">';
         if (($start - COUNT_POS_PAGE) >= 0) {
            echo '<a href="?search=' . $t . '&amp;page=' . ($page-1);
            echo '">&lt;&lt; Предыдущая страница</a> ';
         }
         if (($start + COUNT_POS_PAGE) < $allcount) {
            echo '<a href="?search=' . $t . '&amp;page=' . ($page+1);
            echo '">Следующая страница &gt;&gt;</a> ';
         }
         echo '</div>';
      }
   }
   else {
      echo '<div class="err">Ошибка в запросе</div><br>';
   }
   mysqli_free_result($res);
}
if ($err_text != '') {
   echo '<div class="err">' . $err_text . "</div><br>\n";
}
table_2_end();     // Конец таблицы второго уровня
mysqli_close($db); // Закрываем соединение с базой данных
footer_user();     // Выводим нижний колонтитул
?>
