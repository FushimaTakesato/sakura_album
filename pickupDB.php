<?php

require_once __DIR__ . '/pass_check.php';
require_logined_session();
require_once 'set_env.php';
header('Content-Type: text/html; charset=UTF-8');

?>


<?php
include("controlDB.php");
$place = $_POST['place'];
$event = $_POST['event'];
$dbh = loginDB();
pickupmultiDB($dbh, $place, $event, $value);
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
?>
