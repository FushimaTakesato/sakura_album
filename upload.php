<?php

require_once __DIR__ . '/pass_check.php';
require_logined_session();
require_once 'set_env.php';
header('Content-Type: text/html; charset=UTF-8');

?>

<?php
include("controlDB.php");
//画像の保存先のパスを指定
$filedir = "./data_img/";

//現在時刻を取得yyyymmddhhmmss
include("date.php");
$place    = $_POST['place'];
$event   = $_POST['event'];
$people   = $_POST['people'];

$dbh = loginDB();
//$filesdirで指定したファイルに画像を保存する。
for($i = 0; $i < count($_FILES["upfile"]["tmp_name"]); $i++ ){
    $ext = substr($_FILES["upfile"]["name"][$i], strrpos($_FILES["upfile"]["name"][$i], '.') + 1);
    $num = str_pad($i, 3, 0, STR_PAD_LEFT);
    $id = $tttt.$num;
    $new = $id.".".$ext;
    $org = $filedir.$_FILES["upfile"]["name"][$i];
    if (is_uploaded_file($_FILES["upfile"]["tmp_name"][$i])) {
        if (move_uploaded_file($_FILES["upfile"]["tmp_name"][$i], $filedir.$new)) {
            echo $_FILES["upfile"]["name"] . " uploaded!";
            
            addDB($dbh, $id, $tttt, $new);
            setDB($dbh, $id, "place", $place);
            setDB($dbh, $id, "event", $event);
            setDB($dbh, $id, "people", $people);
            setDBfromEXIF($dbh, $id);
        }
        else {
            echo "can't upload!";
        }
    }
    else {
        echo "not selected";
    }
}
echo '<br>';
echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">前に戻る</a>';
header('Location: '.$_SERVER['HTTP_REFERER']);
exit();
?>
