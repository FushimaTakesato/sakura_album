<html>

<head>
<meta http-equiv="content-type" charset="utf-8">
<?php
include("controlDB.php");
?>


<?php

require_once __DIR__ . '/pass_check.php';
require_logined_session();
require_once 'set_env.php';
header('Content-Type: text/html; charset=UTF-8');

?>


</head>
<body>




<form action="upload.php" method="post" enctype="multipart/form-data">
    アップロード：<br>
    <input type="file" accept="image/*" multiple name="upfile[]" size="30" value=""><br>
    場所<input name="place" type="text"><br>
    イベント<input name="event" type="text"><br>
    人<input name="people" type="text"><br>
    <input type="submit" value="アップロード">
</form>
<br>


<br>
選択表示
<br>
<?php
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
echo '<input type="submit" value="選択表示">';
echo '</form>';


?>
<br>
全画像表示<br>
<?php
    $dbh = loginDB();
    //setDBfromEXIF($dbh,5);
    setDB($dbh,1,'year', 2019);
    show_editDB($dbh);
?>

</body>
</html>
