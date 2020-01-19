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
    function showDBsimpmle($dbh){
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
            echo $row['place'];
            echo '<br>';
            echo $row['event'];
            echo '<br>';
            echo '<img src="'.$img.'" width="300">';
            echo '<br>';
            echo '<form method="POST"  action="setDB.php"><input type="hidden" name = "id" value="'.$row['id'].'"><input type="hidden" name = "tag" value="place"  >place  :<input name="value" type="text" value="'.$row['place'].'"><input type="submit" value="更新"></form>';
            echo '<br>';
            echo '<form method="POST"  action="setDB.php"><input type="hidden" name = "id" value="'.$row['id'].'"><input type="hidden" name = "tag" value="event"  >event  :<input name="value" type="text" value="'.$row['event'].'"><input type="submit" value="更新"></form>';
            echo '<br>';
            echo '<form method="POST"  action="setDB.php"><input type="hidden" name = "id" value="'.$row['id'].'"><input type="hidden" name = "tag" value="comment">comment:<input name="value" type="text" value="'.$row['comment'].'"><input type="submit" value="更新"></form>';
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
    function setDB($dbh, $id, $tag, $value){
        $sql = "SELECT * FROM `test` WHERE `id` = '".$id."'";
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row){
            $sql = "UPDATE `test` SET `".$tag."` = '".$value."' WHERE `test`.`id` = '".$id."'";
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
        $sql = "SELECT * FROM test WHERE `place` LIKE '".$place."' AND `event` LIKE '".$event."'";
        //$sql = "SELECT * FROM test WHERE `place` LIKE '".$place."'";
        showDB($dbh, $sql);
        
    }
    function listDB($dbh, $tag){
        $sql = "SELECT ".$tag." FROM test GROUP BY ".$tag;
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
            //echo $row[$tag];
            //echo '<br>';
            $list[] = $row[$tag];
        }
        return $list;
    }
?>
