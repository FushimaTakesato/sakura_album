<?php
//$_ENV連想配列で取得
echo '$_ENV[\'REMOTE_ADDR\']で取得 <b>[ '.$_ENV['PATH']." ]</b><br/>\n";

//getenv()で取得
$iIp = getenv('REMOTE_ADDR');
echo 'getenv(\'REMOTE_ADDR\')で取得 <b>[ '.$iIp." ]</b><br/>\n";
?>