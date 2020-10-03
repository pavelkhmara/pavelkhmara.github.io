<?php
define('IN_CATALOG', true);
@require_once('allscript.php');
session_start(); // Запускаем сессию
session_unset(); // Удаляем все переменные
session_destroy(); // Удаляем идентификатор
header('Location: ' . URL_SITE);
