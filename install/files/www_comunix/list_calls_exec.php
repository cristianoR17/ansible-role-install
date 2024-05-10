#!/usr/bin/php -q
<?php

header('Content-Type: application/json');

foreach ($argv as $value) {
  $dados = $value;
}

$login = $argv[1];
$j=0;
$a="";
if (file_exists("/var/www/comunix/hist/$login")){
  $arquivo = fopen ("/var/www/comunix/hist/$login", 'r');
  while(!feof($arquivo)){
   $linha = fgets($arquivo, 1000);
   $v = explode(";",$linha);
   $dt=$v[0];
   $clid=$v[1];
   $clid=str_replace("\n", "", $clid);
   echo "$dt $clid \n";
   if (($dt!="") && ($clid!="")){
    $a=$a.'"list'.$j.'":{"dt":"'.$dt.'","clid":"'.$clid.'"},';
    $j++;
   } 
  } 
   fclose($arquivo);
 }
$a=substr($a,0,strlen($a)-1);
echo ('{'.$a.'}');



?>
