#!/usr/bin/php7.3 -q
<?php
#4000:400400:172.17.92.2:49156

foreach($argv as $value){
  $data = $value;
}




$cmd=system("cat /home/extend/comunix.conf | grep 'sipweb='");
$cmd=str_replace(" ","=",$cmd);
$cmd=str_replace(";","=",$cmd);
$v=explode("=",$cmd);
$buf=$v[1];
$servidor=$buf;

$cmd=system("cat /home/extend/comunix.conf | grep 'share_ip='");
$cmd=str_replace(" ","=",$cmd);
$cmd=str_replace(";","=",$cmd);
$v=explode("=",$cmd);
$buf=$v[1];
$share0=$buf;

echo $servidor."\n";
echo $share0."\n";

#$share0="192.168.2.230";
#$share1="192.168.2.230";
//$share0="10.20.1.110";
$share1="127.0.0.1";





$v = explode(":",$data);

if(count($v) < 3){
	die();
}

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
$cti="https://www.uol.com.br";
//$servidor="A";
$tm_status=time();
$nm_queue_now="free";
$last_ms=time();
$nr_pos_now=0;
$tm_first_login=time();
if (($ramal=="") || ($agente=="") || ($ip==""))
 exit;

system("php /home/extend/allow_ip $ip");
//$cmd="rm /dev/shm/".$ramal."_pause";;
//system($cmd);

// rm /dev/shm/${CHANNEL:4:4}_pause
//
//$cmd='rm /dev/shm/'.$ramal.'_pause';
//system($cmd);
//$cmd="asterisk -rx 'core show channels' |grep $ramal |grep AppDial |cut -d' ' -f1 | xargs -n 1 -i asterisk -rx 'hangup request {}'";
//system($cmd);

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
    $nr_pos[$j]=$v[3];
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

$nr_proc=0;
//$nr_proc=system("pgrep -f '/var/www/comunix/websockets_keep.php $agente ' | wc -l");
//echo $nr_proc."\n";
//if ($nr_proc==1)
// exit;

//if ((time()-$last_ms)>15){
// $tmp=getms($ramal);
// $last_ms=time();
	///
	//$v = explode(",",$tmp);
// $ms=$v[0];
// $tm_resp=$v[1];
// $delay=time()-$tm_resp;
// echo "delay =========================================================================> $delay\n";
//}

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
$tmp=getBusy($ramal,$tm_first_login,$nr_pos_now);
$v = explode(",",$tmp);
$status=$v[0];
$clid=$v[1];
$servico_now=$v[2];
if ($clid=="")
 $clid="free";
$uniqueid=$v[3];
if ($uniqueid=="")
 $uniqueid="free";

$clid2=$v[4]??"";
if ($clid2=="")
 $clid2="free";

$cti=$v[5]??"";
if ($cti=="")
 $cti="free";

$inscricao=$v[6]??"";
if ($inscricao=="")
  $inscricao="free";

$protocolo=$v[7]??"";
if ($protocolo=="")
  $protocolo="free";

$cpf=$v[8]??"";
if ($cpf=="")
  $cpf="free";

$showUrlMorpheus=$v[9]??"";
if ($showUrlMorpheus=="")
$showUrlMorpheus="free";

if ($status_old!=$status){
       


 $tm_status=time();
 if ($status=="9")
   $lastcall_old=time();

 $nm_queue_now="free";
 if ($status==6){
 for ($k=0;$k<=$j;$k++){
        if ($cd_queue[$k]==$servico_now){
         $nm_queue_now=$nm_queue[$k];
         $nr_pos_now=$nr_pos[$k];      
	 break;

        }
 }


 //$li=system("cat /var/www/comunix/hist/$agente | wc -l");
 echo "=========================================== li=>$li \n";
 $dt=date("d-m-Y");
 $hr=date("H:i:s"); 
 if ($li==0){
   system("echo '$dt $hr;$clid' > /var/www/comunix/hist/$agente");

 } else {
  if ((int)$li>8){
     //$cmd="ed -s /var/www/comunix/hist/$agente <<< $'-2,d\\nwq'";
     $cmd="tail -n 1 '/var/www/comunix/hist/$agente' | wc -c | xargs -I {} truncate '/var/www/comunix/hist/$agente' -s -{}";
     //system($cmd);	  
     //echo "$cmd \n"; 
  
  } 
  $cmd="sed -i '1s/^/$dt $hr;$clid\\n/' /var/www/comunix/hist/$agente"; 
  //system($cmd);
  $uniq=str_replace('.', '', $uniqueid);
  $uniq="1".$uniq."#";
 } 

  $servico_now_number=substr($servico_now,1);
  if ($servico_now=="")
	  $servico_now="q0000";

//"curl 'http://omnichannel.chat.comunix.tech:81/omnichannel/insert_chat_call.php?number=$clid&channel=$servico_now_number&login=$agente&uniqueid=$uniqueid&cti=$cti'"
  //system("curl 'http://omnichannel.chat.comunix.tech:81/omnichannel/insert_chat_call.php?number=$clid&channel=$servico_now_number&login=$agente&uniqueid=$uniqueid&cti=$cti'"); 
  //sendUPD($ip,"0",9999,$uniq);
 }

 //if ($status==1){
 // sendUPD($ip,"0",9999,"0#");

 //}	 


}

if (($status==1) || ($status==9)){
$pause_old=$pause;
$pause=getPause($ramal);
if ($pause_old!=$pause){
 $tm_status=time();

}
}


$lastcall=time()-$lastcall_old;
$lastcall=time()-get_last_call($ramal);
echo "========================================================================> $lastcall \n";

if (!isset($ms) || (isset($ms) && $ms=="")){
 $ms="0";
}

$delay=0;

if ($servico_now=="")
    $servico_now="free";



$tm=time()-$tm_status;
$pacote="$servico_now,$ramal,$status,$pause,$tm,$lastcall,$clid,$ip,$ms,$agente,$now,$uniqueid,$cti,$servidor,$porta,$cd_equipe,$cd_unidade,$cd_servico,$nu_skill,$cd_camp,$agent_name,0,0,0,0,0,$id_avento,0,0,0,1,$tm_status";
//SIP PROXY
$pacote_sip="echo '$cd_equipe,$cd_unidade,$servidor,$id_avento,$cd_camp,$cti,$uniqueid,' > /dev/shm/$agente";
system($pacote_sip);
//WEbSOCKET
$pacote_web="echo '$agente,$status,$pause,$tm_status,$clid,$cti,$ms,$tm_login,$first_login,$nm_queue_now,$ip,$delay,$clid2,$servico_now,$uniqueid,$cd_camp,$inscricao,$protocolo,$cpf,$showUrlMorpheus,' > /dev/shm/comunix/$agente";
system($pacote_web);
//CDR

////clid,cti,uniqueid,conta
$ramal_full=$ramal.'_full';
$pacote_ramal_full="echo '$clid,$cti,$uniqueid,$agente,' > /dev/shm/comunix/$ramal_full";
system($pacote_ramal_full);


if (($status=="6") && ($uniqueid!="free")){
 $pacote_uniqueid="echo '$agente,' > /dev/shm/comunix/$uniqueid";
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

function getBusy($ramal,$tm_first_login,$pos__){
	$ramal_busy=$ramal.'_busy';
	$ramal_ringing=$ramal.'_ringing';
	$ramal_cti=$ramal.'_cti';
  $ramal_busy_dial=$ramal.'_busy_dial';
  $ramal_inscricao=$ramal.'_INSCRICAO';
  $ramal_protocolo=$ramal.'_PROTOCOLO';
  $ramal_cpf=$ramal.'_CPF';
  $ramal_showUrlMorpheus=$ramal.'_SHOW_URL_MORPHEUS';


  if ((time()-$tm_first_login)<=9)
    return "9,free,free,free";

  if (file_exists("/dev/shm/$ramal_inscricao")){
    $arquivo = fopen ("/dev/shm/$ramal_inscricao", 'r');
    while(!feof($arquivo)){
      $linha = fgets($arquivo, 1000);
      if ($linha!=""){
        $resp=$linha;// echo $linha.'<br />';
        $v = explode(",",$resp);
        $ramal_inscricao=trim($v[0]);
      }
    }
    fclose($arquivo);
    }


  if (file_exists("/dev/shm/$ramal_protocolo")){
    $arquivo = fopen ("/dev/shm/$ramal_protocolo", 'r');
    while(!feof($arquivo)){
      $linha = fgets($arquivo, 1000);
      if ($linha!=""){
        $resp=$linha;// echo $linha.'<br />';
        $v = explode(",",$resp);
        $ramal_protocolo=trim($v[0]);
      }
    }
    fclose($arquivo);
    }

  if (file_exists("/dev/shm/$ramal_cpf")){
    $arquivo = fopen ("/dev/shm/$ramal_cpf", 'r');
    while(!feof($arquivo)){
      $linha = fgets($arquivo, 1000);
      if ($linha!=""){
        $resp=$linha;// echo $linha.'<br />';
        $v = explode(",",$resp);
        $ramal_cpf=trim($v[0]);
      }
    }
    fclose($arquivo);
    }

  if (file_exists("/dev/shm/$ramal_showUrlMorpheus")){
    $arquivo = fopen ("/dev/shm/$ramal_showUrlMorpheus", 'r');
    while(!feof($arquivo)){
      $linha = fgets($arquivo, 1000);
      if ($linha!=""){
        $resp=$linha;// echo $linha.'<br />';
        $v = explode(",",$resp);
        $ramal_showUrlMorpheus=trim($v[0]);
      }
    }
    fclose($arquivo);
    }

	if (file_exists("/dev/shm/$ramal_cti")){
	 $arquivo = fopen ("/dev/shm/$ramal_cti", 'r');
	 while(!feof($arquivo)){
	  $linha = fgets($arquivo, 105);
	  if ($linha!=""){
		$resp=$linha;// echo $linha.'<br />';
		$v = explode(",",$resp);
		$cti=$v[0];
	  }
	 }
	 fclose($arquivo);
	}

	$tm_clid_busy=0;

	if (file_exists("/dev/shm/$ramal_busy")){
	 $arquivo = fopen ("/dev/shm/$ramal_busy", 'r');
	 while(!feof($arquivo)){
	  $linha = fgets($arquivo, 105);
	  if ($linha!=""){
		$resp=$linha;// echo $linha.'<br />';
		$v = explode(",",$resp);
		$tm_clid_busy = $v[0];
		$clid = $v[1];
		$servico=$v[2];
		$uniqueid=$v[3];
	//    $cti=$v[4];
	  }
	 }
	 fclose($arquivo);
	}
	
	$tm_clid = !isset($tm_clid) ? 0 : $tm_clid;

	$xxx=time();
	echo "=======================gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg===================>$xxx  $tm_clid ";
	$uniqueid=str_replace("\n", "",$uniqueid);

	if ((time()-$tm_clid_busy)<=4){
	 system("rm /dev/shm/$ramal_ringing");      
	 return "6,$clid,$servico,$uniqueid,$clid2,$cti,$ramal_inscricao,$ramal_protocolo,$ramal_cpf,$ramal_showUrlMorpheus";


	}


if (file_exists("/dev/shm/$ramal_busy_dial")){
 $arquivo = fopen ("/dev/shm/$ramal_busy_dial", 'r');
 while(!feof($arquivo)){
  $linha = fgets($arquivo, 105);
  if ($linha!=""){
    $resp=$linha;// echo $linha.'<br />';
    $v = explode(",",$resp);
    $tm_clid_busy_ = $v[0];
    $clid = $v[1];
    $servico=$v[2];
    $uniqueid=$v[3];
  }
 }
 fclose($arquivo);
}

$xxx=time();
$tm_clid = !isset($tm_clid) ? 0 : $tm_clid;
echo "=======================gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg===================>$xxx  $tm_clid ";
$uniqueid=str_replace("\n", "",$uniqueid);

if ((time()-$tm_clid_busy_)<=4) {
 system("rm /dev/shm/$ramal_ringing");
 return "6,$clid,$servico,$uniqueid,$clid2,$cti,$ramal_inscricao,$ramal_protocolo,$ramal_cpf,$ramal_showUrlMorpheus";

}







	$tm_clid=0;

	if (file_exists("/dev/shm/$ramal_ringing")){
	 $arquivo = fopen ("/dev/shm/$ramal_ringing", 'r');
	 while(!feof($arquivo)){
	  $linha = fgets($arquivo, 105);
	  if ($linha!=""){
		$resp=$linha;// echo $linha.'<br />';
		$v = explode(",",$resp);
		$tm_clid = $v[0];
		$clid = $v[1];
		$servico=$v[2];
		$uniqueid=$v[3];
		$cti=$v[4];
	  }
	 }
	 fclose($arquivo);
	}

	echo "=======================gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg===================>$xxx  $tm_clid ";
	$uniqueid=str_replace("\n", "",$uniqueid);

	if ((time()-$tm_clid)<=4)
	 return "5,free,free,free";



	//pos-atentimento
	if ( ((time()-$tm_clid_busy)<=$pos__+4) )
	 return "9,free,free,free";

	return "1,free,free,free";
}



function get_last_call($ramal){
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

return $tm;
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

if ($share0!="0"){
$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $share0  $pacote\n";
fwrite($sock,$pacote);
fclose($sock);

$socket = array(
     'ip'   => "udp://$share1",
     'port' => $porta
    );
}

if ($share1!="0"){
$sock = fsockopen($socket["ip"], $socket["port"], $errno, $errstr, 5);
echo "Enviando pacote $share1 $pacote\n";
fwrite($sock,$pacote);
fclose($sock);

}
}

?>

