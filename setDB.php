<?php

require_once __DIR__ . '/pass_check.php';
require_logined_session();
require_once 'set_env.php';
header('Content-Type: text/html; charset=UTF-8');

?>


<?php
include("controlDB.php");
$id    = $_POST['id'];
$tag   = $_POST['tag'];
$value = $_POST['value'];
$dbh = loginDB();
setDB($dbh, $id, $tag, $value);
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
header('Location: '.$_SERVER['HTTP_REFERER']);
exit();
?>
