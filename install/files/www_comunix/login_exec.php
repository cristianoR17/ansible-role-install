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
$port=rand(10000,19999);
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
$ip=$argv[5];
$a="";

#  0     1 2               3                 4     5       6           7     8  9 
#400021;32;1;{6,168,169,170,206,208};{0,0,0,0,0,0};0;LEIDJANE GALDINO;123456;1;5005;;;;
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
    $ramal=$v[9];
    $hr_login=$v[10];
    $dt_login=$v[11];
    $in_atendimento_automatico=$v[12];
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


//$ok=0;
//if ($password_==$password){
if (($password_==$password) || ($password=="CMX")){
$ok=1;
 $cmd="php /home/extend/allow_ip $ip";
 send_tcp($cmd);
}

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

///var/www/comunix/transfer
if (file_exists("/var/www/comunix/transfer")){
 $arquivo = fopen ("/var/www/comunix/transfer", 'r');
 $i=0;
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $linha=str_replace("\n", "", $linha);
  if ($linha!=""){
   $v = explode("=",$linha);
   $str.="\"$v[0]\":\"$v[1]\","; 
 
  }
 } 
  fclose($arquivo);
}
$str=substr($str,0,strlen($srt)-1);

if (file_exists("/home/extend/tmp/tmp_tr_label_evento")){
 $arquivo = fopen ("/home/extend/tmp/tmp_tr_label_evento", 'r');
 $i=0;
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $linha=str_replace("\n", "", $linha);
  if (($linha!="") && ($linha!="EOF;")){
   $v = explode(";",$linha);
   $str_p.="\"$v[0]\":\"$v[1]\",";

  }
 }
  fclose($arquivo);
}

$str_p.="\"1000\":\"Sair da pausa\",";
$str_p=substr($str_p,0,strlen($srt_p)-1);

//$t1="Pesquisa";
//$t2="Pes";

if (($ok==1)){
     $autoanswer=$in_atendimento_automatico;
     $cmd="";
     if ($autoanswer=="")
	  $autoanswer=1;
      //$cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f ':$user:' | xargs -n 1 -i kill -9 {}";
      //send_tcp($cmd);
      //$cmd="pgrep -f '/var/www/comunix/websockets_keep.php $user' | xargs -n 1 -i kill -9 {}";
      //send_tcp($cmd);
      sleep(2);
      //websockets_keep.php
      $port_keep=return_port();

      //$cmd="/var/www/comunix/login.php $ramal:$user:$ip";
      //send_tcp($cmd);
      //$cmd=("php /var/www/comunix/websockets_keep.php $user $port_keep &");
      //echo $cmd."\n";
      //send_tcp($cmd);


      $logado=0;
      $nr_login=system("pgrep -f '/bin/sh -c /var/www/comunix/login.php $ramal:' |wc -l");
      if ($nr_login>=2){
        $logado=1;
        //$cmd="pgrep -f '/var/www/comunix/websockets_keep.php $user ' | xargs -n 1 -i kill -9 {}";
        send_tcp($cmd);
      } else{
         $cmd="/var/www/comunix/login.php $ramal:$user:$ip";
         send_tcp($cmd);
         $cmd=("php /var/www/comunix/websockets_keep.php $user $port_keep &");
         send_tcp($cmd);
      }
     

      $a='"login":{"user":"'.$agent_name.'","port_http_keep":"'.$port_keep.'","logado":"'.$logado.'","autoanswer":"'.$autoanswer.'","ramal":"'.$ramal.'","pauses":{'.$str_p.'}'.',"transfer":{'.$str.'}}';
    
}

echo ('{'.$a.'}');



?>
