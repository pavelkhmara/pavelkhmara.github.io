<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// Заголовок
$title = 'Форма обратной связи';
// Описание страницы
$description = '';
// Ключевые слова для поисковых машин
$keywords = '';
// Выводим верхний колонтитул
header_all($title, $description, $keywords, 2);
?>
<script type="text/javascript">
function test_mail(email) {
   var p = /^[a-z0-9_\.\-]+@([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
   if (!p.test(email)) return false;
   return true;
}
function submit_form() {
   var author = document.getElementById("author");
   var msg = document.getElementById("msg");
   var tema = document.getElementById("tema");
   var emails = document.getElementById("emails");
   if (author.value == "" || msg.value == "" || 
      tema.value == "") {
      window.alert("Не заполнено обязательное поле");
      return false;
   }
   if (author.value.length > 20) {
      window.alert("В поле Имя допустимо не более 20 символов");
      author.focus();
      return false;
   }
   if (tema.value.length > 50) {
      window.alert("В поле Тема допустимо не более 50 символов");
      tema.focus();
      return false;
   }
   if (emails.value.length > 50 || !test_mail(emails.value)) {
      window.alert("Недопустимое значение поля E-mail");
      emails.focus();
      return false;
   }
   return true;
}
</script>
<?php
table_2_start(); // Выводим таблицу второго уровня
echo "<h1>Обратная связь</h1><br>\n";
$emails = '';
$author = '';
$tema = '';
$msg = '';
$err_msg = '';
$ok_add = '';
if (isset($_POST['go'])) {
   // Форма отправлена
   if (isset($_POST['emails'])) $emails = $_POST['emails'];
   else $emails = '';
   if (isset($_POST['author'])) $author = $_POST['author'];
   else $author = '';	
   if (isset($_POST['tema'])) $tema = $_POST['tema'];
   else $tema = '';
   if (isset($_POST['msg'])) $msg = $_POST['msg'];
   else $msg = '';
   // Удаляем тэги
   $author = strip_tags($author);
   $tema = strip_tags($tema);
   // Удаляем пробельные символы
   $author = trim($author);
   $tema = trim($tema);
   $msg = trim($msg);
   $author = str_replace('"', ' ', $author);
   $tema = str_replace('"', ' ', $tema);
   $author = str_replace("'", ' ', $author);
   $tema = str_replace("'", ' ', $tema);
   $pattern = '/^[a-z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/is';
   if (!preg_match($pattern, $emails)) {
      $err_msg .= 'Недопустимый адрес E-mail !!!<br>';
   }
   if (strlen($emails) > 50) {
      $err_msg .= 'Длина E-mail больше допустимой!!!<br>';
   }
   if (strlen($author) > 20 || strlen($author) == 0) {
      $err_msg .= 'Поле имя не заполнено или длина более ';
      $err_msg .= '20 символов!<br>';
   }
   if (strlen($tema) > 50 || strlen($tema) == 0) {
      $err_msg .= 'Поле тема не заполнено или длина более 50 ';
      $err_msg .= 'символов!<br>';
   }
   if (strlen($msg) == 0) {
      $err_msg .= 'Поле Сообщение не заполнено!<br>';
   }
   if ($err_msg == '') {
      // Если ошибок нет отправляем письмо
      $author = "=?windows-1251?B?" . base64_encode($author) . "?=";
      $tema = "=?windows-1251?B?" . base64_encode($tema) . "?=";
      $header = "Content-Type: text/plain; charset=windows-1251\n";
      $header .= "From: $author <$emails>";
      @mail(MAIL_ADDRESS, $tema, $msg, $header);
      $author = $emails = $tema = $msg = '';
      $ok_add = 'Ваше сообщение успешно отправлено<br>';
   }
}
if ($err_msg != '') {
   // Если возникли ошибки, то выводим их описание
   $author = hsc($author);
   $tema = hsc($tema);
   $msg = hsc($msg);
   echo '<div class="err">' . $err_msg . '</div><br>';
}
if ($ok_add != '') {
   echo '<div class="ok">' . $ok_add . '</div><br>';
}
?>
<form action="contact.php" method="POST" onsubmit="return submit_form();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="40%">
<span class="bold">Имя: </span>
</td><td>
<input type="text" name="author" id="author" size="40" maxlength="20" class="txt_frm" value="<?php echo $author; ?>">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">E-mail: </span>
</td><td>
<input type="text" name="emails" id="emails" maxlength="50" size="40" value="<?php echo $emails; ?>" class="txt_frm">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">Тема: </span>
</td><td>
<input type="text" name="tema" id="tema" size="40" maxlength="50" class="txt_frm" value="<?php echo $tema; ?>">
</td></tr>
<tr><td align="right" width="40%"><span class="bold">Сообщение:</span>
</td><td>
<textarea name="msg" id="msg" cols="15" rows="20" class="textarea_frm" 
style="width: 300px; height: 150px"><?php echo $msg; ?></textarea>
</td></tr>
<tr><td align="right" width="40%">&nbsp;</td><td>
<input type="submit" name="go" value="Отправить" class="txt_frm">
</td></tr>
</table>
</form><br>
<?php
table_2_end(); // Конец таблицы второго уровня
footer_user(); // Выводим нижний колонтитул
?>
