<?php
    require('nusoap.php');
    require('xml.functions.php');
    $vetor[0]=$_POST["tipo"];
    $vetor[1]=$_POST["cnh"];
    
    if ($_POST["tipo"] == "") {
       echo("Campo do renavam em branco");
    }
    else{
        $wcClient  = new soapclient('http://200.252.150.7/UraOS/Ura?WSDL','wsdl');
    //    $msg = '711355274';
    //    $vetor[0]='133';
    //    $vetor[1]='711355274';
        $Response = $wcClient->call('consultaPontuacao', $vetor/*array($msg)*/);

        if ($wcClient->getError()) {
            echo '<br><br><br>ERROR<xmp>' . $wcClient->getError() . '</xmp>';
        }
        else {
            $xResponse = xmlize($Response);
            $dados = $xResponse["saida"]["#"];

            if ($dados["descricao-reposta"]['0']['#']=="")
              $descricaoResposta = "FALHA MF DETRAN";
            else
              $descricaoResposta = $dados["descricao-reposta"]['0']['#'];


            $dados = $dados["dados"]['0']["#"];

//            echo "<td>".$dados["tipo-cnh"]['0']['#']."</td>";
//            echo "<td>".$dados["descricao-tipo"]['0']['#']."</td>";
//            echo "<td>".$dados["numero-cnh"]['0']['#']."</td>";
            if ($descricaoResposta=="Consulta Efetuada" ) {
              echo "<table><tr>";
              echo "<td>".$dados["quantidade-pontos"]['0']['#']."</td>";
              echo "<td>".$descricaoResposta."</td>";
              echo "</tr></table>";
	    } else {
              echo "<table><tr>";
              echo "<td>0</td>";
              echo "<td>".$descricaoResposta."</td>";
              echo "</tr></table>";
	    }
        }
    }
?>
