#!/usr/bin/php5 -q
<?php

$try=0;
while (1){
 $ramal=rand(4000,4000);
 $a=system("cat /dev/shm/ramais/channel_ipaddr |grep $ramal |grep OK");

 if ($a==""){
  system("touch /dev/shm/$ramal");
  echo $ramal;
  break;
 }
 sleep(1);
 $try++;
 if ($try==10)
  break;
}

?>
