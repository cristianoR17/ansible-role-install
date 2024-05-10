#!/usr/bin/php5 -q
<?php
$fd = fopen ('/home/extend/status_ramal', "r");
while (!feof ($fd)){
 $buffer = fgets($fd, 4096);
 $var2 = explode(",", $buffer);
 if ('4000' == $var2[1]){
  echo trim($var2[1]);
  echo "\n";
  echo $buffer;
}
}
fclose ($fd);

?>

