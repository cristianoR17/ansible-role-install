#!/usr/bin/php5 -q
<?php

  if(!$fp=fopen("https://www.infomoney.com.br/mercados/cambio" , "r" )) 
  {
    echo "Erro ao abrir a página de cotação" ;
    exit;
  }

  $conteudo = '';
  while(!feof($fp)) 
  { 
    $conteudo .= fgets($fp,1024);
  }
  fclose($fp);

  $valorCompraHTML = explode('<td><span>', $conteudo); 
  $valorCompra = trim(strip_tags($valorCompraHTML[1]));
  $valorVendaHTML = explode('+', strip_tags($valorCompraHTML[2]));

// Dolar comercial posicao 1 e 2
// Euro posicao 7 e 8
// Peso Argentino Posicao 13 e 14

  //Estes são os valores HTML para exibir no site.  
  $valorVendaHTML = explode('-', $valorVendaHTML[0]);
  $valorVenda  = trim($valorVendaHTML[0]) ;

  //Estes são os valores numéricos para cálculos.     
  $valorCompraCalculavel = str_replace(',','.', $valorCompra);
  $valorVendaCalculavel  = str_replace(',','.', $valorVenda);

  echo "Compra ".$valorCompraCalculavel." Venda ".$valorVendaCalculavel;


?>
