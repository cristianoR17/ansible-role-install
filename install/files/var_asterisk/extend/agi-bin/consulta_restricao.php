<?php

/* Campos Esperados na resposta:
   
      resposta
      descricao-reposta
      chassi
      placa
      renavam
      reserva-dominio
      bloqueio-administrativo
*/
    define("COM_RESTRICAO_RESERVA_DOMINIO",              3221);
    define("COM_RESTRICAO_BLOQUEIO_ADMINISTRATIVO",      3222);
    define("SEM_RESTRICOES",                             3220);
    define("COM_RESTRICAO_RESERVA_E_BLOQUEIO",           3223);

    require('nusoap.php');
    require('xml.functions.php');

    $msg=$_POST["renavam"];
    if ($_POST["renavam"] == "") {
       $descricaoResposta = "FALHA MF DETRAN";
    }
    else{
        $wcClient  = new soapclient('http://200.252.150.7/UraOS/Ura?WSDL','wsdl');
        $Response = $wcClient->call('consultaRestricaoBloqueioVeiculo', array($msg));
        if ($wcClient->getError()) {
          $descricaoResposta = "FALHA MF DETRAN";
        }
        else {
            $xResponse = xmlize($Response);
            $dados = $xResponse["saida"]["#"];

            if ($dados["descricao-reposta"]['0']['#']=="")
              $descricaoResposta = "FALHA MF DETRAN";
            else
              $descricaoResposta = $dados["descricao-reposta"]['0']['#'];

            $dados = $dados["dados"]['0']["#"];
            $reserva_dominio=($dados["reserva-dominio"]['0']['#']);
            $bloqueio_administrativo=($dados["bloqueio-administrativo"]['0']['#']);
           
            if($reserva_dominio>0 AND $bloqueio_administrativo==0){
                $numMsg = COM_RESTRICAO_RESERVA_DOMINIO; 
            }else{
                if($reserva_dominio=0 AND $bloqueio_administrativo>0){
                    $numMsg = COM_RESTRICAO_BLOQUEIO_ADMINISTRATIVO; 
                }else{
                    if($reserva_dominio>0 AND $bloqueio_administrativo>0){
                        $numMsg = COM_RESTRICAO_RESERVA_E_BLOQUEIO; 
                    }else{
                        $numMsg = SEM_RESTRICOES; 
                    }
                }
            }
        }
    }

    if ($descricaoResposta=="Consulta Efetuada" ) {
      echo "<table border=\"1\"><tr>";
      echo "<td>".$numMsg."</td>";
      echo "<td>".$descricaoResposta."</td>";
      echo "</tr></table>";
    } else {
      echo "<table border=\"1\"><tr>";
      echo "<td>0</td>";
      echo "<td>".$descricaoResposta."</td>";
      echo "</tr></table>";
    }
?>

