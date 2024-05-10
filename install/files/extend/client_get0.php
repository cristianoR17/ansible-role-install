#!/usr/bin/php5 -q


<?php
foreach($argv as $value)
{
  $getCti = $value;
}

$value=preg_replace(array('/\?/', '/\./', '/\!/', '/\,/', '/\;/'), "", $value);

//$vl=explode("|",$value);

//echo $vl[0]."\n";
//echo $vl[1]."\n";
//exit;
//$value=$vl[0];

$resp=system("/home/extend/./sophia0 '$value'");

$v=explode(";",$resp);
$resp=$v[0];

if ($v[1]!="0")
 $value=$value.' '.$v[1];

if (strcmp("dia_atual",$resp)==0){

$diasemana = array('Domingo', 'Segunda feira', 'Terça feira', 'Quarta feira', 'Quinta feira', 'Sexta feira', 'Sabado');

$data = date('Y-m-d');

$diasemana_numero = date('w', strtotime($data));

$resp="Hoje e ".$diasemana[$diasemana_numero]."";

if ($resp<>"")
  echo (trim($resp));

echo "\n";

exit;

}

//palavrao

if (strcmp("palavra",$resp)==0){


$resp="Não respondo perguntas com palavras de baixo calão";

if ($resp<>"")
  print_r(trim($resp));

exit;

}




//wget 'http://192.168.0.24/sendsmsasr.php?telefone=61999685009&msg=teste'

if (strcmp("dolar_sms",$resp)==0){

$value=preg_replace("/[^0-9]/", "", $value);

print_r("SMS enviado. Para $value ");

$vl=system("/home/extend/./dolar_sms.php");
system("wget 'http://192.168.0.24/sendsmsasr.php?telefone=$value&msg=$vl'");


exit;

}

if (strcmp("boleto_sms",$resp)==0){

$value=preg_replace("/[^0-9]/", "", $value);

$vl="34191.23454 61234.590026 31234.550007 6 70000015300150";
print_r("SMS enviado. Para $value => $vl");
system("wget 'http://192.168.0.24/sendsmsasr.php?telefone=$value&msg=$vl'");


exit;

}


//numero

if (strcmp("numero",$resp)==0){


$value=str_replace("zero", "0", $value);
$value=str_replace("um", "1", $value);
$value=str_replace("dois", "2", $value);
$value=str_replace("tres", "3", $value);
$value=str_replace("quatro", "4", $value);
$value=str_replace("cinco", "5", $value);
$value=str_replace("seis", "6", $value);
$value=str_replace("sete", "7", $value);
$value=str_replace("oito", "8", $value);
$value=str_replace("nove", "9", $value);
$value=str_replace("dez", "10", $value);

$value=preg_replace("/[^0-9]/", "", $value);



if ($value<>"")
  print_r("O número falado foi.;$value");

exit;

}



if (strcmp("dolar",$resp)==0){


$vl=system("/home/extend/./dolar.php");

//if ($vl<>"0")

//  print_r($vl);



exit;

}




//nome_mes
if (strcmp("nome_mes",$resp)==0){

$mons = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");

$date = getdate();
$month = $date['mon'];

$month_name = $mons[$month];

$resp="Estamos em ".$mons[$month];

if ($resp<>"")
  print_r(trim($resp));

exit(0);

}

//dia_mes


if (strcmp("dia_mes",$resp)==0){


$resp="Hoje é dia ".(date('d')*1);

if ($resp<>"")
  print_r((trim($resp)));

exit(0);

}

if (strcmp("idade",$resp)==0){

print_r($value."\n");
//Neymar:26:05/02/1992:Neymar:
$resp=system("/home/extend/./find '/home/extend/exec/dados' '$value' '1' '3'");
$v=explode(";",$resp);

if ($v[0]=="0"){
  print_r("não sei");
  exit(0);

}

if ($resp<>"0"){
  $v=explode(";",trim($resp));
   print_r("$v[1] tem $v[0] anos");
   exit(0);

}

exit(0);
}

//dt_nasciemnto

if (strcmp("dt_nascimento",$resp)==0){

print_r($value."\n");

$resp=system("/home/extend/./find '/home/extend/exec/dados' '$value' '2' '3' '1'");
$v=explode(";",$resp);

if ($v[0]=="0"){
  print_r("não sei");
  exit(0);

}

if ($resp<>""){
  $v=explode(";",trim($resp));
   print_r("$v[1] nasceu tem $v[0]");
   exit(0);

}


}


//capital

if (strcmp("vl_capital",$resp)==0){

print_r($value."\n");

$resp=system("/home/extend/./find '/home/extend/exec/capital' '$value' '1' '2' '2'");
$v=explode(";",$resp);

if ($v[0]=="0"){
  print_r("não sei");
  exit(0);

}

if ($resp<>""){
  $v=explode(";",trim($resp));
   print_r("A capital do $v[0] é a cidade de $v[1]");
   exit(0);

}


}

//Moeda

if (strcmp("vl_moeda",$resp)==0){

print_r($value."\n");

$resp=system("/home/extend/./find '/home/extend/exec/capital' '$value' '1' '3'");
$v=explode(";",$resp);

if ($v[0]=="0"){
  print_r("não sei");
  exit(0);

}

if ($resp<>""){
  $v=explode(";",trim($resp));
   print_r("A moeda do $v[0] é $v[1]");
   exit(0);

}


}

if (strcmp("telefone",$resp)==0){

print_r($value."\n");

$value=preg_replace("/[^0-9]/", "", $value);

$len=strlen($value);
if ($len<8){
 print_r("0"); 

}
if ($resp<>""){
   print_r("$value");


}

exit(0);

}


//
///home/extend/./find '/home/extend/exec/cpf' 'paulo' '1' '1'
if (strcmp("cpf",$resp)==0){

print_r($value."\n");

$value=preg_replace("/[^0-9]/", "", $value);

$ckeck=validaCPF($value);
if ($ckeck!=1){
 print_r("Esse número de cpf é invalido");
 exit(0);
}

$resp=system("/home/extend/./find '/home/extend/exec/cpf' '$value' '1' '2' 1");

if ($resp=="0"){

 print_r("CPF não encontrado");
 exit(0);

}
if ($resp<>""){
  $v=explode(";",trim($resp));
   print_r("$v[1]");


}

exit(0);

}

//./find '/home/extend/exec/lista_nomes' 'benefranklin' '0' '0'
if (strcmp("nome",$resp)==0){

$resp=system("/home/extend/./find '/home/extend/exec/lista_nomes' '$value' '0' '0' 0");

if ($resp<>"0"){
  $v=explode(";",trim($resp));
   print_r("Tudo bem. $v[0].");
   exit(0);

}

print_r("Tudo bem.");
exit(0);

}
//previsao
if (strcmp("previsao",$resp)==0){

$resp=system("/home/extend/./previsao.php '$value' ");

if ($resp<>"0"){
  $v=explode(";",trim($resp));
   print_r("$v[0]");
  exit(0);

}

print_r("Previsão não identificada");
exit(0);

}






if (strcmp("cadastrar",$resp)==0){

$nome=system("/home/extend/./find '/home/extend/exec/lista_nomes' '$value' '0' '0' '0'");

if ($nome!="0"){
$v=explode(";",trim($nome));

$value=preg_replace("/[^0-9]/", "", $value);

$ckeck=validaCPF($value);
if ($ckeck!=1){
 print_r("Esse número de CPF não existe");
 exit(0);
}

system("echo '$value:$v[0]:' >> /home/extend/exec/cpf");

print_r("CPF gravado em minha memoria.");


}

exit(0);

}





if (strcmp("saudacao",$resp)==0){
$hr = date(" H ");
if($hr >= 12 && $hr<18) {
$resp = "Boa tarde!";}
else if ($hr >= 0 && $hr <12 ){
$resp = "Bom dia!";}
else {
$resp = "Boa noite!";}
}




if (strcmp("conta",$resp)==0){
$str=$value;
$str=str_replace("dividido", ":", $str);
$str=str_replace("mais", "+", $str);
$str=str_replace("menos", "-", $str);
$str=str_replace("vezes", "x", $str);
$str=str_replace("*", "x", $str);
$str=str_replace("mil", "000", $str);

$str=str_replace(" ", "", $str);

print_r($str."\n");

$try=preg_replace("/[^+ x \-\ \/\ :]/", "", $str);
$try=trim($try);
if ($try!=""){


$str=str_replace("zero", "0", $str);
$str=str_replace("um", "1", $str);
$str=str_replace("dois", "2", $str);
$str=str_replace("tres", "3", $str);
$str=str_replace("quatro", "4", $str);
$str=str_replace("cinco", "5", $str);
$str=str_replace("seis", "6", $str);
$str=str_replace("sete", "7", $str);
$str=str_replace("oito", "8", $str);
$str=str_replace("nove", "9", $str);
$str=str_replace("dez", "10", $str);

$resp="";
$str=preg_replace("/[^0-9 + x \-\ \/\ :]/", "", $str);
$str=str_replace(":", "/", $str);
$str=trim($str);
$txt=$str;
//soma
$sinal=stripos($str,"+");
if ($sinal>0){
 $a=substr($str,0,$sinal);
 $b=substr($str,$sinal+1);
 $txt="$a mais $b";
 $resp=$a+$b;
}
//divisao
$sinal=stripos($str,"/");
if ($sinal>0){
 $a=substr($str,0,$sinal);
 $b=substr($str,$sinal+1);
 $resp=$a/$b;
}
//Multuplicao
$sinal=stripos($str,"x");
if ($sinal>0){
 $a=substr($str,0,$sinal);
 $b=trim(substr($str,$sinal+1));
 $resp=$a*$b;
}
//subtrcao
$sinal=stripos($str,"-");
if ($sinal>0){
 $a=substr($str,0,$sinal);
 $b=substr($str,$sinal+1);
 $resp=$a-$b;
}
print_r($resp."\n");
exit(0);

}

print_r("Não entendi");
exit(0);



}


if ($resp<>"")
  print_r(trim($resp));



function validaCPF($cpf = null) {
 
    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }
 
    // Elimina possivel mascara
    $cpf = ereg_replace('[^0-9]', '', $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
     
    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) != 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
        return false;
     // Calcula os digitos verificadores para verificar se o
     // CPF é válido
     } else {   
         
        for ($t = 9; $t < 11; $t++) {
             
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}


?>

