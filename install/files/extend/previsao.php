#!/usr/bin/php5 -q


<?php
foreach($argv as $value)
{
  $getCti = $value;
}


$exec=system("/home/extend/./find '/home/extend/exec/cidades' '$value' 1 1 0");
$v=explode(";",trim($exec));


$url = 'http://servicos.cptec.inpe.br/XML/cidade/'.$v[0].'/previsao.xml'; 
$result = file_get_contents($url);

$xml=simplexml_load_string($result);

$previsao=$xml->previsao->tempo;

$exec=system("/home/extend/./find '/home/extend/exec/previsao_cod' '$previsao' 1 1 0");
$v=explode(";",trim($exec));


$previsao=$v[0];
$cidade=$xml->nome;
$minima=$xml->previsao->minima;
$maxima=$xml->previsao->maxima;

if ($xml->nome=="null"){
 print_r("0");

}
else

print_r("$xml->nome. com a mínima de $minima e máxima de $maxima graus. $previsao ");


?>

