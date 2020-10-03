<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// ������������ � ���� ������
$db = db_connect();
// ���������
$title = '������� �������� ��������';
// �������� ��������
$description = '';
// �������� ����� ��� ��������� �����
$keywords = '�������� ����, ������� ������';
// ������� ������� ����������
header_all($title, $description, $keywords);

// �������� ���������� ������ � �������� � ���������� ����������� �� �������
$count_site = 0;
$count_site_evr = 0;
if ($res = @mysqli_query($db, 'SELECT COUNT(`id_site`) FROM `site`')) {
   $count_site = mysqli_fetch_row($res)[0];
   mysqli_free_result($res);
}
$query = 'SELECT COUNT(`id_site`) FROM `site` WHERE `add_date`=CURDATE()';
if ($res = @mysqli_query($db, $query)) {
   $count_site_evr = mysqli_fetch_row($res)[0];
   mysqli_free_result($res);
}
?>
<h1>������� �������� ��������</h1><br>
<table align="center" width="600" bgcolor="#FFFFFF" border="0" 
   cellpadding="2" cellspacing="0">
<tr><td valign="top">
<?php
$query = 'SELECT `id_rubr`, `name_rubr` FROM `rubr` ORDER BY `name_rubr`';
$res = @mysqli_query($db, $query) or die('������ ' . mysqli_errno($db));
$count = mysqli_num_rows($res);
if ($count > 0) {
   $c1 = ceil($count / 3);
   $c2 = $c1 * 2;
   $i = 0;
   while ($row = mysqli_fetch_assoc($res)) {
      if ($i == $c1 || $i == $c2) echo '</td><td valign="top">';
      echo '<a href="' . URL_SITE . 'catalog.php?rubr=' . $row['id_rubr'];
      echo '">' . hsc($row['name_rubr']) . "</a><br>\n";
      $i++;
   }
}
mysqli_free_result($res);
?>
</td></tr>
</table>
<?php
echo '<br><div align="center" class="bold">';
echo '���������� ������ � �������� - ' . $count_site . '<br>';
echo '������� ��������� - ' . $count_site_evr . '</div><br>';
echo "<h1>����� ����� � ��������</h1><br>\n";
table_2_start(); // ������� ������� ������� ������
// �������  ����� �����
$query = "SELECT * FROM `site` WHERE `status_site`='y' ";
$query .= 'ORDER BY `id_site` DESC LIMIT 0, 10';
$res = @mysqli_query($db, $query) or die('������ ' . mysqli_errno($db));
$count = mysqli_num_rows($res);
if ($count > 0) {
   while ($r = mysqli_fetch_assoc($res)) {
      // ������� ��������
      table_site($r['url_site'], $r['title'], $r['descr']);
   }
}
else echo '<div align="center" class="bold">������ ���</div><br>';
mysqli_free_result($res);
table_2_end(); // ����� ������� ������� ������
// ��������� ���������� � ����� ������
mysqli_close($db);
footer_user(); // ������� ������ ����������
?>

