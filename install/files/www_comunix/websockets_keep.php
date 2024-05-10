<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();


foreach($argv as $value){
  $id_user = $value;
}

$id_user=$argv[1];
$port=$argv[2];
$port_http=$argv[3];
$data=$id_user;

echo "id_user:".$id_user."\n";
echo "local port:".$port."\n";
echo "port_http: 0.0.0.0:".$port_http."\n";

$keyFile    = '/home/certificado/webrtc.chat.comunix.tech.key';
$chainFile  = '/home/certificado/webrtc.chat.comunix.tech.intermediate.csr';
$passphrase = '';
$address    = "0.0.0.0:$port";


$cmd="echo \"<html><head></head><body><div id=\"root\"></div><script> var host = 'ws://covid19goiania.chat.comunix.tech:$port/websockets.php'; var socket = new WebSocket(host);socket.onmessage = function(e) {document.getElementById('root').innerHTML = decodeURIComponent(e.data);}</script></body></html>\" > /var/www/covid19goiania/websocket/websocketskeep$port_http.html";
//system($cmd);

//$cmd="pgrep -f websocketskeep$port_http.html | xargs -n 1 -i kill -9 {}";
//system($cmd);

$cmd=("php -S 0.0.0.0:$port_http /var/www/covid19goiania/websocket/websocketskeep$port_http.html&");
//echo $cmd."\n";
//system($cmd);
function decode($text) {
    $length = ord($text[1]) & 127;
    if($length == 126) {
        $masks = substr($text, 4, 4);
        $data = substr($text, 8);
    }
    elseif($length == 127) {
        $masks = substr($text, 10, 4);
        $data = substr($text, 14);
    }
    else {
        $masks = substr($text, 2, 4);
        $data = substr($text, 6);
    }
    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i%4];
    }
    return $text;
}


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


$context = stream_context_create([
	'ssl'=>[
		'local_cert'    => $chainFile
		, 'local_pk'    => $keyFile
		, 'passphrase'  => $passphrase
		, 'verify_peer' => FALSE
	]
]);

$socket = stream_socket_server(
	$address
	, $errorNumber
	, $errorString
	, STREAM_SERVER_BIND|STREAM_SERVER_LISTEN
	, $context
);

// Set an error handler

$errorHandler = set_error_handler(
	function($errCode, $message, $file, $line, $context) use(&$errorHandler) {
		if(substr($message, -9) == 'timed out')
		{
			return;
		}

		fwrite(STDERR, sprintf(
			"[%d] '%s' in %s:%d\n"
			, $errCode
			, $message
			, $file
			, $line
		));

		if($errorHandler)
		{
			$errorHandler($errCode, $message, $file, $line, $context);
		}
	}
);

$clients = [];
$err=0;
$now=time();
while(TRUE)
{
	// Listen for connections
	if($newStream = stream_socket_accept($socket, 0))
	{
		stream_set_blocking($newStream, TRUE);

		stream_socket_enable_crypto(
			$newStream
			, TRUE
			, STREAM_CRYPTO_METHOD_SSLv23_SERVER
		);

		$incomingHeaders = fread($newStream, 2**16);

		if(preg_match('#^Sec-WebSocket-Key: (\S+)#mi', $incomingHeaders, $match))
		{
			stream_set_blocking($newStream, FALSE);

			fwrite(
				$newStream
				, "HTTP/1.1 101 Switching Protocols\r\n"
					. "Upgrade: websocket\r\n"
					. "Connection: Upgrade\r\n"
					. "Sec-WebSocket-Accept: " . base64_encode(
						sha1(
							$match[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'
							, TRUE
						)
					)
					. "\r\n\r\n"
			);
                 }   
//			fwrite(STDERR, 'Accepted client!');
$old_tm_status=0;		
$old_ms=0;
$error_in=0;
$error=0;
$count=0;
$delay=0;
$exec=0;
$logout=0;
$cmd="rm /dev/shm/comunix/logout_$id_user";
system($cmd);


while (1){

$now=time();

if (file_exists("/dev/shm/comunix/$id_user")){
 $arquivo = fopen ("/dev/shm/comunix/$id_user", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $linha=str_replace("\n", "", $linha);
  echo $linha."\n";
  $v = explode(",",$linha);
  $agente=$v[0];
  $status=$v[1];
  $pause=$v[2];
  $tm_status=$v[3];
  $clid=$v[4];
  $cti=$v[5];
  $ms=$v[6];
  $tm_login=$v[7];
  $firt_login=$v[8];
  $nm_servico=$v[9];
  $ip_client=$v[10];
  $delay=$v[11];
  $clid2=$v[12];
  $servico_now=$v[13];
  $uniqueid=$v[14];
  $cd_camp=$v[15];
  $inscricao=$v[16];
  $protocolo=$v[17];
  $cpf=$v[18];
  $showUrlMorpheus=$v[19];

  break;
 }
 fclose($arquivo);
}

if (substr($servico_now,0,1)=="q")
 $servico_now=substr($servico_now,1);

if ($count==0)
 $exec=0;


if ($delay>60)
 $count++;
else{
 $count=0;
 $exec=0;
}

if ($count>5){
 $exec=1;
 $count=0;
}

$exec=0;

$total_seconds = $tm_login+(time()-$tm_status);
$seconds = intval($total_seconds%60);
if (strlen($seconds)==1)
 $seconds="0$seconds";
$total_minutes = intval($total_seconds/60);
$minutes = $total_minutes%60;
if (strlen($minutes)==1)
 $minutes="0$minutes";

$hours = intval($total_minutes/60);
if (strlen($hours)==1)
 $hours="0$hours";

$tm_login="$hours:$minutes:$seconds";
$firt_login=substr($firt_login,11);
//$pacote_web="echo '$agente,$status,$pause,$tm_status,$clid,$cti,$ms,' > /dev/shm/comunix/$agente";

$tm=time()-$tm_status;
$total_seconds = $tm;
$seconds = intval($total_seconds%60);
if (strlen($seconds)==1)
 $seconds="0$seconds";
$total_minutes = intval($total_seconds/60);
$minutes = $total_minutes%60;
if (strlen($minutes)==1)
 $minutes="0$minutes";

$hours = intval($total_minutes/60);
if (strlen($hours)==1)
 $hours="0$hours";

$tm="$hours:$minutes:$seconds";
$logout=0;

// /dev/shm/comunix/logout_
if (file_exists("/dev/shm/comunix/logout_$id_user")){
 $logout=1;
 system("rm /dev/shm/comunix/logout_$id_user");

}

$a='"status0":{"user":"'.$agente.'","status":"'.$status.'","pause":"'.$pause.'","tm_status":"'.$tm.'","clid":"'.$clid.'","cti":"'.$cti.'","ms":"'.$ms.'","tm_login":"'.$tm_login.'","firt_login":"'.$firt_login.'","nm_servico":"'.$nm_servico.'","delay":"'.$exec.'","uniqueid":"'.$uniqueid.'","logout":"'.$logout.'","servico_now":"'.$servico_now.'","uniqueid2":"'.$uniqueid2.'","cd_campanha":"'.$cd_camp.'","inscricao":"'.$inscricao.'","protocolo":"'.$protocolo.'","cpf":"'.$cpf.'","showUrlMorpheus":"'.$showUrlMorpheus.'"}';
//$a='"status0":{"user":"'.$agente.'","status":"'.$status.'","pause":"'.$pause.'","tm_status":"'.$tm.'","clid":"'.$clid.'","cti":"'.$cti.'","ms":"'.$ms.'","tm_login":"'.$tm_login.'","firt_login":"'.$firt_login.'"}';
$a=('{'.$a.'}');
echo $a."\n";
$response= encode($a);

$incoming="";
stream_set_timeout($newStream, 0,500);
$incoming = fread($newStream,1024);
$status = socket_get_status($newStream);
if ($incoming!="")
  $incoming=trim(decode($incoming));
echo "$incoming \n";

if ($incoming=="")
 $error_in++;
else
 $error_in=0;

echo "error_in:$error_in.\n";


if (($error_in>15) || ($incoming=="END")){
 $cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f '$id_user' | xargs -n 1 -i kill -9 {}";       
 system($cmd);
 $cmd="php /home/extend/denny_ip $ip_client";
 system($cmd);
 exit;

}	

//echo "error_in: $error_in \n";

if ($status["eof"]==1){
 $error++;
 if ($error>15){
  $cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f ':$id_user:' | xargs -n 1 -i kill -9 {}";
  system($cmd);
  $cmd="php /home/extend/denny_ip $ip_client";
  system($cmd);
  exit;
  
 }  
   echo "error:$error.\n";
}else
 $error=0;

//print_r($status);
//if (($old_tm_status!=$tm_status) || ($old_ms!=$ms)){
 fwrite($newStream,$response);
 $old_tm_status=$tm_status;
 $old_ms=$ms;
//}

sleep(1);

if (($logout==1)){
 $cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f '$id_user' | xargs -n 1 -i kill -9 {}";
 system($cmd);
 $cmd="php /home/extend/denny_ip $ip_client";
 system($cmd);
 exit;
}


}



		//	$clients[] = $newStream;
		}
		else
		{
		//	stream_socket_shutdown($newStream, STREAM_SHUT_RDWR);
                 //echo (time(NULL)-$now)."\n";

                  if ((time()-$now)>30){
       
                     $cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f ':$id_user:' | xargs -n 1 -i kill -9 {}";
                     system($cmd);
                      // $cmd="pgrep -f '/var/www/comunix/login.php' |pgrep -f ':$id_user:' | xargs -n 1 -i kill -9 {}";
                      // system($cmd);

//socket_close($socket);
                    //$cmd="pgrep -f websocketsmsg$port_http.html | xargs -n 1 -i kill -9 {}";
                   // system($cmd);
                   // echo $cmd."\n";
                       //exit; 
                   }  

                   usleep(100000);
		}
	//}

}

?>
