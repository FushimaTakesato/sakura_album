<?php
    function loginDB(){
        // データベースに接続するために必要なデータソースを変数に格納
        // mysql:host=ホスト名;dbname=データベース名;charset=文字エンコード
        $dsn = $_SERVER['DB_DSN'];
        // データベースのユーザー名
        $user = $_SERVER['DB_USER'];
        // データベースのパスワード
        $password = $_SERVER['DB_PASS'];

        // tryにPDOの処理を記述
        try {
            // PDOインスタンスを生成
            $dbh = new PDO($dsn, $user, $password);
            // エラー（例外）が発生した時の処理を記述
        } catch (PDOException $e) {
            // エラーメッセージを表示させる
            echo 'データベースにアクセスできません！' . $e->getMessage();
            // 強制終了
            exit;
        }
        return $dbh;
    }
    
    //以前のDBから移行
    //INSERT INTO comment(id, value) select id,comment from test;
    function addDB($dbh, $id, $date, $name){
        $sql = "INSERT INTO test(date,name,id) VALUES('".$date."','".$name."',".$id.")";
        $stmt = $dbh->query($sql);
        echo $sql;
    }
    
    function getDB($dbh){
        $sql = "SELECT * FROM test";
        $stmt = $dbh->query($sql);
        // foreach文で配列の中身を一行ずつ出力
        foreach ($stmt as $row) {
            // データベースのフィールド名で出力
            echo $row['id'].'：'.$row['name'];
            // 改行を入れる
            echo '<br>';
        }
    }
    function showDBsimple($dbh){
        $sql = "SELECT * FROM test";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            $img = './data_img/'.$row['name'];
            $exif = @exif_read_data( $img );
            //var_dump($exif['DateTime']);
            echo $row['id'];
            echo '<br>';
            echo $row['name'];
            echo '<br>';
            //var_dump($exif['DateTimeOriginal']);
            //echo '<br>';
            echo $row['place'];
            echo '<br>';
            echo $row['event'];
            echo '<br>';
            echo '<img src="'.$img.'" width="300">';
            echo '<br>';
        }
        
    }
    function printTag($dbh, $tag, $id){
        //tagから、該当idのvalueを引っ張ってくる
        $sql = "SELECT value  FROM ".$tag." WHERE id = '".$id."'";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            echo $row['value'];
            echo '/';
        }
    }
    function editTag($dbh, $tag, $id){
        $sql = "SELECT value  FROM ".$tag." WHERE id = '".$id."'";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            echo '<form method="POST"  action="modifyDB.php">
                  <input type="hidden" name = "id" value="'.$id.'">
                  <input type="hidden" name = "tag" value="'.$tag.'"  >
                  <input type="hidden" name = "orgvalue" value="'.$row['value'].'">
                  '.$tag.'  :
                  <input name="value" type="text" value="'.$row['value'].'">
                  <input type="submit" value="修正">
                  </form>';
        }
        echo '<form method="POST"  action="setDB.php">
              <input type="hidden" name = "id" value="'.$id.'">
              <input type="hidden" name = "tag" value="'.$tag.'">
              '.$tag.'  :
              <input name="value" type="text" value="">
              <input type="submit" value="追加">
              </form>';
    }
    function showDB($dbh, $sql){
        $stmt = $dbh->query($sql);
        //for ($i = 0 ; $i < count($stmt); $i++){
        //    $row = $stmt[i];
        $img_count = 0;
        echo $img_num;
        echo '<br>';
        echo '<table border="1">';
        foreach ($stmt as $row) {
            if($img_count%4==0){
                echo '<tr>';
            }
            echo '<td align="left" valign="top">';
            $img = './data_img/'.$row['name'];
            $exif = @exif_read_data( $img );
            //var_dump($exif['DateTime']);
            //echo $row['id'];
            //echo '<br>';
            //echo $row['name'];
            //echo '<br>';
            //var_dump($exif['DateTimeOriginal']);
            //echo '<br>';
            //placeから、該当idのvalueを引っ張ってくる
            printTag($dbh, 'place', $row['id']);
            echo '<br>';
            printTag($dbh, 'event', $row['id']);
            echo '<br>';
            echo '<img src="'.$img.'" width="300">';
            echo '<br>';
            editTag($dbh, 'place', $row['id']);
            echo '<br>';
            editTag($dbh, 'event', $row['id']);
            echo '<br>';
            editTag($dbh, 'people', $row['id']);
            echo '<br>';
            editTag($dbh, 'impression', $row['id']);
            echo '<br>';
            editTag($dbh, 'comment', $row['id']);
            echo '<br>';
            echo '<form method="POST"  action="deleteDB.php"><input type="hidden" name = "id" value="'.$row['id'].'"><input type="submit" value="削除"></form>';
            echo '<br>';
            echo '</td>';
            if($img_count%4==3){
                echo '</tr>';
            }
            $img_count += 1;
        }
        echo '</table>';
        
    }
    function show_editDB($dbh){
        $sql = "SELECT * FROM test ORDER BY id DESC;";
        showDB($dbh, $sql);
    }

    function deleteDB($dbh, $id){
        $sql = "DELETE FROM `test` WHERE `test`.`id` = '".$id."'";
        $stmt = $dbh->query($sql);
        echo $sql;
    }
    //new
    function setDB($dbh, $id, $tag, $value){
        $sql = "INSERT INTO `".$tag."`(id, value) VALUES('".$id."','".$value."')";
        $stmt = $dbh->query($sql);
    }
    //modify
    function modifyDB($dbh, $id, $tag, $value, $orgvalue){
        $sql = "SELECT * FROM `".$tag."` WHERE `id` = '".$id."' AND `value` = '".$orgvalue."' ";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row){
            $sql = "UPDATE `".$tag."` SET `value` = '".$value."' WHERE `".$tag."`.`id` = '".$id."'";
            $stmt = $dbh->query($sql);
        }
    }
    function setDBfromEXIF($dbh, $id){
        $sql = "SELECT * FROM `test` WHERE `id` = '".$id."'";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row){
            $img = './data_img/'.$row['name'];
            $exif = @exif_read_data( $img ) ;
            //もし、時間が入っていなかったら
            if(empty($exif['DateTime'])){
                echo 'No time.';
            }else{
                list($year, $month, $day, $hour, $min, $sec) = preg_split('/[: ]/', $exif['DateTime']);
                $sql = "UPDATE `test` SET `year` = '".$year."', `month` = '".$month."', `day` = '".$day."', `hour` = '".$hour."', `min` = '".$min."', `sec` = '".$sec."' WHERE `test`.`id` = '".$id."'";
                $stmt = $dbh->query($sql);
            }
        }
    }
    function pickupDB($dbh, $tag, $value){
        $sql = "SELECT * FROM test WHERE `".$tag."` = '".$value."'";
        showDB($dbh, $sql);
        
    }
    function pickupmultiDB($dbh, $place, $event, $value){
        
        //placeターブルの中から検索
        $sql = "SELECT * FROM `place` WHERE `value` LIKE '".$place."'";
        $stmt = $dbh->query($sql);
        echo 'place:'.$place.'<br>';
        foreach ($stmt as $row) {
            //echo $row['id'];
            //echo '<br>';
            $list_place[] = $row['id'];
        }
        if ($list_place) {
            $where_place = "`id` IN ('".implode("', '", $list_place)."')";
        } else {
            $where_place = "";
        }
        //eventターブルの中から検索
        $sql = "SELECT * FROM `event` WHERE `value` LIKE '".$event."'";
        $stmt = $dbh->query($sql);
        echo 'event:'.$event.'<br>';
        foreach ($stmt as $row) {
            //echo $row['id'];
            //echo '<br>';
            $list_event[] = $row['id'];
        }
        if ($list_event) {
            $where_event = "`id` IN ('".implode("', '", $list_event)."')";
        } else {
            $where_event = "";
        }
        //placeかつeventのidを検索
        $sql = "SELECT * FROM `test` WHERE ".$where_place." AND ".$where_event;
        $stmt = $dbh->query($sql);
        //echo 'and<br>';
        
        //foreach ($stmt as $row) {
        //    echo $row['id'];
        //    echo '<br>';
        //}
        showDB($dbh, $sql);
        
    }
    function listDB($dbh, $tag){
        $sql = "SELECT * FROM ".$tag." GROUP BY value";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            $list[] = $row['value'];
        }
        return $list;
    }
?>
