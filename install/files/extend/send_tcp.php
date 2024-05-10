#!/usr/bin/php5 -q
<?PHP

$pacote="http://10.49.216.38#";
$ip="192.168.2.208";
$port=2095;
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
 echo "Enviando TCP... $pacote \n";
 socket_set_option($sock,SOL_SOCKET, SO_RCVTIMEO, array("sec"=>3, "usec"=>0));
 socket_write($sock,$pacote,strlen($pacote));
 socket_close($sock);
}

?>
