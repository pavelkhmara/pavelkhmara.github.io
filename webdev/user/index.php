<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// ������������ � ���� ������
$db = db_connect();
// ���������
$title = '���� � ������ �������';
// �������� ��������
$description = '';
// �������� ����� ��� ��������� �����
$keywords = '';

// ���� � ������ �������
$err_enter = '';
if (isset($_POST['enter'])) {
   if (isset($_POST['login'])) $login = $_POST['login'];
   else $login = '';
   if (isset($_POST['passw'])) $passw = $_POST['passw'];
   else $passw = '';
   $login = trim($login);
   $passw = trim($passw);
   $pattern = '/^[a-z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/is';
   if (!preg_match($pattern, $login)) {
      $err_enter = '������������ ����� E-mail<br>';
   }
   if (strlen($login) > 50) {
      $err_enter .= '����� E-mail ������ ����������<br>';
   }
   if (strlen($passw) > 16 || strlen($passw) < 6) {
      $err_enter .= '����� ������ ������ ���� � ';
      $err_enter .= '�������� �� 6 �� 16 ��������<br>';
   }
   if (!preg_match('/^[a-z0-9]{6,16}$/is', $passw)) {
      $err_enter .= '� ������ ��������� ������ ����� ';
      $err_enter .= 'A-Z (a-z) ��� ����� 0-9<br>';
   }
   if ($err_enter == '') {
      $passw = md5($passw);
      $query_enter = 'SELECT * FROM `user` ';
      $query_enter .= "WHERE `email`='$login' AND `passw`='$passw'";
      $res_enter = @mysqli_query($db, $query_enter) or die('������ ' .
                                                    mysqli_errno($db));
      if (mysqli_num_rows($res_enter) === 1) {
         session_start();
         $_SESSION['sess_login'] = $login;
         $_SESSION['sess_pass'] = $passw;
         $_SESSION['sess_data'] = date('U');
         mysqli_free_result($res_enter);
         mysqli_close($db);
         header('Location: ' . URL_SITE  . 'user/add.php');
         exit();
      }
      else {
         $err_enter .= '�����/������ �� ������<br>';
      }
      mysqli_free_result($res_enter);
   }
}

// ����������� ������ ������������
$emails_tmp = '';
$err_add = '';
if (isset($_POST['add'])) {
   if (isset($_POST['emails'])) $emails = $_POST['emails'];
   else $emails = '';
   if (isset($_POST['pass'])) $pass = $_POST['pass'];
   else $pass = '';
   $pattern = '/^[a-z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/is';
   if (!preg_match($pattern, $emails)) {
      $err_add = '������������ ����� E-mail<br>';
   }
   if (strlen($emails) > 50) {
      $err_add .= '����� E-mail ������ ����������<br>';
   }
   if (strlen($pass) > 16 || strlen($pass) < 6) {
      $err_add .= '����� ������ ������ ���� � �������� ';
      $err_add .= '�� 6 �� 16 ��������<br>';
   }
   if (!preg_match('/^[a-z0-9]{6,16}$/is', $pass)) {
      $err_add .= '� ������ ��������� ������ ����� ';
      $err_add .= 'A-Z (a-z) ��� ����� 0-9<br>';
   }
   if ($err_add == '') {
      $q = "SELECT * FROM `user` WHERE `email`='$emails'";
      $res_add = @mysqli_query($db, $q) or die('������ ' .
                                                mysqli_errno($db));
      if (mysqli_num_rows($res_add) === 0) {
         $pass = md5($pass);
         $pass = mysqli_real_escape_string($db, $pass);
         $query_add = 'INSERT INTO `user` VALUES ';
         $query_add .= "(NULL, '$emails', '$pass', 'y')";
         if (@mysqli_query($db, $query_add)) {
            session_start();
            $_SESSION['sess_login'] = $emails;
            $_SESSION['sess_pass'] = $pass;
            $_SESSION["sess_data"] = date('U');
            mysqli_free_result($res_add);
            mysqli_close($db);
            header('Location: ' . URL_SITE  . 'user/add.php');
            exit();
         }
         else {
            $err_add .= '���������� ������� ������ ����� ��������� �����<br>';
         }
      }
      else {
         $err_add .= "E-mail $emails ��� ��������������� �����!!!<br>";
         $err_add .= '������������ ������ ����� �������� ����� ����<br>';
      }
      mysqli_free_result($res_add);
   }
   else $emails_tmp = hsc($emails);
}

// ����������������� ������
$err_mail = '';
$msg = '';
if (isset($_POST['mail_pass'])) {
   if (isset($_POST['mails'])) $mails = $_POST['mails'];
   else $mails = '';
   $mails = trim($mails);
   $pattern = '/^[a-z0-9_.-]+@([a-z0-9-]+\.)+[a-z]{2,6}$/is';
   if (!preg_match($pattern, $mails)) {
      $err_mail = '������������ ����� E-mail<br>';
   }
   if (strlen($mails) > 50) {
      $err_mail .= '����� E-mail ������ ����������<br>';
   }
   if ($err_mail == '') {
      $q = "SELECT `id_user` FROM `user` WHERE `email`='$mails'";
      $res_mail = @mysqli_query($db, $q) or die('������ ' .
                                                 mysqli_errno($db));
      if (mysqli_num_rows($res_mail) === 1) {
         $row = mysqli_fetch_assoc($res_mail);
         $id_user = $row['id_user'];
         // ���������� ����� ������
         $test_passw = passw_generator();
         $pass2 = md5($test_passw);
         $query = "UPDATE `user` SET `passw`='$pass2' ";
         $query .= "WHERE `id_user`='$id_user' LIMIT 1";
         @mysqli_query($db, $query) or die('������ ' . mysqli_errno($db));
         $msg = "������ ����!\n\n";
         $msg .= "������ ��� ������� $test_passw \n\n";
         $msg .= URL_SITE . "\n";
         $header = "Content-Type: text/plain; charset=windows-1251\r\n";
         $header .= 'From: ' . MAIL_POST;
         $tema = '��������������� ������';
         @mail($mails, $tema, $msg, $header);
         $msg = "������ ��������� �� E-mail $mails";
      }
      else {
         $err_mail .= 'E-mail �� ������<br>';
      }
      mysqli_free_result($res_mail);
   }
}

// ������� ������� ����������
header_all($title, $description, $keywords, 2);
?>
<script type="text/javascript">
function test_mail(email) {
   var c_Reg = /^[a-z0-9_\.\-]+@([a-z0-9\-]+\.)+[a-z]{2,6}$/i;
   if (!c_Reg.test(email)) return false;
   return true;
}
function test_passw(passw) {
   var c_Reg = /^[a-z0-9]{6,16}$/i;
   if (!c_Reg.test(passw)) return false;
   return true;
}
function submit_enter() {
   var login = document.getElementById("login");
   var passw = document.getElementById("passw");
   if (login.value == "" || passw.value == "") {
      window.alert("�� ��������� ������������ ����");
      login.focus();
      return false;
   }
   if (login.value.length > 50 || !test_mail(login.value)) {
      window.alert("������������ �������� ���� E-mail");
      login.focus();
      return false;
   }
   if (!test_passw(passw.value)) {
      var msg = "������������ �������� ���� ������\n";
      msg += "��������� �� 6 �� 16 ��������\n";
      msg += "������� ����� ������������ ������";
      window.alert(msg);
      passw.focus();
      return false;
   }
   return true;
}
function submit_add() {
   var emails = document.getElementById("emails");
   var pass = document.getElementById("pass");
   if (emails.value == "" || pass.value == "") {
      window.alert("�� ��������� ������������ ����");
      emails.focus();
      return false;
   }
   if (emails.value.length > 50 || !test_mail(emails.value)) {
      window.alert("������������ �������� ���� E-mail");
      emails.focus();
      return false;
   }
   if (!test_passw(pass.value)) {
      var msg = "������������ �������� ���� ������\n";
      msg += "��������� �� 6 �� 16 ��������\n";
      msg += "������� ����� ������������ ������";
      window.alert(msg);
      pass.focus();
      return false;
   }
   return true;
}
function submit_mail() {
   var mails = document.getElementById("mails");
   if (mails.value == "") {
      window.alert("���� �� ���������");
      mails.focus();
      return false;
   }
   if (mails.value.length > 50 || !test_mail(mails.value)) {
      window.alert("������������ �������� ���� E-mail");
      mails.focus();
      return false;
   }
   return true;
}
</script>
<?php
table_2_start(); // ������� ������� ������� ������
echo '<h1>������ �������</h1><br>' . "\n";
if ($err_enter != '') {
   echo '<div class="err">' . $err_enter . "</div><br>\n";
}
?>
<form action="index.php" method="POST" onsubmit="return submit_enter();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="50%">
<span class="bold">E-mail: </span></td><td>
<input type="text" name="login" id="login" size="23" class="txt_frm">
</td></tr><tr><td align="right" width="50%">
<span class="bold">������: </span></td><td>
<input type="password" name="passw" id="passw" size="23" class="txt_frm">
</td></tr><tr><td align="right" width="50%">&nbsp;</td><td>
<input type="submit" name="enter" value="����" class="txt_frm">
</td></tr>
</table></form><br>
<?php
if ($err_add != '') {
   echo '<div class="err">' . $err_add . "</div><br>\n";
}
?>
<form action="index.php" method="POST" onsubmit="return submit_add();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="50%">
<span class="bold">E-mail: </span></td><td>
<input type="text" name="emails" id="emails" size="23" class="txt_frm" value ="<?php echo $emails_tmp; ?>">
</td></tr><tr><td align="right" width="50%">
<span class="bold">������: </span></td><td>
<input type="password" name="pass" id="pass" size="23" class="txt_frm">
</td></tr>
<tr><td align="right" width="50%">&nbsp;</td><td>
<input type="submit" name="add" value="�����������" class="txt_frm">
</td></tr>
</table></form><br>
<?php
if ($err_mail != '') {
   echo '<div class="err">' . $err_mail . "</div><br>\n";
}
if ($msg != '') {
   echo '<div class="ok">' . $msg . "</div><br>\n";
}
?>
<form action="index.php" method="POST" onsubmit="return submit_mail();">
<table width="100%" border="0" cellpadding="1" align="center">
<tr><td align="right" width="50%">
<span class="bold">E-mail: </span></td><td>
<input type="text" name="mails" id="mails" size="23" class="txt_frm">
</td></tr><tr><td align="right" width="50%">&nbsp;</td><td>
<input type="submit" name="mail_pass" value="������������ ������" class="txt_frm">
</td></tr>
</table></form><br>
<?php
// ��������� ���������� � ����� ������
mysqli_close($db);
table_2_end(); // ����� ������� ������� ������
footer_user(); // ������� ������ ����������
?>
