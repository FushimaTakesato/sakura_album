<?php
include("controlDB.php");
$place = $_POST['place'];
$event = $_POST['event'];
$dbh = loginDB();
pickupmultiDB($dbh, $place, $event, $value);
?>
