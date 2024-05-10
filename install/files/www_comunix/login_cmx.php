#!/usr/bin/php7.3 -q
<?php
#4000:400400:172.17.92.2:49156

foreach($argv as $value){
  $data = $value;
}

$share0="192.168.2.230";
$share1="127.0.0.2";


$v = explode(":",$data);
$ramal=$v[0];
$agente=$v[1];
$ip=$v[2];
$porta=$v[3];
$lastcall=time();
$servico_now="free";
$clid="free";
$status=1;
$pause=0;
$uniqueid="free";
$cti="free";
$servidor="B";
$tm_status=time();

if (($ramal=="") || ($agente=="") || ($ip==""))
 exit;

system("php /home/extend/allow_ip $ip");


#servico,ramal,status,pausa,tm status,last call,clid,ip,latencia,operador,timestamp,uniqueid,cti,servidor,porta,equipe,unidade,servico,skill,campanha,operador nome,gravando,computador,softphone,cp_extend,cp_extend_version,id_avento,flag_pausa,flag_mic,flag_skp,cd_site,tm_statusd
//IP: 127.0.0.1,Porta: (9995), CMD: free,4000,1,0,12,1585006240,free,172.17.92.2,OK (129 ms),400400,1585016239,free,free,A,14482,0,1,|3|5|9|,|0|0|0|,0,GERALDO NUNES DE ARRUDA,0,0,0,0,0,15850162214000,0,0,0,1,1585016227
$id_avento=time().$ramal;
$last_ms=0;
$last_agent=0;
$last_agent_=0;
while (1){

if ((time()-$last_ms)>60){
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
 $$uniqueid="free";



if ($status_old!=$status){
 $tm_status=time();
 if ($status=="9")
   $lastcall=0;
}

$pause_old=$pause;
$pause=getPause($ramal);
if ($pause_old!=$pause){
 $tm_status=time();

}


$tm=time()-$tm_status;
$pacote="$servico_now,$ramal,$status,$pause,$tm,$lastcall,$clid,$ip,$ms,$agente,$now,$uniqueid,$cti,$servidor,$porta,$cd_equipe,$cd_unidade,$cd_servico,$nu_skill,$cd_camp,$agent_name,0,0,0,0,0,$id_avento,0,0,0,1,$tm_status";
//SIP PROXY
$pacote_sip="echo '$cd_equipe,$cd_unidade,B,$id_avento,$cd_camp,$cti,' > /dev/shm/$agente";
system($pacote_sip);
//WEbSOCKET
$pacote_web="echo '$agente,$status,$pause,$tm_status,$clid,$cti,$ms,' > /dev/shm/comunix/$agente";
system($pacote_web);
//CDR

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
 $pacote="$ip,B,$ramal,$agente,$lastcall,$cd_servico_,$nu_skill_,";
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
  }
 }
 fclose($arquivo);
}

$uniqueid=str_replace("\n", "",$uniqueid);

if ((time()-$tm)<2)
 return "6,$clid,$servico,$uniqueid";
if ( ((time()-$tm)>=2) && ((time()-$tm)<10) )
 return "9,free,free,free";

return "1,free,free,free";
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

