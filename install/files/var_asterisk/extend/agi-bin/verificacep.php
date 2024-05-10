<?php
    require('nusoap.php');
    require('xml.functions.php');
    $msg=$_POST["cep"];
    if ($_POST["cep"] == "") {
       echo("Campo do cep em branco");
    }
    
    else{


        $wcClient  = new soapclient('http://200.252.150.7/UraOS/Ura?WSDL','wsdl');
    //    $msg = '711355274';
    //    $vetor[0]='133';
    //    $vetor[1]='711355274';
        $Response = $wcClient->call('consultaCEP', array($msg));
        if ($wcClient->getError()) {
            echo '<br><br><br>ERROR<xmp>' . $wcClient->getError() . '</xmp>';
        }
        else {
            $xResponse = xmlize($Response);
            $dados = $xResponse["saida"]["#"];

            echo "<table><tr>";
            echo "<td>".$dados["cod-retorno"]['0']['#']."</td>";
            echo "<td>".$dados["id-regiao"]['0']['#']."</td>";
            $dados = $dados["dados"]['0']["#"];


            
              echo "</tr></table>";
        }
    }
?>

