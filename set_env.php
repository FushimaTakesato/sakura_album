<?php
$path = "{$_SERVER['DOCUMENT_ROOT']}../env/album_vars.ini";
if ('support@sakura.ad.jp' == $_SERVER['SERVER_ADMIN'] && file_exists($path))
    $_SERVER = array_merge($_SERVER, parse_ini_file($path));
    
?>
