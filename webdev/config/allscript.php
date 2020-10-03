<?php
// ���� ���� ����������� �� Web-��������, �� �������
if (!defined('IN_CATALOG')) exit;
// �������� ������ PHP
if (!version_compare(PHP_VERSION, '7.0.0', '>=')) {
   $err = '<div style="font-size:18px; font-weight: bold; ';
   $err .= 'text-align:center; color:#FF0000">';
   $err .= '������ PHP ������ ���� >= 7.0.0</div>';
   exit($err);
}
// ���������, ������������ ������������ �������
// URL-����� ����� (��������, http://www.site.ru/)
define('URL_SITE', 'http://site1/');
define('WIDTH_TABLE_1', '760');   // ������ ������ 1-�� ������
define('WIDTH_TABLE_2', '600');   // ������ ������ 2-�� ������
define('WIDTH_TABLE_3', '100%');  // ������ ������ 3-�� ������

// ������ ��� ����������� � ���� ������
define('HOST_CONNECT', 'localhost'); // ������
define('LOGIN_CONNECT', 'root');     // �����
define('PASSW_CONNECT', '');         // ������
define('DB_CONNECT', 'site');        // ���� ������

// ������ ��� ����� (��������, support <user@mail.ru>)
define('MAIL_POST', 'support <user@mail.ru>');
// ��� ����� �������� ����� (��������, user@mail.ru)
define('MAIL_ADDRESS', 'user@mail.ru');

define('TIME_SESS', 3600); // ����� ����� ������ � ��������

// ���������� ��������� � �������� �����
define('COUNT_POS_PAGE_GBOOK', 10);
// ���������� ������ �� ��������
define('COUNT_POS_PAGE', 10);

function db_connect() {
   // ������������ � ���� ������
   if (@$db = mysqli_connect(HOST_CONNECT, LOGIN_CONNECT, PASSW_CONNECT,
                             DB_CONNECT)) {
      mysqli_set_charset($db, 'cp1251'); // ��������� ��������� ����������
      return $db;
   }
   else {
      $err = '<div style="font-size:18px; font-weight: bold; ';
      $err .= 'text-align:center; color:#FF0000">';
      $err .= '�� ������� ���������� ���������� � ����� ������</div>';
      exit($err);
   }
}

function header_all($t, $d, $k, $r=1, $s="") {
   // ��������� ��� ���� �������
   show_header($t, $d, $k, $r); // ������� ������� ����������
   logo();                      // ������� ������� � ������
   menu();                      // ������� ������ ���������
   table();                     // ������� �������-�����������
   search($s);                  // ������� ����� ������
   table();                     // ������� �������-�����������
   table_center_start();        // ������ ������� ��� ��������� ����������
}

function show_header($t, $d, $k, $r=1) {
   // ������� ����������
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo hsc($t); ?></title>
<meta name="description" content="<?php echo hsc($d); ?>">
<meta name="keywords" content="<?php echo hsc($k); ?>">
<meta http-equiv="content-type" content="text/html; charset=windows-1251">
<?php
   if ($r == 1) {
      echo '<meta name="robots" content="index, follow">' . "\n";
   }
   else {
      echo '<meta name="robots" content="noindex, nofollow">' . "\n";
   }
   style(); // ������� ������� ������
?>
</head>
<body>
<?php
}

function search($txt='') {
   $txt = hsc($txt);
?>
<!-- ��������� ����� ������ -->
<script type="text/javascript">
function submit_src() {
   var src = document.getElementById("search");
   if (src.value == "") {
      window.alert("���� �� ���������!");
      src.focus();
      return false;
   }
   if (src.value.length < 3) {
      window.alert("� ���� ��������� �� ����� 3 ��������");
      src.focus();
      return false;
   }
   if (src.value.length > 50) {
      window.alert("� ���� ��������� �� ����� 50 ��������");
      src.focus();
      return false;
   }
return true;
}
</script>
<table width="<?php echo WIDTH_TABLE_1; ?>" align="center" border="0" cellspacing="0" cellpadding="0">
<tr><td class="search-table">
<form action="<?php echo URL_SITE; ?>search.php" class="search_frm" onsubmit="return submit_src();">
<span class="bold">����� �� ��������: </span>
<input type="text" name="search" id="search" value="<?php echo $txt; ?>" size="70"> 
<input type="submit" value="�����">
</form>
</td></tr></table>
<!-- ��������� ����� ����� -->

<?php
}

function search_admin() {
?>
<!-- ��������� ����� ��� ������ ������ -->
<table width="<?php echo WIDTH_TABLE_1; ?>" align="center" border="0" cellspacing="0" cellpadding="0">
<tr><td height="25" class="search-table">
<form action="moder.php" class="search_frm">
<span class="bold">����� ����� �� URL: </span>
<input type="text" name="search" size="70"> 
<input type="submit" value="�����">
</form></td></tr></table>
<!-- ��������� ����� ��� ������ ����� -->
<?php
}

function style() {
?>

<!-- ������� ������ ������ -->
<style type="text/css">
   a:link { text-decoration: none; font-weight: bold; color: #000000 }
   a:visited { text-decoration: none; font-weight: bold; color: #000000 }
   a:hover { text-decoration: underline; font-weight: bold; 
             color: #000000 }
   body { font-family: "Verdana", "Tahoma", sans-serif; font-size: 11px;
          margin-top: 0; background-color: #FFFFFF }
   table {
      font-family: "Verdana", "Tahoma", sans-serif;
      font-size: 12px;
      background-color: #FFFFFF
   }
   h1 {
      font-family: "Tahoma", "Verdana", sans-serif;
      font-size: 16px;
      font-weight: bold;
      text-align: center
   }
   .err {
      font-size:12px;
      font-weight: bold;
      text-align: center;
      color: #FF0000
   }
   .ok {
      font-size:12px;
      font-weight: bold;
      text-align: center;
      color: #008000
   }
   .bold { font-weight: bold }
   .color_table { background-color: #E8EAF1 }
   .logo-table { text-align: center; vertical-align: middle }
   .menu-table {
      text-align: center;
      vertical-align: middle;
      background-color: #43568E
   }
   a.menu:link {
      text-decoration: none;
      font-weight: bold;
      color: #FFFFFF;
      font-size: 11px;
      font-family: "Verdana", "Tahoma", sans-serif
   }
   a.menu:visited {
      text-decoration: none;
      font-weight: bold;
      color: #FFFFFF;
      font-size: 11px;
      font-family: "Verdana", "Tahoma", sans-serif
   }
   a.menu:hover {
      text-decoration: underline;
      font-weight: bold;
      color: #FFFFFF;
      font-size: 11px;
      font-family: "Verdana", "Tahoma", sans-serif
   }
   .search-table {
      text-align: center;
      vertical-align: middle;
      background-color: #FFFFFF
   }
   .txt_frm {
      background-color: #FFFFFF;
      font-size: 8pt;
      color: #000000;
      font-weight: bold;
      border-bottom: 1px solid;
      border-right: 1px solid;
      border-left: 1px solid;
      border-top: 1px solid;
      font-family: "Verdana", "Tahoma", sans-serif
   }
   .textarea_frm {
      background-color: #FFFFFF;
      font-weight: bold;
      font-size: 8pt;
      color: #000000;
      border-bottom: 1px solid;
      border-right: 1px solid;
      border-left: 1px solid;
      border-top: 1px solid;
      font-family: "Verdana", "Tahoma", sans-serif
   }
   .select_frm {
      margin-top: 3px;
      background-color: #FFFFFF;
      font-family: "Verdana", "Tahoma";
      font-size: 8pt;
      font-weight: bold
   }
   .text {
      font-family: "Verdana", "Tahoma", sans-serif;
      font-size: 12px;
      color: #000000;
      vertical-align: top;
      border: 1px solid silver;
   }
   .search_frm { margin-top: 0; margin-bottom: 0 }
</style>
<!-- ������� ������ ����� -->

<?php
}

function logo() {
?>
<!-- ������� � ������ ������ -->
<table width="<?php echo WIDTH_TABLE_1; ?>" align="center" border="0" cellspacing="0" cellpadding="0">
<tr><td class="logo-table" height="70">
&nbsp;
</td></tr></table>
<!-- ������� � ������ ����� -->
<?php
}

function menu() {
?>
<!-- ������ ��������� ������ -->
<table align="center" width="<?php echo WIDTH_TABLE_1; ?>" border="0" cellpadding="0" cellspacing="0">
<tr class="menu-table"><td height="21">
<a href="<?php echo URL_SITE; ?>" class="menu">�� �������</a>
</td><td>
<a href="<?php echo URL_SITE; ?>user/add.php" class="menu">�������� ����</a></td><td>
<a href="<?php echo URL_SITE; ?>gbook.php" class="menu">�������� �����</a></td><td>
<a href="<?php echo URL_SITE; ?>contact.php" class="menu">�������� �����</a></td></tr>
</table>
<!-- ������ ��������� ����� -->
<?php
}

function menu_admin() {
?>
<!-- ������ ��������� ��� �������������� ������ -->
<table align="center" width="<?php echo WIDTH_TABLE_1; ?>" border="0" cellpadding="0" cellspacing="0">
<tr class="menu-table"><td height="21">
<a href="<?php echo URL_SITE; ?>admin/" class="menu">�� �������</a></td><td>
<a href="<?php echo URL_SITE; ?>admin/rubr.php" class="menu">����������</a></td><td>
<a href="<?php echo URL_SITE; ?>admin/gbook.php" class="menu">�������� �����</a></td><td>
<a href="<?php echo URL_SITE; ?>admin/moder.php" class="menu">����� �� ���������</a></td>
</tr></table>
<!-- ������ ��������� ��� �������������� ����� -->
<?php
}

function table() {
?>
<!-- �������-����������� ������ -->
<table width="<?php echo WIDTH_TABLE_1; ?>" align="center" border="0" cellspacing="0" cellpadding="0">
<tr><td height="5">&nbsp;</td></tr></table>
<!-- �������-����������� ����� -->
<?php
}

function table_center_start() {
?>
<!-- ������� ��� ��������� ���������� �������� ������ -->
<table width="<?php echo WIDTH_TABLE_1; ?>" align="center" border="0" cellspacing="0" cellpadding="10">
<tr><td height="300" class="text">
<?php
}

function table_center_end() {
?>
</td></tr></table>
<!-- ������� ��� ��������� ���������� �������� ����� -->
<?php
}

function table_2_start() {
?>
<!-- ������� ������� ������ ������ -->
<table align="center" width="<?php echo WIDTH_TABLE_2; ?>" bgcolor="#FFFFFF" border="0" cellpadding="2" cellspacing="0">
<tr><td valign="top">
<?php
}

function table_2_end() {
?>
</td></tr></table>
<!-- ������� ������� ������ ����� -->
<?php
}

function footer() {
?>
<!-- ������ ���������� -->
</body></html>
<?php
}

function footer_user() {
   // ������� ������ ���������� ��� �������������
   table_center_end();
   // ������� ����� ������� ��� ��������� ����������
   table();   // ������� �������-�����������
   menu();    // ������� ������ ���������
   footer (); // ������� ������ ����������
}

function footer_admin() {
   // ������� ������ ���������� ��� ��������������
   table_center_end();
   // ������� ����� ������� ��� ��������� ����������
   table();           // ������� �������-�����������
   search_admin();    // ��������� ����� ��� ������
   table();           // ������� �������-�����������
   menu_admin();      // ������� ������ ���������
   footer();          // ������� ������ ����������
}

function table_site($url_site, $title_site, $descr_site) {
// �������� �������
?>
<table width="<?php echo WIDTH_TABLE_3; ?>" align="center" border="0" cellspacing="0">
<tr><td class="color_table">
<a href="<?php echo $url_site; ?>" target="_blank"><?php echo hsc($title_site); ?></a></td></tr>
<tr><td><?php echo hsc($descr_site); ?></td></tr></table><br>
<?php
}

function table_gbook($msg_date, $author, $msg) {
// ��������� � �������� �����
?>
<table width="<?php echo WIDTH_TABLE_3; ?>" align="center" border="0" cellspacing="0">
<tr><td class="color_table">
<span class="bold"><?php echo $msg_date . ' ' . hsc($author); ?></span>
</td></tr>
<tr><td><?php echo hsc($msg); ?></td></tr></table><br>
<?php
}

function table_page_start() {
// ������� ��� ���������� ������� ������
?>
<table width="<?php echo WIDTH_TABLE_3; ?>" align="center" border="0" cellspacing="0"><tr><td>
<?php
}

function table_page_end() {
// ������� ��� ���������� ������� �����
?>
</td></tr></table><br>
<?php
}

function passw_generator($length = 8) {
   // ��������� �������
   if ($length < 1) return '';
   $arr = array(
         'a','b','c','d','e','f','g','h','i','j','k','l',
         'm','n','o','p','q','r','s','t','u','v','w','x','y','z',
         'A','B','C','D','E','F','G','H','I','J','K','L',
         'M','N','O','P','Q','R','S','T','U','V', 'W',
         'X','Y','Z','1','2','3','4','5','6','7','8','9','0');
   $password = '';
   $max = count($arr) - 1;
   for ($i = 0; $i < $length; $i++) {
      $password .= $arr[ mt_rand(0, $max) ];
   }
   return $password;
}

function hsc($str) {
   // ������ ����������� ��������
   return htmlspecialchars($str, ENT_COMPAT | ENT_HTML401, 'windows-1251');
}

