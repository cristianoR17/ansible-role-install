#!/usr/bin/php5 -q


<?php

foreach($argv as $value)
{
  $getCti = $value;
}

$msg=preg_replace("/[0-9]/", "", $getCti);
$nr=preg_replace("/[^0-9]/", "", $value);

print_r($msg."\n");
print_r($nr."\n");

system("wget 'http://192.168.0.24/sendsmsasr.php?telefone=$nr&msg=$msg'");


?> 
