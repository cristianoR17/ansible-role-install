<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

foreach($argv as $value){
  $id_user = $value;
}

$port=$argv[1];
$port_http=$argv[2];

echo "local port:".$port."\n";
echo "port_http: 0.0.0.0:".$port_http."\n";


$cmd="echo \"<html><head></head><body><div id=\"root\"></div><script> var host = 'ws://10.49.216.15:$port/websockets.php'; var socket = new WebSocket(host);socket.onmessage = function(e) {document.getElementById('root').innerHTML = decodeURIComponent(e.data);}</script></body></html>\" > /home/extend/tmp/websocket_agent$port_http.html";
system($cmd);

$cmd="pgrep -f websocket_agent$port_http.html | xargs -n 1 -i kill -9 {}";
system($cmd);

$cmd=("php -S 0.0.0.0:$port_http /home/extend/tmp/websocket_agent$port_http.html");
echo $cmd."\n";
//system($cmd);

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

function encode($text)
        {
                $b = 129; // FIN + text frame
                $len = strlen($text);
                if ($len < 126) {
                        return pack('CC', $b, $len) . $text;
                } elseif ($len < 65536) {
                        return pack('CCn', $b, 126, $len) . $text;
                } else {
                        return pack('CCNN', $b, 127, 0, $len) . $text;
                }
        }


function unmask($payload) {
        $length = ord($payload[1]) & 127;

        if($length == 126) {
                $masks = substr($payload, 4, 4);
                $data = substr($payload, 8);
        }
        elseif($length == 127) {
                $masks = substr($payload, 10, 4);
                $data = substr($payload, 14);
        }
        else {
                $masks = substr($payload, 2, 4);
                $data = substr($payload, 6);
        }

        $text = '';
for ($i = 0; $i < strlen($data); ++$i) {
                $text .= $data[$i] ^ $masks[$i%4];
        }
        return $text;
}

$address = '0.0.0.0';
// Create WebSocket.
$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($server, $address, $port);
socket_listen($server);
//socket_set_nonblock($server);
socket_set_option($server, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 30, 'usec' => 0));
$now=time(NULL);
//while(true)
//{
$client = socket_accept($server);
// if(($client = socket_accept($server))== true)
//   break;
// if ((time(NULL)-$now)>10){
//   socket_close($server);
//   $cmd="pgrep -f websocket_agent$port_http.html | xargs -n 1 -i kill -9 {}";
//   system($cmd);
//   exit;
// }
//echo "try...\n";

//usleep(100000);
//}

// Send WebSocket handshake headers.
$request = socket_read($client, 5000);

preg_match('#Sec-WebSocket-Key: (.*)\r\n#', $request, $matches);
$key = base64_encode(pack(
    'H*',
    sha1($matches[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
));
$headers = "HTTP/1.1 101 Switching Protocols\r\n";
$headers .= "Upgrade: websocket\r\n";
$headers .= "Connection: Upgrade\r\n";
$headers .= "Sec-WebSocket-Version: 13\r\n";
$headers .= "Sec-WebSocket-Accept: $key\r\n\r\n";
socket_write($client, $headers, strlen($headers));
$err=0;

while (true) {



$arquivo = fopen ('/dev/shm/ramais/status_ramal', 'r');
// Lê o conteúdo do arquivo 
$res="NOK";
$j=0;
$a="";
while(!feof($arquivo))
{
 //Mostra uma linha do arquivo
 $linha = fgets($arquivo, 1024);
 $linha=str_replace("\n", "", $linha);
 $linha=$linha.",end,";
 //echo "$linha\n";
 //echo substr_count($linha,",")."\n";

 if (substr($linha,0,3)=="EOF"){
  $res="OK";
  break;
 }


 if ((substr_count($linha,","))<30) 
  break;


 $v=explode(",",$linha);
 if ($v[30]=="")
  $v[30]="0";


 if (substr($linha,0,3)!="EOF")
  $a=$a.'"data'.$j.'":{"queue":"'.$v[0].'","exten":"'.$v[1].'","status":"'.$v[2].'","pause":"'.$v[3].'","tm_status":"'.$v[4].'","tm_last_call":"'.$v[5].'","number":"'.$v[6].'","ip":"'.$v[7].'","ms":"'.$v[8].'","member_name":"'.$v[9].'","hr_exten":"'.$v[10].'","uniqueid":"'.$v[11].'","cti":"'.$v[12].'","server":"'.$v[13].'","port":"'.$v[14].'","team":"'.$v[15].'","unit":"'.$v[16].'","id_queue":"'.$v[17].'","nu_skill":"'.$v[18].'","id_dialer":"'.$v[19].'","agent_name":"'.$v[20].'","rec":"'.$v[21].'","computer":"'.$v[22].'","softphone":"'.$v[23].'","cp_extend":"'.$v[24].'","cp_version":"'.$v[25].'","id_event":"'.$v[26].'","flag_pause":"'.$v[27].'","mic":"'.$v[28].'","spk":"'.$v[29].'","site":"'.$v[30].'"},';
 $j++;

}
fclose($arquivo);
$a=substr($a,0,strlen($a)-1);
//$a=time(NULL)." ".$j;
$a=('{'.$a.'}');
if ($res=="OK"){
// echo $a."\n";


$response= encode($a);
if (socket_write($client, $response,strlen($response))==false)
 $err++;
//echo $err."\n";
//$request = socket_read($client, 20);
//echo "#######################################\n";
//print_r(unmask($request));
//echo "#######################################\n";
if ($err>5){
 $cmd="pgrep -f websocket_agent$port_http.html | xargs -n 1 -i kill -9 {}";
 system($cmd);
 break;
}


}
sleep(1);

}
?>
