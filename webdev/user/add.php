<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// ������������ � ���� ������
$db = db_connect();
// �������� ������
session_start();
if (isset($_SESSION['sess_login']) && isset($_SESSION['sess_pass'])) {
   if (isset($_SESSION['sess_data'])) {
      $d = date('U');
      $d2 = $d - $_SESSION['sess_data'];
      if ($d2 > TIME_SESS) { // ���� ��������� ������������ ����� ������
         unset($_SESSION['sess_login']);
         unset($_SESSION['sess_pass']);
         unset($_SESSION['sess_data']);
         session_destroy();
         header('Location: ' . URL_SITE  . 'user/index.php');
         exit();
      }
      else {
         $_SESSION['sess_data'] = $d;
      }
   }
   else {
      header('Location: ' . URL_SITE  . 'user/index.php');
      exit();
   }
   $sess_login = $_SESSION['sess_login'];
   $sess_pass = $_SESSION['sess_pass'];
   $sess_login = mysqli_real_escape_string($db, $sess_login);
   $sess_pass = mysqli_real_escape_string($db, $sess_pass);
   $query_enter = 'SELECT `id_user` FROM `user` ';
   $query_enter .= "WHERE `email`='$sess_login' AND `passw`='$sess_pass'";
   $res_enter = @mysqli_query($db, $query_enter) or die('������ ' .
                                                        mysqli_errno($db));
   if (mysqli_num_rows($res_enter) === 1) {
      $row = mysqli_fetch_assoc($res_enter);
      $id_user = $row['id_user'];
   }
   else {
      header('Location: ' . URL_SITE  . 'user/index.php');
      exit();
   }
   mysqli_free_result($res_enter);
}
else {
   header('Location: ' . URL_SITE  . 'user/index.php');
   exit();
}

// ���������
$title = '�������� ����';
// �������� ��������
$description = '';
// �������� ����� ��� ��������� �����
$keywords = '';
// ������� ������� ����������
header_all($title, $description, $keywords, 2);
?>
<h1>������ �������</h1><br>
<script type="text/javascript">
function submit_form() {
   var urls = document.getElementById("urls");
   var titles = document.getElementById("titles");
   var descr = document.getElementById("descr");
   var rubr = document.getElementById("rubr");
   var msg = '';
   if (urls.value.length < 8 || titles.value == "" || descr.value == "") {
      window.alert("���� �� ���������");
      return false;
   }
   if (titles.value.length > 70 || descr.value.length > 500) {
      msg = "����� �������� ������� �� ������ ��������� ";
      msg += "70 ��������, � �������� - 500";
      window.alert(msg);
      return false;
   }
   var c_Reg = /^http:\/\/(www\.)?([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
   if (!c_Reg.test(urls.value)) {
      msg = "������������ URL\n��������� - \"http://www.mail.ru\"\n";
      msg += "����������� - \"http://www.mail.ru/\"";
      window.alert(msg);
      urls.focus();
      return false;
   }
   if (rubr.options[rubr.selectedIndex].value == 0) {
      window.alert("������� �� �������");
      return false;
   }
   return true;
}
</script>
<?php
$urls = '';
$rubr = '';
$titles = '';
$descr = '';
$err_add = '';
$ok_add = '';
if (isset($_POST['go'])) { // ���� ����� ����������
   if (isset($_POST['urls'])) $urls = $_POST['urls'];
   else $urls = '';
   if (isset($_POST['rubr'])) $rubr = (int)$_POST['rubr'];
   else $rubr = 0;
   if (isset($_POST['titles'])) $titles = $_POST['titles'];
   else $titles = '';
   if (isset($_POST['descr'])) $descr = $_POST['descr'];
   else $descr = '';
   $titles = str_replace("\n", ' ', $titles);
   $titles = str_replace("\t", ' ', $titles);
   $titles = str_replace("\r", '', $titles);
   $descr = str_replace("\n", ' ', $descr);
   $descr = str_replace("\t", ' ', $descr);
   $descr = str_replace("\r", '', $descr);
   $titles = trim($titles);
   $descr = trim($descr);
   if (strlen($titles) > 70 || strlen($descr) > 500) {
      $err_add .= '����� �������� ������� �� ������ ��������� ';
      $err_add .= '70 ��������, � �������� - 500<br>';
   }
   if (preg_match('/[a-z�-��0-9,."\']{26}/is', $titles)) {
      $err_add .= '���� �������� ������� �������� ����� 25 ';
      $err_add .= '�������� ��� �������<br>';
   }
   if (preg_match('/[a-z�-��0-9,."\']{26}/is', $descr)) {
      $err_add .= '���� �������� �������� ����� 25 �������� ';
      $err_add .= '��� �������<br>';
   }
   if (strlen($titles) < 2 || strlen($descr) < 2) {
      $err_add .= '�� ��������� ������������ ����<br>';
   }
   if (!preg_match('#^http://(www\.)?([a-z0-9-]+\.)+[a-z]{2,6}$#is', $urls) || 
      strlen($urls) > 200) {
      $err_add .= '������������ URL<br>';
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
   if (!preg_match('/^[0-9]+$/s', $rubr) || $rubr == 0) {
      $err_add .= '������� �� ������� ��� ����� ������������ ������<br>';
   }
   if ($err_add == '') {
      //���� ������ ��� ������� �������� ����
      $q = "SELECT `id_site` FROM `site` WHERE `url_site_dop`='$host'";
      $res_add = @mysqli_query($db, $q) or die('������ ' .
                                                mysqli_errno($db));
      if (mysqli_num_rows($res_add) === 0) {
         // ���� ���� �� ��������������� ����� ��������� ����
         $titles = mysqli_real_escape_string($db, $titles);
         $descr = mysqli_real_escape_string($db, $descr);
         $q = "INSERT INTO `site` VALUES (NULL, '$rubr', '$id_user', ";
         $q .= "'$urls', '$host', '$titles', '$descr', 'n', 0, CURDATE())";
         if (@mysqli_query($db, $q)) {
            $ok_add = "���� $urls �������� � ���� ������<br>";
            $urls = 'http://';
            $rubr = 0;
            $titles = $descr = '';
         }
         else {
            $err_add .= '���������� ������� ������ ����� ��������� �����<br>';
         }
      }
      else {
         $err_add .= "���� $urls ��� ��������������� �����<br>";
         $urls = 'http://';
         $rubr = 0;
         $titles = $descr = '';
      }
      mysqli_free_result($res_add);
   }
}
else {
   $urls = 'http://';
}
if ($err_add != '') {
   echo '<div class="err">' . $err_add . "</div><br>\n";
   $titles = stripslashes($titles);
   $descr = stripslashes($descr);
   $titles = hsc($titles);
   $descr = hsc($descr);
}
if ($ok_add != '') {
   echo '<div class="ok">' . $ok_add . "</div><br>\n";
}

?>
<form action="add.php" method="POST" name="frm" id="frm" onsubmit="return submit_form();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="40%">
<span class="bold">URL: </span>
</td><td>
<input type="text" name="urls" id="urls" size="40" 
value="<?php echo $urls; ?>" class="txt_frm">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">�������: </span>
</td><td>
<select name="rubr" id="rubr" class="select_frm">
<option value="0">---------�������� �������---------</option>
<?php
$res = @mysqli_query($db, 'SELECT * FROM `rubr` ORDER BY `name_rubr`');
while($row = mysqli_fetch_row($res)) {
   echo '<option value="' .$row[0];
   if ($rubr == $row[0]) echo '" selected>';
   else echo '">';
   echo hsc($row[1]);
   echo "</option>\n";
}
mysqli_free_result($res);
?>
</select>
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">�������� �������: </span>
</td><td>
<input type="text" name="titles" id="titles" size="40" maxlength="70" 
class="txt_frm" value="<?php echo $titles; ?>">
</td></tr>
<tr><td align="right" width="40%">
<span class="bold">�������� �������:<BR> (�� 500 ��������)</span>
</td><td>
<textarea name="descr" id="descr" cols="15" rows="20" class="textarea_frm" 
style="width: 300px; height: 150px"><?php echo $descr; ?></textarea>
</td></tr>
<tr><td align="right" width="40%">&nbsp;</td><td>
<input type="submit" name="go" value="�������� ����" class="txt_frm">
</td></tr>
</table>
</form><br>
<div align="center"><a href="exit.php">����� �� �������</a></div>
<?php
// ��������� ���������� � ����� ������
mysqli_close($db);
footer_user(); // ������� ������ ����������
?>
