#!/usr/bin/php -q
<?php
header('Content-Type: application/json');

//$cmd="pgrep -f websocketskeep$port_http.html | xargs -n 1 -i kill -9 {}";
//system($cmd);


//$name="ceuma.webrtc.comunix.tech"; 
$name="webrtc.chat.comunix.tech"; 
$port_sip=8089;
$port_http=3000;

$a='"server":{"name":"'.$name.'","port_http":"'.$port_http.'","port_sip":"'.$port_sip.'"}';

echo ('{'.$a.'}');



?>
