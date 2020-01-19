<?php
include("controlDB.php");
$id    = $_POST['id'];
$tag   = $_POST['tag'];
$value = $_POST['value'];
$dbh = loginDB();
setDB($dbh, $id, $tag, $value);
?>
