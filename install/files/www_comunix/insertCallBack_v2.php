#!/usr/bin/php5 -q
<?php
//require('/var/lib/asterisk/agi-bin/conexao.php');
ob_implicit_flush(true);
set_time_limit(6);
error_reporting(0); 

/*
$clid_agendamento = '61992230443';
$clid_agendamento = '61996867726';
$id = '001575491146.203';
$campanha = '17';
$nm_menu = 'CALLBACK0800';
$nm_opcao = 'CALLBACK0800';
$clid = '61992230443';
$clid = '61996867726';
*/

$clid_agendamento = trim($argv[1]);
$id = trim($argv[2]);
$campanha = trim($argv[3]);
$nm_menu = trim($argv[4]);
$nm_opcao = trim($argv[5]);
$clid =  trim($argv[6]);


$data_atual = date('Y/m/d H:i:s');
$data_unix = trim(strtotime($data_atual));

#$lista=explode("=",trim(shell_exec("cat /home/extend/comunix.conf |grep -E \"ivr=\"|awk -F \";\" '{print $1}'|tr '\n' ';'|cut -d\";\" -f1")));
$ura = "";#$lista[""];

//print_r("$campanha-$clid-$clid_agendamento-$ura$id-$data_atual-$nm_menu$nm_opcao-$data_atual-$nm_menu$nm_opcao");

$content = http_build_query(array (
        'parCdCampanha' => $campanha,
        'parNuTelefone' => $clid,
        'parNuTelefoneRegistro' => $clid_agendamento,
        'parUniqueid' => "$id",
        'par_ds_complemento' => "Data chamada: $data_atual||Ultimo Acesso:$nm_menu/$nm_opcao",
        'par_js_segmentacao' => "{\"data_chamada\": \"$data_atual\", \"ultimo_acesso\": \"$nm_menu/$nm_opcao\"}",

    ));
$context = stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'content' => $content,
    )
));

$result = file_get_contents('http://192.168.0.25:8085/api/v2/callback/insert', null, $context);

print_r($result);


//==============================================================================
fclose($in);
fclose($stdlog);
exit;



?>


