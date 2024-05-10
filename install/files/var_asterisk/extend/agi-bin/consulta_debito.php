<?php
/* Campos recebidos na resposta:
      resposta
      descricao-reposta
      chassi
      licenciamento
      multa
      seguro
      taxa
      ipva-vencer
      ipva-vencido

      $dados             = $xResponse["saida"]["#"];
      $resposta          =  $dados["resposta"]['0']['#'];
      $descricao-reposta = $dados["descricao-reposta"]['0']['#'];

      $dados         = $dados["dados"]['0']["#"];
      $chassi        = $dados["chassi"]['0']['#'];
      $licenciamento = $dados["licenciamento"]['0']['#'];
      $multa         = $dados["multa"]['0']['#'];
      $seguro        = $dados["seguro"]['0']['#'];
      $taxa          = $dados["taxa"]['0']['#'];
      $ipva_vencer   = $dados["ipva-vencer"]['0']['#'];
      $ipva-vencido  = $dados["ipva-vencido"]['0']['#'];
*/

    require('nusoap.php');
    require('xml.functions.php');

    $msg=$_POST["renavam"];
    if ($_POST["renavam"] == "") {
       echo("Campo do renavam em branco");
    }
    else{
        $wcClient  = new soapclient('http://200.252.150.7/UraOS/Ura?WSDL','wsdl');
        $Response = $wcClient->call('consultaDebitoVeiculo', array($msg));
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
            $seguro = $dados["seguro"]['0']['#']*100;
            $multa  = $dados["multa"]['0']['#']*100;
            $licenc = $dados["licenciamento"]['0']['#']*100;
            $taxa = $dados["taxa"]['0']['#']*100;
            $ipva_vencer = $dados["ipva-vencer"]['0']['#']*100;
            $ipva_vencido = $dados["ipva-vencido"]['0']['#']*100;
        }

        if ($descricaoResposta=="Consulta Efetuada" ) {
          echo "<table><tr>";
          echo "<td>".$seguro."</td>";
          echo "<td>".$multa."</td>";
          echo "<td>".$licenc."</td>";
          echo "<td>".$taxa."</td>";
          echo "<td>".$ipva_vencer."</td>";
          echo "<td>".$ipva_vencido."</td>";
          echo "<td>".($seguro+$multa+$licenc+$taxa+$ipva_vencer+$ipva_vencido)."</td>";
          echo "<td>".$descricaoResposta."</td>";
          echo "</tr></table>";
        } else {
          echo "<table><tr>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>0</td>";
          echo "<td>".$descricaoResposta."</td>";
          echo "</tr></table>";
        }
    }
?>
