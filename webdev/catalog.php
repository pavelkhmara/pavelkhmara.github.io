<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
// ������������ � ���� ������
$db = db_connect();
if (isset($_GET['rubr'])) $rubr = (int)$_GET['rubr'];
else $rubr = 1;
if (isset($_GET['page'])) $page = (int)$_GET['page'];
else $page = 1;
if (!preg_match('/^[0-9]+$/s', $rubr) || $rubr == 0) $rubr = 1;
$query = 'SELECT `name_rubr` FROM `rubr` WHERE `id_rubr`=' . $rubr;
$res_r = @mysqli_query($db, $query) or die('������ ' . mysqli_errno($db));
if (mysqli_num_rows($res_r) === 1) {
   $row = mysqli_fetch_assoc($res_r);
   $rubrika = $row['name_rubr'];
   mysqli_free_result($res_r);
}
else $rubrika = '';
// ���������
$title = '������� ������ >> ������� - ' . $rubrika;
// �������� ��������
$description = '';
// �������� ����� ��� ��������� �����
$keywords = $rubrika;
// ������� ������� ����������
header_all($title, $description, $keywords);
table_2_start(); // ������� ������� ������� ������
echo '<h1>������� ������ &gt;&gt; ' . hsc($rubrika) . "</h1><br>\n";
// ������� �����
$query_count = 'SELECT COUNT(`id_site`) FROM `site` ';
$query_count .= "WHERE `id_rubr`=$rubr AND `status_site`='y'";
$res_count = @mysqli_query($db, $query_count) or die('������ ' .  
                                                      mysqli_errno($db));
if (mysqli_num_rows($res_count) === 1) {
   $count = mysqli_fetch_row($res_count)[0];
   if (preg_match('/^[0-9]+/s', $count) && $count>0) {
      if (!isset($page) || $page < 1) $page = 1;
      if (!preg_match('/^[0-9]+$/s', $page)) $page = 1;
      $count_page = ceil($count / COUNT_POS_PAGE);
      if ($page > $count_page) $page = $count_page;
      $pos_start = ($page - 1) * COUNT_POS_PAGE;
      $query = 'SELECT * FROM `site` WHERE `id_rubr`=' . $rubr;
      $query .= " AND `status_site`='y' ";
      $query .= 'ORDER BY `iq_site` DESC ';
      $query .= 'LIMIT ' . $pos_start . ', ' . COUNT_POS_PAGE;
      $res = @mysqli_query($db, $query) or die('������ ' .
                                                mysqli_errno($db));
      while ($r = mysqli_fetch_assoc($res)) {
         // ������� ��������
         table_site($r['url_site'], $r['title'], $r['descr']);
      }
      mysqli_free_result($res);
      if ($count > COUNT_POS_PAGE) { // ������� ���������� �������
         table_page_start(); // ������� ��� ���������� ������� ������
         echo '<span class="bold">��������:</span>' . "\n";
         for ($j = 1; $j < $count_page + 1; $j++) {
            if ($j == $page) echo '[' . $j . "] \n";
            else echo "<a href=\"?rubr=$rubr&amp;page=$j\">$j</a> \n";
         }
         table_page_end(); // ������� ��� ���������� ������� �����
      }
   }
   else {
      echo '<div align="center" class="bold">������ ���</div><br>';
   }
}
else echo '<div class="err">������</div><br>';
mysqli_free_result($res_count);
table_2_end(); // ����� ������� ������� ������
mysqli_close($db); // ��������� ���������� � ����� ������
footer_user(); // ������� ������ ����������
?>
