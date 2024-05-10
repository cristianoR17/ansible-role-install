#!/usr/bin/php5 -q
<?php

$port=8889;
$address="10.49.216.14";
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$result = socket_connect($sock, $address, $port);
if ($result === false) {
  echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

socket_set_option($sock,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>3, "usec"=>0));
$list="";
while ($out = socket_read($sock, 512)) {
$list .= $out;
$a=explode("#",$list);
if ($a=="END")
  break;
// print_r($a);
$a="";
}

print_r(explode("#",$list));


?>

