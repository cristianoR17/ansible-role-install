<?PHP

$pacote="030384602#";
$ip="192.168.2.211";
//Envia protocolo para o Combox
$socket = array(
     'ip'   => "udp://$ip",
     'port' => "2015"
    );

$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $pacote\n";
fwrite($sock,$pacote);
fclose($sock);


?>
