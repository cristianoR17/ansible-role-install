#!/usr/bin/php -q
<?php
header('Content-Type: application/json');

//$cmd="pgrep -f websocketskeep$port_http.html | xargs -n 1 -i kill -9 {}";
//system($cmd);

$user=$argv[1];
$ip=$argv[2];

$user_full=$user;
//$name="ceuma.webrtc.comunix.tech"; 

$user=substr($user,4,2);
$vl=(int)$user;

$ip=substr($ip,0,3);

if (($vl>=0) && ($vl<=13)){
 $name="sipweb00.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=14) && ($vl<=27)){
 $name="sipweb02.brbservicos.com.br";	
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=28) && ($vl<=41)){
 $name="sipweb03.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=42) && ($vl<=55)){
 $name="sipweb04.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=56) && ($vl<=69)){
 $name="sipweb05.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=70) && ($vl<=84)){
 $name="sipweb06.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($vl>=85) && ($vl<=99)){
 $name="sipweb07.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($user_full=="400402") ){
 $name="sipweb07.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($user_full=="117779") ){
 $name="sipweb00.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}
if (($user_full=="489177") ){
 $name="sipweb05.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}

if (($user_full=="502502") ){
 $name="sipweb00.brbservicos.com.br";
 $port_sip=8089;
 $port_http=3000;
}



//if (($user_full=="495950") || ($user_full=="489631") || ($user_full=="107922") || ($user_full=="107922") || ($user_full=="493557") || ($user_full=="407815") ){
// $name="sipweb00.brbservicos.com.br";
// //$name="webrtcserpro01.chat.comunix.tech";
// $port_sip=8089;
// $port_http=3000;
//}


//$name="sipweb03.brbservicos.com.br";
//$port_sip=8089;
//$port_http=3000;

//if (($vl>=6) && ($vl<=7)){
// $name="caixasipweb02.webrtc.comunix.tech";
// $port_sip=8089;
// $port_http=3000;
//}

//if (($vl>=8) && ($vl<=9)){
// $name="caixasipweb03.webrtc.comunix.tech";
// $port_sip=8089;
// $port_http=3000;
//}


$a='"server":{"name":"'.$name.'","port_http":"'.$port_http.'","port_sip":"'.$port_sip.'"}';

echo ('{'.$a.'}');



?>
