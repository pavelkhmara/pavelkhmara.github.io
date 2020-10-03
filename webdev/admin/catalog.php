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
<h1>Редактирование описания сайта</h1><br>
<script type="text/javascript">
function submit_form() {
   var urls = document.getElementById("urls");
   var titles = document.getElementById("titles");
   var descr = document.getElementById("descr");
   var rubr = document.getElementById("rubr");
   var msg = '';
   if (urls.value.length < 8 || titles.value == "" || descr.value == "") {
      window.alert("Поле не заполнено");
      return false;
   }
   if (titles.value.length > 70) {
      msg = "Длина Названия не должна превышать 70 символов";
      window.alert(msg);
      return false;
   }
   c_Reg = /^http:\/\/(www\.)?([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
   if (!c_Reg.test(urls.value)) {
      msg = "Недопустимый URL\nПравильно – ";
      msg += "\"http://www.mail.ru\"\nНеправильно – ";
      msg += "\"http://www.mail.ru/\""
      window.alert(msg);
      urls.focus();
      return false;
   }
   if (rubr.options[rubr.selectedIndex].value == 0) {
      window.alert("Рубрика не выбрана");
      return false;
   }
   return true;
}
</script>
<?php
$err_add = '';
$ok_add = '';
if (isset($_POST['go'])) { // Если форма отправлена
   if (isset($_POST['urls'])) $urls = $_POST['urls'];
   else $urls = '';
   if (isset($_POST['rubr'])) $rubr = (int)$_POST['rubr'];
   else $rubr = 0;
   if (isset($_POST['titles'])) $titles = $_POST['titles'];
   else $titles = '';
   if (isset($_POST['descr'])) $descr = $_POST['descr'];
   else $descr = '';
   if (isset($_POST['iq_site'])) $iq_site = (int)$_POST['iq_site'];
   else $iq_site = 0;
   if (isset($_POST['status_site'])) $status_site = $_POST['status_site'];
   else $status_site = '';
   if (isset($_POST['id_site'])) $id_site = (int)$_POST['id_site'];
   else $id_site = 0;
   $titles = str_replace("\n", ' ', $titles);
   $titles = str_replace("\t", ' ', $titles);
   $titles = str_replace("\r", '', $titles);
   $descr = str_replace("\n", ' ', $descr);
   $descr = str_replace("\t", ' ', $descr);
   $descr = str_replace("\r", '', $descr);
   $titles = trim($titles);
   $descr = trim($descr);
   if (!preg_match('/^[0-9]+$/s', $id_site) || $id_site==0) {
      $err_add .= 'Идентификатор имеет неправильный формат<br>';
   }
   if (!preg_match('/^[0-9]+$/s', $iq_site)) {
      $err_add .= 'Параметр iq_site имеет неправильный формат<br>';
   }
   if (strlen($titles) > 70) {
      $err_add .= 'Длина Названия ресурса не должна превышать ';
      $err_add .= '70 символов<br>';
   }
   if (strlen($titles) < 2 || strlen($descr) < 2) {
      $err_add .= 'Не заполнено обязательное поле<br>';
   }
   if (!preg_match('#^http://(www\.)?([a-z0-9-]+\.)+[a-z]{2,6}$#is', $urls) || 
      strlen($urls) > 200) {
      $err_add .= 'Недопустимый URL !<br>';
   }
   else {
      $urls = strtolower($urls);
      $mass_url = parse_url($urls);
      $host = $mass_url['host'];
      if (preg_match('/^www\./is', $host)) {
         $kol = strlen($host) - 4;
         $host = substr($host, 4, $kol);
      }
   }
   if ($status_site == 1) $status = 'y';
   elseif ($status_site == 2) $status = 'n';
   elseif ($status_site == 3) $status = 's';
   else {
      $err_add .= 'Статус сайта имеет неправильный формат<br>';
   }
   if (!preg_match('/^[0-9]+$/s', $rubr) || $rubr == 0) {
      $err_add .= 'Рубрика не выбрана или имеет неправильный формат<br>';
   }
   if ($err_add == '') {
      //Если ошибок нет
      $res = @mysqli_query($db, 
                           "SELECT * FROM `site` WHERE `id_site`=$id_site");
      if ($res && mysqli_num_rows($res) === 1) {
         // Если сайт найден изменяем описание
         $titles = mysqli_real_escape_string($db, $titles);
         $descr = mysqli_real_escape_string($db, $descr);
         $query = "UPDATE `site` SET `id_rubr`=$rubr, ";
         $query .= "`url_site`='$urls', `url_site_dop`='$host', ";
         $query .= "`title`='$titles', `descr`='$descr', ";
         $query .= "`status_site`='$status', `iq_site`=$iq_site ";
         $query .= "WHERE `id_site`=$id_site LIMIT 1";
         if (@mysqli_query($db, $query)) {
            $ok_add = 'Информация о сайте ' . $urls . ' обновлена<br><br>';
            $ok_add .= '<a href="?id=' . $id_site . '">Назад</a><br>';
         }
         else {
            $err_add .= 'Попробуйте сделать запрос через некоторое время<br>';
         }
      }
      else {
         $err_add .= 'Сайт ' . $urls . ' не найден<br>';
      }
      mysqli_free_result($res);
   }
}
if ($err_add != '') {
   echo '<div class="err">' . $err_add . '</div><br>';
}
if ($ok_add != '') {
   echo '<div class="ok">' . $ok_add . '</div><br>';
}
$err_enter = '';
if (isset($_GET['id'])) {
   // Выводим заполненную форму
   $id = (int)$_GET['id'];
   if (!preg_match('/^[0-9]+$/s', $id) || $id == 0) {
      $err_enter .= 'Недопустимые символы в параметре id<br>';
   }
   else {
      $res = @mysqli_query($db, "SELECT * FROM `site` WHERE `id_site`=$id");
      if (mysqli_num_rows($res) === 1) {
         $r = mysqli_fetch_assoc($res);
?>
<table width="500" align="center" border="0" cellspacing="0">
<tr><td class="color_table">
<a href="<?php echo $r['url_site']; ?>" target="_blank">
<?php echo hsc($r['title']); ?></a></td></tr>
<tr><td><?php echo hsc($r['descr']); ?></td></tr></table><br>
<form action="catalog.php" method="POST" onsubmit="return submit_form();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="40%">
<span class="bold">URL: </span>
</td><td>
<input type="text" name="urls" id="urls" size="40" 
value="<?php echo $r['url_site']; ?>" class="txt_frm">
<input type="hidden" name="id_site" 
value="<?php echo $r['id_site']; ?>">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">Рубрика: </span>
</td><td>
<select name="rubr" id="rubr" class="select_frm">
<option value="0">---------Выберите рубрику---------</option>
<?php
$res_rubrikator = @mysqli_query($db,
                                'SELECT * FROM `rubr` ORDER BY `name_rubr`');
while($row = mysqli_fetch_row($res_rubrikator)) {
   echo '<option value="' . $row[0];
   if ($r['id_rubr'] == $row[0]) echo '" selected>';
   else echo '">';
   echo hsc($row[1]);
   echo "</option>\n";
}
mysqli_free_result($res_rubrikator);
?>

</select>
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">Название ресурса: </span>
</td><td>
<input type="text" name="titles" id="titles" size="40" class="txt_frm" 
value="<?php echo hsc($r['title']); ?>">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">Описание ресурса: </span>
</td><td>
<textarea name="descr" id="descr" cols="15" rows="20" class="textarea_frm" style="width:300px; height:150px">
<?php echo hsc($r['descr']); ?></textarea>
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">IQ-сайта: </span>
</td><td>
<input type="text" name="iq_site" id="iq_site" size="40" class="txt_frm" 
value="<?php echo $r['iq_site']; ?>">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">Статус сайта: </span>
</td><td>
<input type="radio" name="status_site" 
value="1"<?php echo ($r['status_site'] == 'y')?' checked':''; ?>>
<span class="bold">Активен</span><br>
<input type="radio" name="status_site" 
value="2"<?php echo ($r['status_site'] == 'n')?' checked':''; ?>>
<span class="bold">На модерации</span><br>
<input type="radio" name="status_site" 
value="3"<?php echo ($r['status_site'] == 's')?' checked':''; ?>>
<span class="bold">Временно не работает</span>
</td></tr>
<tr><td align="right" width="40%">&nbsp;</td><td>
<input type="submit" name="go" value="Изменить" class="txt_frm">
</td></tr>
</table>
</form><br>
<?php
      }
      else $err_enter .= 'Сайт не найден<br>';
      mysqli_free_result($res);
   }
}
if ($err_enter != '') {
   echo '<div class="err">' . $err_enter . '</div><br>';
}
// Закрываем соединение с базой данных
mysqli_close($db);
footer_admin(); // Выводим нижний колонтитул
?>
