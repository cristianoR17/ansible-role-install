#!/usr/bin/php -q
<?php

header('Content-Type: application/json');

foreach ($argv as $value) {
  $login = $value;
}
$a="";
$j=0;
// 01-09-2020 15:37:34;61999685009
if (file_exists("/var/www/comunix/hist/$login")){
$arquivo = fopen ("/var/www/comunix/hist/$login", 'r');
while(!feof($arquivo)){
  $linha = fgets($arquivo, 2024);
  if ($linha!=""){
   $resp=$linha;// echo $linha.'<br />';
   $v = explode(";",$resp);
   $dt=$v[0];
   $clid=str_replace("\n","", $v[1]);   
   $a = $a . '"list' . $j . '":{"date":"' . $dt. '","clid":"' . $clid. '"},';
   $j++;
  }
}

$a = substr($a, 0, strlen($a) - 1);
echo ('{' . $a . '}');

fclose($arquivo);

}















?>
