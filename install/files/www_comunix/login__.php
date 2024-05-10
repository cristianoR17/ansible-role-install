#!/usr/bin/php7.3 -q
<?php
#4000:400400:172.17.92.2:49156

foreach($argv as $value){
  $data = $value;
}

$share0="192.168.1.24";
$share1="192.168.1.25";


$v = explode(":",$data);
$ramal=$v[0];
$agente=$v[1];
$ip=$v[2];
//$porta=$v[3];
$porta="0";
$lastcall_old=0;
$servico_now="free";
$clid="free";
$status=1;
$pause=0;
$uniqueid="free";
$cti="free";
$servidor="C";
$tm_status=time();
$nm_queue_now="free";
$tm_login=0;
$first_login=0;
$last_ms=time();
$delay=0;
if (($ramal=="") || ($agente=="") || ($ip==""))
 exit;

system("php /home/extend/allow_ip $ip");
// rm /dev/shm/${CHANNEL:4:4}_pause
//
$cmd='rm /dev/shm/'.$ramal.'_pause';
system($cmd);
$cmd="asterisk -rx 'core show channels' |grep $ramal |grep AppDial |cut -d' ' -f1 | xargs -n 1 -i asterisk -rx 'hangup request {}'";
system($cmd);

for ($i=0;$i<=5;$i++){
if (file_exists("/home/extend/tmp/tmp_tr_realtime_vw_servico")){
 $j=0;
 $i=6;
 $arquivo = fopen ("/home/extend/tmp/tmp_tr_realtime_vw_servico", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $v = explode(";",$linha);
    $id_queue[$j]=$v[0];
    $cd_queue[$j]=$v[1];
    $nm_queue[$j]=$v[2];
    //echo $nm_queue."\n";
    $j++;
 }
 fclose($arquivo);
}
}


#servico,ramal,status,pausa,tm status,last call,clid,ip,latencia,operador,timestamp,uniqueid,cti,servidor,porta,equipe,unidade,servico,skill,campanha,operador nome,gravando,computador,softphone,cp_extend,cp_extend_version,id_avento,flag_pausa,flag_mic,flag_skp,cd_site,tm_statusd
//IP: 127.0.0.1,Porta: (9995), CMD: free,4000,1,0,12,1585006240,free,172.17.92.2,OK (129 ms),400400,1585016239,free,free,A,14482,0,1,|3|5|9|,|0|0|0|,0,GERALDO NUNES DE ARRUDA,0,0,0,0,0,15850162214000,0,0,0,1,1585016227
$id_avento=time().$ramal;
$last_ms=0;
$last_agent=0;
$last_agent_=0;
while (1){

if ((time()-$last_ms)>15){
 $tmp=getms($ramal);
 $last_ms=time();
 $v = explode(",",$tmp);
 $ms=$v[0];
 $tm_resp=$v[1];
 $delay=time()-$tm_resp;
 echo "ms =======================> $ms\n";
}

if ((time()-$last_agent)>120){
#400310;16;1;{3,5,167,169,170};{0,0,0,0,0};0;PAULA PINHEIRO;400310;1;;
for ($i=0;$i<=5;$i++){
if (file_exists("/home/extend/tmp/tmp_tr_agente_eventos")){
 $arquivo = fopen ("/home/extend/tmp/tmp_tr_agente_eventos", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 1000);
  $v = explode(";",$linha);
  $agente_=$v[0];
  if ($agente_==$agente){
    $cd_equipe=$v[1];  
    $cd_unidade=$v[2];  
    $cd_servico=$v[3];  
    $nu_skill=$v[4];  
    $cd_camp=$v[5];  
    $agent_name=$v[6];  
    $password=$v[7];  
    $cd_site=$v[8];  
    $ramal_cfg=$v[9];
    $tm_login=$v[10];
    $first_login=$v[11];
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
$cd_servico=str_replace("{", ",", $cd_servico);
$cd_servico=str_replace("}", ",", $cd_servico);
$cd_servico=str_replace(",", "|", $cd_servico);

$nu_skill=str_replace("{", ",", $nu_skill);
$nu_skill=str_replace("}", ",", $nu_skill);
$nu_skill=str_replace(",", "|", $nu_skill);
$last_agent=time();
echo "Get Agentes =================> $agent_name \n";
}

$now=time();
$status_old=$status;
$tmp=getBusy($ramal);
$v = explode(",",$tmp);
$status=$v[0];
$clid=$v[1];
$servico_now=$v[2];
if ($clid=="")
 $clid="free";
$uniqueid=$v[3];
if ($uniqueid=="")
 $uniqueid="free";
$cti=$v[4];
if ($cti=="")
 $cti="free";
$channel_name=$v[5];
if ($channel_name=="")
 $channel_name="free";

$tmp=getBusyA($ramal);
$v = explode(",",$tmp);
$statusa=$v[0];
$clida=$v[1];
$servico_nowa=$v[2];
if ($clida=="")
 $clida="free";
$uniqueida=$v[3];
if ($uniqueida=="")
 $uniqueida="free";
$channel_namea=$v[4];
if ($channel_namea=="")
 $channel_namea="free";

if ($status_old!=$status){
 $tm_status=time();
 if ($status=="9")
   $lastcall_old=time();

 $nm_queue_now="free";
 if ($status==6){
 for ($k=0;$k<=$j;$k++){
        if ($cd_queue[$k]==$servico_now){
         $nm_queue_now=$nm_queue[$k];
         break;

        }

 }

 }
}

if (($status==1) || ($status==9)){
$pause_old=$pause;
$pause=getPause($ramal);
if ($pause_old!=$pause){
 $tm_status=time();

}
}

$lastcall=time()-$lastcall_old;
$tm=time()-$tm_status;
$pacote="$servico_now,$ramal,$status,$pause,$tm,$lastcall,$clid,$ip,$ms,$agente,$now,$uniqueid,$cti,$servidor,$porta,$cd_equipe,$cd_unidade,$cd_servico,$nu_skill,$cd_camp,$agent_name,0,0,0,0,0,$id_avento,0,0,0,1,$tm_status";
//SIP PROXY
$pacote_sip="echo '$cd_equipe,$cd_unidade,$servidor,$id_avento,$cd_camp,$cti,$ramal,' > /dev/shm/$agente";
system($pacote_sip);
//WEbSOCKET
$pacote_web="echo '$agente,$status,$pause,$tm_status,$clid,$cti,$ms,$tm_login,$first_login,$nm_queue_now,$ip,$delay,$channel_name,$clida,$channel_namea' > /dev/shm/comunix/$agente";
system($pacote_web);
//CDR

if (($status=="6") && ($uniqueid!="free")){
 $pacote_uniqueid="echo '$agente,$clid,$cti,' > /dev/shm/comunix/$uniqueid";
 system($pacote_uniqueid);
}

$pacote_cdr="echo '$agente,' > /dev/shm/comunix/$ramal";
system($pacote_cdr);

sendUPD($share0,$share1,9995,$pacote);
if (($status==1) && ($pause=="0")){ 
#172.17.92.5,A,4000,400111,1585055866,10010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000,00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000,
if ((time()-$last_agent_)>120){
 for ($i=0;$i<=5;$i++){
  if (file_exists("/home/extend/tmp/tmp_tr_agente_servico")){
  $arquivo = fopen ("/home/extend/tmp/tmp_tr_agente_servico", 'r');
  while(!feof($arquivo)){
   $linha = fgets($arquivo, 1000);
   $v = explode(",",$linha);
   $agente_=$v[0];
   if ($agente_==$agente){
     $cd_servico_=$v[1];
     $nu_skill_=$v[2];
     break;
   }
   if ($linha!=""){
     $resp=$linha;// echo $linha.'<br />';
   }
  }
  fclose($arquivo);
  break;
 }
 sleep(1);
 } 
 $last_agent_=time();
}

 $nu_skill_=str_replace("\n", "", $nu_skill_);
 $pacote="$ip,$servidor,$ramal,$agente,$lastcall,$cd_servico_,$nu_skill_,";
 sendUPD($share0,$share1,9994,$pacote);
}
sleep (1);
}
function getms($ramal){
$resp="NI,0";
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 50);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);
    $latencia=$v[0];
    $delay=$v[1];
  }
 }
 fclose($arquivo);
}
$delay=str_replace("\n", "", $delay);
return "$latencia,$delay";
}

# /dev/shm/4000_busy

function getBusy($ramal){
$ramal_pos=$ramal;	
$ramal=$ramal.'_busy';
echo "/dev/shm/$ramal\n";
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 105);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);   
    $tm = $v[0];
    $clid = $v[1];
    $servico=$v[2];
    $uniqueid=$v[3]; 
    $cti=$v[4];
    $channel_name=$v[5];
  }
 }
 fclose($arquivo);
}

$channel_name=str_replace("\n", "",$channel_name);

if ((time()-$tm)<2)
 return "6,$clid,$servico,$uniqueid,$cti,$channel_name,";
if ( ((time()-$tm)>=2) && ((time()-$tm)<=15) )
 return "9,free,free,free,free";

$ramal=$ramal_pos.'_pos';
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 50);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);
    $tm=$v[0];
  }
 }
 fclose($arquivo);
}
$tm=str_replace("\n", "", $tm);
if ($tm=="")
 $tm=0;

if  ((time()-$tm)<=6)
 return "5,free,free,free";

return "1,free,free,free,free";
}

function getBusyA($ramal){
$ramal=$ramal.'_busyA';
echo "/dev/shm/$ramal\n";
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 105);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);
    $tm = $v[0];
    $clid = $v[1];
    $servico=$v[2];
    $uniqueid=$v[3];
    $cti=$v[4];
    $channel_name=$v[5];
  }
 }
 fclose($arquivo);
}

$channel_name=str_replace("\n", "",$channel_name);

if ((time()-$tm)<2)
 return "6,$clid,$servico,$uniqueid,$cti,$channel_name,";
if ( ((time()-$tm)>=2) && ((time()-$tm)<=15) )
 return "9,free,free,free,free";


$ramal=$ramal.'_pos';
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 50);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);
    $tm=$v[0];
  }
 }
 fclose($arquivo);
}
$tm=str_replace("\n", "", $tm);
if ($tm=="")
 $tm=0;

if  ((time()-$tm)<=6)
 return "5,free,free,free";



return "1,free,free,free,free";
}


///dev/shm/4000_pause

function getPause($ramal){
$ramal=$ramal.'_pause';
echo "/dev/shm/$ramal\n";
if (file_exists("/dev/shm/$ramal")){
 $arquivo = fopen ("/dev/shm/$ramal", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 15);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
  }
 }
 fclose($arquivo);
} else return "0";

$resp=str_replace("\n", "", $resp);
echo $resp."\n";
return $resp;

}



for ($i=0;$i<=5;$i++){
if (file_exists("/home/extend/tmp/tmp_tr_agente_eventos")){
 $arquivo = fopen ("/home/extend/tmp/tmp_tr_agente_eventos", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 10);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
  }
 }
 fclose($arquivo);
}
}

function sendUPD($share0,$share1,$porta,$pacote){
//Envia protocolo para o Combox
$socket = array(
     'ip'   => "udp://$share0",
     'port' => $porta
    );

$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $pacote\n";
fwrite($sock,$pacote);
fclose($sock);

$socket = array(
     'ip'   => "udp://$share1",
     'port' => $porta
    );

$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $pacote\n";
fwrite($sock,$pacote);
fclose($sock);

}

?>

