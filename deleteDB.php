<?php
include("controlDB.php");
$id    = $_POST['id'];
$dbh = loginDB();
deleteDB($dbh, $id);
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
header('Location: '.$_SERVER['HTTP_REFERER']);
exit();
?>
