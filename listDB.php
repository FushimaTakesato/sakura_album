<?php

include("controlDB.php");
$dbh = loginDB();
$tag   = 'place';
$lists_place = listDB($dbh, $tag);
$tag   = 'event';
$lists_event = listDB($dbh, $tag);
echo '<form action="pickupDB.php" method="POST">';
foreach ($lists_place as $list) {
    echo '<input type="checkbox" name = place value="'.$list.'">'.$list;
}
echo '<input type="checkbox" name = place value="%">全て';
echo '<br>';
foreach ($lists_event as $list) {
    echo '<input type="checkbox" name = event value="'.$list.'">'.$list;
}
echo '<input type="checkbox" name = event value="%">全て';
echo '<br>';
echo '<input type="submit" value="送信">';
echo '</form>';
?>
