<?php
  $f = fopen("data_cnt.txt", "r+");
  $c = fgets($f, 10);
  //$c = $c + 1;
  fseek($f, 0);
  fputs($f, $c);
  fclose($f);
  echo $c;
?>