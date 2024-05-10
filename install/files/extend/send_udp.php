<?PHP
foreach($argv as $value){
  $getCti = $value;
}

$pacote=$value;
$ip="127.0.0.1";
//Envia protocolo para o Combox
$socket = array(
     'ip'   => "udp://$ip",
     'port' => "8888"
    );

$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $pacote\n";
fwrite($sock,$pacote);
fclose($sock);


?>
