<?php
    require('nusoap.php');
    require('xml.functions.php');
    $vetor[0]=$_POST["cpf"];
    $vetor[1]=$_POST["cep"];
    $vetor[2]=$_POST["telefone"];
    $vetor[3]=$_POST["cep2"];
    if ($_POST["cpf"] == "") {
       echo("Campo do CPF em branco");
    }
    if ($_POST["cep"] == "") {
       echo("Campo do cep em branco");
    }
    if ($_POST["telefone"] == "") {
       echo("Campo do telefone em branco");
    }
    else{


        $wcClient  = new soapclient('http://200.252.150.7/UraOS/Ura?WSDL','wsdl');
    //    $msg = '711355274';
    //    $vetor[0]='133';
    //    $vetor[1]='711355274';
        $Response = $wcClient->call('vinculaDistribuicaoCPF', $vetor/*array($msg)*/);
        if ($wcClient->getError()) {
            echo '<br><br><br>ERROR<xmp>' . $wcClient->getError() . '</xmp>';
        }
        else {
            $xResponse = xmlize($Response);
            $dados = $xResponse["saida"]["#"];

            echo "<table><tr>";
            echo "<td>".$dados["cod-retorno"]['0']['#']."</td>";
            echo "<td>".$dados["cod-clinica"]['0']['#']."</td>";
            echo "<td>".$dados["dias-validade"]['0']['#']."</td>";

            $dados = $dados["dados"]['0']["#"];


            
              echo "</tr></table>";
        }
    }
?>

