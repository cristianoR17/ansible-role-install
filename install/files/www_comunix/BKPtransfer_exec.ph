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

foreach($argv as $value){
  $data = $value;
}


$servico=$argv[1];

$file_transfer="transfer_$servico";
//echo $file_transfer."\n";
if (file_exists("/var/www/comunix/$file_transfer"))
 $file_transfer=$file_transfer;
else
 $file_transfer="transfer";	
if (file_exists("/var/www/comunix/$file_transfer")){
 $arquivo = fopen ("/var/www/comunix/$file_transfer", 'r');
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

$a='"transfer":{'.$str.'}';
    
echo ('{'.$a.'}');



?>
