<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// ������������ � ���� ������
$db = db_connect();
$author = '';
$msg = '';
$err_msg = '';
if (isset($_POST['go'])) {
   // ����� ����������
   if (isset($_POST['author'])) $author = $_POST['author'];
   else $author = '';
   if (isset($_POST['msg'])) $msg = $_POST['msg'];
   else $msg = '';
   // ������� ���������� �������
   $author = trim($author);
   $msg = trim($msg);
   $msg = str_replace("\r", '', $msg);
   if (strlen($author) > 50 || strlen($author) == 0) {
      $err_msg .= '���� ��� �� ��������� ��� ����� ����� ';
      $err_msg .= '50 ��������!<br>';
   }
   if (strlen($msg) > 2000 || strlen($msg) == 0) {
      $err_msg .= '���� ��������� �� ��������� ��� ����� ';
      $err_msg .= '����� 2000 ��������<br>';
   }
   if (preg_match('/[a-z�-��0-9,."\']{26}/is', $msg)) {
      $err_msg .= '���� ��������� �������� ����� 25 ';
      $err_msg .= '�������� ��� �������<br>';
   }
   if ($err_msg == '') {
      // ���� ������ ���
      $author = mysqli_real_escape_string($db, $author);
      $msg = mysqli_real_escape_string($db, $msg);
      $query = 'INSERT INTO `gbook` VALUES ';
      $query .= "(NULL, '$author', '$msg', NOW(), 'n')";
      // ��������� �� ������
      $query2 = 'SELECT * FROM `gbook` ORDER BY `id_msg` DESC LIMIT 0,1';
      if ($result = @mysqli_query($db, $query2)) {
         if (mysqli_num_rows($result) === 0) {
            // ��������� ������ ���������
            @mysqli_query($db, $query);
            // ������ ������������
            header('Location: ' . URL_SITE  . 'gbook.php');
            exit();
         }
         else {
            $r = mysqli_fetch_assoc($result);
            if ($r['author'] === stripslashes($author) && 
               $r['msg'] === stripslashes($msg)) {
               $err_msg = '�� ��������� ������ �������� ���������<br>';
               $author = $msg = '';
            }
            else {
               // ��������� ����� ���������
               @mysqli_query($db, $query);
               // ������ ������������
               header('Location: ' . URL_SITE . 'gbook.php');
               exit();
            }
         }
         mysqli_free_result($result);
      }
   }
}
// ��������� �����������
header('Expires: Wed, 18 Oct 2017 23:17:32 GMT');
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
// ���������
$title = '�������� �����';
// �������� ��������
$description = '';
// �������� ����� ��� ��������� �����
$keywords = '';
// ������� ������� ����������
header_all($title, $description, $keywords, 2);
?>
<script type="text/javascript">
function submit_form() {
   var author = document.getElementById("author");
   var msg = document.getElementById("msg");
   if (author.value == "" || msg.value == "") {
      window.alert("�� ��������� ������������ ����");
      return false;
   }
   if (author.value.length > 50) {
      window.alert("� ���� ��� ��������� �� ����� 50 ��������");
      author.focus();
      return false;
   }
   if (msg.value.length > 2000) {
      window.alert("� ���� ��������� ��������� �� ����� 2000 ��������");
      msg.focus();
      return false;
   }
   return true;
}
function showForm() {
   var frm = document.getElementById("frm");
   if (frm.style.display == 'none') {
      frm.style.display = 'block';
   }
   else {
      frm.style.display = 'none';
   }
}
</script>
<?php
table_2_start(); // ������� ������� ������� ������
echo '<h1>�������� �����</h1><br>' . "\n";
if (isset($_GET['page'])) $page = (int)$_GET['page'];
else $page = 1;
$query_count = 'SELECT COUNT(`id_msg`) FROM `gbook`';
$res_count = @mysqli_query($db, $query_count) or die('������ ' . 
                                                      mysqli_errno($db));
if (mysqli_num_rows($res_count) === 1) {
   $pole = mysqli_fetch_row($res_count);
   $count = $pole[0];
   if (preg_match('/^[0-9]+/s', $count) && $count > 0) {
      if (!isset($page) || $page < 1) $page = 1;
      if (!preg_match('/^[0-9]+$/s', $page)) $page = 1;
      $count_page = ceil($count / COUNT_POS_PAGE_GBOOK);
      if ($page > $count_page) $page = $count_page;
      $pos_start = ($page - 1) * COUNT_POS_PAGE_GBOOK;
      $query = 'SELECT `author`, `msg`, ';
      $query .= "DATE_FORMAT(`msg_date`, '%d.%m.%Y %H:%i') AS `d` ";
      $query .= 'FROM `gbook` ORDER BY `id_msg` DESC ';
      $query .= 'LIMIT ' . $pos_start . ', ' . COUNT_POS_PAGE_GBOOK;
      $res = @mysqli_query($db, $query) or die('������ ' .
                                                mysqli_errno($db));
      while ($r = mysqli_fetch_assoc($res)) {
         // ������� ���������
         table_gbook($r['d'], $r['author'], 
                     str_replace("\n", '<br>', $r['msg']));
      }
      if ($count > COUNT_POS_PAGE_GBOOK) { // ������� ���������� �������
         table_page_start(); // ������� ��� ���������� ������� ������
         echo '<span class="bold">��������:</span>' . "\n";
         for ($j = 1; $j < $count_page + 1; $j++) {
            if ($j == $page) echo '[' . $j . "] \n";
            else echo "<a href=\"?page=$j\">$j</a> \n";
         }
         table_page_end(); // ������� ��� ���������� ������� �����
      }
   }
   else {
      echo '<div align="center" class="bold">��������� ���</div><br>';
   }
   mysqli_free_result($res_count);
}
else echo '<div class="err">������</div><br>';
mysqli_close($db);
table_2_end(); // ����� ������� ������� ������
if ($err_msg == '') $display = 'none';
else $display = 'block';
?>
<div align="center">
<a href="#" onclick="showForm(); return false;">�������� ����� ���������</a>
</div>
<div id="frm" style="display:<?php echo $display; ?>" align="center">
<hr width="70%">
<h1>����� ���������</h1>
<?php
if ($err_msg != '') {
   // ���� �������� ������, �� ������� �� ��������
   $author = stripslashes($author);
   $msg = stripslashes($msg);
   $author = hsc($author);
   $msg = hsc($msg);
   echo '<div class="err">' . $err_msg . "</div><br>\n";
}
?>
<form action="gbook.php" method="POST" onsubmit="return submit_form();">
<span class="bold">���� ���:</span> 
<input type="text" class="txt_frm" style="width:260px" name="author" id="author" value="<?php echo $author; ?>"> 
<span class="bold">���������:</span><br>
<textarea name="msg" id="msg" cols="15" rows="20" class="textarea_frm" 
style="width: 500px; height: 150px"><?php echo $msg; ?>
</textarea><br>
<input type="submit" name="go" value="�������� ���������">
</form></div>
<?php
footer_user(); // ������� ������ ����������
?>
