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

// Добавление новой рубрики
$err_rubr = '';
$ok_rubr = '';
if (isset($_POST['rubr_add'])) {
   if (isset($_POST['rubrika'])) $rubrika = $_POST['rubrika'];
   else $rubrika = '';
   if (strlen($rubrika) > 150) {
      $err_rubr .= 'Длина Рубрики больше допустимой<br>';
   }
   if (strlen($rubrika) == 0) {
      $err_rubr .= 'Поле не заполнено<br>';
   }
   if ($err_rubr == '') {
      $rubrika = mysqli_real_escape_string($db, $rubrika);
      $q = "SELECT * FROM `rubr` WHERE `name_rubr`='$rubrika'";
      $res_rubr = @mysqli_query($db, $q);
      if (mysqli_num_rows($res_rubr) === 0) {
         $query_rubr = "INSERT INTO `rubr` VALUES (NULL, '$rubrika')";
         if (@mysqli_query($db, $query_rubr)) {
            $ok_rubr = 'Рубрика успешно добавлена<br>';
         }
         else {
            $err_rubr .= 'Попробуйте сделать запрос через некоторое время<br>';
         }
      }
      else {
         $err_rubr .= 'Рубрика уже зарегистрирована ранее<br>';
      }
      mysqli_free_result($res_rubr);
   }
}

// Удаление рубрики
$ok_rubrikator = '';
$err_rubrikator = '';
if (isset($_POST['del_rubr'])) {
   if (isset($_POST['rubrikator'])) $rubrikator = (int)$_POST['rubrikator'];
   else $rubrikator = 0;
   if (!preg_match('/^[0-9]+$/s', $rubrikator) || $rubrikator == 0) {
      $err_rubrikator = 'Недопустимый формат номера рубрики!!!<br>';
   }
   if ($err_rubrikator == '') {
      $q = "SELECT * FROM `site` WHERE `id_rubr`=$rubrikator";
      $res_rubrikator = @mysqli_query($db, $q);
      if (mysqli_num_rows($res_rubrikator) === 0) {
         $query_rubrikator = 'DELETE FROM `rubr` WHERE ';
         $query_rubrikator .= "`id_rubr`=$rubrikator LIMIT 1";
         if (@mysqli_query($db, $query_rubrikator)) {
            $ok_rubrikator = 'Рубрика успешно удалена<br>';
         }
         else {
            $err_rubrikator .= 'Попробуйте сделать запрос через некоторое время<br>';
         }
      }
      else {
         $err_rubrikator .= 'Нельзя удалить рубрику, т.к. есть сайты в указанной рубрике<br>';
      }
      mysqli_free_result($res_rubrikator);
   }
}

// Изменение названия рубрики
if (isset($_POST['change'])) {
   if (isset($_POST['rubrikator'])) $rubrikator = (int)$_POST['rubrikator'];
   else $rubrikator = 0;
   if (isset($_POST['change_rubr'])) $change_rubr = $_POST['change_rubr'];
   else $change_rubr = '';
   if (!preg_match('/^[0-9]+$/s', $rubrikator) || $rubrikator == 0) {
      $err_rubrikator = 'Недопустимый формат номера рубрики<br>';
   }
   if (strlen($change_rubr) > 150) {
      $err_rubrikator .= 'Длина Рубрики больше допустимой!!!<br>';
   }
   if (strlen($change_rubr) == 0) {
      $err_rubrikator .= 'Поле не заполнено!!<br>';
   }
   if ($err_rubrikator == '') {
      $change_rubr = mysqli_real_escape_string($db, $change_rubr);
      $q = "SELECT * FROM `rubr` WHERE `name_rubr`='$change_rubr'";
      $res_change_rubr = @mysqli_query($db, $q);
      if (mysqli_num_rows($res_change_rubr) === 0) {
         $q = "SELECT * FROM `rubr` WHERE `id_rubr`=$rubrikator";
         $res_change_r = @mysqli_query($db, $q);
         if (mysqli_num_rows($res_change_r) === 1) {
            $query_change_rubr = 'UPDATE `rubr` SET ';
            $query_change_rubr .= "`name_rubr`='$change_rubr' ";
            $query_change_rubr .= "WHERE `id_rubr`=$rubrikator LIMIT 1";
            if (@mysqli_query($db, $query_change_rubr)) {
               $ok_rubrikator = 'Рубрика успешно переименована<br>';
            }
            else {
               $err_rubrikator .= 'Попробуйте сделать запрос через некоторое время<br>';
            }
         }
         else {
            $err_rubrikator .= 'Рубрика не найдена<br>';
         }
         mysqli_free_result($res_change_r);
      }
      else {
         $err_rubrikator .= 'Рубрика с таким названием уже существует<br>';
      }
      mysqli_free_result($res_change_rubr);
   }
}
?>
<script type="text/javascript">
function submit_rubr() {
   var rubrika = document.getElementById("rubrika");
   if (rubrika.value == "") {
      window.alert("Поле не заполнено");
      rubrika.focus();
      return false;
   }
   if (rubrika.value.length > 150) {
      window.alert("Недопустимое значение поля Рубрика");
      rubrika.focus();
      return false;
   }
   return true;
}

function change_value() {
   var ch = document.getElementById("change_rubr");
   var rubr = document.getElementById("rubrikator");
   ch.value = rubr.options[rubr.selectedIndex].text;
}
function test_change_rubr(e) {
   e = e || window.event;
   var ch = document.getElementById("change_rubr");
   var rubr = document.getElementById("rubrikator");
   if (ch.value == "") {
      window.alert("Поле не заполнено");
      ch.focus();
      if (e.preventDefault) e.preventDefault();
      else e.returnValue = false;
   }
   else {
      var msg= "Вы действительно хотите переименовать\nрубрику \"";
      msg += rubr.options[rubr.selectedIndex].text;
      msg += "\" в \"" + ch.value + "\"?";
      if (window.confirm(msg)) {}
      else {
         if (e.preventDefault) e.preventDefault();
         else e.returnValue = false;
      }
   }
}
function test_del_rubr(e) {
   e = e || window.event;
   var rubr = document.getElementById("rubrikator");
   var msg= "Вы действительно хотите УДАЛИТЬ\nрубрику \"";
   msg += rubr.options[rubr.selectedIndex].text;
   msg += "\"?";
   if (window.confirm(msg)) {}
   else {
      if (e.preventDefault) e.preventDefault();
      else e.returnValue = false;
   }
}
</script>
<h1>Рубрикатор</h1><br><br>
<?php
if ($err_rubr != '') {
   echo '<div class="err">' . $err_rubr . '</div><br>';
}
if ($ok_rubr != '') {
   echo '<div class="ok">' . $ok_rubr . '</div><br>';
}
?>
<form action="rubr.php" method="POST" onsubmit="return submit_rubr();">
<table width="450" border="0" cellpadding="1" align="center">
<tr><td align="right" width="50%">
<span class="bold">Рубрика: </span></td><td>
<input type="text" name="rubrika" id="rubrika" maxlength="50" size="23" class="txt_frm">
</td></tr><tr><td align="right" width="50%">&nbsp;</td><td>
<input type="submit" name="rubr_add" value="Добавить рубрику" class="txt_frm">
</td></tr>
</table>
</form><br>
<?php
if ($err_rubrikator != '') {
   echo '<div class="err">' . $err_rubrikator . '</div><br>';
}
if ($ok_rubrikator != '') {
   echo '<div class="ok">' . $ok_rubrikator . '</div><br>';
}
?>
<form action="rubr.php" method="POST">
<table width="450" border="0" cellpadding="1" align="center">
<tr><td align="right" width="50%">
<span class="bold">Рубрика: </span></td><td>
<select name="rubrikator" id="rubrikator" class="select_frm" style="width: 154px" onchange="change_value();">
<?php
$res_rubrikator = @mysqli_query($db, 
                          'SELECT * FROM `rubr` ORDER BY `name_rubr`');
while($row = mysqli_fetch_row($res_rubrikator)) {
   echo '<option value="' .$row[0] . '">';
   echo hsc($row[1]);
   echo "</option>\n";
}
mysqli_free_result($res_rubrikator);
?>
</select>
</td></tr><tr><td align="right" width="50%">
<span class="bold">Изменить на: </span></td><td>
<input type="text" name="change_rubr" id="change_rubr" maxlength="50" size="23" class="txt_frm">
</td></tr><tr><td align="right" width="50%">
<input type="submit" name="del_rubr" value="Удалить рубрику" class="txt_frm" style="background-color: #FF7F50" onclick="test_del_rubr(event);">
</td><td>
<input type="submit" name="change" value="Изменить название" class="txt_frm" onclick="test_change_rubr(event);">
</td></tr>
</table>
</form><br>
<?php
// Закрываем соединение с базой данных
mysqli_close($db);
footer_admin(); // Выводим нижний колонтитул
?>
