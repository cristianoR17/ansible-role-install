<?PHP

$pacote="*78#";
$ip="192.168.2.208";
//Envia protocolo para o Combox
$socket = array(
     'ip'   => "udp://$ip",
     'port' => "2011"
    );

$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $pacote\n";
fwrite($sock,$pacote);
fclose($sock);


?>
