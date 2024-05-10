#!/usr/bin/php -q
<?php
ob_implicit_flush(true);
set_time_limit(6);
error_reporting(0); 


$ret = exec("curl -X GET 'https://10.20.1.80/protocol' -k");
$ret_arr = json_decode($ret,true);
$protocolo = $ret_arr['protocol'];
echo("SET VARIABLE PROTOCOLO $protocolo\n");


exit;

?>

