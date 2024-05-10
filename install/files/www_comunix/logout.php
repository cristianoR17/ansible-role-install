#!/usr/bin/php7.3 -q
<?php

foreach($argv as $value){
  $data = $value;
}

$cmd="pgrep -f '/var/www/comunix/login.php $data:' | xargs -n 1 -i kill -9 {}";
system($cmd);
$cmd="rm /dev/shm/$data";
system($cmd);

$cmd="asterisk -rx 'core show channels' |grep $data |grep AppDial |cut -d' ' -f1 | xargs -n 1 -i asterisk -rx 'hangup request {}'";
system($cmd);


?>
