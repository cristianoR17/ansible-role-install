#!/usr/bin/php -q
<?php
header('Content-Type: application/json');

//$cmd="pgrep -f websocketskeep$port_http.html | xargs -n 1 -i kill -9 {}";
//system($cmd);


function send_tcp($pacote) {
$ip="127.0.0.1";
$port=9999;
$address=$ip;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$erro=1;
$result = socket_connect($sock, $address, $port);
if ($result === false) {
  $erro=1;
} else {
  $erro=0;
}
if ($erro==0)
{
// echo "Enviando TCP... $pacote \n";
 socket_set_option($sock,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>3, "usec"=>0));
 socket_write($sock,$pacote,strlen($pacote));
 socket_close($sock);
}

}


function return_port(){
while (true){
$port=rand(30000,39000);
$ip="127.0.0.1";
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
if (socket_bind($server, $ip, $port)==true){
 socket_listen($server);
 socket_close($server);
 return $port;
}

}
}

function return_port_http(){
while (true){
$port=rand(50000,58000);
$ip="127.0.0.1";
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
if (socket_bind($server, $ip, $port)==true){
 socket_listen($server);
 socket_close($server);
 return $port;
}

}
}


foreach($argv as $value){
  $data = $value;
}

//$v = explode("|",$data);
//$keep = $_GET['keep'];
//$chat = $_GET['chat'];
//$msg = $_GET['msg'];

$user=$argv[1];
$password=$argv[2];
$reconnect=$argv[3];
$keep=$argv[4];

#400310;16;1;{3,5,167,169,170};{0,0,0,0,0};0;PAULA PINHEIRO;400310;1;;
for ($i=0;$i<=5;$i++){
if (file_exists("/home/extend/tmp/tmp_tr_agente_eventos")){
 $arquivo = fopen ("/home/extend/tmp/tmp_tr_agente_eventos", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $v = explode(";",$linha);
  $agente_=$v[0];
  if ($agente_==$user){
    $cd_equipe=$v[1];
    $cd_unidade=$v[2];
    $cd_servico=$v[3];
    $nu_skill=$v[4];
    $cd_camp=$v[5];
    $agent_name=$v[6];
    $password_=$v[7];
    $cd_site=$v[8];
    break;
  }
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
  }
 }
 fclose($arquivo);
 break;
}
sleep(3);
}
$ok=0;
if ($password_==$password)
 $ok=1;


///var/www/comunix/pausas
if (file_exists("/var/www/comunix/pausas")){
 $arquivo = fopen ("/var/www/comunix/pausas", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $v = explode(";",$linha);
    $p72=$v[0];
    $p73=$v[1];
    $p74=$v[2];
    $p75=$v[3];
    $p76=$v[4];
    $p77=$v[5];
    $p78=$v[6];
    $p79=$v[7];
    $p80=$v[8];
    $p81=$v[9];
    $p82=$v[10];
    $p83=$v[11];
    $p84=$v[12];
    $p85=$v[13];
    break;
 }
 fclose($arquivo);
}


if (($ok==1)){
     $ramal=5000;
     $cmd="";
     if ($keep==1){
      $cmd="pgrep -f '/var/www/comunix/websockets_keep.php $user' | xargs -n 1 -i kill -9 {}";
      //echo $cmd."\n";
      //send_tcp($cmd);
     }


     if ($keep==1){
      //websockets_keep.php
      $port_keep=return_port();
      $cmd=("php /var/www/comunix/websockets_keep.php $user $port_keep &");
      //echo $cmd."\n";
      send_tcp($cmd);
     }

     $a='"login":{"user":"'.$agent_name.'","port_http_keep":"'.$port_keep.'","ramal":"'.$ramal.'","pauses":{"72":"'.$p72.'","73":"'.$p73.'","74":"'.$p74.'","75":"'.$p75.'","76":"'.$p76.'","77":"'.$p77.'","78":"'.$p78.'","79":"'.$p79.'","80":"'.$p80.'","81":"'.$p81.'","82":"'.$p82.'","83":"'.$p83.'","84":"'.$p84.'"}}';
    }


//sleep(2);
echo ('{'.$a.'}');



?>
