#!/usr/bin/php5 -q


<?php
foreach($argv as $value)
{
  $getCti = $value;
}

$v=explode("|",$value);

//$resp=system("/home/extend/./sophia_ '$value'");

$resp=system("/home/extend/sophia/./sophia $v[0] '$v[1]'");


if (strcmp("telefone",$resp)==0){

print_r($value."\n");

$value=preg_replace("/[^0-9]/", "", $value);

$len=strlen($value);
if ($len<8){
 print_r("0"); 
 exit(0);
}
if ($resp<>""){
   print_r("$value");


}

exit(0);

}

if ($resp<>"")
  print_r(trim($resp));



?>

