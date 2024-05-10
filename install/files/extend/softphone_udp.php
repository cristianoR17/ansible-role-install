#!/usr/bin/php5 -q
<?PHP

$pacote="1003:1111#";
//$pacote="1002:*76#";
//$pacote="1000:400111@400111#";
//$pacote="1001:#";
$ip="192.168.2.223";
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
