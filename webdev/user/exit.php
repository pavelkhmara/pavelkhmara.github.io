<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
session_start(); // ��������� ������
session_unset(); // ������� ��� ����������
session_destroy(); // ������� �������������
header('Location: ' . URL_SITE);
